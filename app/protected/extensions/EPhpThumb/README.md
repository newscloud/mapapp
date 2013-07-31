#INTRODUCTION
This Yii extension is a wrapper for phpThumb (http://phpthumb.gxdlabs.com/). It doesn't do anything
fancy, just the things phpThumb does but in a way that is familiar for Yii users

#USAGE
Config in main.php

	'phpThumb'=>array(
		'class'=>'ext.EPhpThumb.EPhpThumb',
		'options'=>array(optional phpThumb specific options are added here)
	),

Creating thumbnails

	$thumb=Yii::app()->phpThumb->create('../images/myImage.jpg');
	$thumb->resize(100,100);
	$thumb->save('../images/thumb.jpg');

#LICENSE
phpThumb is released under the MIT license, so is this extension