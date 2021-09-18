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



class FuncionarioRMController extends BaseController
{
    // --------------------------------------- Resultados
    public function resultados(){
        $analisis = new Analisis();
        $analisis = $analisis->get()->getResult(); 
        return view('funcionarios/resultados',[
            'analisis' => $analisis,
            'filtros' => '',
        ]);
    }

    public function ingreso_muestras(){
        $codigo_amc     = $this->request->getPost('frm_codigo_busca');
        $dia_listar     = $this->request->getPost('frm_dia_listar');
        $tipo_analisis  = $this->request->getPost('frm_tipo_analisis');
        $bandera        = false;
        $certificados   = new Certificacion();
        $analisis       = new Analisis();
        $analisis       = $analisis->get()->getResult(); 
        $fecha = date('Y-m-d H:i:s');
        $nuevafecha0 = strtotime ( '-6 day' , strtotime ( $fecha ) ) ;
        $nuevafecha0 = date ( 'Y-m-d H:i:s' , $nuevafecha0 );
        $nuevafecha1 = strtotime ( '-2 day' , strtotime ( $fecha ) ) ;
        $nuevafecha1 = date ( 'Y-m-d H:i:s' , $nuevafecha1 );
        $certificados->select('*')
            ->join('muestreo', 'certificacion.id_muestreo = muestreo.id_muestreo')
            ->join('muestreo_detalle', 'muestreo_detalle.id_muestra_detalle = certificacion.id_muestreo_detalle')
            ->join('producto', 'producto.id_producto = muestreo_detalle.id_producto');
        if(!empty($codigo_amc)){
            $certificados->where(['id_codigo_amc' => $codigo_amc]);
        }else{
            $certificados->where(['mue_estado' => '1']);
            if(!empty($dia_listar)){
                $nuevafecha0 = strtotime ( '-'.$dia_listar.' day' , strtotime ( $fecha ) ) ;
                $nuevafecha0 = date ( 'Y-m-d' , $nuevafecha0 );
                $nuevafecha1 =0;
                $certificados->where([
                    'mue_fecha_muestreo >=' => $nuevafecha0.' 00:00:00',
                    'mue_fecha_muestreo <=' => $nuevafecha0.' 23:59:59'
                ]);
            }else{
                $certificados->where([
                    'mue_fecha_muestreo >=' => $nuevafecha0,
                    'mue_fecha_muestreo <=' => $nuevafecha1
                ]);
            }
            if(strlen($tipo_analisis)==1){
                list($par1) = str_split($tipo_analisis);
                $tipo_analisis = array($par1);
            }elseif(strlen($tipo_analisis)==2){
                list($par1, $par2) = str_split($tipo_analisis);
                $tipo_analisis = array($par1, $par2);
            }elseif(strlen($tipo_analisis)==3){
                list($par1, $par2, $par3) = str_split( $tipo_analisis);
                $tipo_analisis = array($par1, $par2, $par3);
            }elseif(strlen($tipo_analisis)==4){
                list($par1, $par2, $par3, $par4) = str_split( $tipo_analisis);
                $tipo_analisis = array($par1, $par2, $par3, $par4);
            }else{
                list($par1, $par2, $par3, $par4, $par5) = str_split( $tipo_analisis);
                $tipo_analisis = array($par1, $par2, $par3, $par4, $par5);
            }
            $certificados->whereIn('id_tipo_analisis', $tipo_analisis);
        }
        $filtros = $certificados->orderBy('id_certificacion', 'desc')->get()->getResult();
        // return var_dump($filtros);
        $db = \Config\Database::connect();
        $aux_where = 'p.id_producto in (select d.id_producto from certificacion c, muestreo m, muestreo_detalle d where c.id_muestreo = m.id_muestreo and c.id_muestreo_detalle = d.id_muestra_detalle';
        if(!empty($codigo_amc)){
            $aux_where .=" and d.id_codigo_amc=$codigo_amc";
        }else{
            $aux_where .=" and m.mue_estado =1 ";
            if(!empty($dia_listar)){
                $nuevafecha0 = strtotime ( '-'.$dia_listar.' day' , strtotime ( $fecha ) ) ;
                $nuevafecha0 = date ( 'Y-m-d' , $nuevafecha0 );
                $nuevafecha1 =0;
                $aux_where .=" and m.mue_fecha_muestreo between '".$nuevafecha0." 00:00:00' and '".$nuevafecha0." 23:59:59' ";
            }else{
                $aux_where .=" and m.mue_fecha_muestreo between '".$nuevafecha0."' and '".$nuevafecha1."' ";
            }
            $aux_analisis = implode(',', $tipo_analisis);
            $aux_where .=" and  d.id_tipo_analisis in ($aux_analisis)"; 
        }
        $aux_where .=")"; //echo $aux_;
        $parametros = $db->table('producto p')->select('distinct (select par_nombre from parametro where id_parametro= e.id_parametro) parametro, e.id_parametro, (select concat(t.id_tecnica,"-",t.nor_nombre) nombre from parametro p inner join tecnica t on p.id_tecnica=t.id_tecnica where id_parametro=  e.id_parametro ) tecnica, (select t.id_tecnica from parametro p inner join tecnica t on p.id_tecnica=t.id_tecnica where id_parametro=  e.id_parametro ) id_tecnica')->join('ensayo e', 'p.id_producto=e.id_producto')->where($aux_where)->orderBy('id_parametro', 'ASC')->get()->getResult();
        // foreach($filtros as $key => $muestra){
        //     foreach($parametros as $llave => $parametro){
        //         $ensayo = procesar_registro_fetch('ensayo', 'id_producto', $muestra->id_producto, 'id_parametro', $parametro->id_parametro);
        //         if(!empty($ensayo[0])){
        //             $ensayo_vs_muestra = procesar_registro_fetch('ensayo_vs_muestra', 'id_ensayo', $ensayo[0]->id_ensayo, 'id_muestra', $muestra->id_muestra_detalle);
        //             if(!empty($ensayo_vs_muestra[0])){
        //                 if($key == 3){
        //                     if(!empty($ensayo_vs_muestra[0]->resultado_mensaje)){
        //                         if(!empty($ensayo_vs_muestra[0]->resultado_mensaje <> '')){
        //                             $mensaje_respuesta = $ensayo_vs_muestra[0]->resultado_mensaje;
        //                             $id_ensayo_vs_muestra = $ensayo_vs_muestra[0]->id_ensayo_vs_muestra;
        //                             $algo = evalua_alerta($ensayo[0]->med_valor_min  ,$ensayo[0]->med_valor_max , $ensayo_vs_muestra[0]->resultado_mensaje, $muestra->id_tipo_analisis, $id_ensayo_vs_muestra,2);
        //                             var_dump([$algo, $ensayo_vs_muestra[0]]);
        //                         }
        //                     }    
        //                 }
        //             }
        //         }
        //     }
        //     if($key == 3)
        //         break;
        // }
        return view('funcionarios/resultados',[
            'analisis'      => $analisis,
            'muestras'      => $filtros,
            'parametros'    => $parametros
        ]);
    }

