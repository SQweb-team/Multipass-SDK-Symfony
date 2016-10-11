Get service SQweb in controllers
$sqweb = $this->container->get('sqweb_sqweb.sqweb');

Add SQweb on all Twig template
Go on : app/config/config.yml in # Twig configuration
globals:
        sqweb: "@s_qweb_s_qweb.SQweb"

# SQweb Configuration
s_qweb_s_qweb:
    config:
        id_site: 1
        debug: false
        targeting: false
        beacon: false
        dwide: false
        lang: "en"
        message: ""