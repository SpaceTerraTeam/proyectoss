<?php
namespace Fuel\Migrations;

class planetas
{

    function up()
    {        
        \DBUtil::create_table('planetas', array(
                'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
                'name' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
                'description' => array('type' => 'varchar', 'constraint' => 100),
                'model' => array('type' => 'varchar', 'constraint' => 250),
                'speed' => array('type' => 'float', 'constraint' => 25),
                'size' => array('type' => 'float', 'constraint' => 25),
                'picture' => array('type' => 'varchar', 'constraint' => 250, 'null' => false),
            ), array('id'), false, 'InnoDB', 'utf8_unicode_ci'
        );   
    }

    function down()
    {
       \DBUtil::drop_table('planetas');
    }
}