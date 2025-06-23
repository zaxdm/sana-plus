<?php
    if($ajaxRequest){
        require_once "../Models/inicioModels.php";
    }else{
        require_once "./Models/inicioModels.php";
    }
 
    class inicioControllers extends inicioModels{

        public function count_items_controller(){
            session_start(['name'=>'STR']);
            $ventas=inicioModels::count_ventas_model();
            $v= $ventas->fetch();
            $count_ventas = $v['total'];

            $compras=inicioModels::count_compras_model();
            $c= $compras->fetch();
            $count_compras = $c['total'];

            $user=inicioModels::count_usuarios_model();
            $u= $user->fetch();
            $count_usuarios = $u['usuarios'];

            $productos=inicioModels::count_productos_model();
            $p= $productos->fetch();
            $count_productos = $p['productos'];
  
            echo 
            '
            <div class="col-12 col-sm-12 col-md-3">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><sup style="font-size: 13px">'.$_SESSION['simbolo_str'].' </sup>
                        '.formatMoney($count_ventas).'</h3>
                        <p>Ventas</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-social-usd"></i>
                    </div>
                    <a href="#" class="small-box-footer">Ver todo <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><sup style="font-size: 13px">'.$_SESSION['simbolo_str'].' </sup>
                        '.formatMoney($count_compras).'</h3>
                        <p>Compras</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-cart"></i>
                    </div>
                    <a href="#" class="small-box-footer">Ver todo <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-3">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>'.$count_usuarios.'</h3>
                        <p>Usuarios</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="#" class="small-box-footer">Ver todo <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-3">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>'.$count_productos.'</h3>
                        <p>Productos</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-clipboard"></i>
                    </div>
                    <a href="#" class="small-box-footer">Ver todo <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            ';
        }
        public function sales_statistics_controller(){
            session_start(['name'=>'STR']);
            error_reporting(0);
            $statistics = inicioModels::sales_statistics_model();
            $respuesta = $statistics->fetchAll();

            $arrayFechas = array(); 
            $arrayVentas = array();
            $sumaPagosMes = array();
            
            foreach ($respuesta as $key => $value) {
                $fecha = substr($value["venta_fecha"],0,7);
                array_push($arrayFechas, $fecha);
                $arrayVentas = array($fecha => $value["venta_total"]);
                foreach ($arrayVentas as $key => $value) {
                    $sumaPagosMes[$key] += $value;
                }
            }
            $noRepetirFechas = array_unique($arrayFechas);
            echo 
            ' 
            <section class="col-lg-12 connectedSortable ui-sortable">
                <div class="card bg-white">
                <div class="card-header  ui-sortable-handle" style="cursor: move;">
                    <h3 class="card-title text-secundary">
                        <i class="fas fa-chart-bar mr-1"></i>Gráfico de ventas
                    </h3>
                </div>
                <div class="card-body">
                    <div class="chart" id="line-chart-ventas" style="width:100%;height:250px;"></div>
                </div>
                </div>
            </section>
            <script>
                var line = new Morris.Line({
                    element          : "line-chart-ventas",
                    resize           : true,
                    data             : [
            ';
            if($noRepetirFechas != null){

                foreach($noRepetirFechas as $key){
        
                    echo "{ y: '".$key."', ventas: ".$sumaPagosMes[$key]." },";
        
        
                }
        
                echo "{y: '".$key."', ventas: ".$sumaPagosMes[$key]." }";
        
            }else{
        
               echo "{ y: '0', ventas: '0' }";
        
            }
         echo '
                    ],
                    xkey             : "y",
                    ykeys            : ["ventas"],
                    labels           : ["ventas"],
                    lineColors       : ["#25D5F2"],
                    lineWidth        : 2,
                    hideHover        : "auto",
                    gridTextColor    : "#537375",
                    gridStrokeWidth  : 0.4,
                    pointSize        : 4,
                    pointStrokeColors: ["#efefef"],
                    gridLineColor    : "#AAAAAA",
                    gridTextFamily   : "Open Sans",
                    preUnits         : "'.$_SESSION['simbolo_str'].'",
                    gridTextSize     : 10
                });
            </script>
            ';
        }
        public function recently_product_controller(){
            session_start(['name'=>'STR']);
            $query=inicioModels::recently_product_model();
            $data=Array();
            while ($reg=$query->fetch()) {
 
                $data[]=array(
                    "codigo"=>$reg['prod_codigoin'],
                    "producto"=>$reg['prod_nombre'],
                    "descripcion"=>$reg['prod_concentracion'].' '.$reg['prod_adicional'],
                    "precio"=>$_SESSION['simbolo_str'].formatMoney($reg['prod_precioV']),
                    "presentacion"=>$reg['present_nombre'],
                    "imagen"=>$reg['prod_imagen']
  
                );
            }
            $jasonstring = json_encode($data);
            echo $jasonstring;
        }  
        public function purchase_statistics_controller(){
            session_start(['name'=>'STR']);
            $compras = inicioModels::purchase_statistics_model();
 
            $fechasc='';
            $totalesc='';
            while ($regfechac=$compras->fetch()) {
              $fechasc=$fechasc.'"'.$regfechac['fecha'].'",';
              $totalesc=$totalesc.$regfechac['total'].',';
            }
          
          
            //quitamos la ultima coma
            $fechasc=substr($fechasc, 0, -1);
            $totalesc=substr($totalesc, 0,-1);
            echo '
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fa fa-chart-bar mr-1"></i>Gráfico de compras</h3>
              </div>
              <div class="card-body">
                    <canvas id="compras" width="400" height="320"></canvas>
              </div>
            </div>
            <script>
            var ctx = document.getElementById("compras").getContext("2d");
            var compras = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: ['.$fechasc.'],
                    datasets: [{
                        label: "#Compras los ultimos 12 meses '.$_SESSION['simbolo_str'].'",
                        data: ['.$totalesc.'],
                        backgroundColor: [
                            "#FFA500",
                            "#25D5F2",
                            "#5969FF",
                            "#FF2424",
                            "#FF407B",
                            "#28A745",
                            "#9440ED",
                            "#B2BFCD",
                            "#9CCC66",
                            "#0DC3AA"
                        ],
                        borderColor: [
                            "#FFA500",
                            "#25D5F2",
                            "#5969FF",
                            "#FF2424",
                            "#FF407B",
                            "#28A745",
                            "#9440ED",
                            "#B2BFCD",
                            "#9CCC66",
                            "#0DC3AA"
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                }
            });
            </script>
            ';
        }
         
    } 