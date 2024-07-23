<?php

require_once 'Cifrador.php';
require_once dirname(__FILE__) . "/EnviarCorreo.php";

class Usuario extends Conectar
{

    private $username;
    private $password;

    // Constructor de la clase
    public function __construct()
    {
        $this->username = 'personas';
        $this->password = '@g3137c0120';
    }




    // Función para obtener los datos de la persona POLICIA NACIONAL
    private function obtenerDatosPersona($ci)
    {
        $URL = 'https://ws.mspbs.gov.py/api/getPersonas.php?cedula=' . $ci;

        $ch = curl_init();
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode("$this->username:$this->password")
            )
        );
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $res = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
        curl_close($ch);

        $persona = json_decode($res, true);

        if ('200' == $status) {
            if (empty($persona['cedula_identidad'])) { // no se encontró a la persona, retornamos FALSE
                return FALSE;
            }

            // Procesar otros datos de la persona si es necesario
        }

        return $persona;
    }

    public function update_foto_ci($foto_ci, $usuario_id, $fecha_hora)
    {
        $conectar = parent::conexion();
        $sql = "UPDATE usuarios SET foto_ci = '$foto_ci',
            user_mod = $usuario_id, fecha_mod = '$fecha_hora'::timestamp
            WHERE id = $usuario_id;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return 'ok';
    }

    public function update_foto_registro($foto_resgistro, $usuario_id, $fecha_hora)
    {
        $conectar = parent::conexion();
        $sql = "UPDATE usuarios SET foto_registro_profesional = '$foto_resgistro',

            user_mod = $usuario_id, fecha_mod = '$fecha_hora'::timestamp
            WHERE id = $usuario_id;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return 'ok';
    }

    /* TODO: Listar Registro por ID en especifico */
    public function get_usuario_x_usu_id($usu_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT * FROM usuarios WHERE id = :usu_id";
        $query = $conectar->prepare($sql);
        $query->bindValue(':usu_id', $usu_id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    /* TODO:Actualizar Datos */
    public function update_usuario($datos)
    {

        try {
            $conectar = parent::Conexion();
            $respuesta = $conectar;
            $conectar->beginTransaction();
            $sql = "UPDATE public.usuarios
                SET 
                user_mod=" . $datos['usuario_id'] . ", fecha_mod='" . $datos['fecha_hora'] . "'::timestamp, 
                telefono='" . $datos['telefono'] . "',
                direccion_domicilio='" . $datos['direccion_domicilio'] . "', email='" . $datos['email'] . "',
                ciudad_id='" . $datos['ciudad_id'] . "', departamento_id='" . $datos['departamento_id'] . "', estado_civil='" . $datos['estado_civil'] . "',
                cantidad_hijo='" . $datos['cantidad_hijo'] . "' ,contacto='" . $datos['contacto'] . "'
                WHERE id = " . $datos['usuario_id'] . ";";
            $query = $conectar->prepare($sql);
            $query->execute();

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

    public static function logout()
    {
        $conectar = parent::Conexion();
        date_default_timezone_set('America/Asuncion');
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_hora = $fecha . ' ' . $hora;
        $sql = "UPDATE usuarios
                SET conectado = false, fecha_conexion = '$fecha_hora'
                WHERE id = " . $_SESSION["usuario_id"] . ";";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $conectar = null;
        return 'ok';
    }

    public function login()
    {
        $conectar = parent::Conexion();
        if (isset($_POST["enviar"])) {
            /* TODO: Recepcion de Parametros desde la Vista Login */
            $ci = $_POST["ci"];
            $pass = $_POST["password"];

            if (empty($ci) and empty($pass)) {
                header("Location:" . Conectar::ruta() . "index.php?m=2");
                exit();
            } else {

                // CIFRAR EL PASSWORD - Crear una instancia de la clase Cifrador
                $cifrador = new Cifrador();
                // Llamar al método cifrarPassword

                $ci = $_POST['ci']; // O el método que estés utilizando para obtener el CI

                // Consulta SQL modificada
                $sql = "SELECT u.*, 
                             CASE 
                                 WHEN a.id IS NOT NULL THEN 'si' 
                                 ELSE 'no' 
                             END AS es_asociado
                         FROM usuarios u
                         LEFT JOIN asociados a 
                         ON u.ci = a.ci
                         WHERE u.ci = :ci 
                         AND u.email_verified_at IS NOT NULL";

                $query = $conectar->prepare($sql);
                $query->bindParam(':ci', $ci, PDO::PARAM_STR);
                $query->execute();
                $resultado = $query->fetch(PDO::FETCH_ASSOC);

                if (is_array($resultado)) {
                    $password = $cifrador->descifrar($resultado["password"]);
                } else {

                    $password = '';

                }

                if (is_array($resultado) and count($resultado) > 0 and $password == $pass) {
                    //Registra que el usuario se encuentra activo dentro del sistema
                    date_default_timezone_set('America/Asuncion');
                    $fecha = date('Y-m-d');
                    $hora = date('H:i:s');
                    $fecha_hora = $fecha . ' ' . $hora;
                    $sql = "UPDATE usuarios
                        SET conectado = true, fecha_conexion = '$fecha_hora'
                        WHERE id = ". $resultado["id"] .";";
                    $sql = $conectar->prepare($sql);
                    $sql->execute();
                    /* TODO:Generar variables de Session del Usuario */
                    $_SESSION["usuario_id"] = $resultado["id"];
                    $_SESSION["nombre"] = $resultado["nombre"];
                    $_SESSION["apellido"] = $resultado["apellido"];
                    $_SESSION["email"] = $resultado["email"];
                    $_SESSION["suc_id"] = $resultado["suc_id"];
                    $_SESSION["cedula"] = $resultado["ci"];
                    $_SESSION["fecha_nacimiento"] = $resultado["fecha_nacimiento"];
                    $_SESSION["telefono"] = $resultado["telefono"];
                    $_SESSION["celular"] = $resultado["celular"];
                    $_SESSION["direccion_domicilio"] = $resultado["direccion_domicilio"];
                    $_SESSION["ciudad_id"] = $resultado["ciudad_id"];
                    $_SESSION["area_id"] = $resultado["area_id"];
                    $_SESSION["es_asociado"] = $resultado["es_asociado"];

                    $roles_usuario = Usuario::get_roles_x_usuario($resultado["id"]);
                    foreach ($roles_usuario as $rol_usuario) {
                        if ($_SESSION["es_asociado"] == 'si') {

                            if ($rol_usuario["rol_nom"] == "PROFESIONAL") {
                                $_SESSION["inicio"] = "index.php";

                            } elseif ($rol_usuario["rol_nom"] == "OPERATIVO") {
                                //   $_SESSION["inicio"]="indexOperativo.php";
                                $_SESSION["inicio"] = "index.php";

                            } elseif ($rol_usuario["rol_nom"] == "GERENTE") {
                                $_SESSION["inicio"] = "indexGerencia.php";

                            }

                        } else {

                            $_SESSION["inicio"] = "indexGeneral.php";

                        }

                    }
                    header("Location:" . Conectar::ruta() . "view/home/" . $_SESSION["inicio"]);
                } else {
                    // $_SESSION["m"] = 1;
                    header("Location:" . Conectar::ruta() . "view/Registrarse/login.php?m=1");
                    exit();
                }
                $conectar = null;
            }
        } else {
            exit();
        }
    }

    public function register()
    {

        $conectar = parent::Conexion();
        if (isset($_POST["enviar"])) {
            /* TODO: Recepcion de Parametros desde la Vista Login */
            $ci = $_POST["register_user"];
            $correo = $_POST["register_correo"];
            $concorreo = $_POST["register_concorreo"];
            $register_password = $_POST["register_password"];
            $conpassword = $_POST["register_conpassword"];
            $telefono = $_POST["dial_code"] . $_POST["register_telefono"];

            if (empty($ci) or empty($correo)) {
                header("Location:" . Conectar::ruta() . "view/Registrarse/registrarse.php?m=2");
                exit();

            }

            if ($correo != $concorreo) {

                header("Location:" . Conectar::ruta() . "view/Registrarse/registrarse.php?m=4");

                exit();

            }

            if ($register_password != $conpassword) {

                header("Location:" . Conectar::ruta() . "view/Registrarse/registrarse.php?m=5");

                exit();

            }

            // Verificar que el código de país y el número de teléfono no estén vacíos y sean números
            if (empty($telefono) || !is_numeric($telefono)) {

                header("Location:" . Conectar::ruta() . "view/Registrarse/registrarse.php?m=6");
                exit();
            }

            // Verificar si el correo electrónico es válido
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                // Redirigir al usuario de vuelta a la página de registro con un mensaje de error
                header("Location:" . Conectar::ruta() . "view/Registrarse/registrarse.php?m=7");
                exit();
            }

            $sql = "select * from usuarios where email = '" . $correo . "' or ci= '" . $ci . "'";
            $query = $conectar->prepare($sql);
            $query->execute();
            $resultado = $query->fetch(PDO::FETCH_ASSOC);
            if ($resultado) {
                // Verificar si el resultado coincide con el correo o la cédula
                if ($resultado['email'] == $correo) {
                    // Redirigir al usuario indicando que el correo ya está registrado
                    header("Location:" . Conectar::ruta() . "view/Registrarse/registrarse.php?m=3&tipo=email.$correo");
                    exit();
                } elseif ($resultado['ci'] == $ci) {
                    // Redirigir al usuario indicando que la cédula ya está registrada
                    header("Location:" . Conectar::ruta() . "view/Registrarse/registrarse.php?m=3&tipo=ci.$ci");
                    exit();
                }
            } else {

                $conectar->beginTransaction();

                try {
                    // Obtener los datos de la persona desde la Policía Nacional
                    $persona = $this->obtenerDatosPersona($ci);
                    if ($persona !== FALSE) {
                        // Procesar los datos de la persona obtenidos
                        $nombres = $persona['nombres'];
                        $apellidos = $persona['apellidos'];
                        $nombres_apellidos = $nombres . ' ' . $apellidos;
                        $fecha_nacimiento = date_format(date_create($persona['fecha_nacimiento']), 'Y-m-d');
                        if ($persona['codigo_genero'] == 2) {

                            $sexo = 'M';

                        } else {

                            $sexo = 'F';

                        }
                    } else {
                        // Redirigir al usuario de vuelta a la página de registro con un mensaje de error
                        header("Location:" . Conectar::ruta() . "view/Registrarse/registrarse.php?m=8");
                        exit();
                    }

                    // Generar un token único
                    $token = bin2hex(random_bytes(32)); // Genera un token aleatorio de 32 bytes

                    // CIFRAR EL PASSWORD - Crear una instancia de la clase Cifrador
                    $cifrador = new Cifrador();
                    // Llamar al método cifrarPassword
                    $passwordCifrado = $cifrador->cifrarTexto($register_password);

                    // Insertar datos del usuario en la tabla usuarios
                    $sql = "INSERT INTO public.usuarios (
                                    ci, email, password,
                                    activo, user_crea, fecha_crea, user_mod, fecha_mod,
                                    tmp_token, nombre, apellido, telefono,sexo,fecha_nacimiento
                                ) VALUES (
                                    '" . $ci . "', '" . $correo . "', '" . $passwordCifrado . "', true, '" . $ci . "', NOW(), '" . $ci . "', NOW(), '" . $token . "',
                                    '" . $nombres . "', '" . $apellidos . "', '" . $telefono . "', '" . $sexo . "', '" . $fecha_nacimiento . "'
                                ) RETURNING id"; // Utiliza RETURNING para obtener el usuario_id después de la inserción
                    $query = $conectar->prepare($sql);
                    $query->execute();

                    // Obtener el usuario_id devuelto por la consulta
                    $usuario_id = $query->fetchColumn();

                    // Insertar el rol del usuario en la tabla roles_usuarios
                    $sql2 = "INSERT INTO public.roles_usuarios (
                                    usuario_id, rol_id, activo, user_crea, fecha_crea, user_mod, fecha_mod
                                ) VALUES (
                                    $usuario_id, 3, true, '" . $ci . "', NOW(), '" . $ci . "', NOW()
                                )";
                    $query2 = $conectar->prepare($sql2);
                    $query2->execute();

                    // Enviar el correo de confirmación
                    $enviadorCorreo = new EnviarCorreo();
                    if ($enviadorCorreo->enviarCorreoConfirmacion($correo, $nombres_apellidos, $ci, $telefono, $token)) {
                        $conectar->commit();

                        $correoCifrado = $cifrador->cifrarTexto($correo);
                        header("Location:" . Conectar::ruta() . "view/Registrarse/envio_confirmacion.php?m=1&correo=" . urlencode($correoCifrado));
                        exit();
                    } else {
                        $conectar->rollBack();
                        echo "Error al enviar el correo de confirmación.";
                    }

                } catch (PDOException $e) {
                    // Si hay un error, hacer rollback de la transacción
                    $conectar->rollBack();
                    echo "Error al insertar en la base de datos: " . $e->getMessage();
                }
            }

        }

    }
    public function reEnvioCorreo()
    {

        $conectar = parent::Conexion();

        if (isset($_POST["correo"])) {
            $cifrador = new Cifrador();
            $correoCifrado = trim($_POST["correo"]);
            $correo = $cifrador->descifrar(trim($correoCifrado));
            $sql = "select * from usuarios where email = '" . $correo . "' ";
            $query = $conectar->prepare($sql);
            $query->execute();
            $resultado = $query->fetch();

            if (is_array($resultado)) {

                $nombre = $resultado["nombre"];
                $apellido = $resultado["apellido"];
                $ci = $resultado["ci"];
                $telefono = $resultado["telefono"];
                $token = $resultado["tmp_token"];

                $sql = "select * from usuarios where email = '" . $correo . "' and email_verified_at is not null";
                $query = $conectar->prepare($sql);
                $query->execute();
                if ($query->rowCount() > 0) {

                    header("Location:" . Conectar::ruta() . "view/Registrarse/envio_confirmacion.php?m=2&correo=" . urlencode($correoCifrado));
                    exit();

                } else {

                    try {
                        // Enviar el correo de confirmación
                        $enviadorCorreo = new EnviarCorreo();

                        if ($enviadorCorreo->enviarCorreoConfirmacion($correo, $nombre . ' ' . $apellido, $ci, $telefono, $token)) {

                            header("Location:" . Conectar::ruta() . "view/Registrarse/envio_confirmacion.php?m=1&correo=" . urlencode($correoCifrado));
                            exit();

                        } else {


                            echo "Error al enviar el correo de confirmación.";

                        }

                    } catch (PDOException $e) {
                        // Si hay un error, hacer rollback de la transacción

                        echo "Error al insertar en la base de datos: " . $e->getMessage();
                    }


                }

            } else {


                header("Location:" . Conectar::ruta() . "view/Registrarse/envio_confirmacion.php?m=3&correo=" . urlencode($correoCifrado));
                exit();

            }
        }
    }

    public function recuperarPassword()
    {

        $conectar = parent::Conexion();

        if (isset($_POST["correo"])) {
            $correo = trim($_POST["correo"]);
            $sql = "select * from usuarios where email = '" . $correo . "' and email_verified_at is not null";
            $query = $conectar->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {

                try {

                    // Generar un token único
                    $token = bin2hex(random_bytes(32)); // Genera un token aleatorio de 32 bytes
                    $sql = "update usuarios set  tmp_token = '" . $token . "' where  email = '" . $correo . "'";
                    $query = $conectar->prepare($sql);
                    $query->execute();
                    // Enviar el correo de confirmación
                    $enviadorCorreo = new EnviarCorreo();

                    if ($enviadorCorreo->enviarCorreoReseteo($correo, $token)) {

                        header("Location:" . Conectar::ruta() . "view/Registrarse/send_reset_pass.php?m=1");
                        exit();

                    } else {

                        header("Location:" . Conectar::ruta() . "view/Registrarse/send_reset_pass.php?m=2");
                        exit();

                    }

                } catch (PDOException $e) {
                    // Si hay un error, hacer rollback de la transacción

                    header("Location:" . Conectar::ruta() . "view/Registrarse/send_reset_pass.php?m=2");
                    exit();
                }

            }
        }
    }

    public function confirmarCuenta($token)
    {

        $conectar = parent::Conexion();
        $sql = "select * from usuarios where email_verified_at isnull and tmp_token = '" . $token . "'";
        $query = $conectar->prepare($sql);
        $query->execute();
        $resultado = $query->fetch();
        if (is_array($resultado) and count($resultado) > 0) {

            $sql = "update usuarios set email_verified_at = NOW() where  tmp_token = '" . $token . "'";
            $query = $conectar->prepare($sql);
            $query->execute();
            $resultado = $query->fetch();
            header("Location:" . Conectar::ruta() . "view/Registrarse/confirmacion_cuenta.php?m=1");

            exit();

        }

        $sql = "select * from usuarios where email_verified_at is not null and tmp_token = '" . $token . "'";

        $query = $conectar->prepare($sql);
        $query->execute();
        $resultado = $query->fetch();
        if (is_array($resultado) and count($resultado) > 0) {

            header("Location:" . Conectar::ruta() . "view/Registrarse/confirmacion_cuenta.php?m=2");

            exit();

        }

        $sql = "select * from usuarios where tmp_token = '" . $token . "'";
        $query = $conectar->prepare($sql);
        $query->execute();
        $resultado = $query->fetch();

        if (!is_array($resultado)) {

            header("Location:" . Conectar::ruta() . "view/Registrarse/confirmacion_cuenta.php?m=3");
            exit();

        }
    }
    public function resetPassword()
    {

        $token = $_POST["token"];
        $password = $_POST["password"];

        $conectar = parent::Conexion();
        $sql = "select * from usuarios where tmp_token = '" . $token . "'";
        $query = $conectar->prepare($sql);
        $query->execute();
        $resultado = $query->fetch();
        if (is_array($resultado) and count($resultado) > 0) {
            // CIFRAR EL PASSWORD - Crear una instancia de la clase Cifrador
            $cifrador = new Cifrador();
            // Llamar al método cifrarPassword
            $passwordCifrado = $cifrador->cifrarTexto($password);

            $sql = "update usuarios set  tmp_token = 1,  password = '" . $passwordCifrado . "' where  tmp_token = '" . $token . "'";
            $query = $conectar->prepare($sql);
            $query->execute();
            $resultado = $query->fetch();
            header("Location:" . Conectar::ruta() . "view/Registrarse/reset_password.php?m=1");
            exit();

        } else {

            header("Location:" . Conectar::ruta() . "view/Registrarse/reset_password.php?m=2");
            exit();

        }


    }
    // Carga los permisos que tiene el usuario según sus roles
    public static function get_permisos_x_roles($usuario_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT roles_permisos.permiso_id, permisos.nombre_permiso
            FROM roles_permisos
            JOIN roles_usuarios on roles_usuarios.rol_id = roles_permisos.rol_id
            JOIN permisos on permisos.id = roles_permisos.permiso_id
            WHERE roles_usuarios.usuario_id = $usuario_id;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    // Carga los roles que tiene el usuario según sus roles
    public static function get_roles_x_usuario($usuario_id)
    {
        $conectar = parent::Conexion();
        $sql = "select rol_nom 
            from roles_usuarios
            join roles on roles.id = roles_usuarios.rol_id
            WHERE roles_usuarios.usuario_id = $usuario_id;";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    /* TODO: Subit imagen de usuario */
    public function upload_image()
    {
        if (isset($_FILES["usu_img"])) {
            $extension = explode('.', $_FILES['usu_img']['name']);
            $new_name = rand() . '.' . $extension[1];
            $destination = '../assets/usuario/' . $new_name;
            move_uploaded_file($_FILES['usu_img']['tmp_name'], $destination);
            return $new_name;
        }
    }

    /* TODO: Total de Tickets por categoria segun usuario */
    public function get_usuario_grafico($usuario_id)
    {
        $conectar = parent::conexion();
        // parent::set_names();
        $sql = "SELECT tipos_documentos.documento as nom,COUNT(*) AS total
                FROM   datos_personales  JOIN  
                    tipos_documentos ON datos_personales.tipo_doc_id = tipos_documentos.id  
                WHERE    
                datos_personales.activo = true
                and datos_personales.usuario_id = $usuario_id
                GROUP BY 
                tipos_documentos.documento 
                ORDER BY total DESC";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    /* TODO: Listar documentos pertenecientes a Currículum Virtual */
    public function get_cantidades_tramites($usu_id)
    {
        $conectar = parent::Conexion();
        $sql = "select 
            count(*) as cantidad_tramites from tramites_gestionados
            where usuario_id = $usu_id
            AND activo = true;";
        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_cantidades_tramites_sistema()
    {
        $conectar = parent::Conexion();
        $sql = "select 
            count(*) as cantidad_tramites from tramites_gestionados
            WHERE activo = true;";
        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_cantidades_reposos($cedula)
    {
        $conectar = parent::ConexionSirepro();
        $sql = "select 
            count(*) as cant_reposos from reposos
            where ciprof = '$cedula'";
        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function get_cantidad_usuarios(){
        $conectar = parent::Conexion();
        $sql = "SELECT 
            count(*) as cantidad_usuarios from usuarios
            where activo = true;";
        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetch();
    }

    public static function get_cantidad_publicaciones(){
        $conectar = parent::Conexion();
        $sql = "SELECT 
            count(*) as cantidad_publicaciones from publicaciones
            where activo = true;";
        $query = $conectar->prepare($sql);
        $query->execute();
        return $query->fetch();
    }

    public static function get_usuarios($usuario_id)
    {
        $conectar = parent::Conexion();
        $sql = 'SELECT 
                    id, nombre || \' \' || apellido AS usuario, ci, foto_perfil as amigos_foto_perfil,
                    "jsonDatosProfesionales", conectado
                    FROM usuarios WHERE id  NOT IN (:usuario_id)';
        $query = $conectar->prepare($sql);
        $query->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function get_usuario($usuario_id)
    {
        $conectar = parent::Conexion();

        $sql = 'SELECT u.*, p.*, esta.*
                    FROM public.usuarios u
                    LEFT JOIN profesiones p ON u.profesion_id = p.id
                    LEFT JOIN establecimientos_salud esta ON CAST(u."jsonDatosProfesionales"->0->>\'lugar_trabajo\' AS bigint) = esta.id
                    WHERE u.id = :usuario_id';

        $query = $conectar->prepare($sql);
        $query->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }


    public function get_establecimientos_salud()
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id AS establecimiento_id, 
            nombre_establecimiento 
            FROM establecimientos_salud order by nombre_establecimiento";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_establecimiento_salud($id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT *
            FROM establecimientos_salud WHERE id = $id";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetch(PDO::FETCH_ASSOC);
    }


    public function get_profesiones()
    {
        $conectar = parent::Conexion();
        $sql = "SELECT id AS profesion_id, 
            profesion AS nombre_profesion 
            FROM profesiones;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function get_datos_personales($usuario_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT nombre, apellido, 
            nombre || ' ' || apellido AS nombre_apellido,
            ci AS documento_identidad, fecha_nacimiento,
            telefono, email,
            direccion_domicilio, ciudad_id ,departamento_id, 
            estado_civil, contacto, cantidad_hijo
            FROM usuarios
            WHERE id = $usuario_id;";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update_datos_profesionales($datos)
    {

        try {
            $conectar = parent::Conexion();
            $conectar->beginTransaction();

            // Preparar el JSON para ser insertado correctamente en la consulta SQL
            $jsonDatosProfesionales = json_encode($datos['jsonDatosProfesionales'], JSON_UNESCAPED_UNICODE);

            // Construir la consulta SQL para actualizar los datos
            $sql = "UPDATE public.usuarios
                        SET 
                            profesion_id = :profesion_id,
                             \"jsonDatosProfesionales\" = :jsonDatosProfesionales,
                            user_mod = :usuario_id,
                            fecha_mod = :fecha_hora
                        WHERE id = :usuario_id";

            // Preparar la consulta
            $query = $conectar->prepare($sql);

            // Bind de parámetros
            $query->bindParam(':profesion_id', $datos['profesion_id'], PDO::PARAM_INT);
            $query->bindParam(':jsonDatosProfesionales', $jsonDatosProfesionales, PDO::PARAM_STR);
            $query->bindParam(':usuario_id', $datos['usuario_id'], PDO::PARAM_INT);
            $query->bindParam(':fecha_hora', $datos['fecha_hora'], PDO::PARAM_STR);

            // Ejecutar la consulta
            $query->execute();

            // Confirmar la transacción
            $conectar->commit();

            // Cerrar la conexión
            $conectar = null;

            return 'ok'; // Retornar 'ok' si todo fue exitoso

        } catch (PDOException $e) {

            $mensaje = 'Error en la actualización: ' . $e->getMessage();
            error_log($mensaje);
            return $mensaje;
        }
    }

    public function get_datos_profesionales($usuario_id)
    {
        $conectar = parent::Conexion();
        $sql = "SELECT registro_profesional, profesion_id,
           \"jsonDatosProfesionales\"
            FROM usuarios WHERE id = $usuario_id";
        $query = $conectar->prepare($sql);
        $query->execute();
        $conectar = null;
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>