<?php


namespace App\Controllers;


use App\Traits\Grocery;
use App\Models\MenuCliente;
use App\Models\MenuFuncionarios;
use CodeIgniter\Exceptions\PageNotFoundException;

class TableController extends BaseController
{
    use Grocery;

    private $crud;
    private $certificado_aux;

    public function __construct()
    {
        $this->crud = $this->_getGroceryCrudEnterprise();
        $this->crud->setSkin('bootstrap-v3');
        $this->crud->setLanguage('Spanish');
        $this->get_certificaciones();
    }

    public function index($data)
    {
        if (session('user')->funcionario) $menu = new MenuFuncionarios();
        else $menu = new MenuCliente();
        $component = $menu->where(['table' => $data, 'component' => 'table'])->get()->getResult();
        if($component) {
            $this->crud->setTable($component[0]->table);
            switch ($component[0]->table) {
                case 'certificacion':
                    $this->crud->setTable('view_certificados'.session('user')->id);
                    $this->crud->unsetAdd();
                    $this->crud->unsetEdit();
                    $this->crud->unsetDelete();
                    if(session('user')->id == 10){    
                        $columnas = [
                            'certificado_nro',
                            'mue_fecha_muestreo',
                            'mue_lote',
                            'mue_subtitulo',
                            'mue_identificacion',
                            'mensaje',
                            'preinforme', 'informe', 'informe2'];
                    }else{
                        $columnas = [
                            'certificado_nro',
                            'mue_fecha_muestreo',
                            'id_cliente',
                            'mue_subtitulo',
                            'id_codigo_amc',
                            'mue_identificacion',
                            'mensaje',
                            'informe', 'preinforme', 'informe2'];
                    }
                    $this->crud->columns($columnas);
                    $this->crud->displayAs([
                        'mue_fecha_muestreo' => 'Fecha de registro',
                        'certificado_nro' => 'Cert Nro.',
                        'id_cliente' => 'Cliente',
                        'mue_subtitulo' => 'Seccional',
                        'id_codigo_amc' => 'Codigo AMC',
                        'mue_identificacion' => 'Producto',
                        'mensaje' => 'Resultado',
                        'informe' => 'Resultado',
                        'preinforme' => 'Preliminar',
                        'informe2' => 'Informe'
                    ]);
                    $certificado = [];
                    $this->crud->callbackColumn('mensaje', function($resultado, $row){
                        $div = '<div id="div_resultado_'.$row->certificado_nro.'">'.$resultado.'</div>';
                        return $div;
                    });
                    $this->crud->callbackColumn('informe', function($fecha, $row){
                        $fecha_aux = explode('/', $row->mue_fecha_muestreo);
                        $fecha_aux2 = explode(' ', $fecha_aux[2]);
                        $fecha_m = $fecha_aux2[0].'-'.$fecha_aux[1].'-'.$fecha_aux[0].' '.$fecha_aux2[1];
                        $row = procesar_registro_fetch('certificacion', 'certificado_nro', $row->certificado_nro);
                        $this->certificado_aux[$row[0]->certificado_nro] = $row[0];
                        $row = $row[0];
                        $aux_bttn_preinforme = '<div class="button grocery">';
                        if (strtotime(date_certificados()) >= strtotime($fecha_m))
                            $aux_bttn_preinforme .= '<input type="hidden" id="table-plantilla_'.$row->certificado_nro.'" value="1">';
                        else
                            $aux_bttn_preinforme .= '<input type="hidden" id="table-plantilla_'.$row->certificado_nro.'" value="0">';
                        if ($row->certificado_estado == 3  || $row->certificado_estado == 5){//cer_fecha_preinforme
                            $aux_bttn_preinforme .= '
                                <button class="btn green white-text" onClick="js_mostrar_detalle(`card-detalle`,`card-table`,'.$row->certificado_nro.',1,`php_lista_resultados`)"><i class="fad fa-check-circle"></i></button>
                            ';
                        } else {
                            $aux_bttn_preinforme .='
                                    <button class="btn red white-text" onClick="js_mostrar_detalle(`card-detalle`,`card-table`,'.$row->certificado_nro.',2,`php_lista_resultados`)"><i class="fad fa-times-circle"></i></button>
                            ';
                        }
                        $aux_bttn_preinforme .= '</div>';
                        return $aux_bttn_preinforme;
                    });
                    $this->crud->callbackColumn('preinforme', function($fecha, $row){
                        $certificado_aux = $this->certificado_aux[$row->certificado_nro];
                        $aux_variable_preinforme = 0;
                        if ($certificado_aux->certificado_estado == 3 || $certificado_aux->certificado_estado == 5)
                            $aux_variable_preinforme = 1;
                        $aux_bttn_preinforme ='<div class="button grocery" id="pre_informe_'.$row->certificado_nro.'">';
                        if ($fecha == '0000-00-00 00:00:00'){//cer_fecha_preinforme
                            if($aux_variable_preinforme == 0){
                                $aux_bttn_preinforme .= '<button class="btn red white-text"><i class="fad fa-times-circle"></i></button>';
                                // $aux_bttn_preinforme .= '<button class="btn red white-text" onClick="js_mostrar_detalle(`card-detalle`,`card-table`,'.$row->certificado_nro.',2,`php_lista_resultados`)"><i class="fad fa-times-circle"></i></button>';
                            }else{
                                $aux_bttn_preinforme .= '<button class="btn red white-text" onClick="my_toast(`No puede generar preinforme, ya que posee resultados de an&aacute;lisis`, `red darken-4`, 5000)"><i class="fad fa-times-circle"></i></button>';
                            }
                        } else {
                            $aux_bttn_preinforme .= '<button class="btn green white-text" onClick="descargar_info(`'.$row->certificado_nro.'`, 0, `'.session('user')->usr_rol.'`, 0)"><i class="fad fa-check-circle"></i></button>';
                        }
                        $aux_bttn_preinforme .='</div>';
                        return $aux_bttn_preinforme;
                    });
                    $this->crud->callbackColumn('informe2', function($fecha, $row){
                        $certificado_aux = $this->certificado_aux[$row->certificado_nro];
                        $aux_bttn_preinforme='<div class="button grocery" id="certificado_'.$row->certificado_nro.'">';
                        if ($certificado_aux->cer_fecha_analisis == '0000-00-00 00:00:00'){//cer_fecha_analisis
                            $aux_bttn_preinforme .= '<button class="btn red white-text" onClick="my_toast(`No puede generar informe, ya que &nbsp <b>NO</b> &nbsp posee los resultados del an&aacute;lisis`, `red darken-4`, 5000)"><i class="fad fa-times-circle"></i></button>';
                        }else {
                            if ($certificado_aux->cer_fecha_informe == '0000-00-00 00:00:00'){//cer_fecha_informe
                                $aux_bttn_preinforme .= '<button class="btn red white-text"><i class="fad fa-times-circle"></i></button>';
                                // $aux_bttn_preinforme .= '<button class="btn red white-text" onClick="js_mostrar_detalle(`card-detalle`,`card-table`,`'.$row->certificado_nro.'`,3,`php_lista_resultados`)"><i class="fad fa-times-circle"></i></button>';
                            }else{
                                if($certificado_aux->cer_fecha_publicacion > '0000-00-00 00:00:00'){
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
                                $aux_bttn_preinforme .= '<button class="btn '.$aux_btn_c.' white-text" onClick="actualizar_informe(`'.$row->certificado_nro.'`, `'.$aux_metodo.'`, '.session('user')->usr_rol.', 1)"><i class="'.$aux_btn_i.'"></i></button>';
                            }
                        }
                        $aux_bttn_preinforme .='</div>';
                        return $aux_bttn_preinforme;
                    });
                    break;
                case 'usuario':
                    $this->crud->displayAs([
                        'name'                  => 'Nombre',
                        'username'              => 'Usuario',
                        'email'                 => 'Email',
                        'usertype'              => 'Rol',
                        'registerDate'          => 'Registro',
                        'lastvisitDate'         => 'Ultima visita',
                        'use_cargo'             => 'Cargo',
                        'use_nombre_encargado'  => 'Encargado',
                        'use_telefono'          => 'Tel??fono',
                        'use_fax'               => 'Fax',
                        'use_direccion'         => 'Direcci??n',
                    ]);
                    $this->crud->columns(['name', 'username', 'email', 'usertype', 'registerDate', 'lastvisitDate', 'use_cargo','use_nombre_encargado','use_telefono','use_fax','use_direccion']);                
                    if (session('user')->username){
                        $this->crud->where(['usuario.id = ?' => session('user')->id ]);
                        $this->crud->unsetOperations();
                    }
                    break;
                case 'tecnica':
                    $this->crud->displayAs([
                        'nor_nombre'        => 'Nombre',
                        'nor_descripcion'   => 'Descripci??n'
                    ]);
                    break;
                case 'norma':
                    $this->crud->displayAs([
                        'nor_nombre'        => 'Nombre',
                        'nor_descripcion'   => 'Descripci??n'
                    ]);
                    break;
                case 'producto':
                    $this->crud->displayAs([
                        'pro_nombre'        => 'Nombre',
                        'pro_descripcion'   => 'Descripci??n',
                        'id_norma'          => 'Norma'
                    ]);
                    $this->crud->setRelation('id_norma', 'norma', 'nor_nombre');
                    break;
                case 'parametro':
                    $this->crud->displayAs([
                        'par_nombre'        => 'Nombre',
                        'par_descripcion'   => 'Descripci??n',
                        'par_estado'        => 'Estado',
                        'par_irca'          => 'Irca',
                    ]);
                    $this->crud->setRelation('id_tecnica', 'tecnica', 'nor_nombre');
                    break;
                case 'ensayo':
                    $this->crud->displayAs([
                        'id_producto'   => 'Producto',
                        'id_parametro'  => 'Parametro',
                        'refe_bibl'     => 'Referencia Bibliografica'
                    ]);
                    $this->crud->setRelation('id_producto', 'producto', 'pro_nombre');
                    $this->crud->setRelation('id_parametro', 'parametro', 'par_nombre');
                    break;
                
                default:
                    break;
            }
            $output = $this->crud->render();
            if (isset($output->isJSONResponse) && $output->isJSONResponse) {
                header('Content-Type: application/json; charset=utf-8');
                echo $output->output;
                exit;
            }

            $this->viewTable($output, $component[0]->title, $component[0]->description);
        } else {
            throw PageNotFoundException::forPageNotFound();
        }
    }

    public function get_certificaciones(){
        $db = \Config\Database::connect();
        $aux_columna_cliente =" (select name from usuario where id=m.id_cliente) id_cliente,"; 
        $aux_columna_id_codigo_amc =" 
            if (d.ano_codigo_amc, CONCAT (d.ano_codigo_amc,\"-\",d.id_tipo_analisis,\"-\",LPAD(d.id_codigo_amc,5,\"0\")) ,CONCAT ((select mue_sigla from muestra_tipo_analisis where id_muestra_tipo_analsis= d.id_tipo_analisis),\" \", d.id_codigo_amc)) id_codigo_amc,             
            "; 
        $aux_columna_lote =" "; 
        $sql = "CREATE OR REPLACE VIEW view_certificados".session('user')->id." 
                AS
                select
                c.id_muestreo,
                m.mue_fecha_muestreo,
                c.certificado_nro,
               ".$aux_columna_cliente."
                m.mue_subtitulo,
                ".$aux_columna_id_codigo_amc."
                ".$aux_columna_lote."
                d.mue_identificacion,        
        c.certificado_estado resultados,
        c.cer_fecha_preinforme preinforme,
        c.cer_fecha_analisis informe,
        c.cer_fecha_informe informe2,
        c.cer_fecha_publicacion fecha_publicacion,
        c.cer_fecha_facturacion fecha_facturacion,
        (select mensaje_titulo from mensaje_resultado where id_mensaje=c.id_mensaje) mensaje
                        
                from certificacion c, muestreo m, muestreo_detalle d
                where c.id_muestreo = m.id_muestreo and c.id_muestreo_detalle = d.id_muestra_detalle
                and m.mue_estado <> 0 order by certificado_nro desc ";
        $db->query($sql);
    }
}