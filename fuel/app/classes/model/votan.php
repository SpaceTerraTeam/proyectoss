<?php

class Model_Votan extends Orm\Model
{
	protected static $_table_name = 'votan';
    protected static $_properties = array('id_usuario', 'id_esquema');
    protected static $_primary_key = array('id_usuario', 'id_esquema');

}