<?php

namespace App\Controllers;

use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\Muestreo;
use Config\Services;



class ClienteController extends BaseController
{
    //  MisDatos
    public function user(){
        return view('clientes/user');
    }

	//  Password
	public function password(){
    	$validation = Services::validation();
        return view('clientes/new_password', ['validation' => $validation]);
    }

    public function password_update(){
    	$pwd_actual 	= $this->request->getPost('password_actual');
    	$pwd_new 		= $this->request->getPost('password_new');
        if (empty($pwd_actual)) {
            return redirect()->back()->with('errors', 'La contraseña actual es necesaria.');
        }
		$password = session('user')->password;
        $user     = session('user')->username;
        $pwd_new  = md5($pwd_new);
        if (md5($pwd_actual) != $password) 
			return redirect()->back()->with('errors', 'La contraseña no concuerdan.');

    	if ($this->validate([
    		'password_new' 		=> 'required|min_length[4]|max_length[32]',
    		'password_confirm' 	=> 'required|matches[password_new]',
    	],[
    		'password_new' 		=> [
    			'required' 		=> 'La nueva contraseña es necesaria.',
    			'min_length' 	=> 'La nueva contraseña es muy corta.',
    			'max_length' 	=> 'La nueva contraseña es muy larga.'
    		],
    		'password_confirm' 	=> [
    			'required' 		=> 'La confirmacion de contraseña es necesaria.',
    			'matches' 		=> 'Las contraseñas no concuerdan.'
    		]
    	]))
    	{
            $user = new Cliente();
            $user->set(['password' => $pwd_new])
            ->where(['id' => session('user')->id])
            ->update();
            session('user')->password = $pwd_new;
            return redirect()->back()->with('success', '$result');
    	}else{
    		return redirect()->back()->withInput();
    	}
    }

