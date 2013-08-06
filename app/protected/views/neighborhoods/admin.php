<?php
$this->breadcrumbs=array(
	'Neighborhoods'=>array('index'),
	'Manage',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('neighborhoods-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Neighborhoods</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'neighborhoods-grid',
	'dataProvider'=>$model->search(),
	'type'=>'striped',
	'filter'=>$model,
	'columns'=>array(
    array(
            'type' => 'raw',
            'header' => 'Neighborhood',
            'name' => 'name',
            'value' => 'CHtml::link($data->name,array(\'neighborhoods/view\',\'id\'=>$data->OGR_FID))',
            'htmlOptions'=>array('width'=>'250px')
          ),
		'city',
		'county',
		'state',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
  			'header'=>'Options',
        'template'=>'{view}',
            'buttons'=>array
            (
                'send' => array
                (
                  'options'=>array('title'=>'View'),
                  'label'=>'<i class="icon-map-marker icon-large" style="margin:5px;"></i>',
                  'url'=>'Yii::app()->createUrl("neighorhoods/view", array("id"=>$data->OGR_FID))',
                ),
            ),			
  		),
			
		),
)); ?>
