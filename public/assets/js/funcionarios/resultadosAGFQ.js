function js_cambiar_campos(campo_respuesta,
	valor, frm_resultado, resultado_analisis,  aux_id_ensayo_vs_muestra, aux_id_parametro){
		if(valor){
			if(aux_id_parametro ==101
	            || aux_id_parametro ==86
	            || aux_id_parametro ==241
	            || aux_id_parametro ==237
	            || aux_id_parametro ==102
	            || aux_id_parametro ==234
	            || aux_id_parametro ==235
	            || aux_id_parametro ==236
	            || aux_id_parametro ==240
	            || aux_id_parametro ==244
	            || aux_id_parametro ==239
	            || aux_id_parametro ==238
	            || aux_id_parametro ==242
	            || aux_id_parametro ==245
	            || aux_id_parametro ==243 
	            || aux_id_parametro ==39 // Solidos Totales
	            || aux_id_parametro ==40 // Solidos Totales Lacteos
            ){
        		my_toast('<i class="fas fa-spinner fa-spin"></i>&nbsp Cambiando resultado', 'blue-grey darken-2', 30000);
        		var url = $('#form_cambia_campos').attr('action');
        		var data = new URLSearchParams({
        			campo_respuesta: campo_respuesta,
					valor: valor,
					frm_resultado: frm_resultado,
					resultado_analisis: resultado_analisis,
					aux_id_ensayo_vs_muestra: aux_id_ensayo_vs_muestra,
					aux_id_parametro: aux_id_parametro,
					funcion: 'cambiar_campos'
		        });
		        var result = proceso_fetch(url, data.toString());
		        result.then(respuesta => {
		        	console.log(respuesta);
		        	if(respuesta.validation){
		        		$('input#'+frm_resultado).prop('disabled', true);
		        		$('select#'+frm_resultado).attr('disabled', 'disabled');
		        		$('select').formSelect();
		        		$('input#'+frm_resultado).removeClass();
		        		$('input#'+frm_resultado).addClass('valid');
		        		$('span#'+frm_resultado).removeClass();
		        		$('span#'+frm_resultado).addClass('green-text text-darken-2');
		        		$('#campo_respuesta_agua_'+aux_id_ensayo_vs_muestra).html(respuesta.mensaje_resultado[0]);
		        		$('#campo_respuesta_irca_'+aux_id_ensayo_vs_muestra).html(respuesta.mensaje_resultado[1]);
		        		my_toast('<i class="fas fa-check"></i>&nbsp Resultado actualizado', 'blue darken-2', 3000);
		        	}else{
		        		$('input#'+frm_resultado).removeClass();
		        		$('input#'+frm_resultado).addClass('invalid');
		        		$('span#'+frm_resultado).removeClass();
		        		$('span#'+frm_resultado).addClass('red-text text-darken-2');
		        		my_toast('<i class="fas fa-times"></i>&nbsp Ha ocurrido un error', 'red darken-2', 3000);
		        	}
		        	$('span#'+frm_resultado).html(respuesta.mensaje);
		        	var data_2 = new URLSearchParams({
						aux_id_ensayo_vs_muestra: aux_id_ensayo_vs_muestra,
						funcion: 'calcula_IRCA'
			        });
		        	var result_2 = proceso_fetch(url, data_2.toString());
		        	result_2.then(respuesta => {
		        		console.log(respuesta);
		        		$('#campo_resultado_irca').html(respuesta.mensaje);
		        	})
		        });
        		// xajax_cambiar_campos_resultados_fq(campo_respuesta,valor, frm_resultado, resultado_analisis,  aux_id_ensayo_vs_muestra, aux_id_parametro);
        		// xajax_calcula_solidos_totales(aux_id_ensayo_vs_muestra);   
       		}else{
       			my_toast('<i class="fas fa-spinner fa-spin"></i>&nbsp Cambiando resultado', 'blue-grey darken-2', 30000);
        		var url = $('#form_cambia_campos').attr('action');
        		var data = new URLSearchParams({
        			campo_respuesta: campo_respuesta,
					valor: valor,
					frm_resultado: frm_resultado,
					resultado_analisis: resultado_analisis,
					aux_id_ensayo_vs_muestra: aux_id_ensayo_vs_muestra,
					aux_id_parametro: aux_id_parametro,
					funcion: 'cambiar_campos_resultados_fq_directo'
		        });
		        var result = proceso_fetch(url, data.toString());
		        result.then(respuesta =>{
		        	if(respuesta.validation){
		        		$('input#'+frm_resultado).prop('disabled', true);
		        		$('input#'+frm_resultado).removeClass();
		        		$('input#'+frm_resultado).addClass('valid');
		        		$('span#'+campo_respuesta).removeClass();
		        		$('span#'+campo_respuesta).addClass('green-text text-darken-2');
		        		my_toast('<i class="fas fa-check"></i>&nbsp Resultado actualizado', 'blue darken-2', 3000);
		        	}else{
		        		$('input#'+frm_resultado).removeClass();
		        		$('input#'+frm_resultado).addClass('invalid');
		        		$('span#'+campo_respuesta).removeClass();
		        		$('span#'+campo_respuesta).addClass('red-text text-darken-2');
		        		my_toast('<i class="fas fa-times"></i>&nbsp Ha ocurrido un error', 'red darken-2', 3000);
		        	}
		        	$('span#'+campo_respuesta).html(respuesta.mensaje);
		        })
           		// xajax_cambiar_campos_resultados_fq_directo(campo_respuesta,valor, frm_resultado, resultado_analisis,  aux_id_ensayo_vs_muestra, aux_id_parametro);
       		}
		}
}
