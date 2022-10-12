<?php 
    //si tienes dudas de por que dos foreach, revisa 
    //comom esta configurado $alertas en el ActiveRecord
    foreach ($alertas as $key => $mensajes):
        foreach ($mensajes as $mensaje):
?>

        <div class="alerta <?php echo $key; ?>">
        <?php echo $mensaje; ?></div>

<?php
        endforeach;

    endforeach;

?>