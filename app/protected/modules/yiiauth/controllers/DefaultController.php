<?php

class DefaultController extends Controller
{
	public function actionIndex(){
		$this->renderPartial('index');	
	}
	public function actionauthenticatewith( $provider="" ) {

		$hybridauth_config =Yiiauth::hybridAuthConfig();
		
		$error = false;
		$user_profile = false;
		try{
		// create an instance for Hybridauth with the configuration file path as parameter
			$hybridauth = new Hybrid_Auth( $hybridauth_config );

		// try to authenticate the selected $provider
		if ( isset( $_GET['openid'] ) ){
				$provider = "openid";
				$adapter = $hybridauth->authenticate( $provider,array( "openid_identifier" => $_GET['openid'] ) );
			}else{
				$adapter = $hybridauth->authenticate( $provider );

			}
		// grab the user profile
			$user_profile = $adapter->getUserProfile();
			
		}
		catch( Exception $e ){
			// Display the recived error
			switch( $e->getCode() ){ 
				case 0 : $error = "Unspecified error."; break;
				case 1 : $error = "Hybridauth configuration error."; break;
				case 2 : $error = "Provider not properly configured."; break;
				case 3 : $error = "Unknown or disabled provider."; break;
				case 4 : $error = "Missing provider application credentials."; break;
				case 5 : $error = "Authentification failed. The user has canceled the authentication or the provider refused the connection."; break;
				case 6 : $error = "User profile request failed. Most likely the user is not connected to the provider and he should to authenticate again."; 
					     $adapter->logout(); 
					     break;
				case 7 : $error = "User not connected to the provider."; 
					     $adapter->logout(); 
					     break;
			} 

			// well, basically your should not display this to the end user, just give him a hint and move on..
			$error .= "<br /><br /><b>Original error message:</b> " . $e->getMessage(); 
			$error .= "<hr /><pre>Trace:<br />" . $e->getTraceAsString() . "</pre>";  

		}
		/**$user_profile->identifier; //unique id
		$provider; // the provider name
		$_GET['openid'];//the extra_info
		**/
		
		// workOnUser returns an user object
		if ( is_object ($user_profile) ){
		$user = $this->workOnUser($provider,$user_profile->identifier,$user_profile);
		  if ($user===false or is_null($user)) {
		    lg('Alert: Facebook error:');
		    lg(varDumpToString($user_profile));
        Yii::app()->user->setFlash('fb_problem','Sorry, we are having trouble identifying you with Facebook. Please sign up normally.');
        $this->redirect('/user/registration');
		  }
			if ( $this->autoLogin($user) ){
				//successful login
        $this->redirect('/userlocation/locate');
/*
				$this->render('profile',
					array(
					'error'=>$error, //string
					'provideruser'=>$user_profile,//object
					'yiiuser'=>$user, //object
					'provider'=>$provider,	//string
					) );
*/
				}else{
					// this is where u go otherwise
					$this->render('authenticatewith',array('error'=>$error,'user_profile'=>$user_profile ) );
					}
			}else{
					echo "Something wrong with ".$provider;
				}
	}
	
  public function workOnUser($provider,$provideruser,$profile){
      // $provideruser has profile details from provider
                  $social = Social::model()->find("provider='".$provider."' AND provideruser='".$provideruser."'");
                  if ( $social ){
                           $user = User::model()->find("id=".$social->yiiuser);
                           // $user['yiiuser'] has app user id
                           return $user;
                  }else{ // no user is connected to that provideruser, 
                          $social = new Social; // a new relation will be needed
                          $social->provider = $provider; // what provider
                          $social->provideruser = $provideruser; // the unique user

                          // if a yii-user is already logged in add the provideruser to that account
                          if ( !Yii::app()->user->isGuest ){
                                  $social->yiiuser = Yii::app()->user->id;        
                                  $user = User::model()->findByPk(Yii::app()->user->id);
                                  lg('Existing user'.$social->yiiuser);
                                  lg('Existing user email'.$user->email);
                                  // capture profile info
                                  $user_profile = Yii::app()->getModule('user')->user($social->yiiuser)->profile;
                                  $user->model()->syncProfileViaHybridAuth($user_profile,$profile);
                          } else {
                            lg('Facebook email: '.$profile->email);
                            $user = User::model()->findByAttributes(array('email'=>$profile->email));
                            if (!is_null($user)) {
                              // to do - create way to associate facebook account
                              Yii::app()->user->setFlash('email_problem','Your email already exists. Please log in to your Geogram account with your email. Once you sign in, you can link your Facebook account.');
                              Yii::app()->user->setReturnUrl('/user/profile/edit');
                              $this->redirect('/user/login');
                            }     
                            if($profile->email=='') {
                              lg ('Hybrid: No email, report error & redirect user');
                              Yii::app()->user->setFlash('email_problem','Because Geogram is primarily an email group service, we require your email for registration.');
                              $this->redirect('/user/registration');
                            }                         
                          // we want to create a new user
                          // take new profile info and register user
                            lg('New user');
                              $user_id =User::model()->registerViaHybridAuth($profile);
                              if ($user_id!==false) {
                                $user = User::model()->findByPk($user_id);                                
                                $social->yiiuser = $user->id;
                              }
                              // to do - if no new user_id
                              // then social id is 0 and future log in will fail
                          // return with new user id
                          }
                          if($social->save())
                            return $user;
                  }
          }

         public function autoLogin($user) //accepts a user object
         {
         $identity=new UserIdentity($user->username, "");
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
}?>