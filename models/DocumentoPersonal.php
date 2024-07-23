<?php
    class DocumentoPersonal extends Conectar{

        /* TODO: insertar nuevo documento */
        public function insert_doc_personal($usuario_id,$tipo_documento,$documento,$fecha,$dato_adic){
            $conectar= parent::conexion();
            $sql="INSERT INTO datos_personales (usuario_id,tipo_doc_id,pdf,fecha,dato_adic,user_crea) 
            VALUES ($usuario_id,$tipo_documento,'$documento','$fecha','$dato_adic','$usuario_id');";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            error_log("Docs personales ->  ".$sql);
            $sql1="SELECT datos_personales.id as dato_personal_id, usuarios.ci as cedula, tipo_doc_id
            FROM datos_personales
            INNER JOIN usuarios on usuarios.id = datos_personales.usuario_id
            ORDER BY datos_personales.fecha_mod DESC LIMIT 1;";
            $sql1=$conectar->prepare($sql1);
            $sql1->execute();
            return $resultado=$sql1->fetchAll(pdo::FETCH_ASSOC);
        }

        /* TODO: Listar documentos personales segun id de usuario */
        public function listar_dato_personal_x_usu($usuario_id){
            $conectar= parent::conexion();
            $sql="SELECT 
                datos_personales.id as dato_personal_id, usuario_id,tipos_documentos.documento as tipo_documento,
                pdf as documento,fecha,dato_adic
                FROM 
                datos_personales
                INNER join tipos_documentos on tipos_documentos.id = datos_personales.tipo_doc_id
                WHERE
                datos_personales.activo = true
                AND tipos_documentos.activo = true
                AND tipos_documentos.tipo = 'P'
                AND datos_personales.usuario_id=$usuario_id
                order by datos_personales.id desc";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO: Filtro Avanzado de documentos personales */
        public function filtrar_doc_personal($tipo_documento,$fecha){
            $conectar= parent::conexion();
            $condicionTipoDoc = "";
            $condicionFecha = "";
            $and = "";
            if($tipo_documento > 0){
                $condicionTipoDoc = "tipo_doc_id = $tipo_documento ";
            } 
            if($fecha != ""){
                $condicionFecha= "fecha::date = '$fecha'";
            }
            if($condicionFecha != "" && $condicionTipoDoc != ""){
                $and = " AND ";
            }
            $sql="SELECT tipo_doc_id as tipo_documento, datos_personales.fecha, 
            datos_personales.id as dato_personal_id, 
            tipos_documentos.documento as tipo_documento,
            datos_personales.pdf as documento
            FROM datos_personales 
            INNER JOIN tipos_documentos on datos_personales.tipo_doc_id = tipos_documentos.id
            WHERE datos_personales.activo = true AND ".$condicionTipoDoc.$and.$condicionFecha;
            $sql=$conectar->prepare($sql);
            $sql->execute();
            $conectar = null;

            return $resultado=$sql->fetchAll();
        }

        /* TODO: Mostrar documento personal segun id del documento */
        public function mostrar($doc_personal_id){
            $conectar= parent::conexion();
            $sql="SELECT 
            datos_personales.id as dato_personal_id, usuario_id,tipos_documentos.id as tipo_documento,
            pdf as documento,fecha,dato_adic
            FROM 
            datos_personales
            INNER join tipos_documentos on tipos_documentos.id = datos_personales.tipo_doc_id
            WHERE
            datos_personales.id = $doc_personal_id";

            $sql=$conectar->prepare($sql);
            $sql->execute();
            $conectar = null;
            return $resultado=$sql->fetchAll();
        }

        /* TODO: actualizar documento */
        public function update_doc_personal($doc_personal_id, $usuario_id, $tipo_documento, $documento, $fecha, $dato_adic){
            $conectar= parent::conexion();
            $pdf = "";
            if($documento != ""){
                $pdf = "pdf = '$documento',";
            }
            $sql="update datos_personales 
                set	
                    tipo_doc_id = $tipo_documento,
                    $pdf
                    fecha = '$fecha',
                    dato_adic = '$dato_adic',
                    user_mod = '$usuario_id',
                    fecha_mod = current_timestamp
                where
                    id = $doc_personal_id";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            $sql1="SELECT datos_personales.id as dato_personal_id, usuarios.ci as cedula, tipo_doc_id
            FROM datos_personales
            INNER JOIN usuarios on usuarios.id = datos_personales.usuario_id
            ORDER BY datos_personales.fecha_mod DESC LIMIT 1;";
            $sql1=$conectar->prepare($sql1);
            $sql1->execute();
            $conectar = null;
            return $resultado=$sql1->fetchAll(pdo::FETCH_ASSOC);
        }

        public function delete_doc_personal($doc_personal_id, $usuario_id){
            $conectar= parent::conexion();
            $sql="update datos_personales 
                set	
                    activo = false
                where
                    id = $doc_personal_id";
                $sql=$conectar->prepare($sql);
                
                return $sql->execute();
        }

        /* TODO:Total de documentos por categoria */
        public function get_documentos_grafico(){
            $conectar= parent::conexion();
            $sql="SELECT tipos_documentos.documento as nom,COUNT(*) AS total
                FROM   documentos_personales  JOIN  
                    tm_categoria ON documentos_personales.tipo_doc_id = tipos_documentos.id  
                WHERE    
                documentos_personales.activo = true
                GROUP BY 
                tipos_documentos.documento 
                ORDER BY total DESC";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }
    }
?>