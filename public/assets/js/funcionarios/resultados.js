$(function(){
});
function js_muestra_tip(id_muestreo, codigo_amc, parametro, codigo,nombre_producto, mensaje=0){
    // $('.mensaje_'+id_muestreo+' .row').remove();
    var mensaje = (mensaje)?'<b>Rango:</b> '+mensaje:'';
    var result =
        '<div class="row">'+
            '<div class="col s12 l6"><b>Codigo AMC:</b> '+codigo_amc+'</div>'+
            '<div class="col s12 l6"><b>Parametro:</b> '+parametro+'</div>'+
            '<div class="col s12 l6"><b>Producto:</b> '+nombre_producto+'</div>'+
            '<div class="col s12 l6"><b>Identificación:</b> '+codigo+'</div>'+
            '<div class="col s12 l12">'+mensaje+'</div>'+
        '</div>'+
        '<hr>';
    $('.mensaje_'+id_muestreo).html(result);
}

function js_cambiar_campos(campo_respuesta, valor, frm_resultado, resultado_analisis,  aux_id_ensayo_vs_muestra, id_tecnica, id_tipo_analisis, mohos_levaduras=0){
    if(valor){
        my_toast('<i class="fas fa-spinner fa-spin"></i>&nbsp&nbsp Actualizando resultado', 'blue-grey darken-2', 3000);
        var data = new URLSearchParams({
            campo_respuesta: campo_respuesta,
            valor: valor,
            frm_resultado: frm_resultado,
            resultado_analisis: resultado_analisis,
            aux_id_ensayo_vs_muestra: aux_id_ensayo_vs_muestra,
            id_tecnica: id_tecnica,
            id_tipo_analisis: id_tipo_analisis,
            mohos_levaduras: mohos_levaduras,
        });
        var url = $('#form_resultado').attr('action');
        var result = proceso_fetch(url, data.toString());
        result.then(respuesta => {
            console.log(respuesta);
            if(respuesta.hide){
                $('#'+respuesta.campo_frm).prop('disabled', true);
                my_toast('<i class="fas fa-check"></i>&nbsp&nbsp Resultado actualizado', 'blue darken-2', 3000);
            }
                var campo_mensaje = respuesta.campo_mensajes;
                $('#'+respuesta.campo_frm).removeClass();
                $('#'+respuesta.campo_frm).addClass(respuesta.style);

                var campo_respuesta = respuesta.campo_respuesta;
                $('#'+campo_respuesta).html(respuesta[campo_respuesta]);
                $('#'+campo_mensaje).html(respuesta[campo_mensaje]);

        });
    }
}