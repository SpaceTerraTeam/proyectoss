<?php
use Firebase\JWT\JWT;
class Controller_tipo extends Controller_Base
{
    private $key = 'my_secret_key';
    protected $format = 'json';

    public function post_create(){
        try{
            $jwt = apache_request_headers()['Authorization'];
            $id = $this->validateToken($jwt);

            $input = $_POST;
            $name = $input['name'];
            $size = $input['size'];

            $BDtype = Model_Tipos::find('first', array(
                'where' => array(
                    array('name', $name)),
            ));

            if($id != null){
                if (array_key_exists('name', $input) && !empty($name) && array_key_exists('size', $input) && !empty($size)){
                    if($BDtype == null){
        
                        $new = new Model_Tipos();
                        $new->name = $name;
                        $new->size = $size;
                        $new->save();
                        
                        $this->Mensaje('200', 'Tipo creado', $name);
                    }else{
                        $this->Mensaje('400', 'Ya hay un tipo de estrella con ese nombre', $name);
                    }
                }else{
                    $this->Mensaje('400', 'Todos los campos son obligatorios ', $name);
                }      
            }else{
                $this->Mensaje('400', 'Permisos Denegados', $input);
            }
        }catch(Exception $e){
            echo $e;
            $this->Mensaje('500', 'Error Interno del servidor', "Aprende a programar");
        }  
    }

    public function post_deleteType(){
    
        try{
            $jwt = apache_request_headers()['Authorization'];
            $id = $this->validateToken($jwt);
            
            if($id != null){
                $idTipo = $_POST["id"];

                $BDtype = Model_Tipos::find('first', array(
                'where' => array(
                    array('id', $idTipo)
                    ),
                ));

                if ($BDtype != null) {
                    $BDtype->delete();
                    $this->Mensaje('200', 'Tipo de estrella borrado', $BDtype);
                }else{
                    $this->Mensaje('400', 'Este tipo de estrella no existe', $BDtype);
                }
            } else {
                $this->Mensaje('400', 'Permisos Denegados', $input);
            }
            
        }catch(Exception $e) {
            echo $e;
            $this->Mensaje('500', 'Error de verificacion', "aprender a programar");
        } 
    }

    public function post_modifyType(){
        try{
            $jwt = apache_request_headers()['Authorization'];
            $id = $this->validateToken($jwt);

            $input = $_POST;
            $name = $input['name'];
            $size = $input['size'];
            $idTipo = $input["id"];

            $BDtype = Model_Tipos::find('first', array(
            'where' => array(
                array('id', $idTipo)
                ),
            ));

            $BDtype2 = Model_Estrellas::find('first', array(
                'where' => array(
                    array('name', $name)),
            ));

            if($id != null){
                
                if (array_key_exists('name', $input) && !empty($name) || array_key_exists('size', $input) && !empty($size)){
                    if($BDtype2 == null){
                        if (!empty($name)) {
                            $BDstar->name = $name;
                            $BDstar->save();
                        }
                        if (!empty($size)) {
                            $BDstar->size = $size;
                            $BDstar->save();
                        }
                    
                        $this->Mensaje('200', 'Tipo de estrella modificada', $BDtype);
                    }else{
                        $this->Mensaje('400', 'Ya hay un tipo de estrella con ese nombre', $name);
                    }
                
                }else{
                    $this->Mensaje('400', 'Introduce algun parametro', $input);
                }
            }else {
                $this->Mensaje('400', 'Permisos Denegados', $id);
            }
        }catch(Exception $e){
            echo $e;
            $this->Mensaje('500', 'Error interno del servidor', "Aprende a programar");
        }
    }

    public function get_allTypes(){
        try{
            $jwt = apache_request_headers()['Authorization'];
            $id = $this->validateToken($jwt);

            if ($id != null){
                $types = Model_Tipos::find('all');
                $this->Mensaje('200', 'lista de tipos de estrellas', $types);
            }else {
                $this->Mensaje('400', 'Permisos Denegados', $id);
            }
        }catch(Exception $e){
            $this->Mensaje('500', 'Error interno del servidor', "Aprende a programar");
        }
    }
}