<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
Yii::import('application.modules.user.UserModule');

?>
<div class="row span12">
  <div class="span12" style="min-height:150px;">
  </div>
</div> <!-- end row -->
<div class="row span12">
  <div class ="span2" >
    
    </div>
  <div class="span5">
    <div class="home-header">
    	  <h1>Welcome to MapApp</h1>
<p>An open source demonstration app for Zillow's Neighborhood Maps, Google Maps, HTML 5 Geolocation and use of MySQL Spatial. <a href="http://jeffreifman.com/2013/08/06/how-to-use-zillow-neighborhood-maps-and-html5-geolocation/">Learn more</a>.</p>
    	</div>
    </div>  
  <div class="home-content span3">
<!--      <div class="portlet" id="yw0">
       <div id ="facebookPortlet" class="portlet-content">
        <a class="right" href="/yiiauth/default/authenticatewith/provider/facebook"><img src="http://cloud.geogram.com/images/facebook.png" alt="sign in or join via facebook" title="facebook icon" /></a>
  <div id="facebookHeader"><strong>Sign in or Join</strong><br />via Facebook</div>
      </div>
      </div>        -->
      <!--    -->
    <?php       
  $this->widget('application.modules.user.components.LoginWidget'); 
//  $this->widget('application.modules.user.components.RegistrationWidget');  
//  ,array('profile'=>$profile)
?>
  </div>
  <div class="span2">
  </div>
  </div> <!-- end row -->
  <div class="row">
    <div class="span12" style="min-height:150px;">
    </div>
  </div>
  
</div>