    public function ingreso_muestras_resultado(){
        $campo_salida           =   $this->request->getPost('campo_respuesta');    
        $valor                  =   $this->request->getPost('valor');    
        $nombre_campo_frm       =   $this->request->getPost('frm_resultado');
        $nombre_campo_bd        =   $this->request->getPost('resultado_analisis');    
        $id_tecnica             =   $this->request->getPost('id_tecnica');    
        $id_ensayo_vs_muestra   =   $this->request->getPost('aux_id_ensayo_vs_muestra'); 
        $rol                    =   session('user')->usr_rol;
        $id_tipo_analisis       =   $this->request->getPost('id_tipo_analisis');
        $result['hide'] = false;
        $fila_ensayo_vs_muestra = procesar_registro_fetch("ensayo_vs_muestra", "id_ensayo_vs_muestra", $id_ensayo_vs_muestra);
        $fila_ensayo = procesar_registro_fetch("ensayo", "id_ensayo", $fila_ensayo_vs_muestra[0]->id_ensayo);
        $fila_ensayo = $fila_ensayo[0];
        $aux_guarda_resultado_mensaje = 'SI';
        $no = '<input type="hidden" class="required">';
        $mensaje        = '';
        $mensaje_campo  ='';
        $mensaje_campo2 ='';
        if ($id_tecnica == 1){
            if (is_numeric ($valor)) {
                    if ($valor <= 14){
                        $mensaje = "<b>".$valor." d&iacute;as</b>";
                        //$respuesta->assign("campo_repuesta_".$id_ensayo_vs_muestra, "innerHTML", $mensaje);
                        $aux_guarda_resultado_mensaje='-IC1S-NMC2-';
                        almacena_primer_campo($id_ensayo_vs_muestra, $valor);
                        almacena_campo_resultado($id_ensayo_vs_muestra, $valor);
                        $aux_guarda_resultado_mensaje.=evalua_alerta($fila_ensayo->med_valor_min ,$fila_ensayo->med_valor_max, $valor, $id_tipo_analisis, $id_ensayo_vs_muestra);
                    }else{                        
                        $mensaje ="Valor no permitido xc".$no;
                        //$respuesta->assign("campo_repuesta_".$id_ensayo_vs_muestra, "innerHTML", $mensaje);
                        $aux_guarda_resultado_mensaje='-NMC2-';
                    }
            }else{
                    
                    $mensaje ="Valor no numerico".$no;
                    //$respuesta->assign("campo_repuesta_".$id_ensayo_vs_muestra, "innerHTML", $mensaje);
                    $aux_guarda_resultado_mensaje='-NMC2-';
            }           
        }elseif ($id_tecnica == 2 || $id_tecnica == 4 || $id_tecnica == 10){
            //se ajusta para que si el susario ingresa <10 <100 <1000 lo toma como cero para promediar
            $valor_signo    =   $valor;
            $valor          =   (preg_match("/</", $valor))?0:$valor;
        
            if($nombre_campo_bd=='resultado_analisis'){
                if(is_numeric ($valor)) {               
                    $aux_guarda_resultado_mensaje='-IC1S-';
                    almacena_primer_campo($id_ensayo_vs_muestra, $valor_signo);
                
                    if($rol==1 || $rol==2 || $rol==3){
                        $fila_resultado_1 = procesar_registro_fetch("ensayo_vs_muestra", "id_ensayo_vs_muestra", $id_ensayo_vs_muestra);
                    
                        if($fila_resultado_1[0]->resultado_analisis2){
                            if(preg_match("/</", $fila_resultado_1[0]->resultado_analisis2) && preg_match("/</", $valor_signo)){
                                $valor2=$valor_signo;
                            }elseif(preg_match("/</", $valor_signo)){
                                $valor2     = round(($fila_resultado_1[0]->resultado_analisis2+0)/2); 
                            }elseif(preg_match("/</", $fila_resultado_1[0]->resultado_analisis2)){
                                $valor2     = round((0+$valor)/2); 
                            }else{
                                $valor2     = round(($fila_resultado_1[0]->resultado_analisis2+$valor)/2); 
                            }                        
                            almacena_campo_resultado($id_ensayo_vs_muestra, $valor2);
                            $aux_guarda_resultado_mensaje.='-NMC2-';
                            $mensaje=$valor2;
                        }
                    }
                }else{
                    $mensaje ="Valor no numerico".$no;                
                    $aux_guarda_resultado_mensaje='-NMC2-';
                }
            }else{
                if(is_numeric ($valor)) {  
                    almacena_segundo_campo($id_ensayo_vs_muestra, $valor_signo);
                    $fila_resultado_1 = procesar_registro_fetch("ensayo_vs_muestra", "id_ensayo_vs_muestra", $id_ensayo_vs_muestra);
                    if(preg_match("/</", $fila_resultado_1[0]->resultado_analisis) && preg_match("/</", $valor_signo)){
                        $valor2=$valor_signo;
                    }elseif(preg_match("/</", $valor_signo)){
                        $valor2     = round(($fila_resultado_1[0]->resultado_analisis+0)/2); 
                    }elseif(preg_match("/</", $fila_resultado_1[0]->resultado_analisis)){
                        $valor2     = round((0+$valor)/2); 
                    }else{
                        $valor2     = round(($fila_resultado_1[0]->resultado_analisis+$valor)/2); 
                    }                        
                    almacena_campo_resultado($id_ensayo_vs_muestra, $valor2);                
                    $mensaje=$valor2;
                    $aux_guarda_resultado_mensaje='-IC2S-'.$valor2;
                    $aux_guarda_resultado_mensaje.='-NMC2-';
                    $aux_guarda_resultado_mensaje.=evalua_alerta($fila_ensayo->med_valor_min ,$fila_ensayo->med_valor_max, $valor2, $id_tipo_analisis, $id_ensayo_vs_muestra);
                }else{
                    $mensaje ="Valor no numerico".$no;               
                    $aux_guarda_resultado_mensaje='-NMC2-';
                }
            }
            $valor=$valor_signo;
        }elseif($id_tecnica == 5){// nmp     
            if($nombre_campo_bd=='resultado_analisis'){
                $cantidad = strlen($valor);
                if(is_numeric ($valor) && $cantidad == 3) {
                    $busca = procesar_registro_fetch("tabla_nmp", "combinacion", $valor);
                    if (isset($busca[0]->id)){
                        almacena_primer_campo($id_ensayo_vs_muestra, $valor);
                        $valor = $busca[0]->resultado;
                        $mensaje = "<b>".$valor."</b>";                    
                        $aux_guarda_resultado_mensaje='-IC1S-NMC2-';//                    
                        almacena_campo_resultado($id_ensayo_vs_muestra, $valor);
                        $aux_guarda_resultado_mensaje.=evalua_alerta($fila_ensayo->med_valor_min ,$fila_ensayo->med_valor_max, $valor, $id_tipo_analisis, $id_ensayo_vs_muestra);
                    }else{
                        $mensaje ="Rango no permitido.".$no;                   
                        $aux_guarda_resultado_mensaje='-IC1N-NMC2-';//-IC1S-NMC2-GCMS-
                    }
                }else{
                    $mensaje ="Valor no permitido, solo 3 n&uacute;meros. EJ 001 033".$no;                
                    $aux_guarda_resultado_mensaje='-NMC2-';
                }
            }
        }elseif($id_tecnica == 6 && $id_tipo_analisis==3){// sal tecnica 6 Filtraci�n por Membrana  y tipo de analisis FA     
            if($nombre_campo_bd=='resultado_analisis'){
                almacena_primer_campo($id_ensayo_vs_muestra, $valor);                    
                $mensaje = "<b>".$valor."</b>";                    
                $aux_guarda_resultado_mensaje='-IC1S-NMC2-';//                    
                almacena_campo_resultado($id_ensayo_vs_muestra, $valor);
                $aux_guarda_resultado_mensaje.=evalua_alerta($fila_ensayo->med_valor_min ,$fila_ensayo->med_valor_max, $valor, $id_tipo_analisis, $id_ensayo_vs_muestra);
            }
        }elseif($id_tecnica == 7){//  sal tecnica 7 ausencia presencia         
            if($nombre_campo_bd=='resultado_analisis'){
                if (!is_numeric ($valor)) {
                    almacena_primer_campo($id_ensayo_vs_muestra, $valor);                    
                    $mensaje = "<b>".$valor."</b>";                    
                    $aux_guarda_resultado_mensaje='-IC1S-NMC2-';//                    
                    almacena_campo_resultado($id_ensayo_vs_muestra, $valor);
                    $aux_guarda_resultado_mensaje.=evalua_alerta($fila_ensayo->med_valor_min ,$fila_ensayo->med_valor_max, $valor, $id_tipo_analisis, $id_ensayo_vs_muestra);
                }else{  
                    $mensaje ="Valor numerico".$no;               
                    $aux_guarda_resultado_mensaje='-NMC2-';
                }
                     
            }
        }elseif($id_tecnica == 29){//   29 Sedimentacion de  Ambientes, ajuste realizado el 25 de mayo de 2020. para poder ingresar texto 20(M)5(L)       
            if($nombre_campo_bd=='resultado_analisis'){
                almacena_primer_campo($id_ensayo_vs_muestra, $valor);                    
                $mensaje = "<b>".$valor."</b>";                    
                $aux_guarda_resultado_mensaje='-IC1S-NMC2-';//                    
                almacena_campo_resultado($id_ensayo_vs_muestra, $valor);        
            }
        
        }else{//demas tecnica
            if($nombre_campo_bd=='resultado_analisis'){
                if(is_numeric ($valor)) {               
                    $aux_guarda_resultado_mensaje='-IC1S-';
                    almacena_primer_campo($id_ensayo_vs_muestra, $valor);
                    if($rol==1 || $rol==2 || $rol==3){
                        $fila_resultado_1   = procesar_registro_fetch("ensayo_vs_muestra", "id_ensayo_vs_muestra", $id_ensayo_vs_muestra);
                    
                        if($fila_resultado_1[0]->resultado_analisis2){
                            $valor2 = round(($fila_resultado_1[0]->resultado_analisis2+$valor)/2); 
                            almacena_campo_resultado($id_ensayo_vs_muestra, $valor2);
                            $aux_guarda_resultado_mensaje.='-NMC2-';
                            $mensaje=$valor2;
                        }
                    }
                }else{
                    $aux_guarda_resultado_mensaje='-IC1S-';
                    almacena_primer_campo($id_ensayo_vs_muestra, $valor);
                    almacena_campo_resultado($id_ensayo_vs_muestra, $valor);
                    $aux_guarda_resultado_mensaje.='-NMC2-';
                    $aux_guarda_resultado_mensaje.=evalua_alerta($fila_ensayo->med_valor_min ,$fila_ensayo->med_valor_max, $valor, $id_tipo_analisis, $id_ensayo_vs_muestra);
                }
            }else{
                if(is_numeric ($valor)) {  
                    almacena_segundo_campo($id_ensayo_vs_muestra, $valor);
                    $fila_resultado_1 = procesar_registro_fetch("ensayo_vs_muestra", "id_ensayo_vs_muestra", $id_ensayo_vs_muestra);
                    $aux_valor = is_numeric($fila_resultado_1[0]->resultado_analisis) ? $fila_resultado_1[0]->resultado_analisis:0;
                    $valor2 = round(($aux_valor+$valor)/2); 
                            // return json_encode($valor2);
                    almacena_campo_resultado($id_ensayo_vs_muestra, $valor2);
                    $aux_guarda_resultado_mensaje='-IC2S-'.$valor2;//
                 
                    if($rol==1 || $rol==2 || $rol==3){
                        $valor2     = round(($aux_valor+$valor)/2); 
                        almacena_campo_resultado($id_ensayo_vs_muestra, $valor2);
                        $aux_guarda_resultado_mensaje.='-NMC2-';
                        $mensaje=$valor2;
                    }
                    $aux_guarda_resultado_mensaje.=evalua_alerta($fila_ensayo->med_valor_min ,$fila_ensayo->med_valor_max, $valor2, $id_tipo_analisis, $id_ensayo_vs_muestra);
                }else{
                    $mensaje ="Valor no numerico".$no;               
                    $aux_guarda_resultado_mensaje='-NMC2-';
                }
            }
            $aux_guarda_resultado_mensaje.="Tenica".$id_tecnica;
        }// fin demas tecnica
        $aux_guarda_resultado_mensaje.="Tenica".$id_tecnica;
        $aux_guarda_resultado_mensaje.="Campo".$nombre_campo_bd;
        $aux_guarda_resultado_mensaje.="Valor".$valor;
        $result = [];
        $result['campo_frm'] = $nombre_campo_frm;
        if(preg_match("/-IC1S-/", $aux_guarda_resultado_mensaje)){
            if(preg_match("/-MAS-/", $aux_guarda_resultado_mensaje)){
                $result['style'] = 'invalid';
            }else{
                $result['style'] = 'valid';
            }
            $result['hide'] = true;
        }
        if(preg_match("/-IC2S-/", $aux_guarda_resultado_mensaje)){
            if(preg_match("/-MAS-/", $aux_guarda_resultado_mensaje)){
                $style = 'class="invalid"';
            }else{
                $style = 'class="valid"';
            }
            $mensaje_campo2 .= '<input type="text"  name="frm_resultado2'.$id_ensayo_vs_muestra.'" id="frm_resultado2'.$id_ensayo_vs_muestra.'"   value="'.$valor.'" '.$style.' disabled>';
            $result['campo_respuesta'] = "campo_repuesta2_".$id_ensayo_vs_muestra;
            $result['hide'] = true; 
        }elseif(!preg_match("/-NMC2-/", $aux_guarda_resultado_mensaje)){
            $mensaje_campo2 .= '<input type="text"  name="frm_resultado2'.$id_ensayo_vs_muestra.'" id="frm_resultado2'.$id_ensayo_vs_muestra.'"  onblur="js_cambiar_campos(\'campo_repuesta_'.$id_ensayo_vs_muestra.'\',this.value, \'frm_resultado2'.$id_ensayo_vs_muestra.'\', \'resultado_analisis2\',  \''.$id_ensayo_vs_muestra.'\', \''.$id_tecnica.'\')" value="">';
        }
        // $procedencia = ['name' => 'frm_id_procedencia', 'value' => 2];
        if($mensaje_campo2){
             $result["campo_respuesta"] = "campo_respuesta2_".$id_ensayo_vs_muestra;
             $result["campo_respuesta2_".$id_ensayo_vs_muestra] = $mensaje_campo2;
        }
        
        $result["campo_mensajes"] = "campo_mensajes_".$id_ensayo_vs_muestra;//.$aux_guarda_resultado_mensaje
        $result["campo_mensajes_".$id_ensayo_vs_muestra] = $mensaje;//.$aux_guarda_resultado_mensaje
        return json_encode($result);
        // return json_encode($id_tecnica);
    }
}