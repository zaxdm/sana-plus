<?php
    if($ajaxRequest){
        require_once "../Core/mainModel.php";
    }else{
        require_once "./Core/mainModel.php";
    }

    class ventasModels extends mainModel{

        protected function add_model($data){
            $sql=mainModel::connect()->prepare("INSERT INTO venta(venta_codigo,venta_id_comprobante, venta_serie,venta_numComprobante,venta_fecha,venta_impuesto,venta_total, venta_id_usuario,venta_id_cliente,venta_estado) VALUES (:Codigo,:TipoC,:SerieC,:NumC,:Fecha,:Impuesto,:Total,:Usuario,:Cliente,:Estado)");
            $sql->bindParam(":Codigo",$data['Codigo']);
            $sql->bindParam(":TipoC",$data['TipoC']);
            $sql->bindParam(":SerieC",$data['SerieC']);
            $sql->bindParam(":NumC",$data['NumC']);
            $sql->bindParam(":Fecha",$data['Fecha']);
            $sql->bindParam(":Impuesto",$data['Impuesto']);
            $sql->bindParam(":Total",$data['Total']);
            $sql->bindParam(":Usuario",$data['Usuario']);
            $sql->bindParam(":Cliente",$data['Cliente']);
            $sql->bindParam(":Estado",$data['Estado']);
            $sql->execute();
            return $sql;
        }
        protected function add_detail_model($cantidad,$precioC,$descuento,$venta,$producto){
            $sql=mainModel::connect()->prepare("INSERT INTO detalle_venta(detalleVenta_cantidad, detalleVenta_precioV,detalleVenta_descuento,detalleVenta_id_venta,detalleVenta_id_producto) VALUES (:Cantidad,:PrecioV,:Descuento,:Venta,:Producto)");
            $sql->bindParam(":Cantidad",$cantidad);
            $sql->bindParam(":PrecioV",$precioC);
            $sql->bindParam(":Descuento",$descuento);
            $sql->bindParam(":Venta",$venta);
            $sql->bindParam(":Producto",$producto);
            $sql->execute();
            return $sql; 
        }
        protected function consult_stock_model($codigo){
            $sql=mainModel::connect()->prepare("SELECT lote_cantUnitario FROM lote WHERE lote_id_producto=:Codigo");
            $sql->bindParam(":Codigo",$codigo);
            $sql->execute();
            return $sql;
        }
        protected function subtract_stock_model($codigo,$cantidad){
            $sql=mainModel::connect()->prepare("UPDATE producto SET lote_cantUnitario=:Cantidad WHERE lote_id_producto=:Codigo");
            $sql->bindParam(":Cantidad",$cantidad);
            $sql->bindParam(":Codigo",$codigo);
            $sql->execute();
            return $sql;
        }
        protected function list_model(){ 
            $sql=mainModel::connect()->prepare("SELECT venta_id,comprobante_nombre,venta_serie,venta_numComprobante,venta_fecha,venta_impuesto,venta_total,cliente_nombre,usuario_nombre,venta_estado FROM venta
            join comprobante on venta_id_comprobante = comprobante_id
            join cliente on venta_id_cliente = cliente_id
            join usuario on venta_id_usuario = usuario_id
            ORDER BY venta_id DESC ");
            $sql->execute();
            return $sql;
        }
        protected function list_prod_model(){ 
            $sql=mainModel::connect()->prepare("SELECT p.prod_id,p.prod_imagen,p.prod_adicional,p.prod_nombre,p.prod_concentracion, p.prod_precioV,l.lab_nombre,tp.tipo_nombre,pre.present_nombre 
            FROM producto p 
            INNER JOIN laboratorio l ON p.prod_id_lab = l.lab_id 
            INNER JOIN tipo_producto tp ON p.prod_id_tipo = tp.tipo_id 
            INNER JOIN presentacion pre ON p.prod_id_present = pre.present_id 
            ORDER BY p.prod_id DESC");
            $sql->execute();
            return $sql;
        }
        protected function cancel_sale_model($code){ 
            $sql=mainModel::connect()->prepare("UPDATE venta SET venta_estado='0' WHERE venta_id=:Code");
            $sql->bindParam("Code",$code);
            $sql->execute();
            return $sql;
        }
        protected function show_model($code){ 
            $sql=mainModel::connect()->prepare("SELECT venta_id,comprobante_nombre,venta_serie,venta_numComprobante,date(venta_fecha)as venta_fecha,venta_impuesto,venta_total,cliente_nombre FROM venta
            join comprobante on venta_id_comprobante = comprobante_id
            join cliente on venta_id_cliente = cliente_id 
            WHERE venta_id=:Code");
            $sql->bindParam("Code",$code);
            $sql->execute();
            return $sql;
        }
        protected function show_detail_model($code){ 
            $sql=mainModel::connect()->prepare("SELECT detalleVenta_id,detalleVenta_cantidad,detalleVenta_precioV,detalleVenta_descuento,prod_nombre,prod_concentracion,prod_adicional FROM detalle_venta 
            join producto on detalleVenta_id_producto = prod_id 
            WHERE detalleVenta_id_venta=:Code");
            $sql->bindParam("Code",$code);
            $sql->execute();
            return $sql;
        }
        protected function barcode_prod_model($barcode){
            $sql=mainModel::connect()->prepare("SELECT * FROM producto WHERE prod_codigo=:Barcode");
            $sql->bindParam("Barcode",$barcode);
            $sql->execute();
            return $sql;
        }
        protected function select_cliente_model(){
            $query=mainModel::connect()->prepare("SELECT * FROM cliente");
            $query->execute();
            return $query; 
        }
        protected function select_comprobante_model(){
            $query=mainModel::connect()->prepare("SELECT * FROM comprobante WHERE comprobante_estado='1'");
            $query->execute();
            return $query; 
        }
        protected function select_pago_model(){
            $query=mainModel::connect()->prepare("SELECT * FROM pago");
            $query->execute();
            return $query; 
        }
        protected function get_stock_model($code){
            $sql=mainModel::connect()->prepare("SELECT SUM(lote_cantUnitario) as lote_stock FROM lote WHERE lote_id_producto=:Code");
            $sql->bindParam("Code",$code);
            $sql->execute();
            return $sql; 
        }
        protected function update_lote($loteCant, $id){
            $sql = mainModel::connect()->prepare("UPDATE lote SET lote_cantUnitario=:LoteCant  WHERE lote_id=:Id");
            $sql->bindParam("LoteCant",$loteCant);
            $sql->bindParam("Id",$id);
            $sql->execute();
            return $sql; 

        }
        /* ===========REPORTES========= */ 
        public function ventacabecera($code){ 
            $sql=mainModel::connect()->prepare("SELECT venta_id,comprobante_nombre,venta_serie,venta_numComprobante,venta_fecha,venta_impuesto,venta_total,venta_estado,usuario_nombre,cliente_nombre,cliente_dni,cliente_celular,cliente_direccion,cliente_correo FROM venta
            join comprobante on venta_id_comprobante = comprobante_id
            join cliente on venta_id_cliente = cliente_id 
            join usuario on venta_id_usuario = usuario_id
            WHERE venta_id=:Code");
            $sql->bindParam("Code",$code);
            $sql->execute();
            return $sql;
        }
        public function ventadetalles($code){ 
            $sql=mainModel::connect()->prepare("SELECT detalleVenta_id,detalleVenta_cantidad,detalleVenta_precioV,detalleVenta_descuento,prod_codigoin,prod_nombre,prod_concentracion,prod_adicional,(detalleVenta_cantidad*detalleVenta_precioV-detalleVenta_descuento) AS subtotal FROM detalle_venta 
            join producto on detalleVenta_id_producto = prod_id 
            WHERE detalleVenta_id_venta=:Code");
            $sql->bindParam("Code",$code);
            $sql->execute();
            return $sql;
        }
    }