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
			var data = new URLSearchParams({
                frm_nombre_empresa: empresa,
                buscar: 1,
            });
			var result = proceso_fetch(url, data.toString());
			result.then(lista => {
				$('.autocomplete.frm_nombre_empresa').autocomplete('updateData',lista);
			 	$('.autocomplete.frm_nombre_empresa').autocomplete('open');
			});
		}
	});
	$('#frm_nombre_empresa').blur(function(){buscar_cliente(1)});
	$('#frm_form').validate({
		submitHandler: function(form){
			var url = $(form).attr('action');
			var data = $(form).serialize();
			var boton_empresa = $('#btn-empresa');
			boton_empresa.prop('disabled', true);
			boton_empresa.removeClass('gradient-45deg-purple-deep-orange');
			boton_empresa.addClass('blue-grey darken-3');
			boton_empresa.html('Guardando empresa <i class="fas fa-spinner fa-spin"></i>');
			var result = proceso_fetch(url, data);
			result.then(result => {
			 	$('.empresa_row small').html('');
			 	if(result.success){
			 		$(".empresa_row .frm_hora_muestra label").addClass('active');
			 		Swal.fire({
						position: 'top-end',
					  	icon: 'success',
					  	text: result.success,
					});
					if(result.procedencia == 0){
						$('input#empresa_nueva').val(1);
						$('#frm_nombre_empresa2').val(result.id);
					}
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
			});
		}
	});

	// Muestra
	$('#frm_producto').keyup(function(e){
		var producto = $('#frm_producto').val();
		var form = $('#frm_form_muestra');
		var url = form.attr('action');
		var tecla = e.which;
		if(producto != "" && tecla != 37 && tecla != 38 && tecla != 39 && tecla != 40){
			var data = new URLSearchParams({
                frm_producto: producto,
                buscar: 1,
            });
			result = proceso_fetch(url, data.toString());
			result.then(lista => {
				$('.autocomplete.frm_producto').autocomplete('updateData', lista);
				$('.autocomplete.frm_producto').autocomplete('open');
			 });
		}
	});
	$('#frm_producto').blur(function(e){
		producto_blur(2);
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
				my_toast('<i class="fas fa-spinner fa-spin"></i>&nbsp Agregando detalle', 'blue-grey darken-2', 30000);
				js_enviar_agregar_a_detalle($('#frm_form_muestra').attr('action'), 1);
			}
		}
	});
	$('.frm_analisis li').click(function(e){
		$('.frm_analisis').removeClass('error');
	})
});
// function js_enviar_agregar_a_detalle(){
// 	M.toast({
// 		html: '<i class="fas fa-spinner fa-spin"></i>&nbsp&nbsp Agregando detalle',
// 		classes: 'blue-grey darken-2',
// 		displayLength: 300000,
// 	});
// 	var url = $('#frm_form_muestra').attr('action');
// 	var frm_form 			= $('#frm_form').serialize();
// 	var frm_form_muestra 	= $('#frm_form_muestra').serialize();
// 	var frm_form_pie 		= $('#frm_form_pie').serialize();
// 	var data = 'buscar=3&'+frm_form+'&'+frm_form_muestra+'&'+frm_form_pie;
// 	fetch(url, {
// 		method: 'POST',
// 		headers: { 'Content-Type': 'application/x-www-form-urlencoded', Authentication: 'secret'},
// 		body: data
// 	}).then(response => {
// 	    if (!response.ok) throw Error(response.status);
// 	    return response.json();
// 	}).then(tabla => {
// 		M.Toast.dismissAll();
// 	 	$('#campo_detalle_muestras_basic').hide();
// 	 	$('#campo_detalle_muestras').remove();
// 	 	$('#tabla_detalles_muestras').after(tabla.tabla);
// 	 	$('.row.boton_guardar_remision .centrar_button').remove();
// 	 	$('.row.boton_guardar_remision').append(tabla.boton);
// 		var frm_form_muestra =$('#frm_form_muestra')[0];
// 	 	formateo_forms(0, frm_form_muestra, 0);
// 	 	$('.tabla-productos').remove();
// 	 	$('#frm_id_remision').val(tabla.frm_id_remision);
// 	 	$('.tooltipped').tooltip();
// 	}).catch(error => {
// 		M.Toast.dismissAll();
// 		M.toast({
// 			html: '<i class="fas fa-times"></i>&nbsp&nbsp Error agregando detalle',
// 			classes: 'red darken-2',
// 			displayLength: 3000,
// 		});
// 	});
// };
// function btn_remision_guardar(){
// 	var data = $('#frm_form_pie').serialize();
// 	data += data+'&buscar=4';
// 	var url = $('#frm_form_pie').attr('action');
// 	fetch(url, {
// 		method: 'POST',
// 		headers: { 'Content-Type': 'application/x-www-form-urlencoded', Authentication: 'secret'},
// 		body: data
// 	})
// 	.then(response => {
// 	    if (!response.ok) throw Error(response.status);
// 	    return response.json();
// 	}).then(data => {
// 		Swal.fire({
// 			position: 'top-end',
// 		  	icon: 'success',
// 		  	text: data.mensaje,
// 		});
// 		$('#campo_detalle_muestras').remove();
// 		$('#campo_detalle_muestras_basic').show();
// 		var frm_form =$('#frm_form')[0];
// 		var frm_form_muestra =$('#frm_form_muestra')[0];
// 		var frm_form_pie =$('#frm_form_pie')[0];
// 		formateo_forms(frm_form, frm_form_muestra, frm_form_pie);
// 		$('input#frm_fecha_muestra').val(data.fecha);
// 		$('input#frm_hora_muestra').val(data.hora);

// 		$('#frm_nombre_empresa2').val('');
// 		$('#empresa_nueva').val(1);
// 		$('#frm_estado_remision').val(0);
// 		$('#frm_responsable').val(0);
// 		$('#frm_fecha_analisis').val('0000-00-00');
// 		$('#frm_fecha_informe').val('0000-00-00');
// 		$('#frm_id_remision').val(0);
// 		$('.centrar_button.btn_remision').remove();
// 		$('.tooltipped').tooltip();
// 	});
// };

// function formateo_forms(frm_form, frm_form_muestra, frm_form_pie){
// 	if(frm_form != 0)
// 		frm_form.reset();
// 	if(frm_form_muestra != 0)
// 		frm_form_muestra.reset();
// 	if(frm_form_pie != 0)
// 		frm_form_pie.reset();
// 	M.updateTextFields();
// }