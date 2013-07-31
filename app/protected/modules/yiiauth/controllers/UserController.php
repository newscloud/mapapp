<?php

class UserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
	public function allowedActions()
	{
	 	return 'register,adduser,view,reputation,persona,update,sociallogin';
	}
	

	
	public function actionReputation($id){
		$user = $this->loadModel($id);
		$user->reputation = $user->reputation + 1;
		$user->save();
		echo $this->renderPartial('reputation',array('reputation'=>$user->reputation,'id'=>$id));
	
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView( $id = null , $u = null)
	{
		$owner = false;
		if ( isset ( $_POST['User'] ) )
			$this->actionUpdate( $_POST['User']['id'] );
		if (Yii::app()->user->name == $u)
		{
			$owner = true;
		}
		$this->render( 'view',
			array(
				'model'=>$this->loadModel( $id , $u ),
				'owner'=>$owner,
			));
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionRegister()
	{
		$model = new User('validation');
		// Uncomment the following line if AJAX validation is needed
		 $this->performAjaxValidation( $model , 'user-form' );
	
		if ( isset ( $_POST['User'] ) )
		{
			$model->attributes = $_POST['User'];

		//User::beforeSave() to see what is done with the values from the form
			if ( $model->validate() )
				{ if ( $model->save() )
					{
						$dir = User::USER_DIR . $model->id; 
						
						mkdir($dir,0777,true); 
						if ( Yii::app()->request->isAjaxRequest )
							{
								echo CJSON::encode( array(
									'status'=>'success', 
									'div'=>'Sign up successfull, you can login now if you want',
									'title'=>'',
									));
								exit;             
							}
						else{
							  $this->redirect( array( 'view','id' => $model->id ) );
							}
					}
				}
		}

	if ( Yii::app()->request->isAjaxRequest )
		{
			echo CJSON::encode( array (
				'status'=>'render', 
				'div'=>$this->renderPartial('modalReg',array('model'=>$model),true,true),
				'title'=>'',
				) );
			exit;             
		} else 
		{
			$this->render('Reg',array(
			'model'=>$model,
			));
		}
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate( $id )
	{
		if ( Yii::app()->request->isAjaxRequest )
			$this->layout = 'ajax';
		$model = $this->loadModel( $id );
		$model->scenario = "validation";

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if ( isset ( $_POST['User'] ) )
		{
			$model->attributes = $_POST['User'];
			if ( $model->save() )
				$this->redirect( array( '/profile/u/' . $model->username ) );				
		}
		
		$this->render( 'update', array(	'model'=>$model ));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete( $id )
	{
		if ( Yii::app()->request->isPostRequest )
		{
			// we only allow deletion via POST request
			$this->loadModel( $id )->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if ( !isset ( $_GET['ajax'] ) )
				$this->redirect( isset ( $_POST['returnUrl'] ) ? $_POST['returnUrl'] :
					array( 'admin' ) );
		}
		else
			throw new CHttpException( 400 , 
				'Invalid request. Please do not repeat this request again.' );
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider( 'User' );
		$this->render( 'index',
			array(
				'dataProvider'=>$dataProvider,
			));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new User( 'search' );
		$model->unsetAttributes();  // clear any default values
		if( isset ( $_GET['User'] ) )
			$model->attributes = $_GET['User'];

		$this->render( 'admin',
			array(
				'model'=>$model,
			));
	}
	//search the database for usernames matching a search query 
	public function actionSearch($find=null)
	{
	if( $find !== null )
			{
				$find = addcslashes($find, '%_'); // escape LIKE's special characters

					$criteria = new CDbCriteria( array(
						'condition' => "username LIKE :find",         // no quotes around :match
						'params'    => array(':find' => "%$find%")  // Aha! Wildcards go here
					) );
					//*** FIND THE DATA ***
					$dataProvider = new CActiveDataProvider('User', array(
						'pagination'=>array(
							'pageSize'=>10,
						),
						'criteria'=>$criteria,
					));
					
					$this->render('/yiiauth/user/index',array(
						'dataProvider'=>$dataProvider,
					));
			}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel( $id = null , $username = null )
	{
		if ( $id )
		$model = User::model()->findByPk( $id );
		
		if ( $username )
		$model = User::model()->find( "username = '" . $username . "'" );

		
		if ( $model === null )
			throw new CHttpException( 404,'The requested page does not exist.' );
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	
}
