<div class="row">
  <div class="span1">
    </div>
    <div class="span10">

<div id="tutorial">
  <?php
  $this->pageTitle=Yii::app()->name . ' - How It Works';
  $this->beginWidget('bootstrap.widgets.TbHeroUnit', array(
  	'heading'=>'How It Works',
  ));  
$i=1;
?>
<p>Geogram helps you build community where you live. Create places for your building, block, neighborhood or favorite businesses. Each place acts as its own email group allowing everyone to keep in touch.</p>   

<?php

// if they just registered, ask them to verify their email address 
if(Yii::app()->user->hasFlash('pre_activation')) { ?>
  <p><span class="badge badge-success"><?php echo $i; $i++; ?></span> Verify your email address. Check your inbox and click the activation link. <em>If you didn't receive an activation email when you signed up, check your junk mail folder or email support@geogram.com.</em></p>
<?php }  else if(Yii::app()->user->isGuest) { ?>
  <p><span class="badge badge-success"><?php echo $i; $i++; ?></span> Start by <a href="<?php echo Yii::app()->baseUrl; ?>/user/registration">signing up</a>. We'll never share your email address with anyone.</p>
  <p><span class="badge badge-success"><?php echo $i; $i++; ?></span> Verify your email address. Check your inbox and click the activation link. <em>If you didn't receive an activation email when you signed up, check your junk mail folder or email support@geogram.com.</em></p>
  <?php } ?>

<p><span class="badge badge-success"><?php echo $i; $i++; ?></span> Geogram will locate your address (or you can enter it manually)</p>
<p><span class="badge badge-success"><?php echo $i; $i++; ?></span> Join a place or create one for your block or favorite business</p>
<p><span class="badge badge-success"><?php echo $i; $i++; ?></span> Invite your friends or print flyers to announce your place</p>
<p><span class="badge badge-success"><?php echo $i; $i++; ?></span> Email members of places at the Geogram website or by sending messages to place-address@geogram.com</p>
<div class="center">
<?php
  if(!Yii::app()->user->hasFlash('pre_activation')) {
    if(Yii::app()->user->isGuest) {
      $this->widget('bootstrap.widgets.TbButton', array(
      	'type'=>'primary',
        'size'=>'large',  			
      	'label'=>'Sign Up Now',
      	'url' => Yii::app()->baseUrl.'/',
      ));
    } else {
      $this->widget('bootstrap.widgets.TbButton', array(
      	'type'=>'primary',
        'size'=>'large',  			
      	'label'=>'Visit Your Places',
      	'url' => Yii::app()->baseUrl.'/place',
      ));
    }
  }
?>
</div>
  </div>
    
  </p>
  <?php $this->endWidget(); ?> 
</div>
  <div class="span1">
  </div>
</div>

</div> <!-- end row -->