<?php
namespace Fuel\Migrations;

class roles
{

    function up()
    {        
        \DBUtil::create_table('roles', array(
                'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
                'type' => array('type' => 'varchar', 'constraint' => 100),
            ), array('id'), false, 'InnoDB', 'utf8_unicode_ci'
        );   
    }

    function down()
    {
       \DBUtil::drop_table('roles');
    }
}