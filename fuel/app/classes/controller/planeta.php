<?php
use Firebase\JWT\JWT;
class Controller_planeta extends Controller_Base
{
    private $key = 'my_secret_key';
    protected $format = 'json';

    public function post_create()
    {
        try{
            $jwt = apache_request_headers()['Authorization'];
            $tokenDecode = JWT::decode($jwt, $this->key, array('HS256'));
            $id = $tokenDecode->data->id;

            $input = $_POST;
            $image = $_FILES['image'];
            $name = $input['name'];
            $description = $input['description'];
            $model = $input['model'];
            $size = $input['size'];
            $speed = $input['speed'];

            $BDuser = Model_Usuarios::find('first', array(
                'where' => array(
                    array('id', $id)
                ),
            ));

            $BDplanet = Model_Planetas::find('first', array(
                    'where' => array(
                        array('name', $name),
                        'or' => array(
                        array('description', $description)),
                        'or' => array(
                        array('model', $model)),                       
                    ),
                ));

                
            if($BDuser != null){
                if (array_key_exists('name', $input) && !empty($name)){
                    if (array_key_exists('description', $input) && !empty($description)) {
                        if (array_key_exists('model', $input) && !empty($model)) {
                            if (array_key_exists('size', $input) && !empty($size)) {
                                if (array_key_exists('speed', $input) && !empty($speed)) {
                                    if (array_key_exists('image', $_FILES) && !empty($image)) {
                                        if($BDplanet == null){
                            
                                            $new = new Model_Planetas();
                                            $new->name = $name;
                                            $new->description = $description;
                                            $new->model = $model;
                                            $new->size = $size;
                                            $new->speed = $speed;
                                            $this->Upload($new, $image);
                                            $new->save();


                                            $this->Mensaje('200', 'planeta creado', $name);
                                        }else{
                                            if ($BDplanet->name == $name) {
                                                $this->Mensaje('400', 'Ya hay un planeta con ese nombre', $input);
                                            }elseif ($BDplanet->description == $description) {
                                                $this->Mensaje('400', 'Ya hay un planeta con esa descripcion', $input);
                                            }elseif ($BDplanet->model == $model) {
                                                $this->Mensaje('400', 'Ya hay un planeta con ese modelo', $input);
                                            }
                                        }
                                    }else{
                                        $this->Mensaje('400', 'Campo imagen obligatorio', $image);
                                    }
                                }else{
                                    $this->Mensaje('400', 'Campo velocidad obligatorio', $input);
                                }
                            }else{
                                $this->Mensaje('400', 'Campo tamaño obligatorio', $input);
                            }
                        }else{
                            $this->Mensaje('400', 'Campo modelo obligatorio', $input);
                        }
                    }else{
                        $this->Mensaje('400', 'Campo descripcion obligatorio', $input);
                    }
                }else{
                    $this->Mensaje('400', 'Campo nombre obligatorio', $input);
                }      
            }else{
                $this->Mensaje('400', 'Permisos Denegados', $input);
            }
        }catch (Exception $e) {
            echo $e;
            $this->Mensaje('500', 'Error Interno del servidor', "Aprende a programar");
        }  
    }

    public function post_deletePlanet(){
    
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
                $idPlaneta = $_POST["id"];

                $BDplanet = Model_Planetas::find('first', array(
                'where' => array(
                    array('id', $idPlaneta)
                    ),
                ));
                if ($BDplanet != null) {
                    $BDplanet->delete();
                    $this->Mensaje('200', 'Planeta borrado', $BDplanet);
                }else{
                    $this->Mensaje('400', 'Este planeta no existe', $BDplanet);
                }
                
            } else {
                $this->Mensaje('400', 'Permisos Denegados', $input);
            }
            
        }catch(Exception $e) {
            $this->Mensaje('500', 'Error de verificacion', "aprender a programar");
        } 
    }

    public function post_modifyPlanet(){
        try{
            $jwt = apache_request_headers()['Authorization'];
            $tokenDecode = JWT::decode($jwt, $this->key , array('HS256'));
            $id = $tokenDecode->data->id;

            $input = $_POST;
            $image = $_FILES['image'];
            $name = $input['name'];
            $description = $input['description'];
            $model = $input['model'];
            $size = $input['size'];
            $speed = $input['speed'];
           
            $BDuser = Model_Usuarios::find('first', array(
                'where' => array(
                    array('id', $id)
                ),
            ));

            $idPlaneta = $_POST["id"];

            $BDplanet = Model_Planetas::find('first', array(
            'where' => array(
                array('id', $idPlaneta)
                ),
            ));

            $BDplanet2 = Model_Planetas::find('first', array(
                'where' => array(
                    array('name', $name),
                    'or' => array(
                    array('description', $description)),
                    'or' => array(
                    array('model', $model)),                       
                    ),
                ));

            if($BDuser != null){
                if (array_key_exists('name', $input) && !empty($name) || array_key_exists('description', $input) && !empty($description) || array_key_exists('model', $input) && !empty($model) || !empty($size) &&  array_key_exists('size', $input) || array_key_exists('speed', $input) && !empty($speed) || array_key_exists('image', $_FILES) &&  !empty($image)){
                    if(count($BDplanet2) < 1){

                        if (!empty($name)) {
                            $BDplanet->name = $name;
                            $BDplanet->save();
                        }
                        if (!empty($description)) {
                            $BDplanet->description = $description;
                            $BDplanet->save();
                        }
                        if (!empty($model)) {
                            $BDplanet->model = $model;
                            $BDplanet->save();
                        }
                        if (!empty($size)) {
                            $BDplanet->size = $size;
                            $BDplanet->save();
                        }
                        if (!empty($speed)) {
                            $BDplanet->speed = $speed;
                            $BDplanet->save();
                        }
                        if (!empty($image)) {
                            $this->Upload($BDplanet, $image);
                            $BDplanet->save();
                        }
                        
                        $this->Mensaje('200', 'Planeta modificado', $BDplanet);
                    }else{
                        if ($BDplanet2->name == $name) {
                            $menssage = self::Mensaje('400', 'Ya hay un planeta con ese nombre', $name);
                        }elseif ($BDplanet2->description == $description) {
                            $menssage = self::Mensaje('400', 'Ya hay un planeta con esa descripcion', $descripcion);
                        }elseif ($BDplanet2->model == $model) {
                            $menssage = self::Mensaje('400', 'Ya hay un planeta con ese modelo', $model);
                        }elseif ($BDplanet2->picture == $picture) {
                            $menssage = self::Mensaje('400', 'Ya hay un planeta con esa imagen', $image);
                        }
                    }
                
                }else{
                    $this->Mensaje('400', 'Introduce algun parametro', $input);
                }
            }else {
                $this->Mensaje('400', 'Permisos Denegados', $id);
            }
        }catch(Exception $e) {
            echo $e;
            $this->Mensaje('500', 'Error interno del servidor', "Aprende a programar");
        }
    }

    public function get_allPlanets(){
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
                $planets = Model_Planetas::find('all');
                $menssage = self::Mensaje('200', 'lista de planetas', $planets);
            }else {
                $menssage = self::Mensaje('400', 'Permisos Denegados', $id);
            }
        }catch(Exception $e){
            $this->Mensaje('500', 'Error interno del servidor', "Aprende a programar");
        }
    }

}
