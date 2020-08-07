<?php

class Validate {
    private $validateItem;

    function __construct($validateItem){
        $this->validateItem=$validateItem;
    }

    function numeroCedula () {
        $json = file_get_contents(__DIR__.'/u.json');
        $json = json_decode($json, true);
        $jsonNum = count($json);
        for ($i=0; $i < $jsonNum; $i++) { 
            if ($json[$i]['cedula'] === $this->validateItem) {
                return $json[$i];
            }
        }
    }

    function validate_correo($email){
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    
        if (!empty($email)){
            if (filter_var($email, FILTER_VALIDATE_EMAIL)){
                return $email;
            }else{ return false;}
        }else{ return false;}
    }
    
}
?>