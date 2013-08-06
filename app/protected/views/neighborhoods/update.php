<?php
$this->breadcrumbs=array(
	'Neighborhoods'=>array('index'),
	$model->name=>array('view','id'=>$model->OGR_FID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Neighborhoods','url'=>array('index')),
	array('label'=>'Create Neighborhoods','url'=>array('create')),
	array('label'=>'View Neighborhoods','url'=>array('view','id'=>$model->OGR_FID)),
	array('label'=>'Manage Neighborhoods','url'=>array('admin')),
);
?>

<h1>Update Neighborhoods <?php echo $model->OGR_FID; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>