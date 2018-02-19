<?php
use Firebase\JWT\JWT;
class Controller_esquema extends Controller_Rest
{
    private $key = 'my_secret_key';
    protected $format = 'json';

    public function post_create(){
        try{
            $jwt = apache_request_headers()['Authorization'];
            $tokenDecode = JWT::decode($jwt, $this->key, array('HS256'));
            $id = $tokenDecode->data->id;

            $input = $_POST;
            $name = $input['name'];
            $photo = $input['photo'];

            $BDuser = Model_Usuarios::find('first', array(
                'where' => array(
                    array('id', $id)
                ),
            ));

            $BDscheme = Model_Esquemas::find('first', array(
                    'where' => array(
                        array('name', $name),
                        'or' => array(
                        array('photo', $photo))
                        ),
                    ));

            if($BDuser != null){
                if (array_key_exists('name', $input) && !empty($name)){
                    if (array_key_exists('photo', $input) && !empty($photo)) {
                        if(count($BDscheme < 1)){
            
                            $new = new Model_Esquemas();
                            $new->name = $name;
                            $new->photo = $photo;
                            $new->save();

                            $this->Mensaje('200', 'esquemaCreado');
                        }else{
                            if ($BDscheme->name == $name) {
                                $this->Mensaje('400', 'Ya hay un esquema con ese nombre', $name);
                            }elseif ($BDscheme->photo == $photo) {
                                $this->Mensaje('400', 'Ya hay un esquema con esa foto', $photo);
                            }
                        }
                    }else{
                        $this->Mensaje('400', 'Campo foto obligatorio', $photo);
                    }
                }else{
                    $this->Mensaje('400', 'Campo nombre obligatorio', $name);
                }      
            }else{
                $this->Mensaje('400', 'Permisos Denegados', $input);
            }
        }catch{
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
                    array('id', $idesquema)
                    ),
                ));

                $BDscheme->delete();

                $this->Mensaje('200', 'Esquema borrado', $BDplanet);
            } else {
                $this->Mensaje('400', 'Permisos Denegados', $input);
            }
            
        }catch(Exception $e) {
            $this->Mensaje('500', 'Error de verificacion', "aprender a programar");
        } 
    }

    public function post_modifyScheme(){
        try{
            $jwt = apache_request_headers()['Authorization'];
            $tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));
            $id = $tokenDecode->data->id;

            $input = $_POST;
            $name = $input['name'];
            $photo = $input['photo'];
           
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

            if($BDuser != null){
                
                if (array_key_exists('name', $input)&& !empty($name) && array_key_exists('photo', $input) && !empty($photo){

                    if (!empty($name)) {
                        $BDscheme->name = $name;
                        $BDscheme->save();
                    }
                    if (!empty($photo)) {
                        $BDscheme->photo = $photo;
                        $BDscheme->save();
                    }
                    
                    $this->Mensaje('200', 'Esquema modificado', $BDscheme);
                
                }else{
                    $this->Mensaje('400', 'Introduce algun parametro', $input);
                }
            }else {
                $this->Mensaje('400', 'Permisos Denegados', $id);
            }
        }catch{
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
        }catch{
            $this->Mensaje('500', 'Error interno del servidor', "Aprende a programar");
        }
    }

    function Mensaje($code, $message, $data){
        $json = $this->response(array(
            'code' => $code,
            'message' => $message,
            'data' => $data
            ));
        return $json;
    }
}
