Add SQweb on all Twig template
Go on : app/config/config.yml after
# Twig configuration
paste :
globals:
        sqweb: "@s_qweb_s_qweb.SQweb"

Add SQweb Config on : app/config/config.yml where you want 
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

Add on all Twig template :
{{sqweb.script|raw}}

For use SQweb on Twig template :
	Check if user have an valid subscribe :
	{% if sqweb.abo %}
		Your content
	{% else %}
		Your Adsense
	{% endif %}

	Add Multipass button on your website :
	{{ sqweb.button|raw }}

	Add paywall for hide progressively the articles of no subscribers :
	{{ sqweb.transpartext('Content to hide progressively', '% to show (exemple : 50)') }}

	Add paywall for add time before show articles for no subscribers :
	{% if sqweb.waitToDisplay('Date of publish', 'Time to wait before showing articles') %}
	Your content
	{% endif %}

	Add paywall for limit number articles user can read before blocked :
	{% if sqweb.limitArticle('number of day to wait') %}
	Your content
	{% endif %}

Get SQweb service in controllers
$sqweb = $this->container->get('sqweb_sqweb.sqweb');