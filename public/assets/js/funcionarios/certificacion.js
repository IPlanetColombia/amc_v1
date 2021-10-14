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
function js_enviar(aux, certificado_nro){
    if($('#frm_id_forma').val() == 'forma_muestra_preinforme'){
        if($('#frm_mensaje_resultado').val() && $('#frm_mensaje_observacion').val() && $('#frm_mensaje_firma').val()){
            switch(aux){
                case 0:
                    $('#funcion').val('presentar_preinforme');
                    my_toast('<i class="fas fa-spinner fa-spin"></i>&nbsp Actualizando datos', 'blue-grey darken-2', 30000);
                    var data = new URLSearchParams({
                        frm_id_certificado: certificado_nro,
                        envio: 2,
                        frm_id_procedencia: $('#frm_id_procedencia').val(),
                        funcion: 'presentar_preinforme'
                    });
                    var form = $('#form-certificados');
                    var result = proceso_fetch(form.attr('action'), data.toString());
                    result.then(respuesta => {
                        $(respuesta.data.boton.div).html(respuesta.data.boton.button);
                        descargar();
                    });
                    break;
                case 1:
                    $('#funcion').val('guardar');
                    my_toast('<i class="fas fa-spinner fa-spin"></i>&nbsp Actualizando datos', 'blue-grey darken-2', 30000);
                    var form = $('#form-certificados');
                    var result = proceso_fetch(form.attr('action'), form.serialize());
                    result.then(respuesta => {
                        my_toast(respuesta.data.mensaje.html, respuesta.data.mensaje.class, 3000);
                        $(respuesta.data.boton.div).html(respuesta.data.boton.button);
                    });
                    break;
                case 2:
                    $('#funcion').val('previsualizar');
                    $('#form-certificados').attr('target','_blank');
                    descargar();
                    break;
            }
        }else{
            if($('#frm_mensaje_resultado').val() === '' && $('#frm_mensaje_observacion').val() === ''){
                M.toast({html: '<i class="fas fa-times"></i>&nbsp Debe Seleccionar resultado y observacion', classes: 'red darken-2'});
            }
            else{
                if($('#frm_mensaje_resultado').val() === '')
                    // M.toast({html: '<i class="fas fa-times"></i>&nbsp Debe Seleccionar resultado', classes: 'red darken-2'});
                    my_toast('<i class="fas fa-times"></i>&nbsp Debe Seleccionar resultado', 'red darken-2', 3000);
                else if($('#frm_mensaje_observacion').val() === '')
                    // M.toast({html: '<i class="fas fa-times"></i>&nbsp Debe Seleccionar observacion', classes: 'red darken-2'});
                    my_toast('<i class="fas fa-times"></i>&nbsp Debe Seleccionar observacion', 'red darken-2', 3000);
                else if($('#frm_mensaje_firma').val() === '')
                    // M.toast({html: '<i class="fas fa-times"></i>&nbsp Debe Seleccionar la firma', classes: 'red darken-2'});
                    my_toast('<i class="fas fa-times"></i>&nbsp Debe Seleccionar la firma', 'red darken-2', 3000);
            }
        }
    }
}
function descargar(){
    my_toast('<i class="fas fa-check"></i>&nbsp Generando reporte', 'blue darken-2', 3000);
    $('#form-certificados').submit();
}
function descargar_info(certificado_nro, tipo_documento, rol){
    var form = `
        <div id="div-descargar">
            <form id="descargar-info" method="POST" action="`+$('#form-certificados').attr('action')+`">
                <input type="hidden" name="certificado_nro" value="`+certificado_nro+`" />
                <input type="hidden" name="que_mostrar" value="`+tipo_documento+`" />
                <input type="hidden" name="user_rol_id" value="`+rol+`" />
                <input type="hidden" name="funcion" value="descargar" />
            </form>
        </div>`;
    $('#form-certificados').append(form);
    $('#descargar-info').submit();
    $('#div-descargar').remove();
}
function certificado_facturacion(certificado_nro){
    Swal.fire({
        title: 'Ya realizo factura ?',
        icon: 'warning',
        position: 'top-end',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si'
    }).then((result) => {
        if (result.isConfirmed) {
            var url = $('form#form-certificados').attr('action');
            var data = new URLSearchParams({
                certificado_nro: certificado_nro,
                funcion: 'certificado_facturacion'
            });
            var result = proceso_fetch(url, data.toString());
            result.then(respuesta => {
                Swal.fire({
                    text:respuesta.data.html,
                    position:'top-end',
                    icon: respuesta.data.icon,
                });
                $('#certificado_'+certificado_nro).html(`
                    <button class="btn cyan white-text" onClick="actualizar_informe(`+certificado_nro+`, `+3+`)"><i class="fad fa-usd-circle"></i></button>`);
            })
        }
    })
}
function certificado_autorizacion(certificado_nro){
    Swal.fire({
        title: 'Autoriza publicación?',
        icon: 'warning',
        position: 'top-end',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si'
    }).then((result) => {
        if (result.isConfirmed) {
            var url = $('form#form-certificados').attr('action');
            var data = new URLSearchParams({
                certificado_nro: certificado_nro,
                funcion: 'certificado_autorizacion'
            });
            var result = proceso_fetch(url, data.toString());
            result.then(respuesta => {
                Swal.fire({
                    html:respuesta.data.html,
                    position:'top-end',
                    icon: respuesta.data.icon,
                });
                $('#certificado_'+certificado_nro).html(`
                    <button class="btn deep-orange white-text" onClick="actualizar_informe(`+certificado_nro+`, `+2+`)"><i class="fad fa-thumbs-up"></i></button>`)
            })
        }
    })
}
function actualizar_informe(certificado_nro, metodo, rol, tipo_documento){
    if(metodo == 3){
        my_toast('<i class="fas fa-check"></i>&nbsp Generando reporte', 'blue darken-2', 3000);
        descargar_info(certificado_nro, tipo_documento, rol);
    }else{
        if(metodo == 1){
            var aux_button = `<i class="fad fa-thumbs-up"></i>&nbsp Autorizar publicaci&oacute;n`;
            var clase = '#ff5722';
        }else if(metodo == 2){
            var aux_button = `<i class="fas fa-dollar-sign"></i>&nbsp Indicar que ya se factur&oacute;`;
            var clase = '#00bcd4';
        }
        Swal.fire({
            position: 'top-end',
            title: 'Certificado '+certificado_nro,
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonColor: '#b71c1c',
            cancelButtonColor: '#263238',
            denyButtonColor: clase,
            confirmButtonText: '<i class="fad fa-file-pdf"></i>&nbsp Descargar informe',
            denyButtonText: aux_button,
            cancelButtonText: '<i class="fad fa-times-circle"></i>&nbsp Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                descargar_info(certificado_nro, tipo_documento, rol);
            } else if (result.isDenied) {
                if(metodo == 1){
                    certificado_autorizacion(certificado_nro);
                }else{
                    certificado_facturacion(certificado_nro);
                }
            }
        })

    }
}