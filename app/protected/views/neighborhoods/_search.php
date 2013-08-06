<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'OGR_FID',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'SHAPE',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'state',array('class'=>'span5','maxlength'=>2)); ?>

	<?php echo $form->textFieldRow($model,'county',array('class'=>'span5','maxlength'=>43)); ?>

	<?php echo $form->textFieldRow($model,'city',array('class'=>'span5','maxlength'=>64)); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>64)); ?>

	<?php echo $form->textFieldRow($model,'regionid',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
