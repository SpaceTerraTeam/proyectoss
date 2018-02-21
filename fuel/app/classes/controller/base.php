<?php
use Firebase\JWT\JWT;
class Controller_Base extends Controller_Rest
{
    public function Upload($object, $image)
    {
        
        // Custom configuration for this upload
        $config = array(
            'path' => DOCROOT . 'assets/img',
            'randomize' => true,
            'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
        );
        // process the uploaded files in $_FILES
        Upload::process($config);
        // if there are any valid files
        if (Upload::is_valid())
        {
            // save them according to the config
            Upload::save();
            foreach(Upload::get_files() as $file)
            {
                $object->picture = 'http://h2744356.stratoserver.net/sanwichino/proyectoss/public/assets/img' . $file['saved_as'] ;
                
            }
        }
        // and process any errors
        foreach (Upload::get_errors() as $file)
        {
            $this->Mensaje('500', 'Error al subir la imagen', $file);
        }
        $this->Mensaje('200', 'Imagen subida con exito', $file);
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