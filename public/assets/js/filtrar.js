$(document).ready(function () {
        $('#filtrar').click(function (e) {
            var form        = $('#form_filtrar');
            var form_filtro = $('#form_filtrar').serialize();
            var form_pagina = $('#form_pagina').serialize();
            var data        = form_filtro+'&'+form_pagina+"&pagina=0";
            var url         = form.attr('action');
            var total       = $('#pagina').data('total');
            Swal.fire({
              html:'<div class="card-content redo"><div class="preloader-wrapper big active"><div class="spinner-layer spinner-blue-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div></div>',
              showConfirmButton: false,
              allowOutsideClick: false,
            });
            $.post(url, data, function(resultado) {
                var resultado = JSON.parse(resultado);
                console.log(resultado);
                $('.responsive-table').remove();
                $('#tabla').append(resultado['certificados']);
                $('#r_total').text('Mostrando: '+resultado['total_2']+' de '+resultado['total']);
                if(resultado['count'] == 0){
                    $('.paginator').fadeOut();
                    $('.btn.download').fadeOut();
                }else{
                    $('.pagina_text').html('Pagina '+1);
                    for (var i = 0; i < resultado['count']; i++) {
                        $('.enviar.finish').attr('data-page', i );
                    }
                    $('.enviar.next').attr('data-page', 1);
                    if ( $('.enviar.finish').attr('data-page') == resultado['pagina'])
                        $('.enviar.next').fadeOut();
                    else
                        $('.enviar.next').fadeIn();

                    if( (parseInt(resultado['pagina'])+1) >= $('.enviar.finish').attr('data-page')){
                        $('.enviar.finish').fadeOut();
                    }else
                        $('.enviar.finish').fadeIn();

                    $('.paginator').fadeIn();
                    $('.btn.download').fadeIn();
                    $('.enviar.back').fadeOut();
                    $('.enviar.start').fadeOut();
                    $('.tooltipped').tooltip();
                }
                Swal.close();
            }).fail(function() {
                Swal.close();
                $('.responsive-table').fadeIn();
            })
        });
        $('.enviar').click(function (e) {
            e.preventDefault();
            var form_filtro = $('#form_filtrar').serialize();
            var form_pagina = $('#form_pagina').serialize();
            var url         = $('#form_pagina').attr('action');
            var data        = form_filtro+'&'+form_pagina+"&pagina="+$(this).attr('data-page');
            Swal.fire({
              html:'<div class="card-content redo"><div class="preloader-wrapper big active"><div class="spinner-layer spinner-blue-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div></div><div class="card-action"></div>',
              showConfirmButton: false,
              allowOutsideClick: false,
            });
            $.post(url, data, function(resultado){
                var resultado = JSON.parse(resultado);
                $('.responsive-table').remove();
                $('#tabla').append(resultado['certificados']);
                $('#r_total').text('Mostrando: '+resultado['total_2']+' de '+resultado['total']);
                if(resultado['count'] == 0){
                    $('.paginator').fadeOut();
                    $('.btn.download').fadeOut();
                }else{
                    $('.pagina_text').html('Pagina '+(parseInt(resultado['pagina'])+parseInt(1)));
                    $('.enviar.next').attr('data-page', (parseInt(resultado['pagina'])+parseInt(1)));
                    $('.enviar.back').attr('data-page', (parseInt(resultado['pagina'])-parseInt(1)));
                    if( parseInt(resultado['pagina']) < 2)
                        $('.enviar.start').fadeOut();
                    else
                        $('.enviar.start').fadeIn();

                    if( parseInt(resultado['pagina']) < 1)
                        $('.enviar.back').fadeOut();
                    else
                        $('.enviar.back').fadeIn();

                    if ( $('.enviar.finish').attr('data-page') <= parseInt(resultado['pagina']))
                        $('.enviar.next').fadeOut();
                    else
                        $('.enviar.next').fadeIn();

                    if( (parseInt(resultado['pagina'])+1) >= $('.enviar.finish').attr('data-page'))
                        $('.enviar.finish').fadeOut();
                    else
                        $('.enviar.finish').fadeIn();

                    $('.tooltipped').tooltip();
                }
                Swal.close();
            });
        });
        // var id_selct = $('.paginador_select .select-wrapper .select-dropdown.dropdown-trigger').data('target')
        $('.paginador_select .dropdown-content.select-dropdown li').click(function(e) {
            var form_filtro = $('#form_filtrar').serialize();
            var form_pagina = $('#form_pagina').serialize();
            var data        = form_filtro+'&'+form_pagina+"&pagina=0";
            var url         = $('#form_pagina').attr('action');
            Swal.fire({
                html:'<div class="card-content redo"><div class="preloader-wrapper big active"><div class="spinner-layer spinner-blue-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div></div><div class="card-action"></div>',
                showConfirmButton: false,
                allowOutsideClick: false,
            })
            $.post(url, data, function(resultado){
                var resultado = JSON.parse(resultado);
                $('.responsive-table').remove();
                $('#tabla').append(resultado['certificados']);
                $('#r_total').text('Mostrando: '+resultado['total_2']+' de '+resultado['total']);
                if(resultado['count'] == 0){
                    $('.paginator').fadeOut();
                    $('.btn.download').fadeOut();
                }else{
                    $('.pagina_text').html('Pagina '+1);
                    for (var i = 0; i < resultado['count']; i++) {
                        $('.enviar.finish').attr('data-page', i );
                    }

                    if ( $('.enviar.finish').attr('data-page') <= resultado['pagina'])
                        $('.enviar.next').fadeOut();
                    else
                        $('.enviar.next').fadeIn();

                    if( (parseInt(resultado['pagina'])+1) >= $('.enviar.finish').attr('data-page')){
                        $('.enviar.finish').fadeOut();
                    }else
                        $('.enviar.finish').fadeIn();
                    $('.enviar.next').attr('data-page', 1);
                    $('.enviar.back').fadeOut();
                    $('.enviar.start').fadeOut();
                    $('.tooltipped').tooltip();
                }
                Swal.close();
            });
        });
        $('.reset_btn').click(function () {
           $('#form_pagina')[0].reset();
        });
    });