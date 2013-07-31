<?php

class LogoutController extends Controller
{
	public $defaultAction = 'logout';
	
	/**
	 * Logout the current user and redirect to returnLogoutUrl.
	 */
	public function actionLogout()
	{	  
		Yii::app()->user->logout();
		//  Yii::app()->controller->module->returnLogoutUrl
		$this->redirect(Yii::app()->homeUrl);
	}

}