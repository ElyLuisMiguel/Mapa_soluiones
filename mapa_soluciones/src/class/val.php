<?php

  class Validacion{
      
    function validacion_multiple($ciclo , $opcion_ciclo , $ejecucion_financiera , $poblacion , $lps){
        $opcion_ciclo = strtolower($opcion_ciclo);
        $ciclo = filter_var($ciclo , FILTER_SANITIZE_NUMBER_INT);
        $lps = filter_var($lps, FILTER_SANITIZE_NUMBER_INT);
        $poblacion = filter_var($poblacion, FILTER_SANITIZE_NUMBER_INT);
        $ejecucion_financiera = filter_var($ejecucion_financiera, FILTER_SANITIZE_NUMBER_INT);
        $opcion_ciclo = filter_var($opcion_ciclo, FILTER_SANITIZE_STRING);
    
    
        for ($i=0; $i < count($ejecucion_financiera); $i++) { 
            if (filter_var($ejecucion_financiera[$i], FILTER_VALIDATE_INT)) {
                "ok";
            }else{
                return false;
            }
        }
    
        if (filter_var($ciclo, FILTER_VALIDATE_INT)) {
           if ($opcion_ciclo === "semanas" OR $opcion_ciclo === "dias" OR $opcion_ciclo === "meses") {
               
                   if (filter_var($poblacion, FILTER_VALIDATE_INT)) {
                        if (filter_var($lps, FILTER_VALIDATE_INT)) {
                           return true;
                        }else {
                            return false;
                        }
                   }else {
                return false;
               }
           }else {
            return false;
           }
        }else {
            return false;
        }    
        }
    
    
        function nombre_datos($nombre_datos){
            $nombre_datos = filter_var($nombre_datos, FILTER_SANITIZE_STRING);
    
            if (strlen($nombre_datos) < 300) {
                return true;
            }else{
                return false;
            }
    
        }
    
        function acciones_especificas($acciones){
            
            if (filter_var($acciones, FILTER_VALIDATE_BOOLEAN)) {
                return true;
            }else{
                return false;
            }
        }
    
    
  }
  






?>