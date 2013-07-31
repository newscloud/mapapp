<?php

class SocialController extends Controller
{
	public function actionIndex()
	{
		$this->renderPartial('index');
	}
	public function actionDelete( $id )
	{
		
		if (Yii::app()->request->isAjaxRequest )
		{
			// we only allow deletion via POST request
			$model = $this->loadModel( $id );
			if ($model->yiiuser == Yii::app()->user->id)
				$model->delete();
				echo CJSON::encode(
						array(
							'status'=>'success', 
							'div'=>'You removed the provider from your account',	
							));
					exit;
		}
		else
			throw new CHttpException( 400 , 
				'Invalid request. Please do not repeat this request again.' );
	}


	
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel( $id = null , $userid = null )
	{
		if ( $id )
		$model = Social::model()->findByPk( $id );
		
		if ( $userid )
		$model = Social::model()->find( "yiiuser = '" . $userid . "'" );

		
		if ( $model === null )
			throw new CHttpException( 404,'The requested page does not exist.' );
		return $model;
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}