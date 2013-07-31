<?php

class m130614_032209_create_yii_hybrid_auth_tables extends CDbMigration
{
   protected $MySqlOptions = 'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci';
   public $tablePrefix;
   public $tableName;

   public function before() {
     $this->tablePrefix = Yii::app()->getDb()->tablePrefix;
   }

 	public function safeUp()
 	{
 	  $this->before();
  	if ($this->tablePrefix <> '')
      $this->tableName = $this->tablePrefix.'social';
      
  $this->createTable($this->tableName, array(
             'id' => 'pk',
             'yiiuser'=> 'INTEGER DEFAULT 0',
             'provider' => 'VARCHAR(50) NOT NULL',
             'provideruser' => 'VARCHAR(255) NOT NULL',
               ), $this->MySqlOptions);
              $this->createIndex('social_id', $this->tableName , 'id', true);
//              $this->addForeignKey('fk_social_user_id', $this->tableName, 'yiiuser', $this->tablePrefix.'users', 'id', 'CASCADE', 'CASCADE');

      if ($this->tablePrefix <> '')
        $this->tableName = $this->tablePrefix.'user_sessions';
      $this->createTable($this->tableName, array(
                  'user_id'=> 'INTEGER NOT NULL',
                  'hybridauth_session' => 'TEXT NOT NULL',
                  'updated_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
                    ), $this->MySqlOptions);
                   $this->addForeignKey('fk_user_sessions_id', $this->tableName, 'user_id', $this->tablePrefix.'users', 'id', 'CASCADE', 'CASCADE');
 	}

 	public function safeDown()
 	{
 	  	$this->before();
 	  	if ($this->tablePrefix <> '')
        $this->tableName = $this->tablePrefix.'social';
// 	  	$this->dropForeignKey('fk_social_user_id', $this->tableName);
       $this->dropIndex('social_id', $this->tableName);
 	    $this->dropTable($this->tableName);
      
      if ($this->tablePrefix <> '')
        $this->tableName = $this->tablePrefix.'user_sessions';
 	  	$this->dropForeignKey('fk_user_sessions_id', $this->tableName);
 	    $this->dropTable($this->tableName);      
 	}
  
}
