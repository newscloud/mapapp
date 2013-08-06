<?php

class GeolocationController extends Controller
{

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
	    $model = new Geolocation();
  		if(isset($_POST['Geolocation']))
  		{
        $info = Yii::app()->geocoder->reverse($_POST['Geolocation']['lat'],$_POST['Geolocation']['lon']);
        $gps_for_sql = "Point(".$_POST['Geolocation']['lat']." ".$_POST['Geolocation']['lon'].")";
        $neighborhood = Neighborhoods::model()->lookupFromLatLon($gps_for_sql);        
        $gMap = Neighborhoods::model()->prepareMap($neighborhood['OGR_FID']);
        $marker = new EGMapMarkerWithLabel($_POST['Geolocation']['lat'],$_POST['Geolocation']['lon'], array('title' => 'You are here!'));
        $gMap->addMarker($marker);                  
        $gMap->width = '400';
        $gMap->height = '400';        
        $this->render('view',array(	'data'=>$neighborhood,'info'=>$info,'gMap' => $gMap));
      }  else
  		  $this->render('index',array(	'model'=>$model));
	}
	
	public function actionError() {
	  echo 'There was an error with geolocation. It might be that you are on a cell phone. It might be you are not locatable from this WiFi spot. It might be that you have exceeded the geolocation service limit - try again in a few minutes.';
	}
}
?>