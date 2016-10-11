<?php

namespace SQweb\SQwebBundle\SQweb;

use Symfony\Component\DependencyInjection\ContainerInterface;

class SQwebSQweb
{
	private $config = [];
	public $abo = 0;
	public $script = null;
	public $button = null;

	public function __construct(ContainerInterface $container)
	{
		$this->config['id_site'] = $container->getParameter('id_site');
		$this->config['debug'] = $container->getParameter('debug') ?: 'false';
		$this->config['targeting'] = $container->getParameter('targeting') ?: 'false';
		$this->config['beacon'] = $container->getParameter('beacon') ?: 'false';
		$this->config['dwide'] = $container->getParameter('dwide') ?: 'false';
		$this->config['lang'] = $container->getParameter('lang');
		$this->config['message'] = $container->getParameter('message');
		$this->script();
		$this->checkCredits();
		$this->button();
	}

	/**
	 * Ajoute le script SQweb
	 */
	private function script()
	{
        $this->script = '
<script>
	/* SDK SQweb Symfony 1.0 */
	var _sqw = {
	    id_site: '. $this->config['id_site'] .',
	    debug: '. $this->config['debug'] .',
	    targeting: '. $this->config['targeting'] .',
	    beacon: '. $this->config['beacon'] .',
	    dwide: '. $this->config['dwide'] .',
	    i18n: "'. $this->config['lang'] .'",
	    msg: "'. $this->config['message'] .'"};
	var script = document.createElement("script");
	script.type = "text/javascript";
	script.src = "//cdn.sqweb.com/sqweb-beta.js";
	document.getElementsByTagName("head")[0].appendChild(script);
</script>';
	}

	private function button()
	{
		$this->button = '<div class="sqweb-button-mp"><div class="sqw-btn-mp"></div></div>';
	}

	private function checkCredits()
	{
		$response = null;
        if (isset($_COOKIE['sqw_z']) && null !== $this->config['id_site']) {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.sqweb.com/token/check',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT_MS => 1000,
                CURLOPT_TIMEOUT_MS => 1000,
                CURLOPT_USERAGENT => 'SDK Symfony 1.0',
                CURLOPT_POSTFIELDS => [
                    'token' => $_COOKIE['sqw_z'],
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
}