<?php

class Proyecto {


    function porcentajes4variables($orden, $var1, $var2, $var3, $var4){
/*
        $Porvar3 = null;
        $Porvar4 = null;
        
        $total = $var1 + $var2 + $var3 + $var4;

        $Porvar1 = regla($var1,$total);

        $Porvar2 = regla($var2,$total);

        if (!empty($var3)) {
            $Porvar3 = regla($var3,$total);
        }

        if (!empty($var4)) {
            $Porvar4 = regla($var4,$total);
        }

        return $json = ([
            "Total General" => $total,
            "Orden de Porcentajes" => $orden,
            "Porcentaje Uno" => $Porvar1,
            "Porcentaje Dos" => $Porvar2,
            "Porcentaje Tres" => $Porvar3,
            "Porcentaje Cuatro" => $Porvar4
        ]);

*/
    }   

    function porcentajeAcciones($id_datos){

        $sql = "SELECT acciones_especificas.id_datos, acciones_especificas.valor, datos.id_datos FROM acciones_especificas LEFT JOIN datos ON acciones_especificas.id_datos = datos.id_datos WHERE acciones_especificas.id_datos = ?";

        try {
            $db = new DB();
            $db=$db->connection('mapa_soluciones');
            $stmt = $db->prepare($sql); 
            $stmt->bind_param("i" , $id_datos);
            $stmt->execute();
            $stmt = $stmt->get_result();
            $resultado = $stmt->fetch_all(MYSQLI_ASSOC);
            $accionesFinalizadas = null;
            for ($i=0; $i < count($resultado) ; $i++) { 
                if ($resultado[$i]['valor'] === 1) {
                    $accionesFinalizadas++;
                }
            }

            $porcentaje = ($accionesFinalizadas * 100) / count($resultado);
            return $porcentaje;


            
         }
         catch (MySQLDuplicateKeyException $e) {
             $e->getMessage();
         }
         catch (MySQLException $e) {
             $e->getMessage();
         }
         catch (Exception $e) {
             $e->getMessage();
         }


    }   
    
    
}


?>