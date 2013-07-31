<?php

class m130402_170004_create_auth_tables extends CDbMigration
{
    protected $MySqlOptions = 'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci';
    public $tablePrefix;
    public $tableName;
    
    public function setTable($tblName) {
      $this->tablePrefix = Yii::app()->getDb()->tablePrefix;
      if ($this->tablePrefix <> '')
        $this->tableName = $this->tablePrefix.$tblName;
    }

  	public function safeUp()
  	{
  	$this->setTable('AuthItem');
   $this->createTable($this->tableName, array(
              'name'=> 'VARCHAR(64) NOT NULL',            
              'type'=> 'INTEGER NOT NULL',            
              'description'=> 'TEXT',            
              'bizrule'=> 'TEXT',            
              'data'=> 'TEXT',            
                ), $this->MySqlOptions);
    $this->addPrimaryKey('AuthItem_pk',$this->tableName,'name');

   	$this->setTable('AuthItemChild');
    $this->createTable($this->tableName, array(
               'parent'=> 'VARCHAR(64) NOT NULL',            
               'child'=> 'VARCHAR(64) NOT NULL',            
                 ), $this->MySqlOptions);
     $this->addPrimaryKey('AuthItemChild_pk',$this->tableName,'parent,child');
     $this->addForeignKey('fk_AuthItemChild_parent', $this->tableName, 'parent', $this->tablePrefix.'AuthItem', 'name', 'CASCADE', 'CASCADE');
     $this->addForeignKey('fk_AuthItemChild_child', $this->tableName, 'child', $this->tablePrefix.'AuthItem', 'name', 'CASCADE', 'CASCADE');

    	$this->setTable('AuthAssignment');
     $this->createTable($this->tableName, array(
                'itemname'=> 'VARCHAR(64) NOT NULL',            
                'userid'=> 'VARCHAR(64) NOT NULL',            
                'bizrule'=> 'TEXT',            
                'data'=> 'TEXT',            
                  ), $this->MySqlOptions);
      $this->addPrimaryKey('AuthAssignment_pk',$this->tableName,'itemname,userid');
      $this->addForeignKey('fk_AuthAssignment_itemname', $this->tableName, 'itemname', $this->tablePrefix.'AuthItem', 'name', 'CASCADE', 'CASCADE');

  	}

  	public function safeDown()
  	{
    	$this->setTable('AuthAssignment');
      $this->dropForeignKey('fk_AuthAssignment_itemname', $this->tableName);	
//      $this->dropPrimaryKey('AuthAssignment_pk', $this->tableName);	  	
	    $this->dropTable($this->tableName);
     	$this->setTable('AuthItemChild');
      $this->dropForeignKey('fk_AuthItemChild_child', $this->tableName);	  	
      $this->dropForeignKey('fk_AuthItemChild_parent', $this->tableName);	  	
      //$this->dropPrimaryKey('AuthItemChild_pk',$this->tableName);	  	
      $this->dropTable($this->tableName);
      $this->setTable('AuthItem');
//      $this->dropPrimaryKey('AuthItem_pk', $this->tableName);	  	  	  
      $this->dropTable($this->tableName);
  	}

  }  
