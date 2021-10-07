function js_mostrar_detalle(
        campo_muestra, campo_oculta, certificado_nro,
        que_mostrar, bandera_mostrar_formulario_o_informe, rol = ''){    
    if(certificado_nro){
        my_toast('<i class="fas fa-spinner fa-spin"></i>&nbsp Cargando datos', 'blue-grey darken-2', 30000);
        var url = $('form#form-certificados').attr('action');
        if(bandera_mostrar_formulario_o_informe=='php_lista_resultados'){ 
            var data = new URLSearchParams({
                certificado_nro: certificado_nro,
                que_mostrar: que_mostrar,
                funcion: 'lista_resultados'
            });
            // xajax_php_lista_resultados(certificado_nro,que_mostrar );
        }else{
            var data = new URLSearchParams({
                certificado_nro: certificado_nro,
                que_mostrar: que_mostrar,
                user_rol_id: rol,
                funcion: 'presentar_preinforme2'
            });
            // xajax_php_presentar_preinforme2(certificado_nro,que_mostrar, <?=$user_rol_id;?> );//que_mostrar=id_certificado a presentar, tipo si es preliminar(0) o informe(1)
        }
        var result = proceso_fetch(url, data.toString());
        result.then(respuesta => {
            $('.card-content.card-detalle .content-info').html(respuesta.data);
            $('.card-content.'+campo_oculta).fadeOut();
            $('.card-content.'+campo_muestra).fadeIn();
            my_toast('<i class="fas fa-check"></i>&nbsp Datos cargados', 'blue darken-2', 3000);
            $('table select').formSelect();
            $('.table select').formSelect();
        }).catch(error => {
            my_toast('<i class="fas fa-times"></i>&nbsp Error carga', 'red darken-2', 3000);
        })
    }else{
        $('.card-content.card-detalle .content-info').html('');
        $('.card-content.'+campo_oculta).fadeOut();
        $('.card-content.'+campo_muestra).fadeIn();
    }
}
function editar_campos(type, id_campo, valor_campo, nombre_campo_frm, nombre_campo_bd, tabla_update, id_operacion){
    var text = '<input type="'+type+'" name="'+nombre_campo_frm+'"'+
            'id="'+ nombre_campo_frm + '"'+
            'value="'+valor_campo+'"'+
            'onblur=" cambiar_campos(`'+type+'`, `'+id_campo +'`,this.value, `'+nombre_campo_frm+'`, `'+nombre_campo_bd+'`, `'+ tabla_update +'`, `'+id_operacion+'`)";/>';
    $('#'+id_campo).html(text);
}
function cambiar_campos(type, id_campo, valor, nombre_campo_frm, nombre_campo_bd, tabla_update, id_operacion){
    var url = $('form#form-certificados').attr('action');
    if(valor.length > 0){ 
        var data = new URLSearchParams({
            type: type,
            id_campo: id_campo,
            valor: valor,
            nombre_campo_frm: nombre_campo_frm,
            nombre_campo_bd: nombre_campo_bd,
            tabla_update: tabla_update,
            id_operacion: id_operacion,
            funcion: 'cambiar_campos'
        });
        var result = proceso_fetch(url, data.toString());
        result.then(respuesta => {
            $('#'+respuesta.data[1]).html(respuesta.data[0]);
        })
    }
}
function editar_campos_redondeo(id_campo, valor_campo,parametro, nombre_campo_frm, nombre_campo_bd, tabla_update, id_operacion, conteo){
    var inputs = `
        <hr>
        <div class="row center-align">
            <br>
            <p class="center-align"><b>Seleccione redondeo para : `+valor_campo+`
            <br><small>Parametro: `+parametro+`</small></b></p>
            <br>
            <div class="col s12 m6 l3">
                <label>
                    <input class="with-gap" name="frm_tipo_entero" value="0" type="radio"
                    onclick='redondear_y_enviar(
                        "`+id_campo+`",
                        "`+valor_campo+`" ,
                        "`+nombre_campo_frm+`" ,
                        "`+nombre_campo_bd+`" ,
                        "`+tabla_update+`" ,
                        "`+id_operacion+`",
                        this.value)'/>
                    <span>Entero</span>
                </label>
            </div>
            <div class="col s12 m6 l3">
                <label>
                    <input class="with-gap" name="frm_tipo_entero" value="1" type="radio"
                    onclick='redondear_y_enviar(
                        "`+id_campo+`",
                        "`+valor_campo+`" ,
                        "`+nombre_campo_frm+`" ,
                        "`+nombre_campo_bd+`" ,
                        "`+tabla_update+`" ,
                        "`+id_operacion+`",
                        this.value)'/>
                    <span>1 decimal</span>
                </label>
            </div>
            <div class="col s12 m6 l3">
                <label>
                    <input class="with-gap" name="frm_tipo_entero" value="2" type="radio"
                    onclick='redondear_y_enviar(
                        "`+id_campo+`",
                        "`+valor_campo+`" ,
                        "`+nombre_campo_frm+`" ,
                        "`+nombre_campo_bd+`" ,
                        "`+tabla_update+`" ,
                        "`+id_operacion+`",
                        this.value)'/>
                    <span>2 decimales</span>
                </label>
            </div>
            <div class="col s12 m6 l3">
                <label>
                    <input class="with-gap" name="frm_tipo_entero" value="3" type="radio"
                    onclick='redondear_y_enviar(
                        "`+id_campo+`",
                        "`+valor_campo+`" ,
                        "`+nombre_campo_frm+`" ,
                        "`+nombre_campo_bd+`" ,
                        "`+tabla_update+`" ,
                        "`+id_operacion+`",
                        this.value)'/>
                    <span>3 decimales</span>
                </label>
            </div>
        </div>
    `;
    $('#campo_resultado_redondeo').html( inputs );
}
function mathRound2 (num, decimales) {
 
  var exponente = Math.pow(10, decimales);
  return (num >= 0 || -1) * Math.round(Math.abs(num) * exponente) / exponente;
}
function redondear_y_enviar(id_campo, valor_campo, nombre_campo_frm, nombre_campo_bd, tabla_update, id_operacion, otro){
    //alert('Antes-->'+valor_campo+' - '+otro);  
    var numero = mathRound2(valor_campo.replace(",","."), otro);
    //alert('despues -->'+numero+' - '+otro); 
    
    if (isNaN(numero)){
        my_toast('<i class="fas fa-times"></i>&nbsp No permito el cambio '+numero, 'red darken-2', 3000);
    } else {
        numero =numero.toString();
        numero =numero.replace(".",",");
        // alert('No NAn-->'+numero);  
        var url = $('form#form-certificados').attr('action');
        var data = new URLSearchParams({
            type: 'text',
            id_campo: id_campo,
            valor: numero,
            nombre_campo_frm: nombre_campo_frm,
            nombre_campo_bd: nombre_campo_bd,
            tabla_update: tabla_update,
            id_operacion: id_operacion,
            funcion: 'cambiar_campos'
        });
        var result = proceso_fetch(url, data.toString());
        result.then(respuesta => {
            $('#'+respuesta.data[1]).html(respuesta.data[0]);
        })
        // xajax_cambiar_campos(id_campo, numero, nombre_campo_frm, nombre_campo_bd, tabla_update, id_operacion);
    }
}
function muestra_mensaje(id_mensaje, tabla){
    var url = $('form#form-certificados').attr('action');
    var data = new URLSearchParams({
        id_mensaje: id_mensaje,
        tabla: tabla,
        funcion: 'muestra_mensaje'
    });
    var result = proceso_fetch(url, data.toString());
    result.then(respuesta => {
        $('#campo_'+respuesta.data.tabla).html(respuesta.data.mensaje);
    })
}
function js_enviar(){
    if($('#frm_id_forma').val() == 'forma_muestra_preinforme'){
            $('#form-certificados').validate({
                submitHandler: function(form){
                    if($('#frm_mensaje_resultado').val() && $('#frm_mensaje_observacion').val() && $('#frm_mensaje_firma').val()){
                        $('#funcion').val('presentar_preinforme');
                        my_toast('<i class="fas fa-check"></i>&nbsp Generando reporte', 'blue darken-2', 3000);
                        form.submit();
                        js_mostrar_detalle(`card-table`, `card-detalle`);
                    }else{
                        if($('#frm_mensaje_resultado').val() === '' && $('#frm_mensaje_observacion').val() === '')
                            my_toast('<i class="fas fa-times"></i>&nbsp Debe Seleccionar resultado y observacion', 'red darken-2', 3000);
                        else{
                            if($('#frm_mensaje_resultado').val() === '')
                                my_toast('<i class="fas fa-times"></i>&nbsp Debe Seleccionar resultado', 'red darken-2', 3000);
                            else if($('#frm_mensaje_observacion').val() === '')
                                my_toast('<i class="fas fa-times"></i>&nbsp Debe Seleccionar observacion', 'red darken-2', 3000);
                            else if($('#frm_mensaje_firma').val() === '')
                                my_toast('<i class="fas fa-times"></i>&nbsp Debe Seleccionar la firma', 'red darken-2', 3000);
                        }
                    }
                }
            });
    }
}
function presentar_preinforme(url, data){
    // console.log(data);
    var result = proceso_fetch(url, data.toString());
    result.then(respuesta => {
        console.log(respuesta);
    });
}