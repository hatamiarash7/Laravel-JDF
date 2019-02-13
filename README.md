
# Laravel-JDF
It's a simple library to use Persian timestamps in Laravel

### Install
`composer require hatamiarash7/jdf`

Laravel 5.5+ has auto-discover ability and you don't need add anything to project's configuration

### How to use
```
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
