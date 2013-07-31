<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - About';
$this->breadcrumbs=array(
	'About',
);
?>
<div id="about">
<h1>About Geogram</h1>
<div class="right">
  <a href="http://jeffreifman.com/about"><img class="image_border" src="<?php echo Yii::app()->baseUrl; ?>/images/jeff_reifman.jpg" /></a>
</div>
<p>Geogram is a special project by <a href="http://jeffreifman.com/about">Jeff Reifman</a> of <a href="http://jeffreifman.com/consulting">NewsCloud Consulting</a>. Jeff is a technologist, writer and social activist residing in Seattle's <a href="<?php echo Yii::app()->baseUrl; ?>/green-lake">Green Lake neighborhood</a>. He created Geogram as a way to bring people together in their community.</p>

<h5>Technical Notes</h5>
<p>Geogram is built on the open source <a href="http://yiiframework.com">Yii Framework</a> for PHP. Yii sped development of Geogram and made it possible to be built by just one person. Geogram's email services are powered by <a href="http://mailgun.com">Mailgun</a>. Thank you to <a href="http://www.zillow.com/howto/api/neighborhood-boundaries.htm" target="_blank">Zillow</a> for providing neighborhood shape files for the U.S. region free of charge.</p>

<!-- <p>Geogram is also brought to you by the peace of quiet and focused concentration provided by the Bose QC 15 Noise Canceling Headphones.</p>-->
 </div>