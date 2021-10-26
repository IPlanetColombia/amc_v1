function my_toast(html, clase, duracion, error=false){
	if(error || html.includes('check') || clase.includes('amber'))
		M.Toast.dismissAll();
	M.toast({
	 	html: html,
	 	classes: clase,
	 	displayLength: duracion,
	});
}
function proceso_fetch(url, data){
	return fetch(url, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded', Authentication: 'secret'},
			body: data
		}).then(response => {
	    	if (!response.ok) throw Error(response.status);
	    		return response.json();
	 	}).catch(error => my_toast('<i class="fas fa-times"></i>&nbsp&nbsp Error en consulta', 'red darken-2', 3000, true));
}
function buscar_cliente(remision = 0){
	$('.empresa_row small').html('');
	if($('#frm_nombre_empresa').val() === ''){
		my_toast('Nombre de la empresa vacio', 'red darken-2', 3000);
	}else{
		setTimeout(function(){
			var data = new URLSearchParams({
                frm_nombre_empresa: $('#frm_nombre_empresa').val(),
                buscar: 2,
            });
			var form = $('#frm_form');
			var url = form.attr('action');
			my_toast('<i class="fas fa-spinner fa-spin"></i>&nbsp&nbsp Buscando empresa', 'blue-grey darken-2', 30000);
			var promesa = proceso_fetch(url, data.toString());
			promesa.then(empresa => {
				if(empresa.validation){
					formateo_forms(form[0]);
					$('#frm_nombre_empresa').val(empresa.empresa);
					M.updateTextFields();
					if(remision == 0){
						my_toast('<i class="fas fa-times"></i>&nbsp No se encontro empresa', 'amber darken-2', 3000);
					}else{
						$('#frm_nit').prop('readonly', false);
				 		$('.input-field.nit').removeClass('l6');
				 		$('.input-field.nit').addClass('l2');
				 		if($('.username').length == 0){
					 		$('.input-field.empresa').after(
					 			'<div class="input-field col l4 s12 username">'+
				                    '<input id="username" name="username" type="text" class="validate">'+
				                    '<label for="username">Usuario</label>'+
				                    '<small class=" red-text text-darken-4" id="username"></small>'+
				                '</div>');
				 		}
				 		$('#username').focus();
				 		$('input#empresa_nueva').val(0);
						my_toast('<i class="fad fa-user-times"></i>&nbsp Empresa sin registrar', 'amber darken-2', 3000);
					}
				}else{
					$('.input-field.username').remove();
			 		$('#frm_nit').prop('readonly', true);
			 		$('.input-field.nit').removeClass('l2');
			 		$('.input-field.nit').addClass('l6');
			 		$('#frm_nombre_empresa_subtitulo').focus();
			 		$('input#empresa_nueva').val(1);
					completar_empresa(empresa);
					my_toast('<i class="fas fa-user-check"></i>&nbsp&nbsp Empresa cargada', 'blue darken-2', 3000);
				}
			});
		}, 1000);
	}
}
function completar_empresa(empresa){
	$('#frm_nit').prop('readonly', true);
	$('#frm_nit').val(empresa.id);
	$('#frm_nombre_empresa_subtitulo').val(empresa.sucursal);
 	$('#frm_nombre_empresa').val(empresa.name);
 	$('#frm_nombre_empresa2').val(empresa.id);
 	$('#frm_contacto_cargo').val(empresa.use_cargo);
 	$('#frm_contacto_nombre').val(empresa.use_nombre_encargado);
 	$('#frm_telefono').val(empresa.use_telefono);
 	$('#frm_fax').val(empresa.use_fax);
 	$('#frm_correo').val(empresa.email);
 	$('#frm_direccion').val(empresa.use_direccion);
	$('#frm_nombre_empresa_subtitulo').focus();
	if(empresa.fecha != null){
	 	$('#frm_fecha_muestra').val(empresa.fecha);
		$('#frm_hora_muestra').val(empresa.hora);
	}
 	M.updateTextFields();
}


