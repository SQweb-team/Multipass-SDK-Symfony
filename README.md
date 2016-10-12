SQweb Symfony Package
===

**This package allows you to easily integrate SQweb on your Laravel powered website.**

##Requirements

**This SDK has been tested with PHP 5.5 and greater.**

We are unable to provide official support for earlier versions. For more information about end of life PHP branches, [see this page](http://php.net/supported-versions.php).

##Install

**This package is intended for websites powered by Laravel, and advanced integrations.**

If you're using WordPress, we've made it easy for you. Download the SQweb plugin [directly from WordPress.org](https://wordpress.org/plugins/sqweb/), or check out the source [here](https://github.com/SQweb-team/SQweb-WordPress-Plugin).

###Using Composer

1. In your project root, execute `composer require sqweb/symfony_sdk`;
2. Now, go to app/AppKernel.php and add this line to your bundles array:
```
new SQweb\SQwebBundle\SQwebSQwebBundle()
```
3. Add in your app/config/config.yml after `# Twig configuration`
```yml
globals:
        sqweb: "@s_qweb_s_qweb.SQweb"
```
And at the end of your config.yml add
```yml
# SQweb Configuration
s_qweb_s_qweb:
    config:
        id_site: ID_SITE
        debug: false
        targeting: false
        beacon: false
        dwide: false
        lang: "en"
        message: ""
```
Without forget replace ID_SITE by your id_site and lang by langage of your website

For additional settings, see "[Options](#options)" below.

##Usage

The SDK is really simple to use. Here's how to:

###1. Tagging your pages

This function outputs the SQweb JavaScript tag. Insert it before the closing `</body>` tag in your HTML.

```php
{{ sqweb.script|raw }}
```

**If you previously had a SQweb JavaScript tag, make sure to remove it to avoid any conflicts.**

###2. Checking the credits of your subscribers

This variable is true if the user subscribe to multipass and false if not, so that you can disable ads and/or unlock premium content.

Use it like this:

```php
{% if sqweb.abo %}
	//CONTENT
{% else %}
	//ADS
{% endif %}
```

###3. Showing the Multipass button

Finally, use this code to get the Multipass button on your pages:

```php
{{ sqweb.button|raw }}
```

###4. More functions

1.This function is used to display only a part of your content to non premium users:
```php
{{ sqweb.transpartext(your_content, percent)|raw }}
```
`percent` is the percent of your content you want to display to everyone.

Example:
```php
{{ sqweb.transpartext('one two three four', 50)|raw }}
```
Will display for free users:
```
one two
```

2.The following function is used to display your content later for non paying users:
```php
{% if sqweb.waitToDisplay(publication_date, wait) %}
	Your content
{% endif %}
```
1. `publication_date` is the date when your content is published on your website.
2. `wait` is the number of day you want to wait before showing this content to free users.

Example:
```php
{% if sqweb.waitToDisplay('2016/10/01', 3) %}
	Put your content here
{% else %}
	Here you can display a message that free users will see while your article is not displayed
{% endif %}
```

3.The next function is used to limit the number of articles free users can read a day:
```php
{% if sqweb.limitArticle(numbers_of_articles) %}
	Put your content here
{% else %}
	Here you can display a message that free users will see while your article is not displayed
{% endif %}
```
`number_of_articles` is the number of articles a free user can see.

Example if I want to display only 5 articles to free users:
```php
{% if sqweb.limitArticle(5) %}
	Put your content here
{% else %}
	Here you can display a message that free users will see while your article is not displayed
{% endif %}
```

##Options

Set these variables in your .env file to enable or disable them.

|Option|Description
|---|---|
|`id_site`|Sets your website SQweb ID. Ex: 123456.|
|`debug`|Outputs various messages to the browser console while the plugin executes. Disabled by default. 1 (activated) or 0 (deactivated).|
|`target`|Only shows the button to users with adblockers. Disabled by default. 1 (activated) or 0 (deactivated).|
|`dwide`|Disabling this option will limit SQweb to the current domain. Enabled by default. 1 (activated) or 0 (deactivated).|
|`lang`|Sets the language. Currently supports `en` (English) and `fr` (French). Defaults to `en`|
|`message`|A custom message is displayed to users with an adblocker enabled. Ex:"Please deactivate your adblocker on this website, or support us by using Multipass!". Empty by default.|


##Contributing

We welcome contributions and improvements.

###Coding Style

All PHP code must conform to the [PSR2 Standard](http://www.php-fig.org/psr/psr-2/).

##Bugs and Security Vulnerabilities

If you encounter any bug or unexpected behavior, you can either report it on Github using the bug tracker, or via email at `hello@sqweb.com`. We will be in touch as soon as possible.

If you discover a security vulnerability within SQweb or this plugin, please send an e-mail to `hello@sqweb.com`. All security vulnerabilities will be promptly addressed.

##License

Copyright (C) 2016 – SQweb

This program is free software ; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation ; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY ; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details

You should have received a copy of the GNU General Public License along with this program. If not, see <http://www.gnu.org/licenses/>.