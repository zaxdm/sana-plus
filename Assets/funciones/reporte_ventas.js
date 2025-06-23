var tabla;
function init()
{
	sales_seller();
	cantidad_items();
	estadistica_ventas();
	estadistica_vendedores();

} 
/*=============================================================================
=============================================================================*/
function cantidad_items(){
	$.post(base_url+"/Ajax/reporteVentasAjax.php?op=count", function(r){
		$("#count").html(r);
	});
}
/*=============================================================================
=============================================================================*/
function estadistica_ventas(){
	$.post(base_url+"/Ajax/reporteVentasAjax.php?op=statistics", function(r){
		$("#statistics").html(r);
	});
}
/*=============================================================================
=============================================================================*/
function estadistica_vendedores(){
	$.post(base_url+"/Ajax/reporteVentasAjax.php?op=seller", function(r){
		$("#vendedores").html(r);
	});
}
/*=============================================================================
=============================================================================*/
function sales_seller(){
	$.post(base_url+'/Ajax/reporteVentasAjax.php?op=saleSeller',(response)=>{
		const sellers = JSON.parse(response);
		let template='';
		if(response ==='[]'){
			template+=`
			<tr>	
				<td class="text-center" colspan="5">No hay registro en el sistema</td>
			</tr>
			`;
		}else{
			sellers.forEach(seller => {
				template+=`
					<tr>	
						<td><img style="width:30px;height:30px;background:transparent" src="${seller.perfil}" class="img-thumbnail"></td>
						<td>${seller.vendedor}</td>
						<td>${seller.cargo}</td>
						<td><span class="badge badge-info">${seller.ventas}</span></td>
						<td>${seller.ganancias}</td>
					</tr>
				`;
			});
		}
		$("#sales_seller").html(template);
	});
}
/*=============================================================================
=============================================================================*/
init(); 