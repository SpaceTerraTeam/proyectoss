<?php
use Firebase\JWT\JWT;
class Controller_esquema extends Controller_Base
{
    private $key = 'my_secret_key';
    protected $format = 'json';

    public function post_create(){
        try{
            $jwt = apache_request_headers()['Authorization'];
            $tokenDecode = JWT::decode($jwt, $this->key, array('HS256'));
            $id = $tokenDecode->data->id;

            $input = $_POST;
            $image = $_FILES['image'];
            $name = $input['name'];
            $editable = $input['editable'];
            $ranking = $input['ranking'];

            $BDuser = Model_Usuarios::find('first', array(
                'where' => array(
                    array('id', $id)
                ),
            ));

            $BDscheme = Model_Esquemas::find('first', array(
                'where' => array(
                    array('name', $name)),
            ));

            if($BDuser != null){
                if (array_key_exists('name', $input) && !empty($name)){
                    if (array_key_exists('image', $_FILES) && !empty($image)){
                        if($BDscheme == null){
            
                            $new = new Model_Esquemas();
                            $new->name = $name;
                            $new->editable = $editable;
                            $new->ranking = $ranking;
                            $this->Upload($new, $image);
                            $new->save();
                            
                            $this->Mensaje('200', 'Esquema Creado', $name);
                        }else{
                            $this->Mensaje('400', 'Ya hay un esquema con ese nombre', $name);
                        }
                    }else{
                        $this->Mensaje('400', 'Campo imagen obligatorio', $image);
                    }
                }else{
                    $this->Mensaje('400', 'Campo nombre obligatorio', $name);
                }      
            }else{
                $this->Mensaje('400', 'Permisos Denegados', $input);
            }
        }catch(Exception $e){
            echo $e;
            $this->Mensaje('500', 'Error Interno del servidor', "Aprende a programar");
        }  
    }

    public function post_deleteScheme(){
    
        try{
            $jwt = apache_request_headers()['Authorization'];
            $tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));
            $id = $tokenDecode->data->id;
            
            $BDuser = Model_Usuarios::find('first', array(
                'where' => array(
                    array('id', $id)
                    ),
                ));
            if($BDuser != null){
                $idEsquema = $_POST["id"];

                $BDscheme = Model_Esquemas::find('first', array(
                'where' => array(
                    array('id', $idEsquema)
                    ),
                ));

                if ($BDscheme != null) {
                    $BDscheme->delete();
                    $this->Mensaje('200', 'Esquema borrado', $BDscheme);
                }else{
                    $this->Mensaje('400', 'Este esquema no existe', $BDscheme);
                }
            } else {
                $this->Mensaje('400', 'Permisos Denegados', $input);
            }
            
        }catch(Exception $e) {
            echo $e;
            $this->Mensaje('500', 'Error de verificacion', "aprender a programar");
        } 
    }

    public function post_modifyScheme(){
        try{
            $jwt = apache_request_headers()['Authorization'];
            $tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));
            $id = $tokenDecode->data->id;

            $input = $_POST;
            $image = $_FILES['image'];
            $name = $input['name'];
           
            $BDuser = Model_Usuarios::find('first', array(
                'where' => array(
                    array('id', $id)
                ),
            ));

            $idEsquema = $_POST["id"];

            $BDscheme = Model_Esquemas::find('first', array(
            'where' => array(
                array('id', $idEsquema)
                ),
            ));

            $BDscheme2 = Model_Esquemas::find('first', array(
                'where' => array(
                    array('name', $name)),
            ));

            if($BDuser != null){
                
                if (array_key_exists('name', $input) && !empty($name) || array_key_exists('image', $_FILES) && !empty($image)){
                    if(count($BDscheme2) < 1){
                        if (!empty($name)) {
                            $BDscheme->name = $name;
                            $BDscheme->save();
                        }
                        if (!empty($image)) {
                            $this->Upload($BDscheme, $image);
                            $BDscheme->save();
                        }
                    
                        $this->Mensaje('200', 'Esquema modificado', $BDscheme);
                    }else{
                        $this->Mensaje('400', 'Ya hay un esquema con ese nombre', $name);
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

    public function get_allSchemes(){
        try{
            $jwt = apache_request_headers()['Authorization'];
            $tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));
            $id = $tokenDecode->data->id;
                
            $BDuser = Model_Usuarios::find('first', array(
                'where' => array(
                    array('id', $id)
                    ),
                ));

            if ($BDuser != null){
                $schemes = Model_Esquemas::find('all');
                $this->Mensaje('200', 'lista de esquemas', $schemes);
            }else {
                $this->Mensaje('400', 'Permisos Denegados', $id);
            }
        }catch(Exception $e){
            $this->Mensaje('500', 'Error interno del servidor', "Aprende a programar");
        }
    }
}
