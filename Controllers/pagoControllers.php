<?php
    if($ajaxRequest){
        require_once "../Models/pagoModels.php";
    }else{
        require_once "./Models/pagoModels.php";
    }
 
    class pagoControllers extends pagoModels{

        public function add_pago_controller(){
            $nombre=mainModel::clean_chain($_POST['nombre']); 
            $descripcion=mainModel::clean_chain($_POST['descripcion']); 
            $query1=mainModel::run_simple_query("SELECT pago_nombre FROM pago WHERE pago_nombre='$nombre'");
            if ($query1->rowCount()>=1) {
                $alert=[
                    "Alert"=>"simple",
                    "title"=>"Ocurrió un error inesperado",
                    "text"=>"¡Error! Nombre de pago ya existente",
                    "icon"=>"error"
                ];
            }else{
              
                $dataPago=[
                    "Nombre"=>$nombre,
                    "Descripcion"=>$descripcion,
                    "Estado"=>"1"
                ];

                $saveVoucher=pagoModels::add_pago_model($dataPago);
                    if ($saveVoucher->rowCount()>=1) {
                            $alert=[
                                "Alert"=>"clean",
                                "title"=>"Operacion exitosa",
                                "text"=>"Pago registrado exitosamente",
                                "icon"=>"success"
                            ];
                    } else {
                            $alert=[
                                "Alert"=>"simple",
                                "title"=>"Ocurrió un error inesperado",
                                "text"=>"¡Error! No hemos podido registrar pago, en este momento",
                                "icon"=>"error"
                            ];
                    }
            }
            return mainModel::sweet_alert($alert);
        }
        public function update_pago_controller(){

            $pago_id=mainModel::clean_chain($_POST['pago_id']);
            $nombre=mainModel::clean_chain($_POST['nombre']); 
            $descripcion=mainModel::clean_chain($_POST['descripcion']); 

            $query1=mainModel::run_simple_query("SELECT pago_nombre FROM pago WHERE pago_nombre='$nombre'");
            if ($query1->rowCount()>=1) {
                $alert=[
                    "Alert"=>"simple",
                    "title"=>"Ocurrió un error inesperado",
                    "text"=>"¡Error! Nombre de pago ya existente",
                    "icon"=>"error"
                ];
                return mainModel::sweet_alert($alert);
                exit();
            }else{
                $dataPago=[
                    "Nombre"=>$nombre,
                    "Descripcion"=>$descripcion,
                    "Codigo"=>$pago_id
                ];
                if (pagoModels::update_pago_model($dataPago)) {
                    $alert=[
                        "Alert"=>"clean",
                        "title"=>"Operacion exitosa",
                        "text"=>"Datos actualizados exitosamente",
                        "icon"=>"success"
                    ];
                } else {
                    $alert=[
                        "Alert"=>"simple",
                        "title"=>"Ocurrió un error inesperado",
                        "text"=>"¡Error! No hemos podido actualizar pago, en este momento",
                        "icon"=>"error"
                    ];
                }
            }
       
            return mainModel::sweet_alert($alert);
        }
        public function list_pago_controller(){
            $query = pagoModels::list_pago_model();
            $data=Array();
            while ($reg=$query->fetch()) {
                $data[]=array(
                    "0"=>                  
                    '<div class="btn-group"><button title="Actualizar" class="btn btn-primary btn-sm edit" id="'.mainModel::encryption($reg['pago_id']).'">
                    <i class="fa fa-edit fa-xs"></i>
                    </button>'.' '.' 
                    <button type="button" title="Eliminar" class="btn btn-danger btn-sm delete" id="'.mainModel::encryption($reg['pago_id']).'">
                        <i class="fa fa-trash fa-xs"></i>
                    </button></div>',
                    "1"=>$reg['pago_nombre'],
                    "2"=>$reg['pago_descripcion'],
                    "3"=>($reg['pago_estado'])?'<span class="label label-success label-style">Activado</span>':'<span class="label label-danger label-style">Desactivado</span>'
                );
            }
            $results=array(
                     "sEcho"=>1,
                     "iTotalRecords"=>count($data),
                     "iTotalDisplayRecords"=>count($data),
                     "aaData"=>$data);
            echo json_encode($results);
        }  
        public function delete_pago_controller(){
            $pago_id=mainModel::decryption($_POST['pago_id']);
            $pago_id=mainModel::clean_chain($pago_id);
           
            $query=mainModel::run_simple_query("SELECT venta_id_pago FROM venta WHERE venta_id_pago='$pago_id'");
                if ($query->rowCount()==1){             
                    $alert=[
                        "Alert"=>"simple",
                        "title"=>"Ocurrió un error inesperado",
                        "text"=>"¡Error! Pago asociado a una venta",
                        "icon"=>"error"
                    ];
                } else {
                
                    $pago=pagoModels::delete_pago_model($pago_id);                 
                    if ($pago->rowCount()>=1) {                 
                        $alert=[
                                "Alert"=>"clean",
                                "title"=>"Operacion exitosa",
                                "text"=>"Pago eliminado exitosamente",
                                "icon"=>"success"
                        ];
                    } else {
                        $alert=[
                            "Alert"=>"simple",
                            "title"=>"Ocurrió un error inesperado",
                            "text"=>"¡Error! No hemos podido eliminar pago, en este momento",
                            "icon"=>"error"
                        ];
                    }
                }
     
            return mainModel::sweet_alert($alert);
        }
        public function show_pago_controller(){
            $pago_id=mainModel::decryption($_POST['pago_id']);
            $pago_id=mainModel::clean_chain($pago_id);
            $query=pagoModels::show_pago_model($pago_id);
            $rspta= $query->fetch();
            echo json_encode($rspta); 
        }     
    }