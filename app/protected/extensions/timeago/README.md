yii-timeago
===========

Timeago formatter for yii framework

#Installation
Copy yii-timeago to your app extensions folder.  
In config main add to components section:  
```php
<?php
...
'components' => array(
	'format'=>array(
		'class'=>'application.extensions.timeago.TimeagoFormatter',
    ),
...
?>
```

#Examples
```php
<?php
// with timestamp
echo Yii::app()->format->timeago(time() - 60 * 5); // 5 minutes ago

// with DateTime object 
echo Yii::app()->format->timeago(new DateTime('2018-07-08 11:14:15'));

// with date formatted string
echo Yii::app()->format->timeago('2012-07-31 19:08:58');

// in CGridView column
...
'columns' => array(
	array(  'name'=>'create_date',
            'sortable' => true,
            'header'=>'Date',
            'type' => 'timeago',
    ),
...

// force locale in main config
...
'format' => array(
				'class'=>'application.modules.timeago.TimeagoFormatter',
            	'locale'=>'en_short',
        	),
...
?>
```

#Supported locales
* en (english), en_short (english shortened)
* de (german)
* fr (french)
* ja (japanese)
* ru (russian)
* uk (ukrainian)
* zn_cn (simplified chinese), zn_tw (traditional chinese)  

In future will be added more locales.  

#Requirements
* PHP 5.3
* Yii 1.1.x (tested with 1.1.10 and highter)
