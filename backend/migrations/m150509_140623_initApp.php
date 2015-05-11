<?php

use yii\db\Schema;
use yii\db\Migration;

class m150509_140623_initApp extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK.' NOT NULL AUTO_INCREMENT',
            'username' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING,
            'access_token' => Schema::TYPE_STRING. '(64) NOT NULL',
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'role' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'avatar' => Schema::TYPE_STRING . ' DEFAULT \'svg-2\'',
        ], $tableOptions);

        $this->createTable('{{%result}}', [
            'result_id' => Schema::TYPE_PK.' NOT NULL AUTO_INCREMENT',
            'user_id' => Schema::TYPE_INTEGER.'  NOT NULL',
            'points' => Schema::TYPE_SMALLINT . ' DEFAULT \'0\'',
            'max_points' => Schema::TYPE_SMALLINT . ' DEFAULT \'0\'',
        ], $tableOptions);

        $this->createTable('{{%dictionary}}', [
            'id' => Schema::TYPE_PK.' NOT NULL AUTO_INCREMENT',
            'text_eng' => Schema::TYPE_STRING.'  NOT NULL',
            'text_rus' => Schema::TYPE_STRING.'  NOT NULL',
        ], $tableOptions);

        $this->createTable('{{%answer_wrong}}', [
            'word_id' => Schema::TYPE_INTEGER.'  NOT NULL',
            'user_id' => Schema::TYPE_INTEGER.'  NOT NULL',
            'type' => 'enum(\'text_eng.text_rus\',\'text_rus.text_eng\') DEFAULT NULL',
            'answer' => Schema::TYPE_STRING.'  NOT NULL'
        ], $tableOptions);

        $this->addForeignKey('user_result_fkey', '{{%result}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('wans_word_fkey', '{{%answer_wrong}}', 'word_id', '{{%dictionary}}', 'id', 'NO ACTION', 'CASCADE');
        $this->addForeignKey('wans_user_fkey', '{{%answer_wrong}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->batchInsert('{{%user}}', [
            'username', 
            'auth_key', 
            'password_hash',
            'password_reset_token',
            'access_token',
            'email',
            'role',
            'status',
            'created_at',
            'updated_at',
            'avatar'],
            [
                ['Ivan Ivanov', 'tUu1qHcde0diwUol3xeI-18MuHkkprQI', '$2y$13$nJ1WDlBaGcbCdbNC5.5l4.sgy.OMEKCqtDQOdQ2OWpgiKRWYyzzne', 'RkD_Jw0_8HEedzLk7MM-ZKEFfYR7VbMr_1392559491', '9dccf02964f60444299a8b20f095f57b5d2ad15b083e359940ce3d2f919377a7', 'test@ang.app', '10', '10', '1431181481', '1431181481', 'svg-4'],
                ['Igor Kozlovsky', 'tUu1qHcde0diwUol3xeI-18MuHkkprQI', '$2y$13$nJ1WDlBaGcbCdbNC5.5l4.sgy.OMEKCqtDQOdQ2OWpgiKRWYyzzne', 'RkD_Jw0_8HEedzLk7MM-ZKEFfYR7VbMr_1392559491', 'bfe1fdaa0699d05f42c8cbc602087706447b5e24b243b99c5ea3070c3ff79c0e', 'lemon007@yandex.ru', '1111', '10', '1431181481', '1431181481', 'svg-2']
            ]   
        );

        $this->batchInsert('{{%dictionary}}', [
                'text_eng',
                'text_rus'
            ], [
                ['apple', 'яблоко'],
                ['pear', 'груша'],
                ['orange', 'апельсин'],
                ['grape', 'виноград'],
                ['lemon', 'лимон'],
                ['pineapple', 'ананас'],
                ['watermelon', 'арбуз'],
                ['coconut', 'кокос'],
                ['banana', 'банан'],
                ['pomelo', 'помело'],
                ['strawberry', 'клубника'],
                ['raspberry', 'малина'],
                ['melon', 'дыня'],
                ['peach', 'персик'],
                ['apricot', 'абрикос'],
                ['mango', 'манго'],
                ['pear', 'слива'],
                ['pomegranate', 'гранат'],
                ['cherry', 'вишня']
            ]);

    }

    public function down()
    {
        echo "m150509_140623_initApp cannot be reverted.\n";
        
        $this->dropForeignKey('user_result_fkey', '{{%result}}');
        $this->dropForeignKey('wans_word_fkey', '{{%answer_wrong}}');
        $this->dropForeignKey('wans_user_fkey', '{{%answer_wrong}}');

        $this->dropTable('{{%user}}');
        $this->dropTable('{{%dictionary}}');
        $this->dropTable('{{%result}}');
        $this->dropTable('{{%answer_wrong}}');
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
