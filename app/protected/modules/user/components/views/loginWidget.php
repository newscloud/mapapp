
<?php
 echo CHtml::beginForm(array('/user/login')); 
    
$link = '//' .
Yii::app()->controller->uniqueid .
'/' . Yii::app()->controller->action->id;
echo CHtml::hiddenField('quicklogin', $link);
?>

        <?php echo CHtml::errorSummary($model); ?>
        <div id="newSignInHeader"><strong>Administrator Access</strong></div>
        
                <?php echo CHtml::activeTextField($model,'username', array('placeholder'=>'email','size' => 30)); ?>
                <?php echo CHtml::activePasswordField($model,'password', array('size' => 15,'placeholder' => 'password', 'class' => 'narrowField')); ?>
                
                <span id="home-signin">
                <?php
                $this->widget('bootstrap.widgets.TbButton',array(
                	'label' => 'Sign in',
                	'buttonType'=>'submit',
                	'type' => 'success',
                	'size' => 'small',
                )); 
                ?>
        </span>
<?php echo CHtml::endForm(); ?>

