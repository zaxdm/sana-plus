<?php
    if($ajaxRequest){
        require_once "../Core/mainModel.php";
    }else{
        require_once "./Core/mainModel.php";
    }

    class reporteVentasModels extends mainModel{

        protected function count_day_model(){
            $sql=mainModel::connect()->prepare("SELECT COUNT(venta_id) as total FROM venta WHERE DATE(venta_fecha)=curdate()");
            $sql->execute();
            return $sql; 
        }
        protected function count_month_model(){
            $query=mainModel::connect()->prepare("SELECT COUNT(venta_id) as total FROM venta where MONTH(venta_fecha) = MONTH (NOW())");
            $query->execute();
            return $query;
        }
        protected function count_year_model(){ 
            $sql=mainModel::connect()->prepare("SELECT COUNT(venta_id) as total FROM venta where YEAR(venta_fecha) = YEAR (NOW())");
            $sql->execute();
            return $sql;
        }
        protected function count_productos_model(){
            $sql=mainModel::connect()->prepare("SELECT COUNT(prod_id) as productos FROM producto");
            $sql->execute();
            return $sql;
        }
        protected function sales_statistics_model(){ 
            $sql=mainModel::connect()->prepare("SELECT * FROM venta WHERE venta_estado='1'and year(venta_fecha) = year(now()) and year(venta_fecha) = year(now())  ORDER BY venta_id ASC");
            $sql->execute();
            return $sql;
        }
        protected function show_sale_model(){ 
            $sql=mainModel::connect()->prepare("SELECT * FROM venta WHERE venta_estado='1'and year(venta_fecha) = year(now()) and year(venta_fecha) = year(now())  ORDER BY venta_id  ASC");
            $sql->execute();
            return $sql;
        }  
        protected function show_users_model(){ 
            $sql=mainModel::connect()->prepare("SELECT * FROM usuario ");
            $sql->execute();
            return $sql;
        }  
        protected function sales_seller_model(){ 
            $sql=mainModel::connect()->prepare("SELECT usuario_perfil,usuario_nombre,usuario_cargo,COUNT(venta_id) as ventas,SUM(venta_total)as ganancias FROM venta
            join usuario on venta_id_usuario = usuario_id WHERE date(venta_fecha)=curdate() group by usuario_nombre");
            $sql->execute();
            return $sql;
        } 

    }