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



class FuncionarioController extends BaseController
{
    public function remision(){
        $analisis = new Analisis();
        $muestreo = new Muestreo();
        $muestreo_verifica = $muestreo->where(['mue_estado' => 0])->get()->getResult();
        $analisis = $analisis->get()->getResult(); 
        $validation = Services::validation();
        return view('funcionarios/remision', [
            'analisis' => $analisis,
            'validation' => $validation,
            'muestreo_verifica' => $muestreo_verifica
        ]);
    }

    public function remision_empresa(){
        $validation = Services::validation();
        $message = [
                'frm_nit' => [
                    'required'      => 'Campo obligatorio.',
                    'max_length'    => 'La identificación no debe tener mas de 30 caracteres.',
                    'is_unique'     => 'La identificación ya se encuentra registrada.'
                ],
                'frm_nombre_empresa' => [
                    'required'      => 'Campo obligatorio.',
                    'max_length'    => 'El nombre de la empresa no debe tener mas de 100 caracteres.',
                    'is_unique'     => 'La empresa ya se encuentra registrada.'
                ],
                'username' => [
                    'required'      => 'Campo obligatorio.',
                    'max_length'    => 'El nombre de la empresa no debe tener mas de 100 caracteres.',
                    'is_unique'     => 'El usuario ya se encuentra registrado.'
                ],
                'frm_correo' => [
                    'required'  => 'Campo obligatorio.',
                    'is_unique' => 'El correo ya se encuentra registrado.'
                ],
                'frm_contacto_cargo' => [
                    'required' => 'Campo obligatorio.'
                ],
                'frm_contacto_nombre'      => [
                    'required'      => 'Campo obligatorio.',
                    'max_length'    => 'El nombre del contacto no debe tener mas de 100 caracteres.'
                ],
                'frm_telefono' => [
                    'required'      => 'Campo obligatorio.',
                    'max_length'    => 'El telefono no debe tener mas de 20 caracteres.'
                ]
            ];
        $rules = [
                'frm_nombre_empresa'    => 'required|max_length[30]|is_unique[usuario.name]',
                'frm_contacto_cargo'    => 'required',
                'frm_contacto_nombre'   => 'required|max_length[100]',
                'frm_telefono'          => 'required|max_length[20]',
            ];
        $empresa = $this->request->getPost('frm_nombre_empresa');
        $buscar = $this->request->getPost('buscar');
        $empresa_nueva = $this->request->getPost('empresa_nueva');
        if($buscar == 1){
            $data = new Cliente();
            $empresas = $data->like('name', $empresa)->select('name')->orderBy('name', 'ASC')->get()->getResult();
            $data = array();
            foreach($empresas as $key => $empresa){
                $data[$empresas[$key]->name] = null;
            }
            return json_encode($data);
        }else if($buscar == 2){
            $data = new Cliente();
            $empresas = $data->where(['name' => $empresa])->get()->getResult();
            if( empty($empresas[0]) ){
                return json_encode(['validation' => true, 'empresa' => $empresa]);
            }
            $empresas[0]->password = null;
            return json_encode($empresas[0]);
        }else if($empresa_nueva == 0){ // Creamos empresa
            $rules['frm_nit'] = 'required|max_length[30]|is_unique[usuario.id]';
            $rules['username']  = 'required|max_length[30]|is_unique[usuario.username]';
            $rules['frm_correo']     = 'required|valid_email|is_unique[usuario.email]|max_length[100]';
            if ($this->validate($rules, $message)){
                $data = [
                    'id' => $this->request->getPost('frm_nit'),
                    'name' => $this->request->getPost('frm_nombre_empresa'),
                    'username' => $this->request->getPost('username'),
                    'email' => $this->request->getPost('frm_correo'),
                    'password' => md5( $this->request->getPost('frm_nit') ),
                    'usertype' => 'Registered',
                    'block' => 1,
                    'registerDate' => date('Y-m-d H:i:s'),
                    'lastvisitDate' => date('Y-m-d H:i:s'),
                    'use_cargo' => $this->request->getPost('frm_contacto_cargo'),
                    'use_nombre_encargado' => $this->request->getPost('frm_contacto_nombre'),
                    'use_telefono' => $this->request->getPost('frm_telefono'),
                    'use_fax' => $this->request->getPost('frm_fax'),
                    'use_direccion' => $this->request->getPost('frm_direccion'),
                    'pyme' => 'No'
                ];
                $cliente = new Cliente();
                $cliente->insert($data);
                return json_encode(['success' => 'Empresa creada con exito']);
            } else {
                return json_encode($validation->getErrors());
            }
        }else if($empresa_nueva == 1){ //Editamos empresa
            $id = $this->request->getPost('frm_nombre_empresa2');
            $rules['frm_nit']       = 'required|max_length[100]|is_unique[usuario.id, id, '.$id.']';
            $rules['frm_nombre_empresa2']       = 'required|max_length[100]|is_unique[usuario.id, id, '.$id.']';
            $rules['frm_nombre_empresa']   = 'required|max_length[100]|is_unique[usuario.name, id, '.$id.']';
            $rules['frm_correo']     = 'max_length[100]|required|is_unique[usuario.email, id, '.$id.']';
            if ($this->validate($rules, $message)){
                $data = [
                    'name' => $this->request->getPost('frm_nombre_empresa'),
                    'email' => $this->request->getPost('frm_correo'),
                    'use_cargo' => $this->request->getPost('frm_contacto_cargo'),
                    'use_nombre_encargado' => $this->request->getPost('frm_contacto_nombre'),
                    'use_telefono' => $this->request->getPost('frm_telefono'),
                    'use_fax' => $this->request->getPost('frm_fax'),
                    'use_direccion' => $this->request->getPost('frm_direccion'),
                ];
                $cliente = new Cliente();
                $cliente
                    ->set($data)
                    ->where(['id' => $id])
                    ->update();
                return json_encode(['success' => 'Empresa actualizada con exito']);
            } else {
                return json_encode($validation->getErrors());
            }
        }
    }

