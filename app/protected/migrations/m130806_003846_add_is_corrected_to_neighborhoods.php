<?php

class m130806_003846_add_is_corrected_to_neighborhoods extends CDbMigration
{
   protected $MySqlOptions = 'ENGINE=MyISAM CHARSET=utf8 COLLATE=utf8_unicode_ci';
   public $tablePrefix;
   public $tableName;

   public function before() {
     $this->tablePrefix = Yii::app()->getDb()->tablePrefix;
     if ($this->tablePrefix <> '')
       $this->tableName = $this->tablePrefix.'neighborhoods';
   }

 	public function safeUp()
 	{
 	  $this->before();
    $this->addColumn($this->tableName,'is_corrected','tinyint default 0');
 	}

 	public function safeDown()
 	{
 	  	$this->before();
      $this->dropColumn($this->tableName,'is_corrected');
 	}
}