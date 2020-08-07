<?php
        class Registro {

            private $id_proyecto;
            private $accion;

            function __construct($id_proyecto=null , $accion=null){
                $this->id_proyecto=$id_proyecto;
                $this->accion=$accion;
            }

            function actualizacion($valor){

                $sql = "UPDATE acciones_especificas SET valor = ? WHERE acciones_especificas.id_accion_especifica = ?;";
               
                try {
                    $db = new DB();
                    $db=$db->connection('mapa_soluciones');
                    $stmt = $db->prepare($sql); 
                    $stmt->bind_param("ii", $valor , $this->accion);
                    $stmt->execute();

                    $stmt = $stmt->get_result();
                    return "Se ha actualizado";
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

            function actualizacionFinal($lapso , $ciclos , $ejecucion_financiera , $poblacion , $lps, $proyectos){

                $sql = "UPDATE lapso SET lapso_culminación_inicio = ?, lapso_culminación_final = ? WHERE lapso.id_lapso = ?";
               
                try {
                    $db = new DB();
                    $db=$db->connection('mapa_soluciones');
                    $stmt = $db->prepare($sql); 
                    $stmt->bind_param("ssi" , $lapso[2] , $lapso[1] , $lapso[0]);
                    $stmt->execute();

                        if ($stmt) {
                            $sql = "UPDATE ciclos SET ciclo_final = ? , opcion_ciclo_final = ? WHERE ciclos.id_ciclo = ?;";
                            $db = new DB();
                            $db=$db->connection('mapa_soluciones');
                            $stmt = $db->prepare($sql); 
                            $stmt->bind_param("isi" , $ciclos[0] , $ciclos[1] , $ciclos[2]);
                            $stmt->execute();

                                if ($stmt) {
                                    $sql = "UPDATE ejecucion_financiera SET ejecucion_bolivares_final = ?, ejecucion_euros_final = ?, ejecucion_dolares_final = ?, ejecucion_rublos_final = ? WHERE ejecucion_financiera.id_ejecucion_financiera = ?";
                                    $db = new DB();
                                    $db=$db->connection('mapa_soluciones');
                                    $stmt = $db->prepare($sql); 
                                    $stmt->bind_param("ddddi" , $ejecucion_financiera[0] , $ejecucion_financiera[1] , $ejecucion_financiera[2] , $ejecucion_financiera[3] , $ejecucion_financiera[4]);
                                    $stmt->execute();

                                        if ($stmt) {
                                        $sql = "UPDATE poblacion SET poblacion_final = ? WHERE poblacion.id_problacion = ?";
                                        $db = new DB();
                                        $db=$db->connection('mapa_soluciones');
                                        $stmt = $db->prepare($sql); 
                                        $stmt->bind_param("ii" , $poblacion[0] , $poblacion[1] );
                                        $stmt->execute();

                                            if ($stmt) {

                                                $sql = "UPDATE lps SET lps_final = ? WHERE lps.id_lps = ?";
                                                $db = new DB();
                                                $db=$db->connection('mapa_soluciones');
                                                $stmt = $db->prepare($sql); 
                                                $stmt->bind_param("ii" , $lps[0] , $lps[1] );
                                                $stmt->execute();

                                                if ($stmt) {
                                                    
                                                    $sql = "UPDATE proyectos SET id_estatus = ?, id_estado_proyecto = ? WHERE proyectos.id_proyecto = ?";
                                                    $db = new DB();
                                                    $db=$db->connection('mapa_soluciones');
                                                    $stmt = $db->prepare($sql); 
                                                    $stmt->bind_param("iii" , $proyectos[0] , $proyectos[1], $proyectos[2]);
                                                    $stmt->execute();
                                                    return "Se ha actualizado";
                                                }
                                                
                                            }


                                    }

                                }
                        }
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


            
            
            function crearProyectos($datos , $acciones_especificas , $obras , $sector, $lapso , $ciclos , $ejecucion_financiera , $inversion , $poblacion_inicial , $lps_inicial , $proyecto){
               
                $sql = "INSERT INTO datos (id_datos, nombre, id_tipo_solucion, descripcion, accion_general) VALUES (NULL, ? , ? , ? , ?)";

                    $db = new DB();
                    $db=$db->connection('mapa_soluciones');
                    $id =$datos[1]+0;
                    $stmt = $db->prepare($sql); 
                    $stmt->bind_param("siss", $datos[0] , $id , $datos[2] , $datos[3]);
                    $stmt->execute();  
                                  

                    if ($stmt) {
                        $id_datos = $stmt->{"insert_id"};
                        $sql = "INSERT INTO acciones_especificas (id_accion_especifica, accion_especifica, id_intervencion, cantidad, id_unidades, id_datos, valor) VALUES (NULL, ? , ? , ? , ? , ? , 0 );";
                        for ($i=0; $i < count($acciones_especificas) ; $i++) { 
                            $db = new DB();
                            $db=$db->connection('mapa_soluciones');
                            
                            $accion = $acciones_especificas[0]->descripcionAccion;
                            $intervencion = $acciones_especificas[0]->intervencion+0;
                            $cantidad = $acciones_especificas[0]->cantidad+0;
                            $unidad = $acciones_especificas[0]->unidad+0;
                            $stmt = $db->prepare($sql);
                            $stmt->bind_param("siiii", $accion , $intervencion , $cantidad , $unidad , $id_datos );
                            $stmt->execute(); 
                             
                        }

                        if ($stmt) {                  
                            $sql = "INSERT INTO obras (id_obra, coordenadas) VALUES (NULL, ?)";
                            $db = new DB();
                            $db=$db->connection('mapa_soluciones');
                            $stmt = $db->prepare($sql); 
                            $obras = json_encode($obras);
                            $stmt->bind_param("s", $obras);  
                            $stmt->execute();
                            

                            if ($stmt) {
                                $id_obras = $stmt->{"insert_id"};
                                $sql = "INSERT INTO sector (id_sector, coordenadas) VALUES (NULL, ?)";
                                $db = new DB();
                                $db=$db->connection('mapa_soluciones');
                                $stmt = $db->prepare($sql); 
                                $sector[0] = json_encode($sector);
                                $stmt->bind_param("s", $sector[0]);
                                $stmt->execute();
                                

                                
                                if ($stmt) { 
                                    $id_sector = $stmt->{"insert_id"};
                                    $sql = "INSERT INTO lapso (id_lapso, lapso_estimado_inicio, lapso_estimado_culminacion, lapso_culminación_inicio, lapso_culminación_final) VALUES (NULL, ? , ? , 0 , 0 );";
                                    $db = new DB();
                                    $db=$db->connection('mapa_soluciones');
                                    $stmt = $db->prepare($sql); 
                                    $stmt->bind_param("ss", $lapso[0] , $lapso[1] );
                                    $stmt->execute();
                                    

                                    if ($stmt) { 
                                        $id_lapso = $stmt->{"insert_id"};
                                        $sql = "INSERT INTO ciclos (id_ciclo, ciclo_inicial, opcion_ciclo_inicial, ciclo_final, opcion_ciclo_final) VALUES (NULL, ? , ? , 0 , 0 )";
                                        $db = new DB();
                                        $db=$db->connection('mapa_soluciones');
                                        $stmt = $db->prepare($sql); 
                                        $stmt->bind_param("is", $ciclos[0] , $ciclos[1]);
                                        $stmt->execute();
                                        

                                        if ($stmt) { 
                                            $id_ciclos = $stmt->{"insert_id"};
                                            $sql = "INSERT INTO ejecucion_financiera (id_ejecucion_financiera, ejecucion_bolivares, ejecucion_euros, ejecucion_dolares, ejecucion_rublos , ejecucion_bolivares_final, ejecucion_euros_final, ejecucion_dolares_final, ejecucion_rublos_final) VALUES (NULL, ? , ? , ? , ? , 0 , 0 , 0 , 0)";
                                            $db = new DB();
                                            $db=$db->connection('mapa_soluciones');                                            
                                            $stmt = $db->prepare($sql); 
                                            $stmt->bind_param("dddd", $ejecucion_financiera[0] , $ejecucion_financiera[1] , $ejecucion_financiera[2] , $ejecucion_financiera[3]);                                            
                                            $stmt->execute();
                                            

                                            if ($stmt) { 
                                                $id_ejecucion_financiera = $stmt->{"insert_id"};
                                                $sql = "INSERT INTO inversion (id_inversion, inversion_bolivares, id_ejecucion_financiera, inversion_euros, inversion_dolares, inversion_rublos) VALUES (NULL, ? , ? , ? , ? , ? )";
                                                $db = new DB();
                                                $db=$db->connection('mapa_soluciones');
                                                $stmt = $db->prepare($sql); 
                                                $stmt->bind_param("diddd", $inversion[0] , $id_ejecucion_financiera , $inversion[1] , $inversion[2] , $inversion[3]);
                                                $stmt->execute();
                                                

                                                if ($stmt) { 
                                                    $sql = "INSERT INTO poblacion (id_problacion, poblacion_inicial) VALUES (NULL, ? )";
                                                    $db = new DB();
                                                    $db=$db->connection('mapa_soluciones');
                                                    $stmt = $db->prepare($sql); 
                                                    $poblacion_inicial = $poblacion_inicial+0;
                                                    $stmt->bind_param("i", $poblacion_inicial);
                                                    $stmt->execute();
                                                    

                                                    if ($stmt) { 
                                                        $id_poblacion = $stmt->{"insert_id"};
                                                        $sql = "INSERT INTO lps (id_lps, lps_inicial, lps_final) VALUES (NULL, ? , 0 )";
                                                        $db = new DB();
                                                        $db=$db->connection('mapa_soluciones');
                                                        $stmt = $db->prepare($sql); 
                                                        $stmt->bind_param("i", $lps_inicial);
                                                        $stmt->execute();
                                                        

                                                        if ($stmt){  
                                                            $id_lps = $stmt->{"insert_id"};
                                                            $sql = "INSERT INTO proyectos (id_proyecto, id_datos, id_hidrologica, id_estado, id_municipio, id_parroquia, id_obra, id_sector, id_lapso, id_ciclo, id_estatus, id_estado_proyecto, id_ejecucion_financiera, id_poblacion, id_lps) 
                                                                    VALUES (NULL, ? ,  ? , ? , ? , ? , ?, ? , ? , ?, 0 , ? , ? , ?, ?)";
                                                            $db = new DB();
                                                            $db=$db->connection('mapa_soluciones');
                                                            $stmt = $db->prepare($sql); 
                                                            $stmt->bind_param("iiiiiiiiiiiii", $id_datos , $proyecto[0] , $proyecto[1] , $proyecto[2] , $proyecto[3]  , $id_obras , $id_sector , $id_lapso , $id_ciclos , $proyecto[5] , $id_ejecucion_financiera , $id_poblacion , $id_lps);
                                                            $stmt->execute();
                                                            $id_proyecto = $stmt->{"insert_id"};
                                                            
                                                                if ($stmt) {
                                                                    $sql = "SELECT `hidrologicas`.`hidrologica`, `estados`.`estado` FROM `hidrologicas` LEFT JOIN `estados` ON `hidrologicas`.`id_estado` = `estados`.`id_estado` WHERE `hidrologicas`.`id_estado`=? or `hidrologicas`.`id_estado2`=? OR `hidrologicas`.`id_estado3` = ?";
                                                                    
                                                                    try {
                                                                        $db = new DB();
                                                                        $db=$db->connection('mapa_soluciones');
                                                                        $stmt = $db->prepare($sql); 
                                                                        $stmt->bind_param("iii", $proyecto[1],$proyecto[1], $proyecto[1]);
                                                                        $stmt->execute();
                                                                        $resultado = $stmt->get_result();
                                                                        $resultado = $resultado->fetch_all(MYSQLI_ASSOC); 
                                                                            
                                                                        $Hidrologica = $resultado[0]["hidrologica"];
                                                                        $estado = $resultado[0]["estado"];

                                                                            if ($resultado) {
                                                                                $sql = "SELECT municipios.municipio
                                                                                FROM municipios WHERE municipios.id_municipio = ?";

                                                                                $db = new DB();
                                                                                $db=$db->connection('mapa_soluciones');
                                                                                $stmt = $db->prepare($sql); 
                                                                                $stmt->bind_param("i", $proyecto[2]);
                                                                                $stmt->execute();
                                                                                $resultado = $stmt->get_result();
                                                                                $resultado = $resultado->fetch_all(MYSQLI_ASSOC);         
                                                                                $municipio = $resultado[0]["municipio"];
                                                                                
                                                                            }
                                                                                if ($resultado) {
                                                                                    $sql = "SELECT `soluciones`.`solucion`
                                                                                    FROM `soluciones` WHERE `soluciones`.`id_solucion` = ?";

                                                                                    $db = new DB();
                                                                                    $db=$db->connection('mapa_soluciones');
                                                                                    $stmt = $db->prepare($sql); 
                                                                                    $stmt->bind_param("i", $datos[1]);
                                                                                    $stmt->execute();
                                                                                    $resultado = $stmt->get_result();
                                                                                    $resultado = $resultado->fetch_all(MYSQLI_ASSOC);         
                                                                                    $solucion = $resultado[0]["solucion"];
                                                                                    
                                                                                }

                                                                        $array = [
                                                                            "mensaje" => "Proyecto Creado",
                                                                            "Proyecto"=> $datos[0],
                                                                            "id_proyecto" => $id_proyecto,
                                                                            "Hidrologica" => $Hidrologica,
                                                                            "Estado" => $estado,
                                                                            "Municipio" => $municipio,
                                                                            "Solucion" => $solucion
                                                                        ];
                                                                        return $array;
                                                                                             
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
                                                    }
                                                }            
                                            }
                                        }
                                    }
                                }    
                            }    
                        }
                   }    

            }
        }


//Estado, hidrologica, nombre de proyecto, id?proyecto







?>