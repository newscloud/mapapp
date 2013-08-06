<?php
$this->breadcrumbs=array(
	'Neighborhoods'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Neighborhoods','url'=>array('index')),
	array('label'=>'Manage Neighborhoods','url'=>array('admin')),
);
?>

<h1>Create Neighborhoods</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>