<div id="preSearch" class="center">
  <?php
  $this->beginWidget('bootstrap.widgets.TbHeroUnit', array(
  	'heading'=>'Locate me!',
  ));
?>
  <p>To see where you live, we need to geolocate you:</p>
  <div class="center">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'type'=>'primary',
        'size'=>'large',
        'label'=>'Lookup Your Location Automatically',
        'url'=> 'javascript:beginSearch();'
    )); ?>    
    </div>
    <br />
    
  </p>
  <?php $this->endWidget(); ?> 
</div>

<div class="row">
  <div class="span7">
  <div id="searchArea" class="hidden">
    <div id="autolocateAlert">
    <?php 
    Yii::app()->user->setFlash('info', '<strong>Important:</strong> Your browser may ask you for permission to share your location. Please accept this request so we can locate you using your WiFi address.<br /><div class="center" style="margin:5px;"><img src="http://cloud.geogram.com/images/allow_geolocate.jpg"></div>');
    $this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, // display a larger alert block?
        'fade'=>true, // use transitions?
        'closeText'=>'×', // close link text - if set to false, no close link is displayed
        'alerts'=>array( // configurations per alert type
    	    'info'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), // success, info, warning, error or danger
        ),
    ));   
    ?> 
    </div> <!-- end autolocateAlert -->
    <p>Searching for your current location...<span id="status"></span></p>    
      <div class="center">
    <article>
    </article>  	
    <div class="form-actions hidden" id="actionBar">
      <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
      	'id'=>'geolocation-form',
      	'enableAjaxValidation'=>true,
      )); ?>
      <?php
      $this->widget('bootstrap.widgets.TbButton', array(
  			'buttonType'=>'submit',
  			'type'=>'primary',
        'size'=>'large',  			
  			'label'=>'Show My Neighborhood',
  		));
  		?>
        		<?php 
        		echo $form->hiddenField($model, 'lat','');
            echo $form->hiddenField($model, 'lon','');

      ?>
          <?php $this->endWidget(); ?>
      
  	</div> <!-- end action Bar-->
	  </div> <!-- end center -->
  </div>   <!-- end searchArea -->
  </div> <!-- end span7 -->
	</div> <!-- end row -->