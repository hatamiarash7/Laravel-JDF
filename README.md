# Laravel-JDF

[![Latest Stable Version](https://poser.pugx.org/hatamiarash7/jdf/v/stable)](https://packagist.org/packages/hatamiarash7/jdf) [![Total Downloads](https://poser.pugx.org/hatamiarash7/jdf/downloads)](https://packagist.org/packages/hatamiarash7/jdf) [![License](https://poser.pugx.org/hatamiarash7/jdf/license)](https://packagist.org/packages/hatamiarash7/jdf)

It's a simple library to use Persian timestamps in Laravel

### Install

`composer require hatamiarash7/jdf`

Laravel 5.5+ has auto-discover ability and you don't need add anything to project's configuration

### How to use

```php
use Hatamiarash7\JDF\Generator;

...

$jdf = new Generator();
$date = $jdf->getTimestamp(); // Output : ۱۳۹۷/۱۱/۰۲ - ۰۰:۳۸

$finalDate = $jdf->jdate_words(['ss'=>1390, 'mm'=>5, 'rr'=>20]);
// Output : [
		'ss' => 'هزار و سیصد و نود' ,
		'mm' => 'مرداد' ,
		'rr' => 'بیست'
	    ]
```
