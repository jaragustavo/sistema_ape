<?php
    class DocumentoAcademico extends Conectar{

        /* TODO: insertar nuevo documento */
        public function insert_doc_academico($usuario_id,$tipo_documento,$documento,$institucion,$dato_adic){
            $conectar= parent::conexion();
            $sql="INSERT INTO documentos_academicos (usuario_id,tipo_doc_id,pdf,institucion_id,dato_adic,user_crea) 
            VALUES ($usuario_id,$tipo_documento,'$documento','$institucion','$dato_adic','$usuario_id');";
            $sql=$conectar->prepare($sql);
            $sql->execute();

            $sql1="SELECT documentos_academicos.id as doc_academico_id, usuarios.ci as cedula, tipo_doc_id
            FROM documentos_academicos
            INNER JOIN usuarios on usuarios.id = documentos_academicos.usuario_id
            ORDER BY documentos_academicos.fecha_crea DESC LIMIT 1;";
            $sql1=$conectar->prepare($sql1);
            $sql1->execute();
            return $resultado=$sql1->fetchAll(pdo::FETCH_ASSOC);
        }

        /* TODO: Listar documentos academicos segun id de usuario */
        public function listar_doc_academico_x_usu($usuario_id){
            $conectar= parent::conexion();
            $sql="SELECT 
                documentos_academicos.id as doc_academico_id, usuario_id,tipos_documentos.documento as tipo_documento,
                pdf as documento,instituciones_educativas.nombre_institucion as institucion,dato_adic
                FROM 
                documentos_academicos
                INNER join tipos_documentos on tipos_documentos.id = documentos_academicos.tipo_doc_id
                INNER join instituciones_educativas on instituciones_educativas.id = documentos_academicos.institucion_id
                WHERE
                documentos_academicos.activo = true
                AND tipos_documentos.activo = true
                AND tipos_documentos.tipo = 'A'
                AND documentos_academicos.usuario_id=$usuario_id
                order by documentos_academicos.id desc";
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
        }

        /* TODO: Filtro Avanzado de documentos academicos */
        public function filtrar_doc_academico($tipo_documento,$institucion){
            $conectar= parent::conexion();
            $condicionTipoDoc = "";
            $condicionInstitucion = "";
            $and = "";
            if($tipo_documento > 0){
                $condicionTipoDoc = "tipo_doc_id = $tipo_documento ";
            } 
            if($institucion > 0){
                $condicionInstitucion= "institucion_id = $institucion";
            }
            if($condicionInstitucion != "" && $condicionTipoDoc != ""){
                $and = " AND ";
            }
            $sql="SELECT tipo_doc_id as tipo_documento, 
            instituciones_educativas.nombre_institucion as institucion, 
            documentos_academicos.id as doc_academico_id, 
            tipos_documentos.documento as tipo_documento,
            documentos_academicos.pdf as documento
            FROM documentos_academicos 
            INNER JOIN tipos_documentos on documentos_academicos.tipo_doc_id = tipos_documentos.id
            INNER join instituciones_educativas on instituciones_educativas.id = documentos_academicos.institucion_id
            WHERE documentos_academicos.activo = true
            -- AND docs_academicos.usuario_id = 
             AND ".$condicionTipoDoc.$and.$condicionInstitucion;
            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
            $conectar->close();
            $conectar = null;
        }

        /* TODO: Mostrar documento academico segun id del documento */
        public function mostrar($doc_academico_id){
            $conectar= parent::conexion();
            $sql="SELECT 
            documentos_academicos.id as doc_academico_id, usuario_id,tipos_documentos.id as tipo_documento,
            pdf as documento, instituciones_educativas.id as institucion_educativa,dato_adic
            FROM 
            documentos_academicos
            INNER join tipos_documentos on tipos_documentos.id = documentos_academicos.tipo_doc_id
            INNER join instituciones_educativas on instituciones_educativas.id = documentos_academicos.institucion_id
            WHERE
            documentos_academicos.id = $doc_academico_id";

            $sql=$conectar->prepare($sql);
            $sql->execute();
            return $resultado=$sql->fetchAll();
            $conectar->close();
            $conectar = null;
        }

        /* TODO: actualizar documento */
        public function update_doc_academico($doc_academico_id, $usuario_id, $tipo_documento, $documento, $institucion, $dato_adic){
            $conectar= parent::conexion();
            $pdf = "";
            if($documento != ""){
                $pdf = "pdf = '$documento',";
            }
            $sql="update documentos_academicos 
                set	
                    tipo_doc_id = $tipo_documento,
                    $pdf
                    institucion_id = '$institucion',
                    dato_adic = '$dato_adic',
                    user_mod = '$usuario_id',
                    fecha_mod = current_timestamp
                where
                    id = $doc_academico_id";
            error_log("$$$$$$$$$$  ".$sql);
            $sql=$conectar->prepare($sql);
            $sql->execute();
            $sql1="SELECT documentos_academicos.id as doc_academico_id, usuarios.ci as cedula, tipo_doc_id
            FROM documentos_academicos
            INNER JOIN usuarios on usuarios.id = documentos_academicos.usuario_id
            ORDER BY documentos_academicos.fecha_mod DESC LIMIT 1;";
            $sql1=$conectar->prepare($sql1);
            $sql1->execute();
            return $resultado=$sql1->fetchAll(pdo::FETCH_ASSOC);
            $conectar->close();
            $conectar = null;
        }

        public function delete_doc_academico($doc_academico_id, $usuario_id){
            $conectar= parent::conexion();
            $sql="update documentos_academicos 
                set	
                    activo = false
                where
                    id = $doc_academico_id";
                $sql=$conectar->prepare($sql);
                
                return $sql->execute();
        }

        public function get_instiuciones_educativas(){
            $conectar=parent::Conexion();
            $sql="select id as institucion_id, nombre_institucion from instituciones_educativas;";
            $query=$conectar->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>