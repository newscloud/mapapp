<p>You live in:
<h1> <?php echo $data['name']; ?></h1>
<div class="row">
  <div class="span5">

<?php
  $gMap->renderMap();
?>
  </div>
  <div class="span4">
    <h4>Geocoding Data:</h4>
    <p style="word-wrap:break-word;">
  <?php
  $dumper = new \Geocoder\Dumper\GeoJsonDumper();
  echo $dumper->dump($info);

  ?>
  </p>
  </div>
</div>