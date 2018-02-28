<?php

namespace SQweb\SQwebBundle\SQweb;

use Symfony\Component\DependencyInjection\ContainerInterface;

class SQwebSQweb
{
    private $config = [];
    public $abo = 0;
    public $script = null;
    public $button = null;
    public $supportBlock = null;
    public $lockingBlock = null;
    public $buttonTiny = null;
    public $buttonSlim = null;
    public $buttonLarge = null;
    public $buttonSupport = null;

    public function __construct(ContainerInterface $container)
    {
        $this->config['id_site']    = $container->getParameter('id_site');
        $this->config['sitename']   = $container->getParameter('sitename');
        $this->config['debug']      = $container->getParameter('debug') ?: 'false';
        $this->config['targeting']  = $container->getParameter('targeting') ?: 'false';
        $this->config['beacon']     = $container->getParameter('beacon') ?: 'false';
        $this->config['dwide']      = $container->getParameter('dwide') ?: 'false';
        $this->config['lang']       = $container->getParameter('lang');
        $this->config['message']    = $container->getParameter('message');
        $this->config['autologin']  = $container->getParameter('autologin');
        $this->config['tunnel']     = $container->getParameter('tunnel');

        /* These following configs are for button customization */

        $this->config['login']             = $container->getParameter('login');
        $this->config['connected']         = $container->getParameter('connected');
        $this->config['support']           = $container->getParameter('support');
        $this->config['btn_noads']         = $container->getParameter('btn_noads');
        $this->config['login_tiny']        = $container->getParameter('login_tiny');
        $this->config['connected_s']       = $container->getParameter('connected_s');
        $this->config['btn_unlimited']     = $container->getParameter('btn_unlimited');
        $this->config['connected_tiny']    = $container->getParameter('connected_tiny');
        $this->config['connected_support'] = $container->getParameter('connected_support');

        /* - - - - - - - - - - - - - - - - - - - - - - - - - - */

        $this->script();
        $this->checkCredits();
        $this->supportBlock();
        $this->lockingBlock();
        $this->button();
        $this->buttonTiny();
        $this->buttonSlim();
        $this->buttonLarge();
        $this->buttonSupport();
    }

    /**
     * Add the SQweb script
     */
    private function script()
    {
        $settings = json_encode(array(
            'wsid' => $this->config['id_site'],
            'sitename' => $this->config['sitename'],
            'debug' => $this->config['debug'],
            'targeting' => $this->config['targeting'],
            'beacon' => $this->config['beacon'],
            'dwide' => $this->config['dwide'],
            'locale' => $this->config['lang'],
            'msg' => $this->config['message'],
            'autologin' => $this->config['autologin'],
            'tunnel' => $this->config['tunnel'],
            // User's custom strings for button customization
            'user_strings' => array(
                'login' => $this->config['login'],
                'login_tiny' => $this->config['login_tiny'],
                'connected' => $this->config['connected'],
                'connected_tiny' => $this->config['connected_tiny'],
                'connected_s' => $this->config['connected_s'],
                'connected_support' => $this->config['connected_support'],
                'btn_unlimited' => $this->config['btn_unlimited'],
                'btn_noads' => $this->config['btn_noads'],
                'support' => $this->config['support'],
            ),
        ));

        $this->script  = '<script src="https://cdn.multipass.net/mltpss.min.js" type="text/javascript"></script>'
        . PHP_EOL;

        $this->script .= "<script>/* SDK SQweb Symfony 1.3.2 */
        var mltpss = new Multipass.default($settings);</script>";
    }

    /*
     * Display a button for locked content
     */
    public function lockingBlock()
    {
        $this->lockingBlock = $this->returnBlock('locking');
    }

    /*
     * Display a supporting button
     */
    public function supportBlock()
    {
        $this->supportBlock = $this->returnSupportBlock();
    }

