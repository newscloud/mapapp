EGeocoder
=========

The almost missing Geocoder PHP library as Yii component.
This extension main goal is provide the easiest way to geocode something.

Installation and configuration
------------------------------
Install [Geocoder](https://github.com/willdurand/Geocoder) via composer.

Copy component to `extensions/EGeocoder` directory located inside your application and add it to the application
configuration the following way:

```php
<?php
return array(
...
	'components' => array(
		...
		'geocoder' => array(
			'class' => 'ext.EGeocoder.EGeocoder',
			// 'httpAdapter' => 'Socket',
			'providers' => array(
                new \Geocoder\Provider\FreeGeoIpProvider(new \Geocoder\HttpAdapter\CurlHttpAdapter()),
                'GeoPlugin',
                array(
                    'name' => 'IpInfoDb',
                    // Please use your own api key
                    'apiKey' => 'fe469eea906d1be01894ef2a7dd7a1d64fb9f412ab5ebdcxx2576c7af9ac04a014',
                ),
            ),
		),
		...
	),
...
);
```

Usage example
-------------

```php
<?php
$result = Yii::app()->geocoder->geocode('74.125.235.39');
$dumper = new \Geocoder\Dumper\GeoJsonDumper();
echo $dumper->dump($result);
```