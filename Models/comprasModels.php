<?php
    if($ajaxRequest){
        require_once "../Core/mainModel.php";
    }else{
        require_once "./Core/mainModel.php";
    }

    class comprasModels extends mainModel{
 
        protected function add_model($data){
            $sql=mainModel::connect()->prepare("INSERT INTO compra(compra_codigo,compra_tipoComprobante,compra_serie,compra_numComprobante,compra_fecha,compra_impuesto,compra_total,compra_id_proveedor,compra_id_usuario,compra_estado) VALUES (:Codigo,:TipoC,:SerieC,:NumC,:Fecha,:Impuesto,:Total,:Proveedor,:Usuario,:Estado)");
            $sql->bindParam(":Codigo",$data['Codigo']);
            $sql->bindParam(":TipoC",$data['TipoC']);
            $sql->bindParam(":SerieC",$data['SerieC']);
            $sql->bindParam(":NumC",$data['NumC']);
            $sql->bindParam(":Fecha",$data['Fecha']);
            $sql->bindParam(":Impuesto",$data['Impuesto']);
            $sql->bindParam(":Total",$data['Total']);
            $sql->bindParam(":Proveedor",$data['Proveedor']);
            $sql->bindParam(":Usuario",$data['Usuario']);
            $sql->bindParam(":Estado",$data['Estado']);
            $sql->execute();
            return $sql;
        }
        protected function add_detail_model($cantidad,$precioC,$compra,$producto){
            $sql=mainModel::connect()->prepare("INSERT INTO detalle_compra(detalleCompra_cantidad, detalleCompra_precioC,detalleCompra_id_compra,detalleCompra_id_producto) VALUES (:Cantidad,:PrecioC,:Compra,:Producto)");
            $sql->bindParam(":Cantidad",$cantidad);
            $sql->bindParam(":PrecioC",$precioC);
            $sql->bindParam(":Compra",$compra);
            $sql->bindParam(":Producto",$producto);
            $sql->execute();
            return $sql;
        }
        protected function add_lote_model($codigo,$cantidad,$fechaV,$producto,$proveedor,$compra){
            $sql=mainModel::connect()->prepare("INSERT INTO lote(lote_codigo,lote_cantUnitario,lote_fechaVencimiento, lote_id_producto,lote_id_proveedor,lote_id_compra) VALUES (:Codigo,:Quanty,:DateV,:Product,:Provider,:Compra)");
            $sql->bindParam(":Codigo",$codigo);
            $sql->bindParam(":Quanty",$cantidad);
            $sql->bindParam(":DateV",$fechaV);
            $sql->bindParam(":Product",$producto);
            $sql->bindParam(":Provider",$proveedor);
            $sql->bindParam(":Compra",$compra);
            $sql->execute();
            return $sql;
        }
        protected function list_model(){ 
            $sql=mainModel::connect()->prepare("SELECT c.compra_id,c.compra_fecha,c.compra_total,c.compra_estado,c.compra_tipoComprobante,c.compra_numComprobante,c.compra_serie,u.usuario_nombre,p.proved_nombre
            FROM compra c 
            INNER JOIN usuario u ON c.compra_id_usuario = u.usuario_id 
            INNER JOIN proveedor p ON c.compra_id_proveedor = p.proved_id 
            ORDER BY c.compra_id DESC ");
            $sql->execute();
            return $sql;
        } 
        protected function list_prod_model(){ 
            $sql=mainModel::connect()->prepare("SELECT p.prod_id,p.prod_imagen,p.prod_adicional,p.prod_nombre,p.prod_concentracion, p.prod_precioV,l.lab_nombre,tp.tipo_nombre,pre.present_nombre 
            FROM producto p 
            INNER JOIN laboratorio l ON p.prod_id_lab = l.lab_id 
            INNER JOIN tipo_producto tp ON p.prod_id_tipo = tp.tipo_id 
            INNER JOIN presentacion pre ON p.prod_id_present = pre.present_id 
            ORDER BY p.prod_id DESC ");
            $sql->execute();
            return $sql;
        }
        protected function cancel_purchase_model($code){ 
            $sql=mainModel::connect()->prepare("UPDATE compra SET compra_estado='0' WHERE compra_id=:Code");
            $sql->bindParam("Code",$code);
            $sql->execute();
            return $sql;
        }
        protected function show_model($code){ 
            $sql=mainModel::connect()->prepare("SELECT c.compra_id,c.compra_fecha,c.compra_total,c.compra_impuesto,c.compra_tipoComprobante,c.compra_serie,c.compra_numComprobante,p.proved_nombre,c.compra_id_proveedor
            FROM compra c 
            INNER JOIN proveedor p ON c.compra_id_proveedor = p.proved_id WHERE c.compra_id=:Code");
            $sql->bindParam("Code",$code);
            $sql->execute();
            return $sql;
        }
        protected function show_detail_model($code){ 
            $sql=mainModel::connect()->prepare("SELECT dc.detalleCompra_id_compra,dc.detalleCompra_id_producto,
            p.prod_nombre,p.prod_adicional,p.prod_concentracion,dc.detalleCompra_cantidad,dc.detalleCompra_precioC,dc.detalleCompra_precioV
            FROM detalle_compra dc
            INNER JOIN producto p ON dc.detalleCompra_id_producto = p.prod_id 
            WHERE dc.detalleCompra_id_compra=:Code");
            $sql->bindParam("Code",$code);
            $sql->execute();
            return $sql;
        }
        protected function select_provider_model(){
            $query=mainModel::connect()->prepare("SELECT * FROM proveedor");
            $query->execute();
            return $query; 
        }
        protected function get_stock_model($code){
            $sql=mainModel::connect()->prepare("SELECT SUM(lote_cantUnitario) as lote_stock FROM lote WHERE lote_id_producto=:Code");
            $sql->bindParam("Code",$code);
            $sql->execute();
            return $sql; 
        }
        protected function lote_producto_model($com,$pro){
            $sql=mainModel::connect()->prepare("SELECT lote_codigo,lote_fechaVencimiento FROM lote where lote_id_compra=:Com and lote_id_producto=:Pro");
            $sql->bindParam("Com",$com);
            $sql->bindParam("Pro",$pro);
            $sql->execute();
            return $sql; 
        }

    }