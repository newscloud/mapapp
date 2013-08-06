<?php
$this->breadcrumbs=array(
	'Neighborhoods',
);

$this->menu=array(
	array('label'=>'Create Neighborhoods','url'=>array('create')),
	array('label'=>'Manage Neighborhoods','url'=>array('admin')),
);
?>

<h1>Neighborhoods</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