// Productos
function producto_blur(buscar){
	my_toast('<i class="fas fa-spinner fa-spin"></i>&nbsp&nbsp Buscando producto', 'blue-grey darken-2', 30000);
	setTimeout(function(){
		var form = $('#frm_form_muestra');
		if(buscar == 4)
			var url = $('#myform').attr('action');
		else
			var url = form.attr('action');
		var data = new URLSearchParams(form.serialize());
		data.set('buscar', buscar);
		result = proceso_fetch(url, data.toString());
		result.then(tabla => {
		 	if(tabla === ''){
		 		my_toast('<i class="fas fa-times"></i>&nbsp No se encontro producto', 'amber darken-2', 3000);
		 		return null;
		 	}
		 	my_toast('<i class="fas fa-check"></i>&nbsp Tabla de producto cargada', 'blue darken-2', 3000);
		 	$('.lista_parametros').remove();
		 	$('#frm_form_muestra .row.finish').after(tabla);
		});
	}, 1000)
};
function tabla_detalles_muestras(tabla){
	$('#campo_detalle_muestras_basic').hide();
 	$('#campo_detalle_muestras').remove();
 	$('#tabla_detalles_muestras').after(tabla.tabla);
 	$('.row.boton_guardar_remision .centrar_button').remove();
 	$('.row.boton_guardar_remision').append(tabla.boton);
 	$('.tabla-productos').remove();
}

function recibe_entrega(muestra, certificado){
	$('#frm_id_remision').val(certificado.id_muestreo);
	$('#frm_id_muestra').val(certificado.id_muestreo);

	$('#frm_observaciones').val(muestra.mue_observaciones);
 	$('#frm_entrega').val(muestra.mue_entrega_muestra);
 	$('#frm_recibe').val(muestra.mue_recibe_muestra);
 	$('.tooltipped').tooltip();
 	formateo_forms($('#frm_form_muestra')[0]);
}

