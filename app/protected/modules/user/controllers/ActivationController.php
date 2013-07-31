<?php

class ActivationController extends Controller
{
	public $defaultAction = 'activation';

	
	/**
	 * Activation user account
	 */
	public function actionActivation () {
		$email = $_GET['email'];
		$activkey = $_GET['activkey'];
		if ($email&&$activkey) {
			$find = User::model()->notsafe()->findByAttributes(array('email'=>$email));
			if (isset($find)&&$find->status) {			    
			    $this->autoLogin($find->username);
			    // update user_id in invite table
			    Invite::model()->updateNewUser($find);
			    // account already active
    			Yii::app()->user->setFlash('success','Congratulations! Your account is now active. Please follow the directions below to set up your location.');
    	    $this->redirect('/userlocation/locate');
//			    $this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Your account is active.")));
			} elseif(isset($find->activkey) && ($find->activkey==$activkey)) {
				$find->activkey = UserModule::encrypting(microtime());
				$find->status = 1;
				$find->save();
		    $this->autoLogin($find->username);
		    // direct to autolocate with activation message
  			Yii::app()->user->setFlash('success','Congratulations! Your account is now active. Please follow the directions below to set up your location.');
  	    $this->redirect('/userlocation/locate');
			    // $this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Your account is activated.")));    			
			} else {
			    $this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Incorrect activation URL. Please email support@geogram.com if you need assistance.")));
			}
		} else {
			$this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Incorrect activation URL. Please email support@geogram.com if you need assistance.")));
		}
	}

  public function autoLogin($username) {
    $identity=new UserIdentity($username,null);
    $identity->skip_auth= true;
    $identity->authenticate();
    Yii::app()->user->login($identity);
		$identity->authenticate();
  }
}