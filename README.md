# codeigniter-recaptcha

Little package to get [recaptcha][5] working on [codeigniter][1]

## prerequisites

Setup and obtain a key pair from [recaptcha admin console][6] for your project domain; both are required. The firs is used on captcha widget rendering, the second when checking response validity.

It's also important to check the URL endpoint to be used for remote validation; the information is available under server side integration step.

## setup

Copy `config/Recaptcha.php` and `libraries/Recaptcha.php` under codeigniter project application folder.

Please note that default shipped configuration is using test keys, so you have to replace them in `config/Recaptcha.php` with the ones coming from completion of previous step (and better to disable allowed test keys). The rows to edit are the following:
```php
$config['allowtestkeys'] = TRUE;
$config['profile_sitekey'] = '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI';
$config['profile_secretkey'] = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';
```

According to [documentation][3], config variables can be mapped to customize widget, like theme, size, etc.

A way to install via composer is allowed referencing packagist handle [afelicioni/codeigniter-recaptcha][7] like
```javascript
{
	"require": {
		"afelicioni/codeigniter-recaptcha": "dev-master"
	}
}
```
in a custom composer.json and to be triggered by
```
composer install
```

At last, `controllers/Recaptcha.php` provides a direct usage example.

## how to use

Functionality is split in two parts, for widget rendering and response verification.

First, be sure to load library
```
$this->load->library('recaptcha');
```

In controller, call `widget` to obtain outputable code for displaying widget. You can (it's optional!) pass an array as parameter to customize language, theme, size and so on.
```
$this->recaptcha->widget(array('hl'=>'fr','theme'=>'dark','size'=>'compact'));
```

To verify response, call `verify` and pass a string to perfor check for.
```
$checkme = $this->recaptcha->verify($this->input->post('g-recaptcha-response'));
if ($check) {
	echo 'hey, you look like a human!';
}
```
## todo

Strict check for IP address

## extra

by Alessio Felicioni | [Github](https://github.com/afelicioni)

[1]: http://www.codeigniter.com/
[2]: https://developers.google.com/recaptcha/
[3]: https://developers.google.com/recaptcha/docs/display
[4]: https://developers.google.com/recaptcha/docs/verify
[5]: https://developers.google.com/recaptcha/intro
[6]: https://www.google.com/recaptcha/admin
[7]: https://packagist.org/packages/afelicioni/codeigniter-recaptcha
