var tabla;
function init()
{
	list();
}
function list(){
	tabla=$('#tablaRespuesta').dataTable({
		"aProcessing": true,
		"aServerSide": true,
		dom: 'Bfrtip',
		"ajax":
		{
			url: base_url+"/Ajax/ventasAjax.php?op=list",
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"paging": true,
        "lengthChange": false,
        "autoWidth": false,
        "responsive": true,
		"bDestroy":true,
        "ordering": false,
        "info": true,
		"iDisplayLength":6,
		"order":[[0,"desc"]],
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            },
            "buttons": {
                "copy": "Copiar",
                "colvis": "Visibilidad"
            }
        }
	}).DataTable();
}
/*=============================================================================
=============================================================================*/
$(document).on("click", ".anular", function () {
	var venta_id = $(this).attr("id");
	$.ajax({
	  url: base_url + "/Ajax/ventasAjax.php?op=anular",
	  method: "POST",
	  data: { venta_id: venta_id },
	  success: function (data) {
		$("#ajaxAnswer").html(data);
		list();
	  },
	});
});
/*=============================================================================
=============================================================================*/
$(document).on('click', '.edit', function(){
	var venta_id = $(this).attr("id");
	$.ajax({
		url: base_url + "/Ajax/ventasAjax.php?op=show",
		method:"POST",
		data:{venta_id:venta_id},
		dataType:"json",
		success:function(data)
		{
			console.log(data);
			$("#detalle_venta").modal('show');
			$("#cliente").val(data.cliente_nombre);
			$("#tipo_comprobantem").val(data.comprobante_nombre);
			$("#serie_comprobantem").val(data.venta_serie);
			$("#num_comprobantem").val(data.venta_numComprobante);
			$("#fecha_horam").val(data.venta_fecha);
			$("#impuestom").val(data.venta_impuesto);
			$("#idventam").val(data.venta_id);
	

		}
	});
	$.post(base_url + "/Ajax/ventasAjax.php?op=showDetail&id="+venta_id,function(r){
		$("#detallesm").html(r);
	});
});
/*=============================================================================
=============================================================================*/
init(); 