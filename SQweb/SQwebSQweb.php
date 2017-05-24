<?php

namespace SQweb\SQwebBundle\SQweb;

use Symfony\Component\DependencyInjection\ContainerInterface;

class SQwebSQweb
{
    private $config = [];
    public $abo = 0;
    public $script = null;
    public $button = null;
    public $buttonTiny = null;
    public $buttonSlim = null;
    public $buttonLarge = null;

    public function __construct(ContainerInterface $container)
    {
        $this->config['id_site'] = $container->getParameter('id_site');
        $this->config['sitename'] = $container->getParameter('sitename');
        $this->config['debug'] = $container->getParameter('debug') ?: 'false';
        $this->config['targeting'] = $container->getParameter('targeting') ?: 'false';
        $this->config['beacon'] = $container->getParameter('beacon') ?: 'false';
        $this->config['dwide'] = $container->getParameter('dwide') ?: 'false';
        $this->config['lang'] = $container->getParameter('lang');
        $this->config['message'] = $container->getParameter('message');
        $this->script();
        $this->checkCredits();
        $this->button();
        $this->buttonTiny();
        $this->buttonSlim();
        $this->buttonLarge();
    }

    /**
     * Add the SQweb script
     */
    private function script()
    {
        $this->script = '
<script>
	/* SDK SQweb Symfony 1.1.0 */
	var _sqw = {
	    id_site: '. $this->config['id_site'] .',
        sitename: "'. $this->config['sitename'] .'",
	    debug: '. $this->config['debug'] .',
	    targeting: '. $this->config['targeting'] .',
	    beacon: '. $this->config['beacon'] .',
	    dwide: '. $this->config['dwide'] .',
	    i18n: "'. $this->config['lang'] .'",
	    msg: "'. $this->config['message'] .'"};
	var script = document.createElement("script");
	script.type = "text/javascript";
	script.src = "https://cdn.sqweb.com/sqweb.js";
	document.getElementsByTagName("head")[0].appendChild(script);
</script>';
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

    private function checkCredits()
    {
        $response = null;
        if (isset($_COOKIE['z']) && null !== $this->config['id_site']) {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.sqweb.com/token/check',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT_MS => 1000,
                CURLOPT_TIMEOUT_MS => 1000,
                CURLOPT_USERAGENT => 'SDK Symfony 1.0.3',
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
