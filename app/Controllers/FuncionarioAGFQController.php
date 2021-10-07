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
use App\Models\Parametros;
use Config\Services;



class FuncionarioAGFQController extends BaseController
{
    // --------------------------------------- Resultados ALimentos FQ
    public function index(){
        $validation = Services::validation();
        return view('funcionarios/resultados_aguas_fq',[
            'validation' => $validation,
            'validate' => true,
        ]);
    }

    public function buscar_muestra(){
        $rules = [
            'frm_anio_busca' => 'required',
            'frm_codigo_busca' => 'required',
        ];
        $messages = [
            'frm_anio_busca' => [
                'required' => 'Este campo es obligatorio.'
            ],
            'frm_codigo_busca' => [
                'required' => 'Este campo es obligatorio.'
            ]
        ];
        if($this->validate($rules, $messages)){
            $tipo_analisis = array(3, 4);
            $certificado = new Certificacion();
            $certificado = $certificado
                ->select('*')
                ->join('muestreo', 'certificacion.id_muestreo = muestreo.id_muestreo')
                ->join('muestreo_detalle', 'muestreo_detalle.id_muestra_detalle = certificacion.id_muestreo_detalle')
                ->join('producto', 'producto.id_producto = muestreo_detalle.id_producto')
                ->where([
                    'id_codigo_amc' => $this->request->getPost('frm_codigo_busca'),
                    'ano_codigo_amc' => $this->request->getPost('frm_anio_busca')
                ])
                ->whereIn('id_tipo_analisis', $tipo_analisis)
                ->get()->getResult();
            if(!empty($certificado[0]))
                $certificado = $certificado[0];
            $validation = Services::validation();
            $db = \Config\Database::connect();
            $parametros_array = [101, 86, 241, 237, 102, 234, 235, 236, 240, 244, 239, 238, 242, 245, 243,39,40];
            $otros = $db
                ->table('ensayo_vs_muestra')
                ->select('*')
                ->join('ensayo', 'ensayo_vs_muestra.id_ensayo = ensayo.id_ensayo')
                ->join('parametro', 'ensayo.id_parametro = parametro.id_parametro')
                ->where('ensayo_vs_muestra.id_muestra',$certificado->id_muestra_detalle)
                ->whereNotIn('ensayo.id_parametro', $parametros_array)
                ->where('parametro.par_estado', 'Activo')
                ->get()->getResult();
            return view('funcionarios/resultados_aguas_fq',[
                'certificado' => $certificado,
                'validation' => $validation,
                'otros' => $otros
            ]);
        }else
            return redirect()->to(base_url(['funcionario', 'resultados', 'alimentos']))->withInput();
    }

    public function cambiar_campos_resultados_fq(){
        $funcion = $this->request->getPost('funcion');
        switch ($funcion){
            case 'cambiar_campos':
                $campo_salida           = $this->request->getPost('campo_respuesta');
                $valor                  = $this->request->getPost('valor');
                $nombre_campo_frm       = $this->request->getPost('frm_resultado');
                $nombre_campo_bd        = $this->request->getPost('resultado_analisis');
                $id_ensayo_vs_muestra   = $this->request->getPost('aux_id_ensayo_vs_muestra');
                $id_parametro           = $this->request->getPost('aux_id_parametro');
                $validation = false;
                if(is_numeric(str_replace(",","",$valor))){
                    almacena_campo_fq($id_ensayo_vs_muestra, $valor, $id_parametro, $nombre_campo_frm, $nombre_campo_bd);
                    $mensaje_resultado = comprueba_calcular_resultado_agua($id_ensayo_vs_muestra,  $id_parametro);
                    if (
                        ($nombre_campo_bd == 'result_1' && $valor == 0 && ($id_parametro == 39 || $id_parametro == 40)) ||
                        ($nombre_campo_bd == 'result_1' && $valor == 0 && $id_parametro == 234) ||
                        ($nombre_campo_bd == 'result_1' && $valor == 0 && $id_parametro == 235) ||
                        ($nombre_campo_bd == 'result_1' && $valor == 0 && $id_parametro == 236) ||
                        ($nombre_campo_bd == 'result_6' && $valor == 0 && $id_parametro == 240)
                    ) {
                        $mensaje = 'Proceso incompleto <br> Motivo: division por 0';
                    }else{
                        $mensaje = 'Ok';
                    }
                    $validation = true;
                }else{
                    $mensaje = 'Valor no permitido';
                }
                $data = [
                    'validation' => $validation,
                    'mensaje' => $mensaje,
                    'mensaje_resultado' => $mensaje_resultado,
                ];
            break;
            case 'cambiar_campos_resultados_fq_directo':
                $campo_salida           = $this->request->getPost('campo_respuesta');
                $valor                  = $this->request->getPost('valor');
                $nombre_campo_frm       = $this->request->getPost('frm_resultado');
                $nombre_campo_bd        = $this->request->getPost('resultado_analisis');
                $id_ensayo_vs_muestra   = $this->request->getPost('aux_id_ensayo_vs_muestra');
                $id_parametro           = $this->request->getPost('aux_id_parametro');
                $validation = false;
                if (is_numeric (str_replace(",","",$valor))) {
                    almacena_primer_campo($id_ensayo_vs_muestra, $valor);
                    almacena_campo_resultado($id_ensayo_vs_muestra, $valor);
                    $validation = true;
                    $mensaje = 'Ok';
                }else{
                    $mensaje = 'Valor no permitido';
                }         
                $data = [
                    'validation' => $validation,
                    'mensaje' => $mensaje,
                ];
                break;
            case 'calcula_IRCA';
                $id_ensayo_vs_muestra = $this->request->getPost('aux_id_ensayo_vs_muestra');
                $mensaje = calcula_IRCA($id_ensayo_vs_muestra);
                $data = ['mensaje' => $mensaje];
                break;
        }
        return json_encode($data);
    }
    
}