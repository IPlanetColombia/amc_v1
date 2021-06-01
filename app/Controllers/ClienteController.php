<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Usuarios;
use App\Models\Muestreo;
use Config\Services;



class ClienteController extends BaseController
{

	//  Password
	public function password(){
    	$validation = Services::validation();
        return view('pages/new_password', ['validation' => $validation]);
    }

    public function password_update(){
    	$pwd_actual 	= $this->request->getPost('password_actual');
    	$pwd_new 		= $this->request->getPost('password_new');
    	if (isset(session('user')->usr_usuario)) {
    		$password = session('user')->usr_clave;
    		if ($pwd_actual == null) {
    			return redirect()->back()->with('errors', 'La contraseña actual es necesaria.');
    		}elseif ($pwd_actual != password ) {
    			return redirect()->back()->with('errors', 'La contraseña no concuerda.');
    		}
    	}else{
    		$user = new Usuarios();
            $data = $user->where(['username' => session('user')->username, 'password' => md5($pwd_actual)])->get()->getResult();
    		$password = $data[0]->password;
    		if ($pwd_actual == null) {
    			return redirect()->back()->with('errors', 'La contraseña actual es necesaria.');
    		}elseif (md5($pwd_actual) != $password) {
    			return redirect()->back()->with('errors', 'La contraseña no concuerdan.');
    		}
    	}
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
    		if (isset(session('user')->usr_usuario)) {
    			echo "funcionario";
    		}else{
            	if (isset($data[0])) {
            		$user->set(['password' => md5($pwd_new)]);
	                $user->where('id', $data[0]->id);
	                $user->update();
	                return redirect()->back()->with('success', 'La contraseña se cambio con éxito');
            	}else{
            		return redirect()->back()->with('error', 'No encontramos al usuario');
            	}
    		}
    	}else{
    		return redirect()->back()->withInput();
    	}
    }

    //  Certificados
    public function certificacion($data){
        if ($data['select']) {
            $aux_select = 'count(*) total';
        }else{
            $aux_select = '
                c.id_muestreo AS id_muestreo,
                m.mue_fecha_muestreo AS mue_fecha_muestreo,
                c.certificado_nro AS certificado_nro,
                m.mue_subtitulo AS mue_subtitulo,
                md.mue_lote AS mue_lote,
                md.mue_identificacion AS mue_identificacion,
                c.certificado_estado AS resultados,
                c.cer_fecha_preinforme AS preinforme,
                c.cer_fecha_analisis AS informe,
                c.cer_fecha_informe AS informe2,
                c.cer_fecha_publicacion AS fecha_publicacion,
                c.cer_fecha_facturacion AS fecha_facturacion,
                (select mensaje_resultado.mensaje_titulo from mensaje_resultado where (mensaje_resultado.id_mensaje = c.id_mensaje)) AS mensaje,
                (select p.pro_nombre from producto p where p.id_producto = md.id_producto  ) producto
            ';
        }
        $id = session('user')->id;
    	if ( $data['concepto'] == null )
    		$aux_concepto	= 'and c.id_mensaje IS NULL';
    	else
    		$aux_concepto	= $data['concepto'] === '-1' ? '':"and c.id_mensaje = '".$data['concepto']."'";
        
    	$aux_tipo_analisis 	= $data['tipo_analisis'] >= 1 ? "and md.id_tipo_analisis = '".$data['tipo_analisis']."'":'';
    	$aux_date_start 	= $data['date_start'] == null ? '0000-00-00 00:00:00' : $data['date_start'].' 00:00:00';
    	$aux_date_finish 	= $data['date_finish'] == null ? date("Y-m-d").' 23:59:59' : $data['date_finish'].' 23:59:59';
    	$aux_producto		= $data['producto'] >= 1 ? "and p.id_producto = '".$data['producto']."'" : '';
        if ($data['parametro'] >= 1) {
            $aux_parametro = "and pa.id_parametro = '".$data['parametro']."'";
            $aux_filter    = "
                    left join ensayo_vs_muestra em on em.id_muestra = m.id_muestreo
                    left join ensayo e on e.id_ensayo = em.id_ensayo
                    left join parametro pa on e.id_parametro = pa.id_parametro";
            $aux_par_select= ",pa.par_nombre as parametro";
        }else{
            $aux_parametro = '';
            $aux_filter    = '';
            $aux_par_select= '';
        }

		$sql = "SELECT
                    $aux_select
                    $aux_par_select
				FROM
					muestreo m
                    left join certificacion c on m.id_muestreo = c.id_muestreo
                    left join muestreo_detalle md on c.id_muestreo_detalle = md.id_muestra_detalle
                    left join producto p on md.id_producto = p.id_producto
                    $aux_filter
				WHERE
                    m.id_cliente = $id
					$aux_concepto
					$aux_tipo_analisis
					$aux_producto
                    $aux_parametro
					and m.mue_fecha_muestreo BETWEEN '$aux_date_start' and '$aux_date_finish'
				order by c.certificado_nro desc";
    	return $sql;
    }
    public function certificado(){
    	$db = \Config\Database::connect();
        $data = [
            'concepto'      => '-1',
            'tipo_analisis' => 0,
            'date_start'    => null,
            'date_finish'   => null,
            'producto'      => 0,
            'parametro'     => 0,
            'limite'        => 10,
            'select'        => false,
        ];
    	$sql = $this->certificacion($data);
        $data['select'] = true;
    	$sql_2 = $this->certificacion($data);
    	$count = $db->query($sql_2)->getResult();

    	$certificados 	= $db->query($sql.' LIMIT 0, 10')->getResult();
        $filtros        = $this->filtros();
    	// var_dump($resultado_parametros);
        $certificados_tabla     = $this->tabla($certificados);
    	return view('pages/certificado',[
    		'certificados' 			=> $certificados_tabla,
    		'resultado_concepto' 	=> $filtros['resultado_concepto'],
    		'resultado_muestra' 	=> $filtros['resultado_muestra'],
    		'resultado_parametros'	=> $filtros['resultado_parametros'],
    		'resultado_productos'	=> $filtros['resultado_productos'],
    		'count'					=> ($count[0]->total/$data['limite']),
    		'total'					=> $count[0]->total,
            'total_2'               => count($certificados),
            'result'                => $result
    	]);
    }
    public function certificado_filtrar(){
    	$db = \Config\Database::connect();
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
    	$sql = $this->certificacion($data);
        $data['select'] = true;
    	$sql_2 = $this->certificacion($data);
        $aux_limite = $data['limite'] == null ? ' ':' LIMIT '.($data['pagina']*$data['limite']).' ,'.$data['limite'];
    	$result = $db->query($sql.$aux_limite)->getResult();
    	$count = $db->query($sql_2)->getResult();
        $limite = $data['limite'] == null ? $count[0]->total:$data['limite'];
        $count_1 = $limite == 0 ? '0' : ($count[0]->total/$limite);
    	if ($result != null) {
	    	$certificados = $certificados = $this->tabla($result);
    	}else{
    		$certificados = '
    			<h3 class="responsive-table">No hay coincidencias</h3>
    		';
    	}
    	$resultado = array(
    		'certificados' 	=> $certificados,
    		'count'         => $count_1,
    		'total'			=> $count[0]->total,
            'pagina'        => $data['pagina'],
            'total_2'       => count($result),
            'result'        => $result
    	);
    	return json_encode($resultado);
    }
    public function certificado_download($certificado){
            $mpdf = new \Mpdf\Mpdf([]);
            $html = view('pages/generate_pdf',['value' => $certificado]);
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
                $mpdf = new \Mpdf\Mpdf([]);
                $html = view('pages/generate_pdf',['value' => $value]);
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
        $db             = \Config\Database::connect();
        $filtros        = $this->filtros();
        $date_finish    = date('Y-m-t');
        $data = [
            'concepto'      => '-1',
            'tipo_analisis' => 0,
            'date_start'    => null,
            'date_finish'   => null,
            'producto'      => 0,
            'parametro'     => 0,
            'limite'        => 10,
            'select'        => true,
        ];
        $historial_array = [];
        for ($i=0;$i<=11;$i++){ 
            $aux_date_firts = date('Y-n-01', mktime(0, 0, 0, (date("n")-$i), 1, date("Y") ) );
        }
        $aux_date_finish = date('Y-m-t');
        while (strtotime($aux_date_firts) <= strtotime($aux_date_finish) ) {
            $data['date_start'] = $aux_date_firts;
            $data['date_finish'] = date("Y-m-t",strtotime($aux_date_firts));
            $sql_2 = $this->certificacion($data);
            $count = $db->query($sql_2)->getResult();
            $aux_date_firts = date("Y-m-d",strtotime($aux_date_firts."+1 month"));
            $count[0]->mes = date("Y-F", strtotime($data['date_start']));
            array_push($historial_array, $count[0]);
        }
        return view('pages/reporte', [
            'filtros'   => $filtros,
            'historial' => $historial_array
        ]);
    }
    public function reporte_post(){
        $db = \Config\Database::connect();
        $data = [
            'parametro'        => $this->request->getPost('parametros'),
            'tipo_analisis'    => $this->request->getPost('tipo_analisis'),
            'producto'         => $this->request->getPost('producto'),
            'concepto'         => $this->request->getPost('concepto'),
            'date_start'       => $this->request->getPost('date_start'),
            'date_finish'      => $this->request->getPost('date_finish'),
            'select'           => true
        ];
        $aux_date_finish = $data['date_finish'] == null ? date("Y-m-t") : $data['date_finish'];

        $aux_date_firts = $data['date_start'] == null ? date("Y-m-01", strtotime($aux_date_finish)) : $data['date_start'];
        $result = [];
        $result_aux = [];
        $contador = 0;
        // if($data['date_start'] == null){
        //     for ($i=0;$i<=23;$i++){ 
        //         $aux_date_firts = date('Y-n-01', mktime(0, 0, 0, (date("n")-$i), 1, date("Y") ) );
        //     }
        // }else
        //     $aux_date_firts = $data['date_start'];

        while (strtotime($aux_date_firts) <= strtotime($aux_date_finish) ) {
            $data['date_start'] = $aux_date_firts;
            $data['date_finish'] = date("Y-m-t",strtotime($aux_date_firts));
            $sql_2 = $this->certificacion($data);
            $count = $db->query($sql_2)->getResult();
            if (empty($this->request->getPost('date_start'))) {
                $aux_date_firts = date("Y-m-d",strtotime($aux_date_firts."-1 month"));
                if($count[0]->total != 0){
                    $count[0]->mes = date("Y-F", strtotime($data['date_start']));
                    array_push($result, $count);
                    array_push($result_aux, $count);
                    $contador++;
                    $i = $contador;
                    foreach ($result as $key => $value) {
                        $i--;
                        $result[$key] = $result_aux[$i];
                    }
                }
            }else{
                $aux_date_firts = date("Y-m-d",strtotime($aux_date_firts."+1 month"));
                $count[0]->mes = date("Y-F", strtotime($data['date_start']));
                array_push($result, $count);
            }
            if (strtotime('2010-01-01') == strtotime($aux_date_firts)) {
                break;
            }
        }
        return json_encode([
            'data' => $result
        ]);
    }
    // Filtros y tablas
    public function filtros(){
        $db = \Config\Database::connect();
        $id = session('user')->id;
        $join_p = "
                left join certificacion c on m.id_muestreo = c.id_muestreo
                left join muestreo_detalle md on c.id_muestreo_detalle = md.id_muestra_detalle
                left join producto p on md.id_producto = p.id_producto";
        $mensaje_resultado      = "
            SELECT
                distinct mr.mensaje_titulo as mensaje_titulo,
                mr.id_mensaje as id_mensaje
            FROM
                muestreo m
                left join certificacion c on m.id_muestreo = c.id_muestreo
                left join mensaje_resultado mr on c.id_mensaje = mr.id_mensaje
            WHERE
                m.id_cliente = $id
            ORDER BY
                mr.mensaje_titulo ASC
        ";
        $parametros_resultado   ="
            SELECT
                distinct pa.id_parametro,
                pa.par_nombre
            FROM
                muestreo m
                $join_p
                left join ensayo_vs_muestra em on em.id_muestra = m.id_muestreo
                left join ensayo e on e.id_ensayo = em.id_ensayo
                left join parametro pa on e.id_parametro = pa.id_parametro
            WHERE
                m.id_cliente = $id
            ORDER BY
                pa.par_nombre ASC
        ";

        $productos_resultado    ="
            SELECT
                distinct p.pro_nombre as producto,
                p.id_producto as id_producto
            FROM
                muestreo m
                $join_p
            WHERE
                m.id_cliente = $id
            ORDER BY
                p.pro_nombre ASC
        ";
        $analisis_resultado     ="
            SELECT
                distinct md.id_tipo_analisis as id_muestra_tipo_analsis,
                ma.mue_nombre as mue_nombre
            FROM
                muestreo m
                left join certificacion c on m.id_muestreo = c.id_muestreo
                left join muestreo_detalle md on c.id_muestreo_detalle = md.id_muestra_detalle
                left join muestra_tipo_analisis ma on md.id_tipo_analisis = ma.id_muestra_tipo_analsis
            WHERE
                m.id_cliente = $id
        ";
        $resultado_concepto     = $db->query($mensaje_resultado)->getResult();
        $resultado_muestra      = $db->query($analisis_resultado)->getResult();
        $resultado_parametros   = $db->query($parametros_resultado)->getResult();
        $resultado_productos    = $db->query($productos_resultado)->getResult();
        return [
            'resultado_concepto'    => $resultado_concepto,
            'resultado_muestra'     => $resultado_muestra,
            'resultado_parametros'  => $resultado_parametros,
            'resultado_productos'   => $resultado_productos
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
                            <th>Archivo</th>
                        </tr>
                    </thead>
                    <tbody class="centered">';
                        foreach ($data as $key => $value) {
                            $certificados.='
                                <tr>
                                    <td>'. $value->mue_fecha_muestreo.'</td>
                                    <td>'. $value->certificado_nro.'</td>
                                    <td>'. $value->mue_lote.'</td>
                                    <td>'. $value->parametro.'</td>
                                    <td>'. $value->producto.'</td>
                                    <td>'. $value->mensaje.'</td>
                                    <td class="option">';
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
                                                    <input type="checkbox" name="certificado_down[]" value="'. $value->certificado_nro.'" />
                                                    <span></span>
                                                </label>';

                                        }
                                        if ($value->fecha_publicacion == NULL ){
                                            $certificados.='
                                            <label>
                                                <input type="checkbox" disabled="disable" />
                                                <span></span>
                                            </label>';
                                        }
                                        else{
                                            $certificados.='
                                                <label>
                                                    <input type="checkbox" name="certificado_down[]" value="'. $value->certificado_nro.'" />
                                                    <span></span>
                                                </label>';

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