    /*
     * Return the "Support us" message
     */
    private function returnSupportBlock()
    {
        switch ($this->config['lang']) {
            case 'fr':
            case 'fr_fr':
                $wording = array(
                    'title'         => 'L\'article est terminé ...',
                    'sentence_1'    => '... mais nous avons besoin que vous lisiez ceci: nous avons de plus en plus
                         de lecteurs chaque jour, mais de moins en moins de revenus publicitaires.',
                    'sentence_2'    => 'Nous souhaitons laisser notre contenu accessible à tous. Nous sommes
                         indépendants et notre travail de qualité prend beaucoup de temps, d\'argent et de dévotion',
                    'sentence_3'    => 'Vous pouvez nous soutenir avec Multipass qui permet de payer pour un bouquet de
                         sites, et ainsi financer le travail des créateurs et journalistes que vous aimez.',
                    'support'       => 'Soutenez nous avec'
                );
                break;

            default:
                $wording = array(
                    'title'         => 'Continue reading...',
                    'sentence_1'    => '... we need you to hear this: More people are reading our website than ever but
                         advertising revenues across the media are falling fast.',
                    'sentence_2'    => ' We want to keep our content as open as we can. We are independent,
                         and our quality work takes a lot of time, money and hard work to produce. ',
                    'sentence_3'    => 'You can support us with Multipass which enables you to pay for a bundle of
                         websites: you can finance the work of journalists and content creators you love.',
                    'support'       => 'Support us with'
                );
                break;
        }

        return '
            <div class="article-footer-container">
                <div class="article-footer-body">
                    <div class="article-footer-body-title">' . $wording['title'] . '</div>
                    <div class="article-footer-body-content1">' . $wording['sentence_1'] .'</div>
                    <div class="article-footer-body-content2">' . $wording['sentence_2'] . '</div>
                    <div class="article-footer-body-content3">' . $wording['sentence_3'] . '</div>
                </div>
                <div onclick="mltpss.modal_first(event)" class="article-footer-footer">
                    <div class="article-footer-footer-text">' . $wording['support'] . '</div>
                    <div class="article-footer-footer-logo-container"></div>
                </div>
            </div>
        ';
    }

    /*
     * Return the good button according to parent function.
     */
    private function returnBlock($type)
    {
        $wording = $this->selectText($type);

        return '
            <div class="footer__mp__normalize footer__mp__button_container sqw-paywall-button-container">
                <div class="footer__mp__button_header">
                    <div class="footer__mp__button_header_title">' . $wording['warning'] . '</div>
                    <div onclick="mltpss.modal_first(event)" class="footer__mp__button_signin">'
                    . $wording['already_sub']
                    . '<span class="footer__mp__button_login footer__mp__button_strong">'
                    . $wording['login']
                    . '</span></div>
                </div>
                <div onclick="mltpss.modal_first(event)" class="footer__mp__normalize footer__mp__button_cta">
                    <a href="#" class="footer__mp__cta_fresh">' . $wording['unlock'] . '</a>
                </div>
                <div class="footer__mp__normalize footer__mp__button_footer">
                    <p class="footer__mp__normalize footer__mp__button_p">'. $wording['desc'] . '</p>
                    <a target="_blank" class="footer__mp__button_discover footer__mp__button_strong" href="'
                    . $wording['href']
                    . '"><span>></span> <span class="footer__mp__button_footer_txt">'
                    . $wording['discover']
                    . '</span></a>
                </div>
            </div>';
    }

    private function selectText($type)
    {
        if ($type == 'support') {
            switch ($this->config['lang']) {
                case 'fr':
                case 'fr_fr':
                    $wording = array(
                        'warning'       => 'Surfez sans publicité.',
                        'already_sub'   => 'Déjà abonné ? ',
                        'login'         => 'Connexion',
                        'unlock'        => 'Soutenez notre site grâce à ',
                        'desc'          => 'L\'abonnement multi-sites, sans engagement.',
                        'href'          => 'https://www.multipass.net/fr/sites-partenaires-premium-sans-pub-ni-limites',
                        'discover'      => 'Découvrir les partenaires'
                    );
                    break;

                default:
                    $href = 'https://www.multipass.net/en premium-partners-website-without-ads-nor-restriction';
                    $wording = array(
                        'warning'       => 'Surf our website ad free',
                        'already_sub'   => 'Already a member? ',
                        'login'         => 'Sign in',
                        'unlock'        => 'Support our website, get your',
                        'desc'          => 'The multisite subscription, with no commitment.',
                        'href'          => $href,
                        'discover'      => 'Discover all the partners'
                    );
                    break;
            }
        } elseif ($type == 'locking') {
            switch ($this->config['lang']) {
                case 'fr':
                case 'fr_fr':
                    $wording = array(
                        'warning'       => 'Cet article est reservé.',
                        'already_sub'   => 'Déjà abonné ? ',
                        'login'         => 'Connexion',
                        'unlock'        => 'Débloquez ce contenu avec',
                        'desc'          => 'L\'abonnement multi-sites, sans engagement.',
                        'href'          => 'https://www.multipass.net/fr/sites-partenaires-premium-sans-pub-ni-limites',
                        'discover'      => 'Découvrir les partenaires'
                    );
                    break;

                default:
                    $href = 'https://www.multipass.net/en premium-partners-website-without-ads-nor-restriction';
                    $wording = array(
                        'warning'       => 'The rest of this article is restricted.',
                        'already_sub'   => 'Already a member? ',
                        'login'         => 'Sign in',
                        'unlock'        => 'Unlock this content, get your ',
                        'desc'          => 'The multisite subscription, with no commitment.',
                        'href'          => $href,
                        'discover'      => 'Discover all the partners'
                    );
                    break;
            }
        }
        return $wording;
    }

    private function button()
    {
        $this->button = '<div class="sqweb-button"></div>';
    }

    private function buttonTiny()
    {
        $this->buttonTiny = '<div class="sqweb-button multipass-tiny"></div>';
    }

