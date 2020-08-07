<?php
        class Usuarios {

            private $nick;
            private $pass;

            function __construct($nick=null , $pass=null){
                $this->nick=$nick;
                $this->pass=$pass;
            }

            function creacion($body , $email , $rol , $hidrologica){


                $sql = "SELECT usuarios.nick, usuarios.email FROM usuarios WHERE usuarios.nick = ? OR usuarios.email = ? ";
         
                try {
                   $db = new DB();
                   $db=$db->connection('usuarios_m_soluciones');
                   $stmt = $db->prepare($sql); 
                   $stmt->bind_param("ss", $this->nick , $email);
                   $stmt->execute();
                   $stmt = $stmt->get_result();

                   if ($stmt->num_rows === 0) {
                       $sql = "INSERT INTO usuarios (id_usuario, nick, email, pass, id_rol, id_hidrologica) VALUES (NULL, ? , ? , ? , 3 , ? )";
                       
                       $db = new DB();
                       $db=$db->connection('usuarios_m_soluciones');
                       $stmt = $db->prepare($sql); 
                       $stmt->bind_param("sssi", $this->nick , $email , $this->pass , $hidrologica);
                       $stmt->execute();

                        return "Usuario Creado";
                    
                    }else{
                        return "error";
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








        } 


?>