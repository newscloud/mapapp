<?php

class m130505_001935_add_profile_image_field_to_user_profile_table extends CDbMigration
{
       protected $MySqlOptions = 'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci';
     public $tablePrefix;
     public $tableName;

     public function before() {
       $this->tablePrefix = Yii::app()->getDb()->tablePrefix;
       if ($this->tablePrefix <> '')
         $this->tableName = Yii::app()->getModule('user')->tableProfiles;
     }

   	public function safeUp()
   	{
   	  $this->before();   	  
      $this->addColumn($this->tableName,'profile_image_url','VARCHAR(255) DEFAULT NULL');
  		$this->insert(Yii::app()->getModule('user')->tableProfileFields, array(
              "varname" => "profile_image_url",
              "title" => "Profile Image",
              "field_type" => "VARCHAR",
              "field_size" => "255",
              "field_size_min" => "0",
              "required" => "0",
              "match" => "",
              "range" => "",
              "error_message" => "Incorrect image url.",
              "other_validator" => "",
              "default" => "",
              "widget" => "UWprofilepic",
              "widgetparams" => '{"rawPath":"assets/pic/raw/","path":"assets/pic/thumb/","defaultPic":"images/default.jpg","maxRawW":"200","maxRawH":"200","thumbW":"50","thumbH":"50","maxSize":"2","types":"jpg,jpeg,png,gif"}',
              "position" => "10",
              "visible" => "3",
          ));
      
   	}

   	public function safeDown()
   	{
   	  	$this->before();
        $this->dropColumn($this->tableName,'profile_image_url');        
          $del = Yii::app()->db->createCommand()            ->delete(Yii::app()->getModule('user')->tableProfileFields,'varname=:varname', array(':varname'=>'profile_image_url'));
   	}
}