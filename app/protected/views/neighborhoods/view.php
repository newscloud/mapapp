<?php
$this->breadcrumbs=array(
	'Neighborhoods'=>array('index'),
	$model->name,
);

?>

<h1>View <?php echo $model->name; ?> Neighborhood</h1>

<?php
  $gMap->renderMap();
?>
<p></p>
<p><a href="<?php echo ($model->OGR_FID+1); ?>">Next neighborhood</a></p>