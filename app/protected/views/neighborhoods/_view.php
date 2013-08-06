<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('OGR_FID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->OGR_FID),array('view','id'=>$data->OGR_FID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SHAPE')); ?>:</b>
	<?php echo CHtml::encode($data->SHAPE); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('state')); ?>:</b>
	<?php echo CHtml::encode($data->state); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('county')); ?>:</b>
	<?php echo CHtml::encode($data->county); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('city')); ?>:</b>
	<?php echo CHtml::encode($data->city); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('regionid')); ?>:</b>
	<?php echo CHtml::encode($data->regionid); ?>
	<br />


</div>