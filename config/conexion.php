<?php
    /* TODO: Inicio de Session */
    session_start();
    class Conectar{
        
        static public function Conexion(){
			// $contraseña = "nicoHermann2003....";
			// $usuario = "postgres";
			// $nombreBaseDeDatos = "sistema_ape";
	        // $rutaServidor = "localhost";
			// $puerto = "5432";
				
			$contraseña = "postgres";
			$usuario = "postgres";
			$nombreBaseDeDatos = "sistema_ape";
	        $rutaServidor = "localhost";
			$puerto = "5432";

			try {
           $dbh= new PDO("pgsql:host=$rutaServidor;port=$puerto;dbname=$nombreBaseDeDatos", $usuario, $contraseña, 
						 array(PDO::ATTR_PERSISTENT => true));
						 $conectar =$dbh;
			$conectar->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			} catch (Exception $e) {

				echo "Ocurrió un error con la base de datos: " . $e->getMessage();
			}
			return $conectar;
		
	    }

        protected function ConexionSirepro(){

			$contraseña = "nicoHermann2003....";
			$usuario = "postgres";
			$nombreBaseDeDatos = "sirepro";
	        $rutaServidor = "159.65.242.229";
			$puerto = "5432";

			try {
            $conectar = $this->dbh= new PDO("pgsql:host=$rutaServidor;port=$puerto;dbname=$nombreBaseDeDatos", $usuario, $contraseña, 
						 array(PDO::ATTR_PERSISTENT => true));

			$conectar->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			} catch (Exception $e) {

				echo "Ocurrió un error con la base de datos: " . $e->getMessage();
			}
			return $conectar;
		
	    }

        public static function ruta(){
			/* TODO: Ruta de acceso del Proyecto (Validar su puerto y nombre de carpeta por el suyo) */
            return "http://localhost:90/sistema_ape/";
			// return "http://localhost/MSPBS_SISTEMA/sistema_ape/";
			// return "http://159.65.242.229/sistema_ape/";
			// return "https://www.ape.org.py/sistema_ape/";
        }
    }
?>