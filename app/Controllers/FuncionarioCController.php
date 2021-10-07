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
                $css  = file_get_contents('assets/css/styles-f.css');
                $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
                $mpdf->WriteHTML($salida, \Mpdf\HTMLParserMode::HTML_BODY);
                // $this->response->setHeader('Content-Type', 'application/pdf');
                // $mpdf->Output('arjun.pdf','I');
                $name = $response['aux_mensaje'].'_'.$response['certificado']->certificado_nro.'.pdf';
                $mpdf->Output($name,'D');
                break;
            default:
                $response = 'Funcion no definida';
                break;
        }
        return json_encode(['data' => $response]);
    }
}