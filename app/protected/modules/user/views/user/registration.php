<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Registration");
$this->breadcrumbs=array(
	UserModule::t("Registration"),
);
?>

<h1>Sign Up with Geogram</h1>

<?php
if(Yii::app()->user->hasFlash('fb_problem')) {
  $this->widget('bootstrap.widgets.TbAlert', array(
      'block'=>true, // display a larger alert block?
      'fade'=>true, // use transitions?
      'closeText'=>'×', // close link text - if set to false, no close link is displayed
      'alerts'=>array( // configurations per alert type
  	    'fb_problem'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), 
      ),
  ));
  
}
?>
<?php
if(Yii::app()->user->hasFlash('email_problem')) {
  $this->widget('bootstrap.widgets.TbAlert', array(
      'block'=>true, // display a larger alert block?
      'fade'=>true, // use transitions?
      'closeText'=>'×', // close link text - if set to false, no close link is displayed
      'alerts'=>array( // configurations per alert type
  	    'email_problem'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), 
      ),
  ));
  
}
?>
<?php

 // display flash when user has pending invitations
if(Yii::app()->user->hasFlash('invite_waiting')) {
  $this->widget('bootstrap.widgets.TbAlert', array(
      'block'=>true, // display a larger alert block?
      'fade'=>true, // use transitions?
      'closeText'=>'×', // close link text - if set to false, no close link is displayed
      'alerts'=>array( // configurations per alert type
  	    'invite_waiting'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), // success, info, warning, error or danger
      ),
  ));   
}

?>

<?php if(Yii::app()->user->hasFlash('registration')): ?>
<div class="success">
<?php echo Yii::app()->user->getFlash('registration'); ?>
</div>
<?php else: ?>

<div class="form">
<?php $form=$this->beginWidget('UActiveForm', array(
	'id'=>'registration-form',
	'enableAjaxValidation'=>true,
	'disableAjaxValidationAttributes'=>array('RegistrationForm_verifyCode'),
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>
	
	<?php echo $form->errorSummary(array($model,$profile)); ?>

  <?php 
  		$profileFields=Profile::getFields();
  		if ($profileFields) {
  			foreach($profileFields as $field) {
  			?>
  	<div class="row">
  		<?php echo $form->labelEx($profile,$field->varname); ?>
  		<?php 
  		if ($widgetEdit = $field->widgetEdit($profile)) {
  			echo $widgetEdit;
  		} elseif ($field->range) {
  			echo $form->dropDownList($profile,$field->varname,Profile::range($field->range));
  		} elseif ($field->field_type=="TEXT") {
  			echo$form->textArea($profile,$field->varname,array('rows'=>6, 'cols'=>50));
  		} else {
  			echo $form->textField($profile,$field->varname,array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
  		}
  		 ?>
  		<?php echo $form->error($profile,$field->varname); ?>
  	</div>	
  			<?php
  			}
  		}
  ?>

	<div class="row">
	<?php echo $form->labelEx($model,'email'); ?>
	<?php echo $form->textField($model,'email'); ?>
	<?php echo $form->error($model,'email'); ?>
	</div>
	
	
	<div class="row">
	<?php echo $form->labelEx($model,'username'); ?>
	<?php echo $form->textField($model,'username'); ?>
	<?php echo $form->error($model,'username'); ?>
	</div>
	
	<div class="row">
	<?php echo $form->labelEx($model,'password'); ?>
	<?php echo $form->passwordField($model,'password'); ?>
	<?php echo $form->error($model,'password'); ?>
	<p class="hint">
	<?php echo UserModule::t("Minimal password length 4 symbols."); ?>
	</p>
	</div>
	
	<div class="row">
	<?php echo $form->labelEx($model,'verifyPassword'); ?>
	<?php echo $form->passwordField($model,'verifyPassword'); ?>
	<?php echo $form->error($model,'verifyPassword'); ?>
	</div>
	
	<?php if (UserModule::doCaptcha('registration')): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		
		<?php $this->widget('CCaptcha'); ?>
		<?php echo $form->textField($model,'verifyCode'); ?>
		<?php echo $form->error($model,'verifyCode'); ?>
		
		<p class="hint"><?php echo UserModule::t("Please enter the letters as they are shown in the image above."); ?>
		<br/><?php echo UserModule::t("Letters are not case-sensitive."); ?></p>
	</div>
	<?php endif; ?>
	
	<div class="row submit">
  	<div class="form-actions">
  		<?php $this->widget('bootstrap.widgets.TbButton', array(
  			'buttonType'=>'submit',
      	'size' => 'large',
  			'type'=>'primary',
  			'label'=> UserModule::t("Register"),
  		)); ?>
  	</div>	  
		<?php // echo CHtml::submitButton(UserModule::t("Register")); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<?php endif; ?>