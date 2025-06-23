<?php
    $ajaxRequest=true; 
    require_once "../Core/generalConfig.php";
	require_once "../Helpers/Helpers.php";
    require_once "../Controllers/pagoControllers.php";
    $insPago = new pagoControllers(); 
    switch ($_GET["op"]){
        case 'saveandedit':
            if (empty($_POST['pago_id'])) {
                echo $insPago->add_pago_controller(); 
            }else{
                echo $insPago->update_pago_controller(); 
            }
        break;
        case 'list':
            echo $insPago->list_pago_controller();     
        break;
        case 'delete':
            echo $insPago->delete_pago_controller();     
        break;
        case 'show':
            echo $insPago->show_pago_controller();     
        break;
    }  