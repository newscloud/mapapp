<?php
Yii::import('zii.widgets.CPortlet');

class RegistrationWidget extends CPortlet
{
        public function init()
        {
                parent::init();
        }

        protected function renderContent()
        {
                $this->render('registrationWidget', array('model' => new RegistrationForm()));
        }
} 
?>