<?php
namespace Fuel\Migrations;

class orbitas
{

    function up()
    {        
        \DBUtil::create_table('orbitas', array(
                'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
                'radius' => array('type' => 'float', 'constraint' => 25),
            ), array('id'), false, 'InnoDB', 'utf8_unicode_ci'
        );
    }

    function down()
    {
       \DBUtil::drop_table('orbitas');
    }
}