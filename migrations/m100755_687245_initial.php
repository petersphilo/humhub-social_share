<?php

//use yii\db\Schema;
//use yii\db\Migration;
use humhub\components\Migration;

class m100755_687245_initial extends Migration{
	
	public function up(){
		$this->createTable('social_share', [
			'id' => 'pk',
			'shared_link' => 'varchar(255) NULL',
			'originator_ID' => 'int(11) NULL',
			'originator_email' => 'varchar(255) NULL',
			'username' => 'varchar(255) NULL',
			'date_created' => 'datetime NULL DEFAULT CURRENT_TIMESTAMP',
			], '');
		$this->safeCreateIndex('originator_ID','social_share','originator_ID',false);
		}

	public function down(){
		echo "my_initial_social_share does not support migration down.\n";
		return false;
		}
}