    //  Certificados
    public function model(){
        $data = new Muestreo();
        $id = session('user')->id;
        $data->select('
            certificacion.id_muestreo AS id_muestreo,
            muestreo.mue_fecha_muestreo AS mue_fecha_muestreo,
            certificacion.certificado_nro AS certificado_nro,
            muestreo.mue_subtitulo AS mue_subtitulo,
            muestreo_detalle.mue_lote AS mue_lote,
            muestreo_detalle.mue_identificacion AS mue_identificacion,
            certificacion.certificado_estado AS resultados,
            certificacion.cer_fecha_preinforme AS preinforme,
            certificacion.cer_fecha_analisis AS informe,
            certificacion.cer_fecha_informe AS informe2,
            certificacion.cer_fecha_publicacion AS fecha_publicacion,
            certificacion.cer_fecha_facturacion AS fecha_facturacion,
            (select mensaje_resultado.mensaje_titulo from mensaje_resultado where (mensaje_resultado.id_mensaje = certificacion.id_mensaje)) AS mensaje,
            producto.pro_nombre as producto
        ');
        $data->join('certificacion', 'muestreo.id_muestreo = certificacion.id_muestreo');
        $data->join('muestreo_detalle', 'certificacion.id_muestreo_detalle = muestreo_detalle.id_muestra_detalle');
        $data->join('producto', 'producto.id_producto = muestreo_detalle.id_producto');
        $data->orderBy('certificacion.certificado_nro', 'desc');
        $data->where(['id_cliente' => $id]);
        return $data;
    }
    public function filtrar($data){
        $sql = $this->model();
        if($data['concepto'] != (-1)){
            if(!empty($data['concepto'])){
                $sql = $sql->where('certificacion.id_mensaje', $data['concepto']);
            }else{
                $sql = $sql->where('certificacion.id_mensaje', null);
            }
        }
        if($data['tipo_analisis'] > 0 )
            $sql = $sql->where('muestreo_detalle.id_tipo_analisis', $data['tipo_analisis']);
        if($data['producto'] > 0 )
            $sql = $sql->where('producto.id_producto', $data['producto']);
        if(!empty($data['parametro'])){
            $sql = $sql->select('parametro.par_nombre as parametro')
                    ->join('ensayo_vs_muestra', 'ensayo_vs_muestra.id_muestra = muestreo.id_muestreo')
                    ->join('ensayo', 'ensayo.id_ensayo = ensayo_vs_muestra.id_ensayo')
                    ->join('parametro', 'ensayo.id_parametro = parametro.id_parametro')
                    ->where(['parametro.id_parametro' => $data['parametro']]);
        }
        if(empty($data['date_start'])){
            $sql = $sql->where(['mue_fecha_muestreo >=' => '0000-00-00 00:00:00']);
        }else{
            $sql = $sql->where('mue_fecha_muestreo >', $data['date_start'].' 00:00:00');
        }

        if(empty($data['date_finish'])){
            $sql = $sql->where(['mue_fecha_muestreo <=' => date('Y-m-t').' 23:59:59']);
        }else{
            $sql = $sql->where('mue_fecha_muestreo <=', $data['date_finish'].' 23:59:59');
        }
        return $sql;
    }
    public function certificado(){
        $id = session('user')->id;
        $count                  = $this->model()->countAllResults();
        $certificados           = $this->model()->limit(10, 0)->get()->getResult();
        $filtros                = $this->filtros();
        $certificados_tabla     = $this->tabla($certificados);
        return view('clientes/certificado',[
            'certificados'          => $certificados_tabla,
            'filtros'               => $filtros,
            'count'                 => ($count%10) == 0 ? ($count/10) - 1 : round($count/10, 0, PHP_ROUND_HALF_DOWN),
            'total'                 => $count,
            'total_2'               => count($certificados),
        ]);
    }
    public function certificado_filtrar(){
        $id = session('user')->id;  
        $data = [
            'concepto'         => $this->request->getPost('concepto'),
            'tipo_analisis'    => $this->request->getPost('tipo_analisis'),
            'date_start'       => $this->request->getPost('date_start'),
            'date_finish'      => $this->request->getPost('date_finish'),
            'producto'         => $this->request->getPost('producto'),
            'limite'           => $this->request->getPost('limite'),
            'pagina'           => $this->request->getPost('pagina'),
            'parametro'        => $this->request->getPost('parametros'),
            'select'           => false,
        ];
        $count = $this->filtrar($data)->countAllResults();
        if(empty($data['limite'])){
            $certificados = $this->filtrar($data)->get()->getResult();
        }else{
            $certificados = $this->filtrar($data)->limit($data['limite'], ($data['limite']*$data['pagina']))->get()->getResult();
        }
        $limite_aux = $this->request->getPost('limite');
        $limite = $limite_aux == null ? $count:$limite_aux;
        $count_1 = $limite == 0 ? '0' : ($count/$limite);
        if (!empty($certificados)) {
            $certificados_table = $this->tabla($certificados);
        }else{
            $certificados_table = '
                <h3 class="responsive-table">No hay coincidencias</h3>
            ';
        }
        $pagina_aux = $this->request->getPost('pagina');
        $resultado = [
            'certificados'  => $certificados_table,
            'count'         => $count_1,
            'total'         => $count,
            'pagina'        => $pagina_aux,
            'total_2'       => count($certificados),
        ];
        return json_encode($resultado);
    }
    public function certificado_download($certificado){
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
            $html = view('clientes/generate_pdf',['value' => $certificado]);
            $css  = file_get_contents('assets/css/styles.css');
            $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
            $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
            // $this->response->setHeader('Content-Type', 'application/pdf');
            // $mpdf->Output('arjun.pdf','I');
            $name = 'certificado_'.$certificado.'.pdf';
            $mpdf->Output($name,'D');
    }
    public function certificado_down(){
    	if (isset($_POST['certificado_down'])) {
            $zip = new \ZipArchive();
            $count = count($_POST['certificado_down']);
            $archivo = 'certificados_amc.zip';
    		foreach ($_POST['certificado_down'] as $key => $value) {
                $zip->open($archivo, \ZIPARCHIVE::CREATE);
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
                $html = view('clientes/generate_pdf',['value' => $value]);
                $css  = file_get_contents('assets/css/styles.css');
                $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
                $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
                $name = 'certificado_'.$value.'.pdf';
                if($count == 1 ){
                    $mpdf->Output($name,'D');
                    exit;
                }
                $mpdf->Output($name,'F');
                $zip->addFile($name, $name);
                $zip->close();
                unlink($name);
    		}
            header("Content-type: application/octet-stream");
            header("Content-disposition: attachment; filename=$archivo");
            readfile($archivo);
            unlink($archivo);
    	}else{
    		return redirect()->back();
    	}
    }


     // Reportes
    public function reporte(){
        $id         = session('user')->id;
        $filtros    = $this->filtros();
        $date_finish    = date('Y-m-t');
        $data = [
            'concepto'      => '-1',
            'tipo_analisis' => 0,
            'date_start'    => null,
            'date_finish'   => null,
            'producto'      => 0,
            'parametro'     => 0,
            'count'         => true
        ];
        $historial = [];
        for ($i=0;$i<=11;$i++){ 
            $aux_date_firts = date('Y-n-01', mktime(0, 0, 0, (date("n")-$i), 1, date("Y") ) );
        }
        $aux_date_finish = date('Y-m-t');
        $i = 0;
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        while (strtotime($aux_date_firts) <= strtotime($aux_date_finish) ) {
            $data['date_start'] = $aux_date_firts;
            $data['date_finish'] = date("Y-m-t",strtotime($aux_date_firts));
            $info = $this->filtrar($data)->countAllResults();
            $historial[$i]['total'] = $info;
            $historial[$i]['mes'] = date("Y", strtotime($data['date_start'])).' - '.$meses[(date("m", strtotime($data['date_start']))-1)];
            $aux_date_firts = date("Y-m-d",strtotime($aux_date_firts."+1 month"));
            $i++;
        }
        // var_dump($historial);
        return view('clientes/reporte', [
            'filtros'   => $filtros,
            'historial' => $historial,
        ]);
    }
    public function reporte_post(){
// $db = \Config\Database::connect();
        $data = [
            'parametro'        => $this->request->getPost('parametros'),
            'tipo_analisis'    => $this->request->getPost('tipo_analisis'),
            'producto'         => $this->request->getPost('producto'),
            'concepto'         => $this->request->getPost('concepto'),
            'date_start'       => $this->request->getPost('date_start'),
            'date_finish'      => $this->request->getPost('date_finish')
        ];
        $id = session('user')->id;
        $aux_date_finish = $data['date_finish'] == null ? date("Y-m-t") : $data['date_finish'];
        $aux_date_firts = $data['date_start'] == null ? date("Y-m-01", strtotime($aux_date_finish)) : $data['date_start'];
        $result = [];
        $result_aux = [];
        $contador = 0;
        $j = 0;
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        while (strtotime($aux_date_firts) <= strtotime($aux_date_finish) ) {
            $data['date_start'] = $aux_date_firts;
            $data['date_finish'] = date("Y-m-t",strtotime($aux_date_firts));
            $count[$j]['total'] = $this->filtrar($data)->countAllResults();
            if (empty($this->request->getPost('date_start'))) {
                $aux_date_firts = date("Y-m-d",strtotime($aux_date_firts."-1 month"));
                if($count[$j]['total'] >= 1){
                    $count[$j]['mes'] = date("Y", strtotime($data['date_start'])).' - '.$meses[(date("m", strtotime($data['date_start']))-1)];
                    array_push($result, $count[$j]);
                    array_push($result_aux, $count[$j]);
                    $contador++;
                    $i = $contador;
                    foreach ($result as $key => $value) {
                        $i--;
                        $result[$key] = $result_aux[$i];
                    }
                }
            }else{
                $aux_date_firts = date("Y-m-d",strtotime($aux_date_firts."+1 month"));
                $count[$j]['mes'] = date("Y", strtotime($data['date_start'])).' - '.$meses[(date("m", strtotime($data['date_start']))-1)];
                array_push($result, $count[$j]);
            }
            if (strtotime('2010-01-01') == strtotime($aux_date_firts)) {
                break;
            }
            $j++;
        }
        return json_encode([
            'data' => $result
        ]);
    }
    // Filtros y tablas
    public function filtros(){
        $id = session('user')->id;
        $muestreo = new Muestreo();
        $mensaje_resultado = $muestreo
            ->distinct('mensaje_resultado.mensaje_titulo')
            ->select('mensaje_resultado.mensaje_titulo as mensaje_titulo,
                mensaje_resultado.id_mensaje as id_mensaje')
            ->join('certificacion', 'muestreo.id_muestreo = certificacion.id_muestreo')
            ->join('mensaje_resultado', 'certificacion.id_mensaje = mensaje_resultado.id_mensaje')
            ->where(['id_cliente' => $id])
            ->orderBy('mensaje_titulo', 'asc')
            ->get()->getResult();

        $parametros_resultado = $muestreo
            ->distinct('parametro.id_parametro')
            ->select('parametro.id_parametro,parametro.par_nombre')
            ->join('certificacion', 'muestreo.id_muestreo = certificacion.id_muestreo')
            ->join('muestreo_detalle', 'certificacion.id_muestreo_detalle = muestreo_detalle.id_muestra_detalle')
            ->join('producto', 'producto.id_producto = muestreo_detalle.id_producto')
            ->join('ensayo_vs_muestra', 'ensayo_vs_muestra.id_muestra = muestreo.id_muestreo')
            ->join('ensayo', 'ensayo.id_ensayo = ensayo_vs_muestra.id_ensayo')
            ->join('parametro', 'ensayo.id_parametro = parametro.id_parametro')
            ->where(['id_cliente' => $id])
            ->orderBy('par_nombre', 'asc')
            ->get()->getResult();

        $productos_resultado = $muestreo
            ->distinct('producto.pro_nombre')
            ->select('producto.pro_nombre as producto,
                producto.id_producto as id_producto')
            ->join('certificacion', 'muestreo.id_muestreo = certificacion.id_muestreo')
            ->join('muestreo_detalle', 'certificacion.id_muestreo_detalle = muestreo_detalle.id_muestra_detalle')
            ->join('producto', 'producto.id_producto = muestreo_detalle.id_producto')
            ->where(['id_cliente' => $id])
            ->orderBy('producto', 'asc')
            ->get()->getResult();

        $analisis_resultado = $muestreo
            ->distinct('muestreo_detalle.id_tipo_analisis')
            ->select('muestreo_detalle.id_tipo_analisis as id_muestra_tipo_analsis,
                muestra_tipo_analisis.mue_nombre as mue_nombre')
            ->join('certificacion', 'muestreo.id_muestreo = certificacion.id_muestreo')
            ->join('muestreo_detalle', 'certificacion.id_muestreo_detalle = muestreo_detalle.id_muestra_detalle')
            ->join('muestra_tipo_analisis', 'muestreo_detalle.id_tipo_analisis = muestra_tipo_analisis.id_muestra_tipo_analsis')
            ->where(['id_cliente' => $id])
            ->orderBy('mue_nombre', 'asc')
            ->get()->getResult();

        $resultado_seccional = $muestreo
            ->distinct('muestreo.mue_subtitulo')
            ->select('muestreo.mue_subtitulo as seccional')
            ->where(['id_cliente' => $id])
            ->orderBy('muestreo.mue_subtitulo', 'asc')
            ->get()->getResult();

        $array_seccional = [];
        foreach($resultado_seccional as $seccional){
            $aux_seccional = true;
            $aux_seccion = explode(' ',$seccional->seccional);
            $aux_seccion = implode(' ', $aux_seccion);
            foreach($array_seccional as $seccion){
                if($seccion == $aux_seccion)
                    $aux_seccional = false;
            }
            if($aux_seccional){
                array_push($array_seccional, $aux_seccion);
            }
        }
        return [
            'resultado_concepto'    => $mensaje_resultado,
            'resultado_muestra'     => $analisis_resultado,
            'resultado_parametros'  => $parametros_resultado,
            'resultado_productos'   => $productos_resultado,
            'resultado_seccional'   => $array_seccional
        ];
    }
    public function tabla($data){
        $certificados = '
                <table class="responsive-table striped">
                    <thead class="highlight">
                        <tr>
                            <th>Fecha de registro</th>
                            <th>Cert Nro.</th>
                            <th>Lote</th>
                            <th>Seccional</th>
                            <th>Producto</th>
                            <th>Resultado</th>
                            <th>Pre informe</th>
                            <th>Informe</th>
                            <th>Estado</th>
                        </tr>
                        <tr>
                        </tr>
                    </thead>
                    <tbody class="centered">';
                        foreach ($data as $key => $value) {
                            $certificados.='
                                <tr>
                                    <td>'. $value->mue_fecha_muestreo.'</td>
                                    <td>'. $value->certificado_nro.'</td>
                                    <td>'. $value->mue_lote.'</td>
                                    <td>'. $value->mue_subtitulo.'</td>
                                    <td>'. $value->producto.'</td>
                                    <td>'. $value->mensaje.'</td>
                                    <td class="action">';
                                        if ($value->preinforme === '0000-00-00 00:00:00' ){
                                            $certificados.='
                                            <label>
                                                <input type="checkbox" disabled="disable" />
                                                <span></span>
                                            </label>';
                                        }
                                        else{
                                            $certificados.='
                                                <label>
                                                    <input type="checkbox" name="certificado[]" value="'. $value->certificado_nro.'" />
                                                    <span></span>
                                                </label>';

                                        }
                                        
                                        $certificados.='
                                    </td>
                                    <td class="action">';
                                        if ($value->fecha_publicacion == NULL ){
                                            $certificados.='
                                            <label>
                                                <input type="checkbox" disabled="disable" />
                                                <span></span>
                                            </label>
                                            ';
                                        }
                                        else{
                                            $certificados.='
                                                <label>
                                                    <input type="checkbox" name="certificado[]" value="'. $value->certificado_nro.'" />
                                                    <span></span>
                                                </label>
                                                ';

                                        }
                                        $certificados.='
                                    </td>
                                    <td class="estado">';
                                        if ($value->fecha_publicacion == NULL ){
                                            $certificados.='
                                                <span class="error tooltipped" data-html="true" data-position="left" data-tooltip="Certificado en proceso"><i class=';$certificados.="'fas fa-cog fa-spin'";
                                                    $certificados.='></i></span>
                                            ';
                                        }
                                        else{
                                            $certificados.='
                                                    <span class="check tooltipped" data-html="true" data-position="left" data-tooltip="Certificado listo para descargar"><i class=';$certificados.="'far fa-check-circle'";
                                                    $certificados.='></i> </span>
                                                ';
                                        }
                                        $certificados.='
                                    </td>
                                </tr>
                            ';
                        }
                $certificados.='
                    </tbody>
                </table>
            ';
        return $certificados;
    }
}