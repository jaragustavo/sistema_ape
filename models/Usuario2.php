<?php
    class Usuario2 extends Conectar{
        /* TODO: Listar Registros */
        public function get_usuario_x_suc_id($suc_id){
            $conectar=parent::Conexion();
            $sql="SP_L_USUARIO_01 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        /* TODO: Listar Registro por ID en especifico */
        public function get_usuario_x_usu_id($usu_id){
            $conectar=parent::Conexion();
            $sql="SP_L_USUARIO_02 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$usu_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        /* TODO: Eliminar o cambiar estado a eliminado */
        public function delete_usuario($usu_id){
            $conectar=parent::Conexion();
            $sql="SP_D_USUARIO_01 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$usu_id);
            $query->execute();
        }

        /* TODO: Registro de datos */
        public function insert_usuario($suc_id,$usu_correo,$usu_nom,$usu_ape,$usu_dni,$usu_telf,$usu_pass,$rol_id,$usu_img){
            $conectar=parent::Conexion();

            require_once("Usuario.php");
            $usu=new Usuario();
            $usu_img='';
            if($_FILES["usu_img"]["name"] !=''){
                $usu_img=$usu->upload_image();
            }

            $sql="SP_I_USUARIO_01 ?,?,?,?,?,?,?,?,?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->bindValue(2,$usu_correo);
            $query->bindValue(3,$usu_nom);
            $query->bindValue(4,$usu_ape);
            $query->bindValue(5,$usu_dni);
            $query->bindValue(6,$usu_telf);
            $query->bindValue(7,$usu_pass);
            $query->bindValue(8,$rol_id);
            $query->bindValue(9,$usu_img);
            $query->execute();
        }

        /* TODO:Actualizar Datos */
        public function update_usuario($datos){
            
            try{
                $conectar = parent::Conexion();
                $respuesta = $conectar;
                $conectar->beginTransaction();$sql="UPDATE public.usuarios
                SET nombre='".$datos['nombre']."', apellido='".$datos['apellido']."',
                user_mod=".$datos['usuario_id'].", fecha_mod='".$datos['fecha_hora']."'::timestamp, 
                telefono='".$datos['telefono']."',fecha_nacimiento='".$datos['fecha_nacimiento']."'::timestamp,
                direccion_domicilio='".$datos['direccion_domicilio']."', email='".$datos['email']."'
                WHERE id = ".$datos['usuario_id'].";";
                $query=$conectar->prepare($sql);
                $query->execute();
                $_SESSION["nombre"]=$datos['nombre'];
                $_SESSION["apellido"]=$datos['apellido'];
            } catch (Exception $e) {
                $conectar->rollBack();

                $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
                error_log($men . ' ' . $sql);
                echo $men . ' ' . $sql;
                return $men . ' ' . $sql;
            }
            if ($respuesta === $conectar) {
                $conectar->commit();
                echo 'ok';
                $conectar = null;
                return $conectar;

            }
        }

        public static function get_datos_personales($usuario_id){
            $conectar=parent::Conexion();
            $sql="SELECT nombre, apellido, 
            nombre || ' ' || apellido AS nombre_apellido,
            ci AS documento_identidad, fecha_nacimiento,
            telefono, email,
            direccion_domicilio, ciudad_id AS ciudad
            FROM usuarios
            WHERE id = $usuario_id;";
            $query=$conectar->prepare($sql);
            $query->execute();
            $conectar = null;
            return $query->fetch();
        }

        public function update_usuario_pass($usu_id,$usu_pass){
            $conectar=parent::Conexion();
            $sql="SP_U_USUARIO_02 ?,?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$usu_id);
            $query->bindValue(2,$usu_pass);
            $query->execute();
            $conectar = null;
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        /* TODO:Acceso al Sistema */
        public function login(){
            $conectar=parent::Conexion();
            if (isset($_POST["enviar"])){
                /* TODO: Recepcion de Parametros desde la Vista Login */
                $ci = $_POST["ci"];
                $pass =  $_POST["password"];
               
                if (empty($ci) and empty($pass)){
                    header("Location:".Conectar::ruta()."index.php?m=2");
                    exit();
                }else{
                    $sql="select * from usuarios where ci = '".$ci."' and password= '".$pass."'";

                    // error_log('$$$$$$$$$$$$$$ '.$sql);
                    $query=$conectar->prepare($sql);
                    $query->execute();
                    $resultado = $query->fetch();
                    if (is_array($resultado) and count($resultado)>0){
                        /* TODO:Generar variables de Session del Usuario */
                        $_SESSION["usuario_id"]=$resultado["id"];
                        $_SESSION["nombre"]=$resultado["nombre"];
                        $_SESSION["apellido"]=$resultado["apellido"];
                        $_SESSION["email"]=$resultado["email"];
                        $_SESSION["suc_id"]=$resultado["suc_id"];
                        $_SESSION["cedula"]=$resultado["ci"];
                        $_SESSION["fecha_nacimiento"]=$resultado["fecha_nacimiento"];
                        $_SESSION["telefono"]=$resultado["telefono"];
                        $_SESSION["celular"]=$resultado["celular"];
                        $_SESSION["direccion_domicilio"]=$resultado["direccion_domicilio"];
                        $_SESSION["ciudad_id"]=$resultado["ciudad_id"];
                        $_SESSION["area_id"]=$resultado["area_id"];

                        $roles_usuario = Usuario::get_roles_x_usuario($resultado["id"]);
                        foreach ($roles_usuario as $rol_usuario){
                            if($rol_usuario["rol_nom"] == "PROFESIONAL"){
                                $_SESSION["inicio"]="index.php";
                                header("Location:".Conectar::ruta()."view/home/");
                            }
                            elseif($rol_usuario["rol_nom"] == "OPERATIVO"){
                                $_SESSION["inicio"]="indexOperativo.php";
                                header("Location:".Conectar::ruta()."view/home/indexOperativo.php");
                            }
                            elseif($rol_usuario["rol_nom"] == "GERENTE"){
                                $_SESSION["inicio"]="indexGerencia.php";
                                header("Location:".Conectar::ruta()."view/home/indexGerencia.php");
                            }
                        }
                    }else{
                        // $_SESSION["m"] = 1;
                        header("Location:".Conectar::ruta()."index.php?m=1");
                        exit();
                    }
                }
            }else{
                exit();
            }
        }

        // Carga los permisos que tiene el usuario según sus roles
        public static function get_permisos_x_roles($usuario_id){
            $conectar=parent::Conexion();
            $sql="SELECT roles_permisos.permiso_id, permisos.nombre_permiso
            FROM roles_permisos
            JOIN roles_usuarios on roles_usuarios.rol_id = roles_permisos.rol_id
            JOIN permisos on permisos.id = roles_permisos.permiso_id
            WHERE roles_usuarios.usuario_id = $usuario_id;";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        // Carga los roles que tiene el usuario según sus roles
        public static function get_roles_x_usuario($usuario_id){
            $conectar=parent::Conexion();
            $sql="select rol_nom 
            from roles_usuarios
            join roles on roles.id = roles_usuarios.rol_id
            WHERE roles_usuarios.usuario_id = $usuario_id;";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO: Subit imagen de usuario */
        public function upload_image(){
            if (isset($_FILES["usu_img"])){
                $extension = explode('.', $_FILES['usu_img']['name']);
                $new_name = rand() . '.' . $extension[1];
                $destination = '../assets/usuario/' . $new_name;
                move_uploaded_file($_FILES['usu_img']['tmp_name'], $destination);
                return $new_name;
            }
        }

        /* TODO: Total de Tickets por categoria segun usuario */
        public function get_usuario_grafico($usuario_id){
            $conectar= parent::conexion();
            // parent::set_names();
            $sql="SELECT tipos_documentos.documento as nom,COUNT(*) AS total
                FROM   datos_personales  JOIN  
                    tipos_documentos ON datos_personales.tipo_doc_id = tipos_documentos.id  
                WHERE    
                datos_personales.activo = true
                and datos_personales.usuario_id = $usuario_id
                GROUP BY 
                tipos_documentos.documento 
                ORDER BY total DESC";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO: Listar documentos pertenecientes a Currículum Virtual */
        public function get_cantidades_tramites($usu_id){
            $conectar=parent::Conexion();
            $sql="select 
            count(*) as cantidad_tramites from tramites_gestionados
            where usuario_id = $usu_id
            AND activo = true;";
            $query=$conectar->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get_cantidades_reposos($cedula){
            $conectar=parent::ConexionSirepro();
            $sql="select 
            count(*) as cant_reposos from reposos
            where ciprof = '$cedula'";
            $query=$conectar->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get_total_reposos_visados(){
            $conectar=parent::ConexionSirepro();
            $sql="select 
            count(*) as cant_reposos from reposos";
            $query=$conectar->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function get_usuarios($usuario_id){
            $conectar=parent::Conexion();
            $sql="SELECT 
            id, nombre || ' ' || apellido AS usuario, ci 
            from usuarios WHERE id NOT IN ($usuario_id)";
            $query=$conectar->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get_establecimientos_salud(){
            $conectar=parent::Conexion();
            $sql="SELECT id AS establecimiento_id, 
            nombre_establecimiento 
            FROM establecimientos_salud;";
            $query=$conectar->prepare($sql);
            $query->execute();
            $conectar = null;
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get_profesiones(){
            $conectar=parent::Conexion();
            $sql="SELECT id AS profesion_id, 
            profesion AS nombre_profesion 
            FROM profesiones;";
            $query=$conectar->prepare($sql);
            $query->execute();
            $conectar = null;
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        public function update_datos_profesionales($datos){
            
            try{
                $conectar = parent::Conexion();
                $respuesta = $conectar;
                $conectar->beginTransaction();
                
                $sql="UPDATE public.usuarios
                SET registro_profesional=".$datos['registro_profesional'].", 
                profesion_id=".$datos['profesion'].",
                user_mod=".$datos['usuario_id'].", fecha_mod='".$datos['fecha_hora']."'::timestamp
                WHERE id = ".$datos['usuario_id'].";";
                $query=$conectar->prepare($sql);
                $query->execute();

                // Split the string into an array using the explode function
                $lugares_trabajo_array = explode(',', $datos['lugares_trabajo']);
                $sql = "DELETE FROM public.lugares_trabajos
                    WHERE usuario_id = ".$datos['usuario_id'].";";
                    $query=$conectar->prepare($sql);
                    $query->execute();
                if(is_array($lugares_trabajo_array)){
                    foreach($lugares_trabajo_array as $lugar_trabajo){
                        $sql="INSERT INTO public.lugares_trabajos(
                            usuario_id, establecimiento_salud_id, 
                            fecha_crea, user_crea, 
                            fecha_mod, user_mod, activo)
                            VALUES (".$datos['usuario_id'].", $lugar_trabajo, 
                                '".$datos['fecha_hora']."'::timestamp, ".$datos['usuario_id'].", 
                                '".$datos['fecha_hora']."'::timestamp, ".$datos['usuario_id'].", true);";
                        $query=$conectar->prepare($sql);
                        $query->execute();
                        
                    }
                }

            } catch (Exception $e) {
                $conectar->rollBack();

                $men = str_replace('SQLSTATE[P0001]: Raise exception: 7 ERROR:', '', $e->getMessage());
                error_log($men . ' ' . $sql);
                echo $men . ' ' . $sql;
                return $men . ' ' . $sql;
            }
            if ($respuesta === $conectar) {
                $conectar->commit();
                echo 'ok';
                $conectar = null;
                return $conectar;

            }
        }

        public function get_datos_profesionales($usuario_id){
            $conectar=parent::Conexion();
            $sql="SELECT registro_profesional, profesion_id AS profesion,
            es.id AS id_establecimiento
            FROM lugares_trabajos AS lt
            JOIN usuarios AS u ON u.id = lt.usuario_id
            JOIN establecimientos_salud AS es ON es.id = lt.establecimiento_salud_id
            WHERE lt.usuario_id = $usuario_id
            AND lt.activo
            AND es.activo;";
            $query=$conectar->prepare($sql);
            $query->execute();
            $conectar = null;
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>