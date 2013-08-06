<?php /* @var $this Controller */ 
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/main.js'); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
  <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<!-- font-awesome -->
  <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
  <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" >

		<?php $this->widget('bootstrap.widgets.TbNavbar', array(
#	'brand' => '<img src="http://cloud.geogram.com/images/geogram-logo.gif" style="height:30px;">',
	'brandUrl'=>Yii::app()->getBaseUrl(true),
	'collapse' => true,
	'items' => array(
	  (!Yii::app()->user->isGuest ? array(	   
			'class' => 'bootstrap.widgets.TbMenu',
			'htmlOptions'=>array('class'=>'pull-left'),
			'items' => array(
				array('label'=>'Neighborhoods', 'url'=>array('/neighborhoods/index'), ),
				array('label'=>'Geolocation', 'url'=>array('/geolocation/index'), ),
			)
  	  ) : array() ),

		array(
			'class' => 'bootstrap.widgets.TbMenu',
			'htmlOptions'=>array('class'=>'pull-right'),
			'items' => array(
        array('label'=>'About', 'items'=> array(
 array('url'=>'http://jeffreifman.com/2013/07/31/how-to-use-zillow-neighborhood-maps-and-html5-geolocation', 'label'=>'Read the tutorial'),
        array('url'=>'https://github.com/newscloud/mapapp', 'label'=>'Get the code'),
        array('url'=>'http://jeffreifman.com/consulting', 'label'=>'About NewsCloud'),
				)),
				array('label'=>'Account', 'items'=> array(
          array('label'=>'Hi '.getFirstName(), 'visible'=>!Yii::app()->user->isGuest),
array('url'=>Yii::app()->getModule('user')->loginUrl, 'label'=>Yii::app()->getModule('user')->t("Login"), 'visible'=>Yii::app()->user->isGuest),
array('url'=>Yii::app()->getModule('user')->registrationUrl, 'label'=>Yii::app()->getModule('user')->t("Sign up"), 'visible'=>Yii::app()->user->isGuest),
array('url'=>Yii::app()->getModule('user')->logoutUrl, 'label'=>'Sign out', 'visible'=>!Yii::app()->user->isGuest),			

				)),
			),
		)
	)
));	 ?>
	<!-- mainmenu -->
	<?php //if(isset($this->breadcrumbs))     $this->widget('bootstrap.widgets.TbBreadcrumbs', array('links'=>array('Library'=>'#', 'Data'),)); ?>
	<div class="nav_spacer">&nbsp;</div>
	  
	<?php echo $content; ?>

	<div class="clear"></div>

  	<div id="footer">
    <div class="right"><a href="http://www.zillow.com/howto/api/neighborhood-boundaries.htm" target="_blank"><img src="http://cloud.geogram.com/images/zillow.gif"></a></div>
    <div class="left"><ul class="inline"><li>&copy; <?php echo date('Y'); ?> <a href="http://jeffreifman.com/consulting">NewsCloud Consulting</a></li><li><span class="dot divider"> &middot;</span></li><li><a href="https://twitter.com/intent/user?screen_name=reifman">@reifman</a></li></ul></div>
  	</div><!-- footer -->

  </div><!-- page -->

</div><!-- page -->
</body>
</html>
