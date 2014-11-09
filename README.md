laravel-shorcode
==============

Really Simple Laravel Shortcode

----------

Installation
============

Add the following to your `composer.json` file:

```json
"nerweb/laravel-shortcode": "1.0.x"
```

Then, run `composer update nerweb/laravel-shortcode` or `composer install` if you have not already installed packages.

Add the below line to the providers array in app/config/app.php configuration file (add at the end):

```php
'Nerweb\Shortcode\ShortcodeServiceProvider',
```

Add the below line to the aliases array in app/config/app.php configuration file (add at the end):

```php
'Shortcode' => 'Nerweb\Shortcode\Facades\Shortcode',
```

Usage
====

```php
$string = '
Hi [FIRST_NAME] [LAST_NAME],

To confirm your account.

please click [confirm_url title="Confirm example" text="here"]

or

[CONFIRM_URL_TEXT]

Thanks,
[FROM_NAME]
';

Shortcode::register('FROM_NAME', 'Lisa Dy');

Shortcode::register('confirm_url', function($parameters = array(), $Shortcode) {
    // short code available parameters
    $href           = array_get($parameters, 'href');
    $title          = array_get($parameters, 'title');
    $text           = array_get($parameters, 'text');

    // link
    return link_to($Shortcode->get('CONFIRM_URL_TXT'), $text, array(
        'title' => $title
    ));
});

echo Shortcode::decode($string, array(
    'FIRST_NAME'        => 'Hamill',
    'LAST_NAME'         => 'Esmeralda',
    'CONFIRM_URL_TEXT'  => URL::to('/account/confirm/?something=here')
));
```

[mit-url]: http://opensource.org/licenses/MIT