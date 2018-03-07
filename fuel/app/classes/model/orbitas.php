<?php

class Model_Orbitas extends Orm\Model
{
	protected static $_table_name = 'orbitas';
    protected static $_properties = array('id', 'radius');
    protected static $_primary_key = array('id');
    protected static $_many_many = array(
    'estrellas' => array(
        'key_from' => 'id',
        'key_through_from' => 'id_orbita', // column 1 from the table in between, should match a posts.id
        'table_through' => 'rodean', // both models plural without prefix in alphabetical order
        'key_through_to' => 'id_estrella', // column 2 from the table in between, should match a users.id
        'model_to' => 'Model_Estrellas',
        'key_to' => 'id',
        'cascade_save' => true,
        'cascade_delete' => false,
    ),
    'planetas' => array(
        'key_from' => 'id',
        'key_through_from' => 'id_orbitas', // column 1 from the table in between, should match a posts.id
        'table_through' => 'contienen', // both models plural without prefix in alphabetical order
        'key_through_to' => 'id_planetas', // column 2 from the table in between, should match a users.id
        'model_to' => 'Model_Planetas',
        'key_to' => 'id',
        'cascade_save' => true,
        'cascade_delete' => false,
    )
);
}