$(document).ready(function(){
	$('input#frm_nit').blur(function(){$('input#frm_nit').removeClass('invalid');});
	$('input.autocomplete').autocomplete({data: {}});
	$('form').keypress(function(e) { // Negamos el envio por la tecla enter
        if (e.which == 13)
            return false;
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
	$('#frm_nombre_empresa').blur(function(){
		setTimeout(function(){empresa_blur()}, 500);
	});
	var boton_empresa = $('#btn-empresa');
	boton_empresa.click(function(e){
		e.preventDefault();
		$('.empresa_row small').html('');
		var form = $('#frm_form');
		var url = form.attr('action');
		var data = form.serialize();
		boton_empresa.prop('disabled', true);
		boton_empresa.removeClass('gradient-45deg-purple-deep-orange');
		boton_empresa.addClass('blue-grey darken-3');
		boton_empresa.html('Guardando empresa <i class="fas fa-spinner fa-spin"></i>');
		fetch(url, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded', Authentication: 'secret'},
			body: data
		})
		.then(response => {
		    if (!response.ok) throw Error(response.status);
		    return response.json();
		 }).then(result => {
		 	if(result.success){
		 		$(".empresa_row .frm_hora_muestra label").addClass('active');
		 		$('.empresa_row small').html('');
		 		Swal.fire({
					position: 'top-end',
				  	icon: 'success',
				  	text: result.success,
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
			boton_empresa.html('Guardar empresa');
		 }).catch(error => {
		 	boton_empresa.addClass('gradient-45deg-purple-deep-orange');
			boton_empresa.removeClass('blue-grey darken-3');
			boton_empresa.prop('disabled', false);
			boton_empresa.html('Guardar empresa');
		 });
	});

	// Muestra
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
		setTimeout(function(){producto_blur()}, 500);
	});
	$('#frm_producto').blur(function(e){
		setTimeout(function(){producto_blur()}, 500);
	});
    $('#frm_form_muestra').validate({ // Guardamos el detalle
		rules: {
			frm_identificacion: {required:true},
			frm_analisis: { minlength: 1 },
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
	$('.frm_analisis li').click(function(e){
		$('.frm_analisis').removeClass('error');
	})
});
function empresa_blur(){
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
			setTimeout(function(){empresa_blur()}, 500);
		}else{
			M.toast({
				html: '<i class="fas fa-spinner fa-spin"></i>&nbsp&nbsp Buscando empresa',
				classes: 'blue-grey darken-2',
				displayLength: 30000,
			});
			$('#click').val(0);
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
			 	$('.input-field.username').remove();
			 	if(empresa.validation){
			 		M.toast({
						html: '<i class="fas fa-user-times"></i>&nbsp&nbsp Empresa sin registrar',
						classes: 'amber darken-2',
						displayLength: 3000,
					});
		 			$('#frm_form')[0].reset();
		 			$('#frm_nombre_empresa').val(empresa.empresa);
		 			M.updateTextFields();
			 		$('#frm_nit').prop('readonly', false);
			 		$('.input-field.nit').removeClass('l6');
			 		$('.input-field.nit').addClass('l2');
			 		$('.input-field.empresa').after(
			 			'<div class="input-field col l4 s12 username">'+
		                    '<input id="username" name="username" type="text" class="validate">'+
		                    '<label for="username">Usuario</label>'+
		                    '<small class=" red-text text-darken-4" id="username"></small>'+
		                '</div>');
			 		$('#username').focus();
			 		$('input#empresa_nueva').val(0);
			 	}else{
			 		M.toast({
						html: '<i class="fas fa-user-check"></i>&nbsp&nbsp Empresa registrada',
						classes: 'light-blue darken-2',
						displayLength: 3000,
					});
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
				 	$('#frm_nombre_empresa2').val(empresa.id);
			 		M.updateTextFields();
			 	}
			}).catch(error => {
			 	$('#frm_nombre_empresa').focus();
			 	$('#frm_nombre_empresa').next().focus();
			});
		}
	}
};
function producto_blur(){
	$('#frm_producto').next().focus();
	var producto = $('#frm_producto').val();
	var form = $('#frm_form_muestra');
	var url = form.attr('action');
	var data = form.serialize();
	data += "&buscar=2";
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
	 	$('.tabla-productos').remove();
	 	$('#frm_form_muestra .row.finish').after(tabla);
	 });
};
function js_enviar_agregar_a_detalle(){
	M.toast({
		html: '<i class="fas fa-spinner fa-spin"></i>&nbsp&nbsp Agregando detalle',
		classes: 'blue-grey darken-2',
		displayLength: 300000,
	});
	var url = $('#frm_form_muestra').attr('action');
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
	 	$('.tooltipped').tooltip();
	}).catch(error => {
		M.Toast.dismissAll();
		M.toast({
			html: '<i class="fas fa-times"></i>&nbsp&nbsp Error agregando detalle',
			classes: 'red darken-2',
			displayLength: 3000,
		});
	});
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
			fetch(url, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded', Authentication: 'secret'},
				body: data
			})
			.then(response => {
			    if (!response.ok) throw Error(response.status);
			    return response.json();
			}).then(tabla => {
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
function formateo_forms(frm_form, frm_form_muestra, frm_form_pie){
	if(frm_form != 0)
		frm_form.reset();
	if(frm_form_muestra != 0)
		frm_form_muestra.reset();
	if(frm_form_pie != 0)
		frm_form_pie.reset();
	M.updateTextFields();
}