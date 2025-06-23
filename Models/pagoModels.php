<?php
    if($ajaxRequest){
        require_once "../Core/mainModel.php";
    }else{
        require_once "./Core/mainModel.php";
    }

    class pagoModels extends mainModel{

        protected function add_pago_model($data){
            $sql=mainModel::connect()->prepare("INSERT INTO pago(pago_nombre,pago_descripcion,pago_estado) VALUES (:Nombre,:Descripcion,:Estado)");
            $sql->bindParam(":Nombre",$data['Nombre']);
            $sql->bindParam(":Descripcion",$data['Descripcion']);
            $sql->bindParam(":Estado",$data['Estado']);
            $sql->execute();
            return $sql; 
        }
        protected function update_pago_model($data){
            $sql=mainModel::connect()->prepare("UPDATE pago SET 
            pago_nombre=:Nombre,
            pago_descripcion=:Descripcion
            WHERE pago_id=:Codigo");
            $sql->bindParam(":Nombre",$data['Nombre']);
            $sql->bindParam(":Descripcion",$data['Descripcion']);
            $sql->bindParam(":Codigo",$data['Codigo']);
            $sql->execute();
            return $sql;
        }
        protected function list_pago_model(){ 
            $sql=mainModel::connect()->prepare("SELECT * FROM pago  ORDER BY pago_id DESC");
            $sql->execute();
            return $sql;
        }
        protected function delete_pago_model($code){
            $query=mainModel::connect()->prepare("DELETE FROM pago WHERE pago_id=:Code");
            $query->bindParam(":Code",$code);
            $query->execute();
            return $query;
        }
        protected function show_pago_model($code){ 
            $sql=mainModel::connect()->prepare("SELECT * FROM pago  WHERE pago_id=:Code");
            $sql->bindParam("Code",$code);
            $sql->execute();
            return $sql;
        }
    }
