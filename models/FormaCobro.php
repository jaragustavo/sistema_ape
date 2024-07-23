<?php
    class FormaCobro {

        /* TODO:Todos los registros */
        public static function get_tipo_cobro($forma_cobro){
          if($forma_cobro == 1){

            return 'Por transferencia';

          }elseif ($forma_cobro == 2) {

            return "Retirar en efectivo de la APE"; 
          }
           
        }
    }
?> 