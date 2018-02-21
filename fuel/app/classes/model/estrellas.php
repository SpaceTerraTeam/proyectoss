<?php

class Model_Estrellas extends Orm\Model
{
	protected static $_table_name = 'estrellas';
    protected static $_properties = array('id', 'name','x', 'y', 'id_tipo');
    protected static $_primary_key = array('id');
    protected static $_belongs_to = array(
    'tipos' => array(
        'key_from' => 'id_tipo',
        'model_to' => 'Model_Tipos',
        'key_to' => 'id',
        'cascade_save' => true,
        'cascade_delete' => false,
    ));
    protected static $_many_many = array(
    'esquemas' => array(
        'key_from' => 'id',
        'key_through_from' => 'id_estrella', // column 1 from the table in between, should match a posts.id
        'table_through' => 'poseen', // both models plural without prefix in alphabetical order
        'key_through_to' => 'id_esquema', // column 2 from the table in between, should match a users.id
        'model_to' => 'Model_Esquemas',
        'key_to' => 'id',
        'cascade_save' => true,
        'cascade_delete' => false,
    ),
    'orbitas' => array(
        'key_from' => 'id',
        'key_through_from' => 'id_estrella', // column 1 from the table in between, should match a posts.id
        'table_through' => 'rodean', // both models plural without prefix in alphabetical order
        'key_through_to' => 'id_orbita', // column 2 from the table in between, should match a users.id
        'model_to' => 'Model_Orbitas',
        'key_to' => 'id',
        'cascade_save' => true,
        'cascade_delete' => false,
    )
);
}