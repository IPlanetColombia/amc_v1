$(document).ready(function(){
	$('#frm_nombre_empresa.autocomplete').autocomplete({data: {}});
	$('#frm_producto.autocomplete').autocomplete({data: {}});
	$('form').keypress(function(e) { if (e.which == 13) return false;});
	$('input#frm_nit').blur(function(){$('input#frm_nit').removeClass('invalid');});
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
		submitHandler: function(form){
			my_toast('<i class="fas fa-spinner fa-spin"></i>&nbsp Buscando certificado', 'blue-grey darken-2', 30000);
			var url = $(form).attr('action');
			var data = $(form).serialize();
			var resultado = proceso_fetch(url, data);
			resultado.then(data => {
				if(data.result){
					completar_empresa(data.cliente);
					tabla_detalles_muestras(data.tabla);
					recibe_entrega(data.muestra, data.certificado);
					my_toast('<i class="fas fa-check"></i>&nbsp Datos cargados', 'blue darken-2', 3000);
				}else my_toast('<i class="fas fa-times"></i>&nbsp No se encontro certificado', 'amber darken-2', 3000);
			});
		}
	});
	$('#frm_form').validate({
		rules: {
			frm_nombre_empresa:{required:true},
		},
		showErrors: function(errorMap, errorList) {
			errorList.forEach(key => {
				var input = [key.element];
				id = $(input).attr('id');
				$('input#'+id).addClass('invalid');
			});
		},
		submitHandler: function(form){
			var url = $('#myform').attr('action');
			var data = $(form).serialize();
			var boton_empresa = $('#btn-empresa');
			boton_empresa.prop('disabled', true);
			boton_empresa.removeClass('gradient-45deg-purple-deep-orange');
			boton_empresa.addClass('blue-grey darken-3');
			boton_empresa.html('Editando empresa <i class="fas fa-spinner fa-spin"></i>');
			var resultado = proceso_fetch(url, data);
			resultado.then(result => {
				$('.empresa_row small').html('');
				if(result.vacio){
					Swal.fire({
						position: 'top-end',
					  	icon: 'warning',
					  	text: result.mensaje,
					});
				}
			 	if(result.success){
			 		Swal.fire({
						position: 'top-end',
					  	icon: 'success',
					  	text: 'Datos empresa cambiada correctamente',
					});
			 	}else{
			 		var mensajes = Object.entries(result);
			 		mensajes.forEach(([key, value])=> {
			 			$('input#'+key).addClass('invalid');
			 			$('small#'+key).html(value);
			 		});
			 	}
			 	boton_empresa.addClass('gradient-45deg-purple-deep-orange');
				boton_empresa.removeClass('blue-grey darken-3');
				boton_empresa.prop('disabled', false);
				boton_empresa.html('Actualizar empresa');
			}).catch(error => {
				boton_empresa.addClass('gradient-45deg-purple-deep-orange');
				boton_empresa.removeClass('blue-grey darken-3');
				boton_empresa.prop('disabled', false);
				boton_empresa.html('Actualizar empresa');
				my_toast('<i class="fa fas-times"></i>&nbsp&nbsp Error en la consulta', 'red darken-2', 3000);
			})
		}
	});
	$('#frm_nombre_empresa').keyup(function(e){
		var empresa = $('#frm_nombre_empresa').val();
		var form = $('#frm_form');
		var url = form.attr('action');
		var tecla = e.which;
		if(empresa != "" && tecla != 37 && tecla != 38 && tecla != 39 && tecla != 40){
			var data = "frm_nombre_empresa="+empresa+"&buscar=1";
			var resultado = proceso_fetch(url, data);
			resultado.then(lista => {
				$('.autocomplete.frm_nombre_empresa').autocomplete('updateData',lista);
			 	$('.autocomplete.frm_nombre_empresa').autocomplete('open');
			})
		}
	});
	$('#frm_nombre_empresa').blur(function(){
		buscar_cliente();
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
				my_toast('<i class="fas fa-spinner fa-spin"></i>&nbsp&nbsp Editando detalle','blue-grey darken-2',300000);
				js_enviar_agregar_a_detalle($('#myform').attr('action'));
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
			result = proceso_fetch(url, data);
			result.then(lista => {
				$('.autocomplete.frm_producto').autocomplete('updateData', lista);
				$('.autocomplete.frm_producto').autocomplete('open');
			 });
		}
	});
	$('#frm_producto').blur(function(e){
		producto_blur(4);
	});
});