    public function remision_muestra(){
        $producto = $this->request->getPost('frm_producto');
        $buscar = $this->request->getPost('buscar');
        if($buscar == 1){
            $tabla = new Producto();
            $productos = $tabla->like('pro_nombre', $producto)->select('pro_nombre')->get()->getResult();
            $data = [];
            foreach($productos as $key => $producto){
                $data[$producto->pro_nombre] = null;
            }
            return json_encode($data);
        }else if($buscar == 2){
            $data = muestra_tabla($producto);
            return json_encode($data);
        }else if($buscar == 3){
            $forms = $this->request->getPost();
            $data = detalles_tabla($forms);
            return json_encode($data);
        }else if($buscar == 4){
            $forms = $this->request->getPost();
            $accion = $this->request->getPost('accion');
            $data = guardar_remision($forms, $accion);
            return json_encode($data);
        }else if($buscar == 5){
            $certificado = $this->request->getPost('id_certificacion');
            $data = delete_detail_list($certificado);
            return json_encode($data);
        }
    }

    public function remision_ticket($id_certificacion){
        $certificado = new Certificacion();
        $certificado = $certificado->where(['id_certificacion' => $id_certificacion])->get()->getResult();
        $muestreo = new Muestreo();
        $muestreo = $muestreo->where(['id_muestreo' => $certificado[0]->id_muestreo])->get()->getResult();
        $muestreo_detalle = new MuestreoDetalle();
        $muestreo_detalle = $muestreo_detalle->where(['id_muestra_detalle' => $certificado[0]->id_muestreo_detalle])->get()->getResult();
        $muestreo_tipo =   procesar_registro_fetch('muestra_tipo_analisis', 'id_muestra_tipo_analsis',$muestreo_detalle[0]->id_tipo_analisis);
        $producto = new Producto();
        $producto = $producto->where(['id_producto' => $muestreo_detalle[0]->id_producto])->get()->getResult();
        $norma =   procesar_registro_fetch('norma', 'id_norma',$producto[0]->id_norma);
        $aux_codigo =   construye_codigo_amc($muestreo_detalle[0]->id_muestra_detalle);
        $ensayo = new Ensayo();
        $ensayo = $ensayo->where(['id_producto' => $muestreo_detalle[0]->id_producto])->get()->getResult();
        $data = [
            'certificado'       => $certificado[0],
            'muestreo'          => $muestreo[0],
            'muestreo_detalle'  => $muestreo_detalle[0],
            'muestreo_tipo'     => $muestreo_tipo[0],
            'producto'          => $producto[0],
            'norma'             => $norma[0],
            'codigo'            => $aux_codigo,
            'ensayos'           => $ensayo
        ];
        // return var_dump($data);
        $mpdf = new \Mpdf\Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'Letter',
        ]);
        $html = view('views_mpdf/ticket_2',$data);
        $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
        $this->response->setHeader('Content-Type', 'application/pdf');
        $name = 'muestras_analisis_'.$certificado[0]->id_certificacion.'.pdf';
        $mpdf->Output($name,'D');
    }



    // ------------------------------------- EDITAR REMISIONES
    public function remision_edit(){
        $analisis = new Analisis();
        $analisis = $analisis->get()->getResult(); 
        $certificados = new Certificacion();
        $certificados = $certificados->select('certificado_nro')->orderBy('certificado_nro', 'DESC')->limit(2000)->get()->getResult();
        foreach ($certificados as $key => $certificado) {
            $data[$certificado->certificado_nro] = null;
        }
        $ultimo = $certificados[1999]->certificado_nro;
        $primero = $certificados[0]->certificado_nro;
        return view('funcionarios/remision_edit', [
            'certificados'  => $data,
            'ultimo'        => $ultimo,
            'primero'       => $primero,
            'analisis'      => $analisis
        ]);
    }
    public function remision_edit_muestra(){
        $db = \Config\Database::connect();
        $buscar = $this->request->getPost('buscar');
        $certificado = $this->request->getPost('frm_certificados_editar');
        $certificado = procesar_registro_fetch('certificacion', 'certificado_nro', $certificado);
        if($buscar == 0){
            if(empty($certificado[0]))
                return json_encode(['result' => false]);
            else{
                $certificado = $certificado[0];
                $muestra = procesar_registro_fetch('muestreo', 'id_muestreo', $certificado->id_muestreo);
                $cliente = procesar_registro_fetch('usuario', 'id', $muestra[0]->id_cliente);
                $cliente[0]->fecha = date('Y-m-d', strtotime($muestra[0]->mue_fecha_muestreo));
                $cliente[0]->hora = date('H:i:s', strtotime($muestra[0]->mue_fecha_muestreo));
                $tabla = imprime_detalle_muestras($muestra[0]->id_muestreo, 1);
                $conceptos = mensaje_resultado($certificado->certificado_nro);
                return json_encode([
                    'result'            =>  true,
                    'certificado'       =>  $certificado,
                    'muestra'           =>  $muestra[0],
                    'cliente'           =>  $cliente[0],
                    'tabla'             =>  $tabla,
                    'conceptos'         =>  $conceptos,
                ]);
            }
        }else if($buscar == 1){ //Editamos empresa
            $id_cliente = $this->request->getPost('frm_nit');
            $id_muestreo = $this->request->getPost('frm_id_muestra');
            if(empty($id_cliente)){
                return json_encode(['vacio' => true, 'mensaje' => 'No se a seleccionado la empresa.']);
            }
            if(empty($id_muestreo)){
                return json_encode(['vacio' => true, 'mensaje' => 'No se ha seleccionado el certificado']);
            }
            $muestra = new Muestreo();
            $muestra->set(['id_cliente' => $id_cliente])
                ->where(['id_muestreo' => $id_muestreo])
                ->update();
            $cliente = new Cliente();
            $data = [
                'use_cargo'             => $this->request->getPost('frm_contacto_cargo'),
                'use_nombre_encargado'  => $this->request->getPost('frm_contacto_nombre'),
                'use_telefono'          => $this->request->getPost('frm_telefono'),
                'use_fax'               => $this->request->getPost('frm_fax'),
                'use_direccion'         => $this->request->getPost('frm_direccion'),
            ];
            $cliente->set($data)->where(['id' => $id_cliente])->update();
            return json_encode(['success' => true]);
        }else if($buscar == 2){ //Buscar detalle
            $idMuestraDetalle = $this->request->getPost('id_muestra_detalle');
            $detalle = procesar_registro_fetch('muestreo_detalle', 'id_muestra_detalle', $idMuestraDetalle);
            $producto = procesar_registro_fetch('producto', 'id_producto', $detalle[0]->id_producto);
            $tabla = muestra_tabla($producto[0]->pro_nombre, $idMuestraDetalle);
            return json_encode([
                'detalle'   => $detalle[0],
                'producto'  => $producto[0],
                'tabla'     => $tabla,
            ]);
        }else if($buscar == 3){
            $forms = $this->request->getPost();
            $idMuestraDetalle = $this->request->getPost('id_muestra_detalle');
            $data = detalles_tabla($forms, $idMuestraDetalle);
            return json_encode($data);
        }else if($buscar == 4){
            $producto = $this->request->getPost('frm_producto');
            $idMuestraDetalle = $this->request->getPost('id_muestra_detalle');
            $data = muestra_tabla($producto, $idMuestraDetalle);
            return json_encode($data);
        }else if($buscar == 5){
            $data = [
                'id_mensaje_resultado' => $this->request->getPost('frm_mensaje_resultado'),
                'id_mensaje_comentario' => $this->request->getPost('frm_mensaje_observacion'),
            ];
            $id = $this->request->getPost('frm_id_certificado');
            $db->table('certificacion_vs_mensaje')->set($data)->where(['id_certificacion' => $id])->update();
            return json_encode('Mensaje actualizado.');
        }
    }

    // --------------------------------------- Resultados
    public function resultados(){
        $analisis = new Analisis();
        $analisis = $analisis->get()->getResult(); 
        return view('funcionarios/resultados',[
            'analisis' => $analisis,
        ]);
    }
}