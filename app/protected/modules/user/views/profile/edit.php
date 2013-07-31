<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Profile");
$this->breadcrumbs=array(
	UserModule::t("Profile")=>array('profile'),
	UserModule::t("Edit"),
);
?>

<h1><?php echo UserModule::t('Edit your profile'); ?></h1>

<?php 

if (Yii::app()->user->hasFlash('success')) {
  $this->widget('bootstrap.widgets.TbAlert', array(
      'block'=>true, // display a larger alert block?
      'fade'=>true, // use transitions?
      'closeText'=>'×', // close link text - if set to false, no close link is displayed
      'alerts'=>array( // configurations per alert type
  	    'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), // success, info, warning, error or danger
      ),
  ));
  
}

?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'profile-form',
	'enableAjaxValidation'=>true,
	'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>
	<?php echo $form->errorSummary(array($model,$profile)); ?>

<div class="row span12">
  <div class="span5">


    <?php 
    		$profileFields=Profile::getFields();
    		if ($profileFields) {
    			foreach($profileFields as $field) {
    			?>
    	<div class="row">
    		<?php echo $form->labelEx($profile,$field->varname);

    		if ($widgetEdit = $field->widgetEdit($profile)) {
    			echo $widgetEdit;
    		} elseif ($field->range) {
    			echo $form->dropDownList($profile,$field->varname,Profile::range($field->range));
    		} elseif ($field->field_type=="TEXT" or $field->varname =='bio') {
    			echo $form->textArea($profile,$field->varname,array('rows'=>6, 'cols'=>80));
    			// Important to note - we split the span here
    		} elseif ($field->varname =='facebook') {
    			echo $form->textField($profile,$field->varname,array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
    			if ($social===null)
    			echo '<p><em>Wish to login via Facebook? <a href="/yiiauth/default/authenticatewith/provider/facebook">Link your account</a></em></p>'; // class="popup"
    		} else {
    			echo $form->textField($profile,$field->varname,array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
    		}
    		echo $form->error($profile,$field->varname); ?>
    	</div>	
    			<?php
    			if ($field->varname=='bio') {
      			echo '</div><div class="span5">';
    			}
    			}
    		}    		
    ?>
    <!-- 
      	<div class="row">
    		<?php echo $form->labelEx($model,'username'); ?>
    		<?php echo $form->textField($model,'username',array('size'=>20,'maxlength'=>20)); ?>
    		<?php echo $form->error($model,'username'); ?>
    	</div>

    // to do - allow email change / reverify
    	<div class="row">
    		<?php echo $form->labelEx($model,'email'); ?>
    		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
    		<?php echo $form->error($model,'email'); ?>
    	</div>

    -->
    <?php echo $form->hiddenField($model,'email'); ?>

  </div> <!-- end second span -->
</div> <!-- end row -->
<div class="row buttons">
	<div class="form-actions">
    <!--  <?php //echo CHtml::submitButton($model->isNewRecord ? UserModule::t('Create') : UserModule::t('Save')); ?>
    -->
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'size'=>'large',
//			'htmlOptions'=>array('class'=>'right'),
			'label'=>$model->isNewRecord ? UserModule::t('Create') : UserModule::t('Save Settings'),
		)); ?>
	</div>

</div>
<?php $this->endWidget(); ?>
</div><!-- form -->
