<?php

class WebUser extends CWebUser
{

    /**
     * @var boolean whether to enable cookie-based login. Defaults to false.
     */
    public $allowAutoLogin=true;
    /**
     * @var string|array the URL for login. If using array, the first element should be
     * the route to the login action, and the rest name-value pairs are GET parameters
     * to construct the login URL (e.g. array('/site/login')). If this property is null,
     * a 403 HTTP exception will be raised instead.
     * @see CController::createUrl
     */
    public $loginUrl=array('/user/login');

    public function getRole()
    {
        return $this->getState('__role');
    }
    
    public function getId()
    {
        return $this->getState('__id') ? $this->getState('__id') : 0;
    }

//    protected function beforeLogin($id, $states, $fromCookie)
//    {
//        parent::beforeLogin($id, $states, $fromCookie);
//
//        $model = new UserLoginStats();
//        $model->attributes = array(
//            'user_id' => $id,
//            'ip' => ip2long(Yii::app()->request->getUserHostAddress())
//        );
//        $model->save();
//
//        return true;
//    }

    protected function afterLogin($fromCookie)
	{
        parent::afterLogin($fromCookie);
        $this->updateSession();
	}

    public function updateSession() {
        $user = Yii::app()->getModule('user')->user($this->id);
        // patch applied from https://github.com/jmartin82/yii-user/commit/ef3e84cdc3c5fd35de4d809ad7d59b45e1fc76c4
        if ($user!==false){        
          $this->name = $user->username;
          $userAttributes = CMap::mergeArray(array(
                                                  'email'=>$user->email,
                                                  'username'=>$user->username,
                                                  #'is_located'=>$user->is_located,
                                                  'create_at'=>$user->create_at,
                                                  'lastvisit_at'=>$user->lastvisit_at,
                                             ),$user->profile->getAttributes());
          foreach ($userAttributes as $attrName=>$attrValue) {
              $this->setState($attrName,$attrValue);
          }
        }
    }

    public function model($id=0) {
        return Yii::app()->getModule('user')->user($id);
    }

    public function user($id=0) {
        return $this->model($id);
    }

    public function getUserByName($username) {
        return Yii::app()->getModule('user')->getUserByName($username);
    }

    public function getAdmins() {
        return Yii::app()->getModule('user')->getAdmins();
    }

    public function isAdmin() {
        return Yii::app()->getModule('user')->isAdmin();
    }

}