    private function buttonSlim()
    {
        $this->buttonSlim = '<div class="sqweb-button multipass-slim"></div>';
    }

    private function buttonLarge()
    {
        $this->buttonLarge = '<div class="sqweb-button multipass-large"></div>';
    }

    private function buttonSupport()
    {
        $this->buttonSupport = '<div class="sqweb-button-support"></div>';
    }

    private function checkCredits()
    {
        $response = null;
        if (isset($_COOKIE['z']) && null !== $this->config['id_site']) {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.multipass.net/token/check',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT_MS => 1000,
                CURLOPT_TIMEOUT_MS => 1000,
                CURLOPT_USERAGENT => 'SDK Symfony 1.2.1',
                CURLOPT_POSTFIELDS => [
                    'token' => $_COOKIE['z'],
                    'site_id' => $this->config['id_site'],
                ],
            ]);
            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response);
        }
        if ($response !== null && $response->status === true && $response->credit > 0) {
            $this->abo = $response->credit;
        } else {
            $this->abo = 0;
        }
    }

    public function sqwBalise($balise, $match)
    {
        if (preg_match('/<(\w+)(?(?!.+\/>).*>|$)/', $match, $tmp)) {
            if (!isset($balise)) {
                $balise = array();
            }
            $balise[] = $tmp[1];
        }
        foreach ($balise as $key => $value) {
            if (preg_match('/<\/(.+)>/', $value, $tmp)) {
                unset($balise[ $key ]);
            }
        }
        return $balise;
    }

    /**
     * Put opacity to your text
     * Returns the text with opcaity style.
     * @param text, which is your text.
     * @param int percent which is the percent of your text you want to show.
     * @return string
     */
    public function transparent($text, $percent = 100)
    {
        if ($this->abo === 1 || $percent == 100 || empty($text)) {
            return $text;
        }
        if ($percent == 0) {
            return '';
        }
        $arr_txt = preg_split('/(<.+?><\/.+?>)|(<.+?>)|( )/', $text, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        foreach (array_keys($arr_txt, ' ', true) as $key) {
            unset($arr_txt[ $key ]);
        }
        $arr_txt = array_values($arr_txt);
        $words = count($arr_txt);
        $nbr = ceil($words / 100 * $percent);
        $lambda = (1 / $nbr);
        $alpha = 1;
        $begin = 0;
        $balise = array();
        while ($begin < $nbr) {
            if (isset($arr_txt[$begin + 1])) {
                if (preg_match('/<.+?>/', $arr_txt[ $begin ], $match)) {
                    $balise = $this->sqwBalise($balise, $match[0]);
                    $final[] = $arr_txt[ $begin ];
                    $nbr++;
                } else {
                    $tmp = number_format($alpha, 5, '.', '');
                    $final[] = '<span style="opacity: ' . $tmp . '">' . $arr_txt[ $begin ] . '</span>';
                    $alpha -= $lambda;
                }
            }
            $begin++;
        }
        foreach ($balise as $value) {
            $final[] = '</' . $value . '>';
        }
        $final = implode(' ', $final);
        return $final;
    }

    /**
     * Limit the number of articles free users can read per day.
     * @param $limitation int The number of articles a free user can see.
     * @return bool
     */
    public function limitArticle($limitation = 0)
    {
        if ($this->abo === 0 && $limitation != 0) {
            if (!isset($_COOKIE['sqwBlob']) || (isset($_COOKIE['sqwBlob']) && $_COOKIE['sqwBlob'] != -7610679)) {
                $ip2 = ip2long($_SERVER['REMOTE_ADDR']);
                if (!isset($_COOKIE['sqwBlob'])) {
                    $sqwBlob = 1;
                } else {
                    $sqwBlob = ($_COOKIE['sqwBlob'] / 2) - $ip2 - 2 + 1;
                }
                if ($limitation > 0 && $sqwBlob <= $limitation) {
                    $tmp = ($sqwBlob + $ip2 + 2) * 2;
                    setcookie('sqwBlob', $tmp, time()+60*60*24);
                    return true;
                } else {
                    setcookie('sqwBlob', -7610679, time()+60*60*24);
                }
            }
            return false;
        } else {
            return true;
        }
    }

    public function isTimestamp($string)
    {
        return (1 === preg_match('~^[1-9][0-9]*$~', $string));
    }

    /**
     * Display your premium content at a later date to non-paying users.
     * @param  string  $date  When to publish the content on your site. It must be an ISO format(YYYY-MM-DD).
     * @param  integer $wait  Days to wait before showing this content to free users.
     * @return bool
     */
    public function waitToDisplay($date, $wait = 0)
    {
        if ($wait == 0 || $this->abo === 1) {
            return true;
        }
        $date = date_create($date);
        $now = date_create('now');
        date_modify($now, '-'.$wait.' days');
        return $date < $now;
    }
}
