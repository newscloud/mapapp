<?php
  $baseUrl = Yii::app()->baseUrl; 
  $cs = Yii::app()->getClientScript();  $cs->registerScriptFile('http://maps.google.com/maps/api/js?sensor=false');
  $cs->registerScriptFile($baseUrl.'/js/locate.js');
  $cs->registerScriptFile($baseUrl.'/js/geoPosition.js');
  
?>

<?php

  // display flash when for activation
 if(Yii::app()->user->hasFlash('success')) {
   $this->widget('bootstrap.widgets.TbAlert', array(
       'block'=>true, // display a larger alert block?
       'fade'=>true, // use transitions?
       'closeText'=>'×', // close link text - if set to false, no close link is displayed
       'alerts'=>array( // configurations per alert type
   	    'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), // success, info, warning, error or danger
       ),
   ));   
 }
?>

<?php echo $this->renderPartial('_formIndex', array('model'=>$model)); ?>
