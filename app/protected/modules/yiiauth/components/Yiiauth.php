<?php class Yiiauth extends CController{
	public static function hybridAuthConfig(){
	require_once( '/var/www/geogram/geo/hybridauth/Hybrid/Auth.php' );

//if (Yii::app()->params['env']=='live') 
//else
//	  require_once( '/Users/Jeff/sites/yii/geo/hybridauth/Hybrid/Auth.php' );

	/*!
	* HybridAuth
	* http://hybridauth.sourceforge.net | https://github.com/hybridauth/hybridauth
	*  (c) 2009-2011 HybridAuth authors | hybridauth.sourceforge.net/licenses.html
	*/

	// ----------------------------------------------------------------------------------------
	//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
	// ----------------------------------------------------------------------------------------
	return Yii::app()->controller->module->config;	
	}
	
	/* this method take a provider and the unique provideruser id
		if its the first time this provideruser logs in:
		* No yii user currently logged in? make a new yii user account and connect them
		* A yii user currently logged in? connect that user to this provideruser
		
		else if the provider user is connected to a yii user already, find that.
		Then a user model is returned that can be logged in.
	*/
	public function workOnUser($provider,$provideruser){
		$userClass = Yii::app()->controller->module->userClass;

		$social = Social::model()->find("provider='".$provider."' AND provideruser='".$provideruser."'");
		if ( $social ){
			 $user = $userClass::model()->find("id=".$social->yiiuser);
			 return $user;
		}else{ // no user is connected to that provideruser, 
			$social = new Social; // a new relation will be needed
			$social->provider = $provider; // what provider
			$social->provideruser = $provideruser; // the unique user
			
			// if a yii-user is already logged in add the provideruser to that account
			if ( !Yii::app()->user->isGuest ){
				$social->yiiuser = Yii::app()->user->id;	
				$user = $userClass::model()->findByPk(Yii::app()->user->id);
			}else{
			// we want to create a new $userClass
				$user = new $userClass;
				$user->username = $provideruser; 

				if ( $user->save() ){ //we get an user id
					$social->yiiuser = $user->id;
					}
			}
			if($social->save())
				return $user;
		}
	
	}
	
	public function autoLogin($user) //accepts a user object
	{
	$identity=new userIdentity($user->username, "");
	$identity->hybridauth($user->username);
	if ( $identity->errorCode == UserIdentity::ERROR_NONE )
		{
			$duration= 3600*24*30; // 30 days
			Yii::app()->user->login($identity,$duration);
			return true;
		}
		else
		{
			echo $identity->errorCode;
			return false;
		}
	
	}
public function newAuth(){
	$hybridauth_config = $this->hybridAuthConfig();
	return $hybridauth = new Hybrid_Auth( $hybridauth_config );

}
public function twitterApi($api){
$hybridauth = $this->newAuth();
	
	# try to authenticate with some providers
	 
	$twitter = $hybridauth->authenticate( "Twitter" );	 
	return $response = $twitter->api()->get( $api); 
}

/*
Stores a session in a database, so you can retrieve it later with loadSession()
This makes u able to skip authentication
*/
public function storeSession($provider){
	$hybridauth = $this->newAuth();
	//authenticate with the providers
	$hybridauth->authenticate($provider);
	$hybridauth_session_data = $hybridauth->getSessionData();
	$model = new UserSessions;
	$model->user_id = Yii::app()->user->id;
	$model->hybridauth_session = $hybridauth_session_data;
	
	if($model->save(false)){
		return true;
	}else{
		return false;
	}
}
//loads a session previously stored for a user to skip authentication
/* 
	example use:
	$hybridauth = $this->loadSesson();
	 call back an instance of Twitter adapter
	$twitter = $hybridauth->getAdapter( "Twitter" );
	 regrab te user profile
	$user_profile = $twitter->getUserProfile(); 
*/
public function loadSession(){
	$hybridauth = $this->newAuth();
	$model = UserSession::model()->findByPk(Yii::app()->user->id);
	$hybridauth_session_data = $model->hybridauth_session;
	// then call Hybrid_Auth::restoreSessionData() to get stored data
	$hybridauth->restoreSessionData( $hybridauth_session_data );

	return $hybridauth;
}

public function facebookApi($api){
	$hybridauth = $this->newAuth();
	
	# try to authenticate with some providers
	 
	$facebook = $hybridauth->authenticate( "Facebook" );	 

	return $response = $facebook->api()->api($api); 

}
public function updateStatus($provider,$status){
	$this->newAuth();
	// these providers are the only ones who support status updating
	$providers = array('facebook', 'twitter', 'identica', 'linkedin', 'qq', 'sina', 'murmur', 'pixnet','plurk');
		
	if(in_array($provider,$providers)){
			//
			$adapter = $hybridauth->authenticate( $provider );
			// update the user status
			if($provider !== 'Facebook'){
				$adapter->setUserStatus( $status ); 
			}else{
				//facebook have some more options
				if(is_array($status)){
					$adapter->setUserStatus(
					array(
					"message" => $status['message'], // status or message content
					"link" => $status['link'], // webpage link
					"picture" => $status['picture'] // a picture link
					));
				}else{$adapter->setUserStatus($status);}
			}
		}
	}
}?>