function buscar_detalle(producto){
	my_toast('<i class="fas fa-spinner fa-spin"></i>&nbsp&nbsp Buscando detalle','blue-grey darken-2',300000);
	$('#id_muestra_detalle').val(producto);
	var url = $('#myform').attr('action');
	var frm_form 			= $('#frm_form').serialize();
	var frm_form_muestra 	= $('#frm_form_muestra').serialize();
	var frm_form_pie 		= $('#frm_form_pie').serialize();
	var data = 'buscar=2&id_muestra_detalle='+producto;
	var result = proceso_fetch(url, data);
	result.then(result => {
	 	$('#campo_detalle_muestras_basic').hide();
	 	$('#campo_parametros_producto_detalle').remove();
	 	$('#campo_detalle_muestras').before(
	 			'<div id="campo_parametros_producto_detalle">'+
	 				result.tabla+
                '</div>');
	 	$('#frm_identificacion').val(result.detalle.mue_identificacion);
		$('#frm_lote').val(result.detalle.mue_lote);
		$('#frm_fecha_produccion').val(result.detalle.mue_fecha_produccion);
		$('#frm_fecha_vencimiento').val(result.detalle.mue_fecha_vencimiento);
		$('#frm_tmp_muestreo').val(result.detalle.mue_temperatura_muestreo);
		$('#frm_momento_muestreo').val(result.detalle.mue_momento_muestreo);
		$('#frm_tmp_recepcion').val(result.detalle.mue_temperatura_laboratorio);
		$('#frm_condiciones_recibido').val(result.detalle.mue_condiciones_recibe);
		$('#frm_cantidad').val(result.detalle.mue_cantidad);
		$('#frm_procedencia').val(result.detalle.mue_procedencia);
		$('#frm_parametro').val(result.detalle.mue_parametro);
		$('#frm_area').val(result.detalle.mue_area);
		$('#frm_tipo_muestreo').val(result.detalle.mue_tipo_muestreo);
		$('#frm_adicional').val(result.detalle.mue_adicional);
		$('#frm_analisis').val(result.detalle.id_tipo_analisis);
		$('#frm_producto').attr('type', 'text');
		$('#frm_producto').val(result.producto.pro_nombre);
		$('select').formSelect();
		M.updateTextFields();
	 	my_toast('<i class="fas fa-check"></i>&nbsp&nbsp Muestra encontrada','blue darken-2',3000);
	 	$('.tooltipped').tooltip();
	 }).catch(error => M.Toast.dismissAll());
}
function js_enviar_agregar_a_detalle(url, create=0){
	var frm_form 			= $('#frm_form').serialize();
	var frm_form_muestra 	= $('#frm_form_muestra').serialize();
	var frm_form_pie 		= $('#frm_form_pie').serialize();
	// var data = new URLSearchParams(frm_form.serialize(),frm_form_muestra.serialize(),frm_form_pie.serialize());
	// data.set('buscar', 3);
	var data = frm_form+'&'+frm_form_muestra+'&'+frm_form_pie+'&buscar=3';
	var result = proceso_fetch(url, data);
	result.then(tabla => {
	 	$('#campo_detalle_muestras_basic').hide();
	 	$('#campo_detalle_muestras').remove();
	 	$('#tabla_detalles_muestras').after(tabla.tabla);
	 	$('.row.boton_guardar_remision .centrar_button').remove();
	 	$('.row.boton_guardar_remision').append(tabla.boton);
		var frm_form_muestra =$('#frm_form_muestra')[0];
	 	formateo_forms(0, frm_form_muestra, 0);
	 	$('.lista_parametros').remove();
	 	$('#frm_id_remision').val(tabla.frm_id_remision);
	 	if(create == 0)
	 		$('#frm_producto').attr('type', 'hidden');
	 	$('.tooltipped').tooltip();
	 	my_toast('<i class="fas fa-check"></i>&nbsp&nbsp Detalle agregado', 'blue darken-2', 3000);
	}).catch(error => M.Toast.dismissAll());
};
function btn_remision_guardar(){
	var data = $('#frm_form_pie').serialize();
	data += data+'&buscar=4';
	var url = $('#frm_form_pie').attr('action');
	var result = proceso_fetch(url, data);
	result.then(data => {
		Swal.fire({
			position: 'top-end',
		  	icon: 'success',
		  	text: data.mensaje,
		});
		$('#campo_detalle_muestras').remove();
		$('#campo_detalle_muestras_basic').show();
		var frm_form =$('#frm_form')[0];
		var frm_form_muestra =$('#frm_form_muestra')[0];
		var frm_form_pie =$('#frm_form_pie')[0];
		formateo_forms(frm_form, frm_form_muestra, frm_form_pie);
		$('input#frm_fecha_muestra').val(data.fecha);
		$('input#frm_hora_muestra').val(data.hora);

		$('#frm_nombre_empresa2').val('');
		$('#empresa_nueva').val(1);
		$('#frm_estado_remision').val(0);
		$('#frm_responsable').val(0);
		$('#frm_fecha_analisis').val('0000-00-00');
		$('#frm_fecha_informe').val('0000-00-00');
		$('#frm_id_remision').val(0);
		$('.centrar_button.btn_remision').remove();
		$('.tooltipped').tooltip();
		$('.lista_parametros').remove();
	});
};
function formateo_forms(frm_form=0, frm_form_muestra=0, frm_form_pie=0){
	if(frm_form != 0)
		frm_form.reset();
	if(frm_form_muestra != 0)
		frm_form_muestra.reset();
	if(frm_form_pie != 0)
		frm_form_pie.reset();
	M.updateTextFields();
}

function quitar_detalle(certificado, producto, codigo){
	Swal.fire({
		position: 'top-end',
		icon: 'warning',
		title: 'Desea quitar de la lista el producto '+producto+', con Codigo AMC '+codigo+' ?',
		text: 'Recuerde que se sacaran de la lista los productos con Certificados numeros mayores',
  		confirmButtonColor: '#1976d2',
  		cancelButtonColor: '#d32f2f',
		showDenyButton: true,
		confirmButtonText: 'Eliminar',
		denyButtonText: `Cancelar`,
	}).then((result) => {
		if (result.isConfirmed) {
			$('tr.tr_'+certificado).hide();
			var data = 'id_certificacion='+certificado+"&buscar=5";
			var url = $('#frm_form_pie').attr('action');
			var result = proceso_fetch(url, data); 
			result.then(tabla => {
				M.toast({
					html: '<i class="fas fa-check"></i>&nbsp Detalle eliminado',
					classes: 'blue darken-2',
					displayLength: 3000,
				});
				$('#campo_detalle_muestras').remove();
	 			$('#tabla_detalles_muestras').after(tabla.tabla);
			 	$('.row.boton_guardar_remision .centrar_button').hide();
			 	$('.row.boton_guardar_remision .centrar_button').remove();
	 			$('.row.boton_guardar_remision').append(tabla.boton);
				formateo_forms(0, 0, 0);
				$('.tooltipped').tooltip();
			});
		}
	})
};