# SQweb Symfony Package

[![Build Status](https://travis-ci.org/SQweb-team/SQweb-SDK-Symfony.svg?branch=master)](https://travis-ci.org/SQweb-team/SQweb-SDK-Symfony)

**This package allows you to easily integrate SQweb on your Symfony powered website.**

## Requirements

**This SDK has been tested with PHP 5.5 and greater.**

We are unable to provide official support for earlier versions. For more information about end of life PHP branches, [see this page](http://php.net/supported-versions.php).

## Install

**This package is intended for websites powered by Symfony.**

If you're using WordPress, we've made it easy for you. Download the SQweb plugin [directly from WordPress.org](https://wordpress.org/plugins/sqweb/), or check out the source [here](https://github.com/SQweb-team/SQweb-WordPress-Plugin).

### Using Symfony 3.x

1. In your project root, execute `composer require sqweb/symfony_bundle`. Now, go to `app/AppKernel.php` and add this line to your bundles array:

    ```php
    new SQweb\SQwebBundle\SQwebSQwebBundle()
    ```

2. Add in your `app/config/config.yml` after `# Twig configuration`

    ```yml
    twig:
	    globals:
   	    	sqweb: "@s_qweb_s_qweb.SQweb"
    ```

3. And at the end of your `config.yml` add :

	```yml
    # SQweb Configuration
    s_qweb_s_qweb:
        config:
            id_site: ID_SITE
            sitename: "website_name"
            debug: false
            targeting: false
            beacon: false
            dwide: false
            autologin: true
            lang: "en"
            message: ""
			login: ""
			support: ""
			connected: ""
			btn_noads: ""
			login_tiny: ""
			connected_s: ""
			btn_unlimited: ""
			connected_tiny: ""
			connected_support: ""
```

**Don't forget to set your `id_site`, `sitename` and `lang` accordingly.**

For additional settings, see "[Options](#options)" below.

### Using Symfony 4.x

1. Open `a`

## Usage

### 1. Tagging your pages

This function outputs the SQweb JavaScript tag. Insert it before the closing `</body>` tag in your HTML.

```php
{{ sqweb.script|raw }}
```

**If you previously had a SQweb JavaScript tag, make sure to remove it to avoid any conflicts.**

### 2. Checking the credits of your subscribers

This variable is `true` if the user subscribe to multipass and `false` if not, so that you can disable ads and/or unlock premium content.

Use it like this:

```php
{% if sqweb.abo %}
    //CONTENT
{% else %}
    //ADS
{% endif %}
```

### 3. a) Showing the Multipass button

Finally, use this code to display the Multipass button on your pages:

```php
{{ sqweb.button|raw }}
```

We have differents size for the button here is how to use them e.g:

```php
{{ sqweb.buttonTiny|raw }}
OR
{{ sqweb.buttonSlim|raw }}
OR
{{ sqweb.buttonLarge|raw }}
```

![Example Buttons](https://cdn.multipass.net/github/buttons@2x.png "Example Buttons")

### 3. b) Customizing the Multipass button

If you want to customize our different type of button, edit the following in your `config.yaml` file.

For instance:

```yml
# SQweb Configuration
s_qweb_s_qweb:
    config:
		...
		login:"Hello world"
		...
```
Will display `Hello world` instead of `Premium with Multipass` on the regular button for logged out visitors.


|Button Model|Logged in|Logged out
|---|:---:|:---:|
|Tiny|`connected_tiny`|`login_tiny`|
|Regular|`connected`|`login`|
|Large - before black dot| N / A | `btn_unlimited`|
|Large - after black dot| N / A | `btn_noads`|
|Large connected| `connected_s` | N / A|

### 4. More functions

#### Display a support div for your users
```php
/**
 * Display a support block.
 */

function supportBlock() {   }
``

For instance:

```php
{{sqweb.supportBlock|raw}}
```

Will display the block.

#### Display a locking div for your users
```php
/**
 * Display a locking block.
 */

function lockingBlock() {   }
``

For instance:

```php
{{sqweb.lockingBlock|raw}}
```

Will display the block.
We recommand you to use it in combination with our other limiting fuctions, like this:
```php
{% if sqweb.waitToDisplay('2016-09-15', 2) %}
    // The content here will appear the 2016-09-17, 2 days after the publication date for non paying users.
{% else %}
    // Here you can display a message that free users will see while your article is not displayed
    {{sqweb.lockingBlock|raw}}
{% endif %}
```

#### Display only a part of your content to non premium users

```php
/**
 * Put opacity to your text
 * Returns the text with opcaity style.
 * @param text, which is your text.
 * @param int percent which is the percent of your text you want to show.
 * @return string
 */

public function transparent($text, $percent = 100) { ... }
```

Example:

```php
{{ sqweb.transpartext('one two three four', 50)|raw }}
```

Will display for free users:

```text
one two
```

#### Display your content later for non paying users

```php
/**
 * Display your premium content at a later date to non-paying users.
 * @param  string  $date  When to publish the content on your site. It must be an ISO format(YYYY-MM-DD).
 * @param  integer $wait  Days to wait before showing this content to free users.
 * @return bool
 */

public function waitToDisplay($date, $wait = 0) { ... }
```

Example:

```php
{% if sqweb.waitToDisplay('2016-09-15', 2) %}
    // The content here will appear the 2016-09-17, 2 days after the publication date for non paying users.
{% else %}
    // Here you can display a message that free users will see while your article is not displayed
{% endif %}
```

#### Limit the number of articles free users can read per day

```php
/*
 * @param int $limitation  Number of articles a free user can see.
 */

function limitArticle($limitation = 0) { ... }
```

For instance, if I want to display only 5 articles to free users:

```php
{% if sqweb.limitArticle(5) %}
    // Put your content here
{% else %}
    // Here you can display a message that free users will see while your article is not displayed
{% endif %}
```

## Options

Unless otherwise noted, these options default to `false`. You can set them in your configuration file eg: `config.yml`.

|Option|Description
|---|---|
|`id_site`|Sets your website SQweb ID. Ex: 123456.|
|`sitename`|The name that will appear on the large version of our button. You must set this variable.|
|`blockers`|Automatically display the Multipass modal to detected adblockers.|
|`message`|A custom message that will be shown to your adblockers. If using quotes, you must escape them.|
|`targeting`|Only show the button to detected adblockers. Cannot be combined with the `beacon` mode.|
|`beacon`|Monitor adblocking rates quietly, without showing a SQweb button or banner to the end users.|
|`debug`|Output various messages to the browser console while the plugin executes.|
|`dwide`|Set to `false` to only enable SQweb on the current domain. Defaults to `true`.|
|`lang`|You may pick between `en_US`, and `fr_FR`.|
|`autologin`|Automatically login Multipass users on your website.|

## Contributing

We welcome contributions and improvements.

### Coding Style

All PHP code must conform to the [PSR2 Standard](http://www.php-fig.org/psr/psr-2/).

## Bugs and Security Vulnerabilities

If you encounter any bug or unexpected behavior, you can either report it on Github using the bug tracker, or via email at `hello@sqweb.com`. We will be in touch as soon as possible.

If you discover a security vulnerability within SQweb or this plugin, please e-mail `security@sqweb.com`. Vulnerabilities will be promptly addressed.

## License

Copyright (C) 2016 – SQweb

This program is free software ; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation ; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY ; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details

You should have received a copy of the GNU General Public License along with this program. If not, see <http://www.gnu.org/licenses/>.
