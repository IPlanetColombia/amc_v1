<?php

namespace App\Controllers;
use App\Models\Cliente;
use App\Models\Certificacion;
use App\Models\Funcionario;
use App\Models\Muestreo;
use App\Models\MuestreoDetalle;
use App\Models\Analisis;
use App\Models\Producto;
use App\Models\Ensayo;
use Config\Services;



class FuncionarioCController extends BaseController
{
    public function index(){
        $funcion = $this->request->getPost('funcion');
        switch ($funcion){
            case 'certificado_facturacion':
                $certificado_nro = $this->request->getPost('certificado_nro');
                $response = certificado_facturacion($certificado_nro);
                break;
            case 'certificado_autorizacion':
                $certificado_nro = $this->request->getPost('certificado_nro');
                $response = certificado_autorizacion($certificado_nro);
                break;
            case 'lista_resultados':
                $certificado_nro = $this->request->getPost('certificado_nro');
                $que_mostrar = $this->request->getPost('que_mostrar');
                $response = lista_resultados($certificado_nro, $que_mostrar);
                break;
            case 'cambiar_campos':
                $type = $this->request->getPost('type');
                $campo_salida = $this->request->getPost('id_campo');
                $valor = $this->request->getPost('valor');
                $nombre_campo_frm = $this->request->getPost('nombre_campo_frm');
                $nombre_campo_bd = $this->request->getPost('nombre_campo_bd');
                $tabla_update = $this->request->getPost('tabla_update');
                $id_operacion = $this->request->getPost('id_operacion');
                $response = cambiar_campos($type, $campo_salida, $valor, $nombre_campo_frm, $nombre_campo_bd, $tabla_update, $id_operacion);
                break;
            case 'muestra_mensaje':
                $id_mensaje = $this->request->getPost('id_mensaje');
                $tabla = $this->request->getPost('tabla');
                $response = muestra_mensaje($id_mensaje, $tabla);
                break;
            case 'guardar':
                $form = $this->request->getPost();
                $certificado = procesar_registro_fetch('certificacion', 'certificado_nro', $form['frm_id_certificado']);
                $certificado = $certificado[0];
                $response = guardar_certificado($form, $certificado);
                $aux_mensaje = '';
                if($form['frm_id_procedencia'] == 2){
                    if($certificado->cer_fecha_publicacion > '0000-00-00 00:00:00'){
                        $mensajes = procesar_registro_fetch('mensaje_resultado', 'id_mensaje', $form['frm_mensaje_resultado']);
                        $aux_mensaje = $mensajes[0]->mensaje_titulo;
                        if($certificado->cer_fecha_facturacion > '0000-00-00 00:00:00' ){
                            $aux_btn_i = "fad fa-usd-circle";
                            $aux_btn_c = 'cyan';
                            $aux_metodo = 3;
                        }else{
                            $aux_btn_i = "fad fa-thumbs-up";
                            $aux_btn_c = 'deep-orange';
                            $aux_metodo = 2;
                        }
                    }else{
                        $aux_btn_i = "fad fa-check-circle";
                        $aux_btn_c = 'green';
                        $aux_metodo = 1;
                    }
                    $aux_bttn .= '<button class="btn '.$aux_btn_c.' white-text" onClick="actualizar_informe(`'.$certificado->certificado_nro.'`, `'.$aux_metodo.'`, '.session('user')->usr_rol.', 1)"><i class="'.$aux_btn_i.'"></i></button>';
                    $aux_div = '#certificado_'.$form['frm_id_certificado'];
                }else{
                    $aux_bttn .= '<button class="btn green white-text" onClick="descargar_info(`'.$certificado->certificado_nro.'`, 0, `'.session('user')->usr_rol.'`, 0)"><i class="fad fa-check-circle"></i></button>';
                    $aux_div = '#pre_informe_'.$form['frm_id_certificado'];
                }
                $boton = [
                    'button' => $aux_bttn,
                    'div' => $aux_div
                ];
                $mensaje_resultado = [
                    'div' => '#div_resultado_'.$certificado->certificado_nro,
                    'mensaje' => $aux_mensaje
                ];
                $response = [
                    'mensaje' => $response,
                    'boton' => $boton,
                    'mensaje_resultado' => $mensaje_resultado
                ];
                break;
            case 'previsualizar':
                $form = $this->request->getPost();
                $response = previsualizar($form);
                $mpdf = new \Mpdf\Mpdf([
                    'mode' => 'utf-8',
                    'format' => 'Letter',
                    "margin_left" => 0,
                    "margin_right" => 0,
                    "margin_top" => 0,
                    "margin_bottom" => 0,
                    "margin_header" => 0,
                    "margin_footer" => 0
                ]);
                $salida = view('views_mpdf/preliminar',[
                    'db' => $response['db'],
                    'certificado' => $response['certificado'],
                    'cliente' => $response['cliente'],
                    'muestreo' => $response['muestreo'],
                    'aux_fecha_informe' => $response['aux_fecha_informe'],
                    'aux_mensaje' => $response['aux_mensaje'],
                    'fecha_analisis' => $response['fecha_analisis'],
                    'fila_detalle_para_tipo_muestreo' => $response['fila_detalle_para_tipo_muestreo'],
                    'plantilla' => $form['frm_plantilla'],
                    'form_entrada' => $form
                ]);
                $css  = file_get_contents('assets/css/styles-f.css');
                $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
                $mpdf->WriteHTML($salida, \Mpdf\HTMLParserMode::HTML_BODY);
                $this->response->setHeader('Content-Type', 'application/pdf');
                $mpdf->Output('arjun.pdf','I');
                break;
            case 'presentar_preinforme':
                $form = $this->request->getPost();
                $response = presentar_preinforme($form);
                $mpdf = new \Mpdf\Mpdf([
                    'mode' => 'utf-8',
                    'format' => 'Letter',
                    "margin_left" => 0,
                    "margin_right" => 0,
                    "margin_top" => 0,
                    "margin_bottom" => 0,
                    "margin_header" => 0,
                    "margin_footer" => 0
                ]);
                $salida = view('views_mpdf/preliminar',[
                    'db' => $response['db'],
                    'certificado' => $response['certificado'],
                    'cliente' => $response['cliente'],
                    'muestreo' => $response['muestreo'],
                    'aux_fecha_informe' => $response['aux_fecha_informe'],
                    'aux_mensaje' => $response['aux_mensaje'],
                    'fecha_analisis' => $response['fecha_analisis'],
                    'fila_detalle_para_tipo_muestreo' => $response['fila_detalle_para_tipo_muestreo'],
                    'plantilla' => $form['frm_plantilla'],
                    'form_entrada' => $form
                ]);
                if($form['envio'] == 2){
                    $certificado_aux = $response['certificado'];
                    $aux_mensaje = '';
                    if($form['frm_id_procedencia'] == 2){
                        if($certificado_aux->cer_fecha_publicacion > '0000-00-00 00:00:00'){
                            $mensajes = procesar_registro_fetch('mensaje_resultado', 'id_mensaje', $form['frm_mensaje_resultado']);
                            $aux_mensaje = $mensajes[0]->mensaje_titulo;
                            if($certificado_aux->cer_fecha_facturacion > '0000-00-00 00:00:00' ){
                                $aux_btn_i = "fad fa-usd-circle";
                                $aux_btn_c = 'cyan';
                                $aux_metodo = 3;
                            }else{
                                $aux_btn_i = "fad fa-thumbs-up";
                                $aux_btn_c = 'deep-orange';
                                $aux_metodo = 2;
                            }
                        }else{
                            $aux_btn_i = "fad fa-check-circle";
                            $aux_btn_c = 'green';
                            $aux_metodo = 1;
                        }
                        $aux_bttn .= '<button class="btn '.$aux_btn_c.' white-text" onClick="actualizar_informe(`'.$certificado_aux->certificado_nro.'`, `'.$aux_metodo.'`, '.session('user')->usr_rol.', 1)"><i class="'.$aux_btn_i.'"></i></button>';
                        $aux_div = '#certificado_'.$form['frm_id_certificado'];
                    }else{
                        $aux_bttn .= '<button class="btn green white-text" onClick="descargar_info(`'.$certificado_aux->certificado_nro.'`, 0, `'.session('user')->usr_rol.'`, 0)"><i class="fad fa-check-circle"></i></button>';
                        $aux_div = '#pre_informe_'.$form['frm_id_certificado'];
                    }
                    $boton = [
                        'button' => $aux_bttn,
                        'div' => $aux_div
                    ];
                    $mensaje_resultado = [
                        'div' => '#div_resultado_'.$certificado_aux->certificado_nro,
                        'mensaje' => $aux_mensaje
                    ];
                    $response = [
                        'boton' => $boton,
                        'mensaje' => $mensaje_resultado
                    ];
                }else{
                    $css  = file_get_contents('assets/css/styles-f.css');
                    $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
                    $mpdf->WriteHTML($salida, \Mpdf\HTMLParserMode::HTML_BODY);
                    $name = strtolower($response['aux_mensaje']);
                    $name = str_replace(' ', '_', $name);
                    $name = $response['certificado']->certificado_nro.'_'.$name.'.pdf';
                    $mpdf->Output($name,'D');
                }
                break;
            case 'presentar_preinforme2':
                $certificado_nro = $this->request->getPost('certificado_nro');
                $que_mostrar = $this->request->getPost('que_mostrar');
                $user_rol_id = $this->request->getPost('user_rol_id');
                $response = presentar_preinforme2($certificado_nro, $que_mostrar, $user_rol_id);
                break;
            case 'descargar':
                $certificado_nro = $this->request->getPost('certificado_nro');
                $que_mostrar = $this->request->getPost('que_mostrar');
                $user_rol_id = $this->request->getPost('user_rol_id');
                $response = presentar_preinforme2($certificado_nro, $que_mostrar, $user_rol_id, true);
                $css  = file_get_contents('assets/css/styles-f.css');
                $mpdf = new \Mpdf\Mpdf([
                    'mode' => 'utf-8',
                    'format' => 'Letter',
                    "margin_left" => 0,
                    "margin_right" => 0,
                    "margin_top" => 0,
                    "margin_bottom" => 0,
                    "margin_header" => 0,
                    "margin_footer" => 0
                ]);
                $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
                // return $response;
                $mpdf->WriteHTML($response, \Mpdf\HTMLParserMode::HTML_BODY);
                // $this->response->setHeader('Content-Type', 'application/pdf');
                // $mpdf->Output('arjun.pdf','I');
                $name = $que_mostrar == 0 ? $certificado_nro.'-PRELIMINAR.pdf':$certificado_nro.'-REPORTE_DE_ENSAYO_3.pdf';
                $name = strtolower($name);
                $mpdf->Output($name,'D');
                break;
            default:
                $response = 'Funcion no definida';
                break;
        }
        return json_encode(['data' => $response]);
    }
}