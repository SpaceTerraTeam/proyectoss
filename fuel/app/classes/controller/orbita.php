<?php
use Firebase\JWT\JWT;
class Controller_orbita extends Controller_Base
{
    private $key = 'my_secret_key';
    protected $format = 'json';

    public function post_deleteOrbit(){
    
        try{
            $jwt = apache_request_headers()['Authorization'];
            $id = $this->validateToken($jwt);

            if($id != null){
                $idOrbita = $_POST["id"];

                $BDorbit = Model_Orbitas::find('first', array(
                'where' => array(
                    array('id', $idOrbita)
                    ),
                ));

                if ($BDorbit != null) {
                    $BDorbit->delete();
                    $this->Mensaje('200', 'Orbita borrada', $BDorbit);
                }else{
                    $this->Mensaje('400', 'Este orbita no existe', $BDorbit);
                }
            } else {
                $this->Mensaje('400', 'Permisos Denegados', $input);
            }
            
        }catch(Exception $e) {
            echo $e;
            $this->Mensaje('500', 'Error de verificacion', "aprender a programar");
        } 
    }

    public function post_modifyOrbit(){
        try{
            $jwt = apache_request_headers()['Authorization'];
            $id = $this->validateToken($jwt);
            

            $input = $_POST;
            $radius = $input['radius'];
            $idOrbita = $input["id"];

            $BDorbit = Model_Orbita::find('first', array(
            'where' => array(
                array('id', $idOrbita)
                ),
            ));

            if($id != null){
                
                if (array_key_exists('radius', $input) && !empty($radius)){
                        
                        $BDstar->radius = $radius;
                        $BDstar->save();
                    
                    
                        $this->Mensaje('200', 'Orbita modificada', $BDorbit);                
                }else{
                    $this->Mensaje('400', 'Introduce un radio', $input);
                }
            }else {
                $this->Mensaje('400', 'Permisos Denegados', $id);
            }
        }catch(Exception $e){
            echo $e;
            $this->Mensaje('500', 'Error interno del servidor', "Aprende a programar");
        }
    }

    public function get_allOrbitas(){
        try{
            $jwt = apache_request_headers()['Authorization'];
            $id = $this->validateToken($jwt);

            if ($id != null){
                $orbits = Model_Orbitas::find('all');
                $this->Mensaje('200', 'lista de orbitas', $orbits);
            }else {
                $this->Mensaje('400', 'Permisos Denegados', $id);
            }
        }catch(Exception $e){
            $this->Mensaje('500', 'Error interno del servidor', "Aprende a programar");
        }
    }
}