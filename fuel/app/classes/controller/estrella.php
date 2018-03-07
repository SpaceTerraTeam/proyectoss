<?php
use Firebase\JWT\JWT;
class Controller_estrella extends Controller_Base
{
    private $key = 'my_secret_key';
    protected $format = 'json';

    public function post_create(){
        try{
            $jwt = apache_request_headers()['Authorization'];
            $id = $this->validateToken($jwt);

            $input = $_POST;
            $name = $input['name'];
            $x = $input['x'];
            $y = $input['y'];
            $idTipo = $input['idTipo'];
            $images = $_FILES;
            $model = $images['model'];
            $picture = $images['picture'];

            $BDstar = Model_Estrellas::find('first', array(
                'where' => array(
                    array('name', $name)),
            ));

            if($id != null){
                if (array_key_exists('name', $input) && !empty($name) && array_key_exists('x', $input) && !empty($x) && array_key_exists('y', $input) && !empty($y) && array_key_exists('idTipo', $input) && !empty($idTipo) &&  array_key_exists('model', $images) && !empty($model) && array_key_exists('picture', $images) && !empty($picture)){
                    if($BDstar == null){
        
                        $new = new Model_Estrellas();
                        $new->name = $name;
                        $new->x = $x;
                        $new->y = $y;
                        $new->id_tipo = $idTipo;
                        $this->Upload($new);
                        $new->save();
                        
                        $this->Mensaje('200', 'Estrella Creada', $name);
                    }else{
                        $this->Mensaje('400', 'Ya hay una estrella con ese nombre', $name);
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

    public function post_deleteStar(){
    
        try{
            $jwt = apache_request_headers()['Authorization'];
            $id = $this->validateToken($jwt);
        
            if($id != null){
                $idEstrella = $_POST["id"];

                $BDstar = Model_Estrellas::find('first', array(
                'where' => array(
                    array('id', $idEstrella)
                    ),
                ));

                if ($BDstar != null) {
                    $BDstar->delete();
                    $this->Mensaje('200', 'Estrella borrada', $BDstar);
                }else{
                    $this->Mensaje('400', 'Esta estrella no existe', $BDstar);
                }
            } else {
                $this->Mensaje('400', 'Permisos Denegados', $input);
            }
            
        }catch(Exception $e) {
            echo $e;
            $this->Mensaje('500', 'Error de verificacion', "aprender a programar");
        } 
    }

    public function post_modifyStar(){
        try{
            $jwt = apache_request_headers()['Authorization'];
            $id = $this->validateToken($jwt);

            $input = $_POST;
            $name = $input['name'];
            $x = $input['x'];
            $y = $input['y'];
            $idTipo = $input['idTipo'];
            $idEstrella = $input["id"];
            $images = $_FILES;
            $model = $images['model'];
            $picture = $images['picture'];

            $BDstar = Model_Estrellas::find('first', array(
            'where' => array(
                array('id', $idEstrella)
                ),
            ));

            $BDstar2 = Model_Estrellas::find('first', array(
                'where' => array(
                    array('name', $name)),
            ));

            if($id != null){
                
                if (array_key_exists('name', $input) && !empty($name) || array_key_exists('x', $input) && !empty($x) || array_key_exists('y', $input) && !empty($y) || array_key_exists('idTipo', $input) && !empty($idTipo) || array_key_exists('model', $images) && !empty($model) || array_key_exists('picture', $images) &&  !empty($picture)){
                    if($BDstar2 == null){
                        if (!empty($name)) {
                            $BDstar->name = $name;
                        }
                        if (!empty($x)) {
                            $BDstar->x = $x;
                        }
                        if (!empty($y)) {
                            $BDstar->y = $y;
                        }
                        if (!empty($idTipo)) {
                            $BDstar->idTipo = $idTipo;
                        }
                        if (!empty($picture)) {
                            $this->Upload($BDplanet);
                        }
                        if (!empty($model)) {
                            $this->Upload($BDplanet);
                        }
                        $BDstar->save();
                    
                        $this->Mensaje('200', 'Estrella modificada', $BDstar);
                    }else{
                        $this->Mensaje('400', 'Ya hay una estrella con ese nombre', $name);
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

    public function get_allStars(){
        try{
            $jwt = apache_request_headers()['Authorization'];
            $id = $this->validateToken($jwt);

            if ($id != null){
                $stars = Model_Estrellas::find('all');
                $this->Mensaje('200', 'lista de estrellas', $stars);
            }else {
                $this->Mensaje('400', 'Permisos Denegados', $id);
            }
        }catch(Exception $e){
            $this->Mensaje('500', 'Error interno del servidor', "Aprende a programar");
        }
    }
}