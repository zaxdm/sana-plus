<?php
    if($ajaxRequest){
        require_once "../Models/loginModels.php";
    }else{
        require_once "./Models/loginModels.php";
    }

    class loginControllers extends loginModels{

        public function login_controller(){
            
            $username=mainModel::clean_chain($_POST['usuario']);
            $password=mainModel::clean_chain($_POST['clave']);

            $password=mainModel::encryption($password);

            $validation=mainModel::run_simple_query("SELECT usuario_login FROM usuario WHERE usuario_login='$username'");
            if($validation->rowCount()>=1){

                $validation2=mainModel::run_simple_query("SELECT usuario_contrasena FROM usuario WHERE  	usuario_contrasena='$password'");
                if ($validation2->rowCount()>=1){

                    $dataLogin=[
                        "Username"=>$username,
                        "Pass"=>$password
                    ];
        
                    $dataAccount=loginModels::login_model($dataLogin);
        
                    if($dataAccount->rowCount()==1){ 
        
                        $row=$dataAccount->fetch();
        
                        $current_date=date("Y-m-d");  //fecha actual
                        $current_year=date("Y"); //año actual
                        $current_time=date("h:i:s a"); //hora actual
        
                        $query1=mainModel::run_simple_query("SELECT bitacora_id FROM bitacora");
                        $number=($query1->rowCount())+1;
        
                        $code_binnacle=mainModel::random_code("BIT-",6,$number);
        
                        $dataBinnacle=[
                            "Code"=>$code_binnacle,
                            "Date"=>$current_date,
                            "StartTime"=>$current_time,
                            "EndTime"=>"sin registro",
                            "Type"=>$row['usuario_cargo'],
                            "Year"=>$current_year,
                            "AccountCode"=>$row['usuario_id']
                        ];
        
                        $addBinnacle=mainModel::save_binnacle($dataBinnacle);
                        if ( $addBinnacle->rowCount()>=1) {
                            $query3=mainModel::run_simple_query("SELECT *FROM empresa");
                            $company=$query3->fetch();

                                //session_start(['name'=>'STR']);
                                
                                $_SESSION['name_str']=$row['usuario_nombre'];
                                $_SESSION['lastname_str']=$row['usuario_apellido'];
                                $_SESSION['dni_str']=$row['usuario_dni'];
                                $_SESSION['mobile_str']=$row['usuario_celular'];
                                
                                $_SESSION['username_str']=$row['usuario_login'];
                                $_SESSION['type_str']=$row['usuario_cargo'];
                                $_SESSION['gender_str']=$row['usuario_genero'];
                                $_SESSION['profession_str']=$row['usuario_profesion'];
                                $_SESSION['birthdate_str']=$row['usuario_fechanacimiento'];
                                $_SESSION['description_str']=$row['usuario_descripcion'];
                                $_SESSION['image_str']=$row['usuario_perfil'];
                                $_SESSION['token_str']=md5(uniqid(mt_rand(), true));
                                $_SESSION['code_user_str']=$row['usuario_codigo'];
                                $_SESSION['id_user_str']=$row['usuario_id'];
        
                                $_SESSION['code_binnacle_str']=$code_binnacle;

                                $_SESSION['company_str']=$company['empresa_nombre'];
                                $_SESSION['logotype_str']=$company['empresa_logo'];
                                $_SESSION['simbolo_str']=$company['empresa_simbolo'];
        
                                if($row['usuario_cargo']=="Administrador"){
                                    $url=base_url()."/dashboard/";
                                }else{
                                    $url=base_url()."/product_catalog/";
                                }
                                
                                return $urlLocation='<script>window.location="'.$url.'"</script>';
                  
                       
                        }else{
        
                            $alert=[
                                "Alert"=>"simple",
                                "title"=>"Ocurrió un error inesperado",
                                "text"=>"Usuario / Contraseña , Son incorrectas",
                                "icon"=>"error"
                            ];
                            return mainModel::sweet_alert($alert);
                        }
                        
                    }else{
                        $alert=[
                            "Alert"=>"simple",
                            "title"=>"Ocurrió un error inesperado",
                            "text"=>"Usuario / Contraseña , Son incorrectas",
                            "icon"=>"error"
                        ];
                        return mainModel::sweet_alert($alert);
                    }

                }else{
                    $alert=[
                        "Alert"=>"simple",
                        "title"=>"Ocurrió un error inesperado",
                        "text"=>"Usuario / Contraseña , Son incorrectas",
                        "icon"=>"error"
                    ];
                }
               
            }else{
                $alert=[
                    "Alert"=>"simple",
                    "title"=>"Ocurrió un error inesperado",
                    "text"=>"Usuario / Contraseña , Son incorrectas",
                    "icon"=>"error"
                ];
            }
            return mainModel::sweet_alert($alert);
        }
        public function logout_controller(){

            session_start(['name'=>'STR']);
            $token=mainModel::decryption($_GET['Token']);
            $End_Time=date("h:i:s a");
            $data=[
                "Username"=>$_SESSION['username_str'],
                "Token_User"=>$_SESSION['token_str'],
                "Token"=>$token,
                "Code_binnacle"=>$_SESSION['code_binnacle_str'],
                "End_Time"=>$End_Time
            ];

            return loginModels::logout_model($data);
        }
        public function force_logoff_controller(){
            session_unset();
            session_destroy();

            $redirect='<script> window.location.href="'.base_url().'/login/"</script>';
            return $redirect;
        }
        public function redirect_user_controller($type){
            if($type=="Administrador"){
                $redirect='<script> window.location.href="'.base_url().'/dashboard/"</script>';
            }else{
                $redirect='<script> window.location.href="'.base_url().'/product_catalog/"</script>';
            }
            return $redirect;
        }
    } 