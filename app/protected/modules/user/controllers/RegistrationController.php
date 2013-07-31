<?php

class RegistrationController extends Controller
{
	public $defaultAction = 'registration';
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
		);
	}
	/**
	 * Registration user
	 */
	public function actionRegistration() {
        Profile::$regMode = true;
        $model = new RegistrationForm;
        $profile=new Profile;

        // ajax validator
        if(isset($_POST['ajax']) && $_POST['ajax']==='registration-form')
        {
            echo UActiveForm::validate(array($model,$profile));
            Yii::app()->end();
        }

        if (Yii::app()->user->id) {
            $this->redirect(Yii::app()->controller->module->profileUrl);
        } else {
            if(isset($_POST['RegistrationForm'])) {
                $model->attributes=$_POST['RegistrationForm'];
$profile->attributes=((isset($_POST['Profile'])?$_POST['Profile']:array()));
                if($model->validate()&&$profile->validate())
                {
                    $sourcePassword = $model->password;
//$model->eid = uniqid(); $model->activkey=UserModule::encrypting(microtime().$model->password);
                    $model->password=UserModule::encrypting($model->password);
                    $model->verifyPassword=UserModule::encrypting($model->verifyPassword);
                    $model->superuser=0;
                    $model->status=((Yii::app()->controller->module->activeAfterRegister)?User::STATUS_ACTIVE:User::STATUS_NOACTIVE);

                    if ($model->save()) {
                        $profile->user_id=$model->id;
                        $profile->save();
                        if (Yii::app()->controller->module->sendActivationMail) {
                            $activation_url = $this->createAbsoluteUrl('/user/activation/activation',array("activkey" => $model->activkey, "email" => $model->email));
                            UserModule::sendMail($model->email,UserModule::t("Your registration with {site_name}",array('{site_name}'=>Yii::app()->name)),UserModule::t("To activate your account, please visit {activation_url}",array('{activation_url}'=>$activation_url)));
                        }

                        if ((Yii::app()->controller->module->loginNotActiv||(Yii::app()->controller->module->activeAfterRegister&&Yii::app()->controller->module->sendActivationMail==false))&&Yii::app()->controller->module->autoLogin) {
                                $identity=new UserIdentity($model->username,$sourcePassword);
                                $identity->authenticate();
                                Yii::app()->user->login($identity,0);
                                $this->redirect(Yii::app()->controller->module->returnUrl);
                        } else {
                            if (!Yii::app()->controller->module->activeAfterRegister&&!Yii::app()->controller->module->sendActivationMail) {
                                Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Contact Admin to activate your account."));
                            } elseif(Yii::app()->controller->module->activeAfterRegister&&Yii::app()->controller->module->sendActivationMail==false) {
                                Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please {{login}}.",array('{{login}}'=>CHtml::link(UserModule::t('Login'),Yii::app()->controller->module->loginUrl))));
                            } elseif(Yii::app()->controller->module->loginNotActiv) {
                                // Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please check your email or login."));
                                Yii::app()->user->setFlash('pre_activation','test');
                                $this->redirect(Yii::app()->baseUrl.'/site/page?view=tutorial');
                            } else {
                                // Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please check your email."));
                                Yii::app()->user->setFlash('pre_activation','test');
                                $this->redirect(Yii::app()->baseUrl.'/site/page?view=tutorial');
                            }
                            $this->refresh();
                        }
                    }
                } else $profile->validate();
            }
            $this->render('/user/registration',array('model'=>$model,'profile'=>$profile));
        }
	}
	
  	public function actionQuick() {
          Profile::$regMode = true;
          $model = new QuickregistrationForm;
          $profile=new Profile;

          // ajax validator
          if(isset($_POST['ajax']) && $_POST['ajax']==='quick-registration-form')
          {
              echo UActiveForm::validate(array($model,$profile));
              Yii::app()->end();
          }

          if (Yii::app()->user->id) {
              $this->redirect(Yii::app()->controller->module->profileUrl);
          } else {
              if(isset($_POST['RegistrationForm'])) {     
                // coming from home page
                // substitute verifyPassword
                $_POST['RegistrationForm']['verifyPassword']=$_POST['RegistrationForm']['password'];
                  // create username
                  $_POST['RegistrationForm']['username']=$_POST['Profile']['first_name'].'_'.$_POST['Profile']['last_name'].time();
                  $model->attributes=$_POST['RegistrationForm'];
$profile->attributes=((isset($_POST['Profile'])?$_POST['Profile']:array()));
                  if($model->validate()&&$profile->validate())
                  {
                      $sourcePassword = $model->password;
//  $model->eid = uniqid(); $model->activkey=UserModule::encrypting(microtime().$model->password);
                      $model->password=UserModule::encrypting($model->password);
                      $model->verifyPassword=UserModule::encrypting($model->verifyPassword);
                      $model->superuser=0;
                      $model->status=((Yii::app()->controller->module->activeAfterRegister)?User::STATUS_ACTIVE:User::STATUS_NOACTIVE);

                      if ($model->save()) {
                          $profile->user_id=$model->id;
                          $profile->save();
                          if (Yii::app()->controller->module->sendActivationMail) {
                              $activation_url = $this->createAbsoluteUrl('/user/activation/activation',array("activkey" => $model->activkey, "email" => $model->email));
                              UserModule::sendMail($model->email,UserModule::t("Your registration with {site_name}",array('{site_name}'=>Yii::app()->name)),UserModule::t("To activate your account, please visit {activation_url}",array('{activation_url}'=>$activation_url)));
                          }

                          if ((Yii::app()->controller->module->loginNotActiv||(Yii::app()->controller->module->activeAfterRegister&&Yii::app()->controller->module->sendActivationMail==false))&&Yii::app()->controller->module->autoLogin) {
                                  $identity=new UserIdentity($model->username,$sourcePassword);
                                  $identity->authenticate();
                                  Yii::app()->user->login($identity,0);
                                  $this->redirect(Yii::app()->controller->module->returnUrl);
                          } else {
                              if (!Yii::app()->controller->module->activeAfterRegister&&!Yii::app()->controller->module->sendActivationMail) {
                                  Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Contact Admin to activate your account."));
                              } elseif(Yii::app()->controller->module->activeAfterRegister&&Yii::app()->controller->module->sendActivationMail==false) {
                                  Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please {{login}}.",array('{{login}}'=>CHtml::link(UserModule::t('Login'),Yii::app()->controller->module->loginUrl))));
                              } elseif(Yii::app()->controller->module->loginNotActiv) {
                                  // Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please check your email or login."));
                                  Yii::app()->user->setFlash('pre_activation','test');
                                  $this->redirect(Yii::app()->baseUrl.'/site/page?view=tutorial');                                  
                              } else {
                                  // Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please check your email."));
                                  Yii::app()->user->setFlash('pre_activation','test');
                                  $this->redirect(Yii::app()->baseUrl.'/site/page?view=tutorial');                                  
                              }
                              $this->refresh();
                          }
                      }
                  } else $profile->validate();
              }
              $this->render('/user/registration',array('model'=>$model,'profile'=>$profile));
          }
  	}	
}