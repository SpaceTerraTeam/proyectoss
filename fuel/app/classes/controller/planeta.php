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
            $id = $this->validateToken($jwt);

            $input = $_POST;
            $images = $_FILES;
            $name = $input['name'];
            $description = $input['description'];
            $model = $images['model'];
            $picture = $images['picture'];
            $size = $input['size'];
            $speed = $input['speed'];

            $BDplanet = Model_Planetas::find('first', array(
                    'where' => array(
                        array('name', $name),
                        'or' => array(
                        array('description', $description)),                      
                    ),
                ));

                
            if($id != null){
                if (array_key_exists('name', $input) && !empty($name) && array_key_exists('description', $input) && !empty($description) && array_key_exists('model', $images) && !empty($model) && array_key_exists('size', $input) && !empty($size) && array_key_exists('speed', $input) && !empty($speed) && array_key_exists('picture', $images) && !empty($picture)){
                    if($BDplanet == null){
        
                        $new = new Model_Planetas();
                        $new->name = $name;
                        $new->description = $description;
                        $new->model = $model;
                        $new->size = $size;
                        $new->speed = $speed;
                        $this->Upload($new);
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
                }elseif(empty($picture)){
                    $this->Mensaje('400', 'Campo imagen obligatorio', $input);
                }elseif (empty($speed)) {
                    $this->Mensaje('400', 'Campo velocidad obligatorio', $input);
                }elseif(empty($size)){
                    $this->Mensaje('400', 'Campo tamaño obligatorio', $input);
                }elseif(empty($model)){
                    $this->Mensaje('400', 'Campo modelo obligatorio', $input);
                }elseif(empty($description)){
                    $this->Mensaje('400', 'Campo descripcion obligatorio', $input);
                }elseif(empty($name)){
                    $this->Mensaje('400', 'Campo nombre obligatorio', $input);
                }  
            }else{
                $this->Mensaje('400', 'Permisos Denegados', $input);
            }
        }catch (Exception $e) {
            
            $this->Mensaje('500', 'Error Interno del servidor', $e->getMessage());
        }  
    }

    public function post_deletePlanet(){
    
        try{
            $jwt = apache_request_headers()['Authorization'];
            $id = $this->validateToken($jwt);
            
            if($id != null){
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
            $id = $this->validateToken($jwt);

            $input = $_POST;
            $images = $_FILES;
            $name = $input['name'];
            $description = $input['description'];
            $model = $images['model'];
            $picture = $images['picture'];
            $size = $input['size'];
            $speed = $input['speed'];
            $idPlaneta = $input["id"];

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
                    'or' => array(
                    array('picture', $picture)),                       
                    ),
                ));

            if($id != null){
                if (array_key_exists('name', $input) && !empty($name) || array_key_exists('description', $input) && !empty($description) || array_key_exists('model', $images) && !empty($model) || !empty($size) &&  array_key_exists('size', $input) || array_key_exists('speed', $input) && !empty($speed) || array_key_exists('picture', $images) &&  !empty($picture)){
                    if($BDplanet2 == null){

                        if (!empty($name)) {
                            $BDplanet->name = $name;
                        }
                        if (!empty($description)) {
                            $BDplanet->description = $description;
                        }
                        if (!empty($model)) {
                            $this->Upload($BDplanet, $model);
                        }
                        if (!empty($size)) {
                            $BDplanet->size = $size;
                        }
                        if (!empty($speed)) {
                            $BDplanet->speed = $speed;
                        }
                        if (!empty($picture)) {
                            $this->Upload($BDplanet);
                        }
                        if (!empty($model)) {
                            $this->Upload($BDplanet);
                        }
                        
                        $BDplanet->save();
                        $this->Mensaje('200', 'Planeta modificado', $BDplanet);
                    }else{
                        if ($BDplanet2->name == $name) {
                            $menssage = self::Mensaje('400', 'Ya hay un planeta con ese nombre', $name);
                        }elseif ($BDplanet2->description == $description) {
                            $menssage = self::Mensaje('400', 'Ya hay un planeta con esa descripcion', $descripcion);
                        }elseif ($BDplanet2->model == $model) {
                            $menssage = self::Mensaje('400', 'Ya hay un planeta con ese modelo', $model);
                        }elseif ($BDplanet2->picture == $picture) {
                            $menssage = self::Mensaje('400', 'Ya hay un planeta con esa imagen', $picture);
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
            $id = $this->validateToken($jwt);

            if ($id != null){
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
