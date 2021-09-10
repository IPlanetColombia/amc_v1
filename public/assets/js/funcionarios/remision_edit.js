$(document).ready(function(){
	$('#frm_nombre_empresa.autocomplete').autocomplete({data: {}});
	$('#frm_producto.autocomplete').autocomplete({data: {}});
	$('form').keypress(function(e) {
        if (e.which == 13)
            return false;
    });
	$('input#frm_nit').blur(function(){
		$('input#frm_nit').removeClass('invalid');
	});
	$('#myform').validate({
		rules: {
			frm_certificados_editar: {required:true}
		},
		showErrors: function(errorMap, errorList) {
			errorList.forEach(key => {
				var input = [key.element];
				id = $(input).attr('id');
				$('input#'+id).addClass('invalid');
			});
		},
		submitHandler: function(){
			setTimeout(function () {buscar_certificado()}, 500);
		}
	});
	$('#frm_nombre_empresa').blur(function(e){
		setTimeout(function (){buscar_cliente()}, 500);
	});
	$('#frm_nombre_empresa').keyup(function(e){
		var empresa = $('#frm_nombre_empresa').val();
		var form = $('#frm_form');
		var url = form.attr('action');
		var tecla = e.which;
		if(empresa != "" && tecla != 37 && tecla != 38 && tecla != 39 && tecla != 40){
			var data = "frm_nombre_empresa="+empresa+"&buscar=1";
			fetch(url, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded', Authentication: 'secret'},
				body: data
			})
			.then(response => {
			    if (!response.ok) throw Error(response.status);
			    return response.json();
			 }).then(lista => {
				$('.autocomplete.frm_nombre_empresa').autocomplete('updateData',lista);
			 	$('.autocomplete.frm_nombre_empresa').autocomplete('open');
			 });
		}
	});
	$('.empresa .autocomplete-content').click(function(e){
		setTimeout(function (){buscar_cliente()}, 500);
	});

	$('#frm_form_muestra').validate({
		rules: {
			frm_identificacion: {required:true},
		},
		showErrors: function(errorMap, errorList) {
			errorList.forEach(key => {
				var input = [key.element];
				id = $(input).attr('id');
				$('input#'+id).addClass('invalid');
			});
		},
		submitHandler: function(){
			var mensaje = '';
			var select = true;
			if($('#frm_analisis').val() == ''){
				var select = false;
				$('.frm_analisis').addClass('error');
				$('.select-dropdown.dropdown-trigger').focus();
			}else if($('#frm_nombre_empresa').val() == ''){
				mensaje = 'Seleccione una empresa o registre una.';
				$('input#frm_nombre_empresa').addClass('invalid');
			}else if($('#frm_entrega').val() == ''){
				mensaje = 'Registre una persona quien entrego la muestra.';
				$('input#frm_entrega').addClass('invalid');
			}else if($('#frm_recibe').val() == ''){
				mensaje = 'Registre una persona responsable quien recibe la muestra.';
				$('input#frm_recibe').addClass('invalid');
			}
			if(mensaje != ''){
				Swal.fire({
					position: 'top-end',
				  	icon: 'error',
				  	text: mensaje,
				});
			}else if(select){
				var boton = $('#btn-muestreo-form');
				boton.prop('disabled', true);
				boton.addClass('blue-grey darken-3');
				boton.removeClass('gradient-45deg-purple-deep-orange');
				js_enviar_agregar_a_detalle();
			}
		}
	});

	$('#frm_producto').keyup(function(e){
		var producto = $('#frm_producto').val();
		var form = $('#frm_form_muestra');
		var url = form.attr('action');
		var tecla = e.which;
		if(producto != "" && tecla != 37 && tecla != 38 && tecla != 39 && tecla != 40){
			var data = "frm_producto="+producto+"&buscar=1";
			fetch(url, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded', Authentication: 'secret'},
				body: data
			}).then(response => {
			    if (!response.ok) throw Error(response.status);
			    return response.json();
			 }).then(lista => {
				$('.autocomplete.frm_producto').autocomplete('updateData', lista);
				$('.autocomplete.frm_producto').autocomplete('open');
			 });
		}
	});
	$('.frm_producto .autocomplete-content').click(function(e){
		setTimeout(function(){producto_blur()}, 100);
	});
	$('#frm_producto').blur(function(e){
		setTimeout(function(){producto_blur()}, 100);
	});
});
var boton_empresa = $('#btn-empresa');
boton_empresa.click(function(e){
	$('.empresa_row small').html('');
	var form = $('#myform');
	var url = form.attr('action');
	var data = $('#frm_form').serialize();
	data += "&buscar=1";
	boton_empresa.prop('disabled', true);
	boton_empresa.removeClass('gradient-45deg-purple-deep-orange');
	boton_empresa.addClass('blue-grey darken-3');
	boton_empresa.html('Editando empresa <i class="fas fa-spinner fa-spin"></i>');
	fetch(url, {
		method: 'POST',
		headers: { 'Content-Type': 'application/x-www-form-urlencoded', Authentication: 'secret'},
		body: data
	})
	.then(response => {
	    if (!response.ok) throw Error(response.status);
	    return response.json();
	 }).then(result => {
	 	$('.empresa_row small').html('');
	 	console.log(result);
	 	if(result.vacio){
	 		Swal.fire({
				position: 'top-end',
			  	icon: 'warning',
			  	text: result.mensaje,
			});
	 	}else if(result.result){
	 		Swal.fire({
				position: 'top-end',
			  	icon: 'success',
			  	text: 'Datos empresa cambiada correctamente',
			});
	 	}else{
	 		var mensajes = Object.entries(result);
	 		mensajes.forEach(([key, value])=> {
	 			$('small#'+key).html(value);
	 		});
	 	}
	 	boton_empresa.addClass('gradient-45deg-purple-deep-orange');
		boton_empresa.removeClass('blue-grey darken-3');
		boton_empresa.prop('disabled', false);
		boton_empresa.html('Editar empresa');
	 }).catch(error => {
	 	boton_empresa.addClass('gradient-45deg-purple-deep-orange');
		boton_empresa.removeClass('blue-grey darken-3');
		boton_empresa.prop('disabled', false);
		boton_empresa.html('Editar empresa');
	 });
});
function buscar_certificado(){
	M.toast({
		html: '<i class="fas fa-spinner fa-spin"></i>&nbsp&nbsp Buscando certificado',
		classes: 'blue-grey darken-2',
		displayLength: 30000,
	});
	var form = $('#myform');
	var data = form.serialize();
	data+="&buscar=0";
	var url = form.attr('action');
	fetch(url, {
		method: 'POST',
		headers: { 'Content-Type': 'application/x-www-form-urlencoded', Authentication: 'secret'},
		body: data
	}).then(response => {
	    if (!response.ok) throw Error(response.status);
	    return response.json();
	 }).then(muestra => {
	 	M.Toast.dismissAll();
	 	if(muestra.result){
	 		M.toast({
				html: '<i class="fas fa-check"></i>&nbsp&nbsp Datos cargados',
				classes: 'blue darken-2',
				displayLength: 3000,
			});
	 		$('#campo_detalle_muestras_basic').hide();
		 	$('#campo_detalle_muestras').remove();
		 	$('#tabla_detalles_muestras').after(muestra.tabla.tabla);
		 	$('.row.boton_guardar_remision .centrar_button').remove();
		 	$('.row.boton_guardar_remision').append(muestra.tabla.boton);
		 	$('.tabla-productos').remove();

		 	$('#frm_nit').val(muestra.cliente.id);
	 		$('#frm_nit').prop('readonly', true);
		 	$('#frm_id_remision').val(muestra.certificado.id_muestreo);
	 		$('#frm_nombre_empresa_subtitulo').val(muestra.cliente.sucursal);
		 	$('#frm_nombre_empresa').val(muestra.cliente.name);
		 	$('#frm_nombre_empresa2').val(muestra.cliente.id);
	 		$('#frm_nombre_empresa_subtitulo').focus();
		 	$('#frm_contacto_cargo').val(muestra.cliente.use_cargo);
		 	$('#frm_contacto_nombre').val(muestra.cliente.use_nombre_encargado);
		 	$('#frm_fecha_muestra').val(muestra.fecha_muestreo);
		 	$('#frm_hora_muestra').val(muestra.hora_muestreo);
		 	$('#frm_telefono').val(muestra.cliente.use_telefono);
		 	$('#frm_fax').val(muestra.cliente.use_fax);
		 	$('#frm_correo').val(muestra.cliente.email);
		 	$('#frm_direccion').val(muestra.cliente.use_direccion);
		 	$('#frm_id_muestra').val(muestra.certificado.id_muestreo);

		 	$('#frm_observaciones').val(muestra.muestra.mue_observaciones);
		 	$('#frm_entrega').val(muestra.muestra.mue_entrega_muestra);
		 	$('#frm_recibe').val(muestra.muestra.mue_recibe_muestra);
		 	$('.tooltipped').tooltip();
		 	$('#frm_form_muestra')[0].reset();
		 	M.updateTextFields();
	 	}else{
		 	M.toast({
				html: '<i class="fas fa-times"></i>&nbsp&nbsp No se encontro certificado',
				classes: 'amber darken-2',
				displayLength: 3000,
			});
	 	}
	 });
}
function buscar_cliente(){
	$('.empresa_row small').html('');
	if($('#frm_nombre_empresa').val() === ''){
		M.toast({
			html: 'Nombre de la empresa vacio',
			classes: 'red darken-2',
			displayLength: 3000,
		});
	}else{
		var data = "frm_nombre_empresa="+$('#frm_nombre_empresa').val()+"&buscar=2";
		var form = $('#frm_form');
		var url = form.attr('action');
		if($('#click').val() == 0){
			$('#click').val(1);
			setTimeout(function (){buscar_cliente()}, 200);
		}else{
			$('#click').val(0);
			M.toast({
				html: '<i class="fas fa-spinner fa-spin"></i>&nbsp&nbsp Buscando empresa',
				classes: 'blue-grey darken-2',
				displayLength: 30000,
			});
			fetch(url, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded', Authentication: 'secret'},
				body: data
			})
			.then(response => {
			    if (!response.ok) throw Error(response.status);
			    return response.json();
			}).then(empresa => {
				$('.autocomplete').autocomplete('close');
				M.Toast.dismissAll();
			 	if(empresa.validation){
			 		var nom_empresa = $('#frm_nombre_empresa').val();
			 		$('#frm_form')[0].reset();
			 		$('#frm_nombre_empresa').val(nom_empresa);
			 		M.toast({
						html: '<i class="fas fa-user-times"></i>&nbsp&nbsp No se encontro la empresa',
						classes: 'amber darken-2',
						displayLength: 3000,
					});
			 	}else{
			 		M.toast({
						html: '<i class="fas fa-user-check"></i>&nbsp&nbsp Empresa registrada',
						classes: 'light-blue darken-2',
						displayLength: 3000,
					});
				 	$('#frm_nombre_empresa').val(empresa.name);
				 	$('#frm_nombre_empresa_subtitulo').val(empresa.sucursal);
				 	$('#frm_nombre_empresa2').val(empresa.id);
				 	$('#frm_nit').val(empresa.id);
			 		$('#frm_nit').prop('readonly', true);
			 		$('.input-field.nit').removeClass('l2');
			 		$('.input-field.nit').addClass('l6');
			 		$('#frm_nombre_empresa_subtitulo').focus();
			 		$('input#empresa_nueva').val(1);
				 	$('#frm_contacto_cargo').val(empresa.use_cargo);
				 	$('#frm_contacto_nombre').val(empresa.use_nombre_encargado);
				 	$('#frm_telefono').val(empresa.use_telefono);
				 	$('#frm_fax').val(empresa.use_fax);
				 	$('#frm_correo').val(empresa.email);
				 	$('#frm_direccion').val(empresa.use_direccion);
			 	}
			 	M.updateTextFields();
			}).catch(error => {
			 	$('#frm_nombre_empresa').focus();
			 	$('#frm_nombre_empresa').next().focus();
			});
		}
	}
}
function producto_blur(){
	var producto = $('#frm_producto').val();
	var form = $('#frm_form_muestra');
	var url = $('#myform').attr('action');
	var data = form.serialize();
	data += "&buscar=4";
	fetch(url, {
		method: 'POST',
		headers: { 'Content-Type': 'application/x-www-form-urlencoded', Authentication: 'secret'},
		body: data
	}).then(response => {
	    if (!response.ok) throw Error(response.status);
	    return response.json();
	 }).then(tabla => {
	 	if(tabla === '')
	 		return null;
	 	$('#campo_parametros_producto_detalle .tabla-productos').remove();
	 	$('#campo_parametros_producto_detalle').after(tabla);
	 });
};
function buscar_detalle(producto){
	M.toast({
		html: '<i class="fas fa-spinner fa-spin"></i>&nbsp&nbsp Buscando detalle',
		classes: 'blue-grey darken-2',
		displayLength: 300000,
	});
	$('#id_muestra_detalle').val(producto);
	var url = $('#myform').attr('action');
	var frm_form 			= $('#frm_form').serialize();
	var frm_form_muestra 	= $('#frm_form_muestra').serialize();
	var frm_form_pie 		= $('#frm_form_pie').serialize();
	var data = 'buscar=2&'+frm_form+'&'+frm_form_muestra+'&'+frm_form_pie;
	fetch(url, {
		method: 'POST',
		headers: { 'Content-Type': 'application/x-www-form-urlencoded', Authentication: 'secret'},
		body: data
	}).then(response => {
	    if (!response.ok) throw Error(response.status);
	    return response.json();
	 }).then(result => {
	 	M.Toast.dismissAll();
	 	$('#campo_detalle_muestras_basic').hide();
	 	$('#campo_parametros_producto_detalle .tabla-productos').remove();
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
	 	$('.row.boton_guardar_remision .centrar_button').remove();
	 	$('.row.boton_guardar_remision').append(tabla.boton);
		var frm_form_muestra =$('#frm_form_muestra')[0];
	 	formateo_forms(0, frm_form_muestra, 0);
	 	$('.tabla-productos').remove();
	 	$('#frm_id_remision').val(tabla.frm_id_remision);
	 	$('.tooltipped').tooltip();
	 }).catch(error => M.Toast.dismissAll());
}
function js_enviar_agregar_a_detalle(){
	M.toast({
		html: '<i class="fas fa-spinner fa-spin"></i>&nbsp&nbsp Editando detalle',
		classes: 'blue-grey darken-2',
		displayLength: 300000,
	});
	var url = $('#myform').attr('action');
	var frm_form 			= $('#frm_form').serialize();
	var frm_form_muestra 	= $('#frm_form_muestra').serialize();
	var frm_form_pie 		= $('#frm_form_pie').serialize();
	var data = 'buscar=3&'+frm_form+'&'+frm_form_muestra+'&'+frm_form_pie;
	fetch(url, {
		method: 'POST',
		headers: { 'Content-Type': 'application/x-www-form-urlencoded', Authentication: 'secret'},
		body: data
	}).then(response => {
	    if (!response.ok) throw Error(response.status);
	    return response.json();
	}).then(tabla => {
		M.Toast.dismissAll();
	 	$('#campo_detalle_muestras_basic').hide();
	 	$('#campo_detalle_muestras').remove();
	 	$('#tabla_detalles_muestras').after(tabla.tabla);
	 	$('.row.boton_guardar_remision .centrar_button').remove();
	 	$('.row.boton_guardar_remision').append(tabla.boton);
		var frm_form_muestra =$('#frm_form_muestra')[0];
	 	formateo_forms(0, frm_form_muestra, 0);
	 	$('.tabla-productos').remove();
	 	$('#frm_id_remision').val(tabla.frm_id_remision);
	 	$('#frm_producto').attr('type', 'hidden');
	 	$('.tooltipped').tooltip();
	}).catch(error => M.Toast.dismissAll());
};
function btn_remision_guardar(){
	var data = $('#frm_form_pie').serialize();
	data += data+'&buscar=4';
	var url = $('#frm_form_pie').attr('action');
	fetch(url, {
		method: 'POST',
		headers: { 'Content-Type': 'application/x-www-form-urlencoded', Authentication: 'secret'},
		body: data
	})
	.then(response => {
	    if (!response.ok) throw Error(response.status);
	    return response.json();
	}).then(data => {
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
	});
};
function formateo_forms(frm_form, frm_form_muestra, frm_form_pie){
	if(frm_form != 0)
		frm_form.reset();
	if(frm_form_muestra != 0)
		frm_form_muestra.reset();
	if(frm_form_pie != 0)
		frm_form_pie.reset();
	M.updateTextFields();
}