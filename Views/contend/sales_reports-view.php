<!-- Vista Reportes -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h6 class="header-title">Reportes de ventas</h6>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item breadcrumb-title"><a href="<?= base_url() ?>/dashboard/">Inicio</a></li>
          <li class="breadcrumb-item active breadcrumb-title">Reportes</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="container-fluid">
    <!-- Cantidades -->
    <div class="row" id="count"></div>
    <!-- Estadistica -->
    <div class="row" id="statistics"></div>
    <!-- Vendedores -->
    <div class="row">
      <div class="col-lg-6" id="vendedores"></div>
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-users mr-1"></i>Ventas por vendedor
            </h3>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover m-0">
                <thead>
                  <tr>
                    <th>Perfil</th>
                    <th>Vendedor</th>
                    <th>Cargo</th>
                    <th>Ventas</th>
                    <th>Ganancias</th>
                  </tr>
                </thead>
                <tbody id="sales_seller">
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section> 
<!-- /.Vista Reportes -->
<script src="<?= media(); ?>/funciones/reporte_ventas.js"></script>
