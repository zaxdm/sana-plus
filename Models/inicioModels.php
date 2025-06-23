<?php
    if($ajaxRequest){
        require_once "../Core/mainModel.php";
    }else{
        require_once "./Core/mainModel.php";
    }

    class inicioModels extends mainModel{

        protected function count_ventas_model(){
            $sql=mainModel::connect()->prepare("SELECT IFNULL(SUM(venta_total),0) as total FROM venta WHERE DATE(venta_fecha)=curdate()");
            $sql->execute();
            return $sql; 
        }
        protected function count_compras_model(){
            $query=mainModel::connect()->prepare("SELECT IFNULL(SUM(compra_total),0) as total FROM compra WHERE DATE(compra_fecha)=curdate() AND compra_estado=1");
            $query->execute();
            return $query;
        }
        protected function count_usuarios_model(){ 
            $sql=mainModel::connect()->prepare("SELECT COUNT(usuario_id) as usuarios FROM usuario");
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
        protected function recently_product_model(){ 
            $sql=mainModel::connect()->prepare("SELECT * FROM producto join presentacion on prod_id_present = present_id  ORDER BY prod_id  DESC LIMIT 6");
            $sql->execute();
            return $sql;
        } 
        protected function purchase_statistics_model(){ 
            $sql=mainModel::connect()->prepare("SELECT DATE_FORMAT(compra_fecha,'%M') AS fecha, SUM(compra_total) AS total FROM compra where year(compra_fecha) = year(now()) and year(compra_fecha) = year(now()) GROUP BY MONTH(compra_fecha)  ORDER BY compra_fecha DESC LIMIT 0,12");
            $sql->execute();
            return $sql;
        }  

    }
