<?php if ($tipo_solicitud == 'ADMIN') {

    require_once "../../models/Movimiento.php";
    
    // Decodificar json_gestiones para obtener los valores booleanos
    $jsonGestiones = json_decode( Movimiento::requisitos_tramite($decifrado)["tramite_json_requisito"], true);

    // Decodificar el JSONB
    $jsonRequisito = json_decode($tramite_json_requisito, true);
?>
   <div class="row m-t-lg">
        <div class="col-md-12 col-sm-12">
            <?php foreach ($jsonRequisito as $key => $label) {
                // Determinar la clase CSS para el checkbox 
                $isChecked = isset($jsonGestiones[$key]) && $jsonGestiones[$key] === true;
           //     $class = $isChecked ? '' : '';
            ?>
                <div class="checkbox-bird green <?php //echo $class; ?>">
                    <input type="checkbox" id="<?php echo $key; ?>" <?php echo $isChecked ? 'checked' : ''; ?>>
                    <label for="<?php echo $key; ?>"><?php echo htmlspecialchars($label); ?></label>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>