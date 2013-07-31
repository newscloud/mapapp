
<?php echo CHtml::beginForm(array('/user/registration/quick/')); 
$link = '//' .
Yii::app()->controller->uniqueid .
'/' . Yii::app()->controller->action->id;
echo CHtml::hiddenField('quickregistration', $link);
?>

      	<?php echo CHtml::errorSummary(array($model)); ?>
            <div id="newSignUpHeader"><strong>New to Geogram?</strong> Sign up</div>

                <?php 
                $profile = new Profile;
                echo CHtml::activeTextField($profile,'first_name', array('size' => 12, 'value' => '', 'placeholder' => 'first name','class' => 'narrowField inlineField'));  
                echo CHtml::activeTextField($profile,'last_name', array('size' => 12, 'value' => '','placeholder' => 'last name', 'class' => 'narrowField inlineField'));
                
                ?>          
                
                <?php echo CHtml::activeTextField($model,'email', array('size' => 30, 'value' => '','placeholder'=>'email')) ?>
        
                <?php echo CHtml::activePasswordField($model,'password', array('size' => 15,'value' => '','placeholder'=>'password','class' => 'narrowField')); ?>

                <span id="home-signup">

                <?php
                //         <div class=" signup-button"></div>
                 $this->widget('bootstrap.widgets.TbButton',array(
                	'label' => 'Sign up',
                	'type' => 'warning',
                	'size' => 'small',
                	'buttonType'=>'submit'
                )); ?> </div>
        
<?php echo CHtml::endForm(); ?>
