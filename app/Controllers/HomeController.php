<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Usuarios;
use Config\Services;



class HomeController extends BaseController
{

	public function index()
	{
		$db = \Config\Database::connect();
		$id = session('user')->id;
		$sql_ensayos = "
			SELECT 
				c.certificado_nro,
				p.pro_nombre as producto,
				c.cer_fecha_publicacion AS fecha_publicacion,
				m.id_muestreo
			FROM 
				muestreo m
                -- left join ensayo_vs_muestra em on em.id_muestra = m.id_muestreo
                -- left join ensayo e on e.id_ensayo = em.id_ensayo
				left join certificacion c on m.id_muestreo = c.id_muestreo
				left join muestreo_detalle md on c.id_muestreo_detalle = md.id_muestra_detalle
               	left join producto p on md.id_producto = p.id_producto
			WHERE
				m.id_cliente = $id
			ORDER BY c.certificado_nro DESC
		";
		$sql_solicitues = "
			SELECT
				count(*) as total
			FROM
				muestreo m
			WHERE
			m.id_cliente = $id;
		";
		$sql_proceso = "
			SELECT
				count(*) as total
			FROM
				muestreo m
			WHERE
			m.id_cliente = $id
		";

		$date_init = date('Y-m-01', strtotime('-11 month'));
		$date_finish = date('Y-m-t');
		$historial_array = [];
		while(strtotime($date_init) <= strtotime($date_finish)){
			$aux_date_finish = date('Y-m-t', strtotime($date_init));
			$sql_historial = "
				SELECT
					count(*) as total
				FROM
					muestreo m
				WHERE
				m.id_cliente = $id
				and m.mue_fecha_recepcion BETWEEN '$date_init' and '$aux_date_finish'
			";
			$historial = $db->query($sql_historial)->getResult();
			$historial[0]->mes =  date("Y-F", strtotime($date_init));
			array_push($historial_array, $historial[0]);
			$date_init = date('Y-m-01', strtotime($date_init.' +1 month'));
		}
		$ensayos = $db->query($sql_ensayos.' LIMIT 0,5')->getResult();
		$solicitud = $db->query($sql_solicitues)->getResult();
		return  view('pages/home',[
			'ensayos_r' 	=> $ensayos,
			'solicitudes' 	=> $solicitud[0],
			'historial' 	=> $historial_array
		]);
	}

	public function about()
    {
        return view('pages/about');
    }
    
}
