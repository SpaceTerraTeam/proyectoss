<?php
use Firebase\JWT\JWT;
class Controller_esquema extends Controller_Base
{
    public function post_create(){
        try{
            $jwt = apache_request_headers()['Authorization'];
            $id = $this->validateToken($jwt);

            $input = $_POST;
            $image = $_FILES['image'];
            $name = $input['name'];
            $editable = $input['editable'];
            $ranking = $input['ranking'];

            $BDscheme = Model_Esquemas::find('first', array(
                'where' => array(
                    array('name', $name)),
            ));

            if($id != null){
                if (array_key_exists('name', $input) && !empty($name)){
                    if (array_key_exists('image', $_FILES) && !empty($image)){
                        if($BDscheme == null){
            
                            $new = new Model_Esquemas();
                            $new->name = $name;
                            $new->editable = $editable;
                            $new->ranking = $ranking;
                            $this->Upload($new);
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
            $this->Mensaje('500', 'Error Interno del servidor', $e->getMessage());
        } 
    }

    public function post_deleteScheme(){
    
        try{
            $jwt = apache_request_headers()['Authorization'];
            $id = $this->validateToken($jwt);
            
            if($id != null){
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
            $id = $this->validateToken($jwt);

            $input = $_POST;
            $image = $_FILES['image'];
            $name = $input['name'];

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

            if($id != null){
                
                if (array_key_exists('name', $input) && !empty($name) || array_key_exists('image', $_FILES) && !empty($image)){
                    if($BDscheme2 == null){
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
            $id = $this->validateToken($jwt);
            
            if ($id != null){

                $schemes = Model_Esquemas::find('all');
                $this->Mensaje('200', 'lista de esquemas', $schemes);
            }else {
                $this->Mensaje('400', 'Permisos Denegados', $id);
            }
        }catch(Exception $e){
            $this->Mensaje('500', 'Error interno del servidor', "Aprende a programar");
        }
    }

    public function post_save(){
        try{
            $jwt = apache_request_headers()['Authorization'];
            $id = $this->validateToken($jwt);

            $input = $_POST;
            $planets = $input['planets'];
            $idScheme = $input['idScheme'];

            if ($id != null) {
                if (array_key_exists('planets', $input) && !empty($planets) && array_key_exists('idScheme', $input) && !empty($idScheme)) {
                    
                    $BDposeen = Model_Poseen::find('all', array(
                        'where' => array(
                            array('id_esquema', $idScheme)
                        ),
                    ));

                    $BDscheme = Model_Esquemas::find('first', array(
                        'where' => array(
                            array('id', $idScheme)
                        ),
                    ));

                    $newEscheme = new Model_Esquemas();
                    $newEscheme->name = $BDscheme->name;
                    $newEscheme->picture = $BDscheme->picture;
                    $newEscheme->ranking = 0;
                    $newEscheme->editable = true;
                    $newEscheme->save(); 

                    $newTienen = new Model_Tienen();
                    $newTienen->id_usuario = $id;
                    $newTienen->id_esquema = $newEscheme->id;
                    $newTienen->save();

                    foreach ($BDposeen as $key => $value) {
                        $newPoseen = new Model_Poseen();
                        $newPoseen->id_esquema = $newEscheme->id;
                        $newPoseen->id_estrella = $value->id_estrella;
                        $newPoseen->save();
                    }

                    foreach ($planets as $key => $value) {

                        $newOrbit = new Model_Orbitas();
                        $newOrbit->radius = $value['radius'];
                        $newOrbit->save();

                        $newRodean = new Model_Rodean();
                        $newRodean->id_estrella = $value['idStar'];
                        $newRodean->id_orbita = $newOrbit->id;
                        $newRodean->save();

                        $newContienen = new Model_Contienen();
                        $newContienen->id_orbita = $newOrbit->id;
                        $newContienen->id_planeta = $value['idPlanet'];
                        $newContienen->save();
                    }                  

                    $this->Mensaje('200', 'Esquema Guardado', $newEscheme);
                }else{
                    $this->Mensaje('400', 'Faltan parametros', $input);
                }
            }else {
                $this->Mensaje('400', 'Permisos Denegados', $id);
            }
        }catch(Exception $e){
            echo $e;
            $this->Mensaje('500', 'Error interno del servidor', $e->getMessage());
        }
    }

    /*public function get_load(){
        try{
            $jwt = apache_request_headers()['Authorization'];
            $id = $this->validateToken($jwt);

            $input = $_GET;
            $idScheme = $input['idScheme'];

            if ($id != null) {
                if (array_key_exists('idScheme', $input) && !empty($idScheme)) {

                    $BDposeen = Model_Poseen::find('all', array(
                        'where' => array(
                            array('id_esquema', $idScheme)
                        ),
                    ));


                    $this->Mensaje('200', 'Esquema Cargado', $new);
                }else{
                    $this->Mensaje('400', 'Faltan parametros', $input);
                }
            }else {
                $this->Mensaje('400', 'Permisos Denegados', $id);
            }
        }catch(Exception $e){
            echo $e;
            $this->Mensaje('500', 'Error interno del servidor', "Aprende a programar");
        }
    }*/

}
