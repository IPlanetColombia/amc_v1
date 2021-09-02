$(document).ready(function(){
	$('input.autocomplete').autocomplete({data: {}});
	$('form').keypress(function(e) {
        if (e.which == 13)
            return false;
    });
    var empresa_nueva = 3; 
	$('#empresa').keyup(function(e){
		var empresa = $('#empresa').val();
		var form = $('#remicion-form');
		var url = form.attr('action');
		var tecla = e.which;
		if(empresa != "" && tecla != 37 && tecla != 38 && tecla != 39 && tecla != 40){
			var data = "empresa="+empresa+"&buscar=1";
			fetch(url, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded', Authentication: 'secret'},
				body: data
			})
			.then(response => {
			    if (!response.ok) throw Error(response.status);
			    return response.json();
			 }).then(lista => {
				$('.autocomplete.empresa').autocomplete('updateData',lista);
			 	$('.autocomplete.empresa').autocomplete('open');
			 });
		}
	});
	$('#empresa').blur(function(e){
		$('.empresa_row small').html('');
		var empresa = $('#empresa').val();
		var data = "empresa="+empresa+"&buscar=2";
		var form = $('#remicion-form');
		var url = form.attr('action');
		fetch(url, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded', Authentication: 'secret'},
			body: data
		})
		.then(response => {
		    if (!response.ok) throw Error(response.status);
		    return response.json();
		 }).then(empresa => {
		 	$('#sucursal').val(empresa.sucursal);
		 	$('#nit').val(empresa.id);
		 	$('.input-field.username').remove();
		 	if(empresa.id == ''){
		 		$('#nit').prop('disabled', false);
		 		empresa_nueva = 3;
		 		$('.input-field.nit').removeClass('l6');
		 		$('.input-field.nit').addClass('l2');
		 		$('.input-field.empresa').after(
		 			'<div class="input-field col l4 s12 username">'+
                        '<input id="username" name="username" type="text" class="validate">'+
                        '<label for="username">Usuario</label>'+
                        '<small class=" red-text text-darken-4" id="username"></small>'+
                    '</div>');
		 		$('#username').focus();
		 	}else{
		 		$('#nit').prop('disabled', true);
		 		empresa_nueva = 4;
		 		$('.input-field.nit').removeClass('l2');
		 		$('.input-field.nit').addClass('l6');
		 		$('#sucursal').focus();
		 	}
		 	$('#cargo').val(empresa.use_cargo);
		 	$('#name_contact').val(empresa.use_nombre_encargado);
		 	$('#phone').val(empresa.use_telefono);
		 	$('#fax').val(empresa.use_fax);
		 	$('#email').val(empresa.email);
		 	$('#direction').val(empresa.use_direccion);
		 	$('#date').val(empresa.fecha);
		 	$('#hora').val(empresa.hora);
		 	$(".empresa_row label").addClass('active');
		 }).catch(error => {
		 	$('#empresa').focus();
		 	$('#empresa').next().focus(); 
		 });
	});
	$('.empresa .autocomplete-content').click(function(e){
		$('#empresa').focus();
		$('#empresa').next().focus();
	});

	var boton_empresa = $('#btn-empresa');
	boton_empresa.click(function(e){
		$('.empresa_row small').html('');
		e.preventDefault();
		var form = $('#remicion-form');
		var url = form.attr('action');
		var data = form.serialize();
		data+='&date='+$('#date').val()+'&hora='+$('#hora').val()+'&buscar='+empresa_nueva+'&nit='+$('#nit').val();
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
		 		// $('#remicion-form')[0].reset();
		 		$(".empresa_row .hora label").addClass('active');
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
		 		console.log(mensajes);
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

	$('#producto').keyup(function(e){
		var producto = $('#producto').val();
		var form = $('#muestra-form');
		var url = form.attr('action');
		var tecla = e.which;
		if(producto != "" && tecla != 37 && tecla != 38 && tecla != 39 && tecla != 40){
			var data = "producto="+producto+"&buscar=1";
			fetch(url, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded', Authentication: 'secret'},
				body: data
			}).then(response => {
			    if (!response.ok) throw Error(response.status);
			    return response.json();
			 }).then(lista => {
				$('.autocomplete.producto').autocomplete('updateData', lista);
				$('.autocomplete.producto').autocomplete('open');
			 });
		}
	});
	$('.producto .autocomplete-content').click(function(e){
		$('#producto').focus();
		$('#producto').next().focus();
	});
	$('#producto').blur(function(e){
		$('#lote').focus();
		var producto = $('#producto').val();
		var form = $('#muestra-form');
		var url = form.attr('action');
		var tecla = e.which;
		var data = form.serialize();
		if(producto != "" && tecla != 37 && tecla != 38 && tecla != 39 && tecla != 40){
			data += "&buscar=2";
			fetch(url, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded', Authentication: 'secret'},
				body: data
			}).then(response => {
			    if (!response.ok) throw Error(response.status);
			    return response.json();
			 }).then(tabla => {
			 	$('.tabla-productos').remove();
			 	$('#muestra-form .row').after(tabla);
			 });
		}
	});
});