<?php

namespace App\Controllers;
use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\Muestreo;
use App\Models\Analisis;
use App\Models\Producto;
use Config\Services;



class FuncionarioController extends BaseController
{
    public function remicion(){
        $analisis = new Analisis();
        $muestreo = new Muestreo();
        $muestreo_verifica = $muestreo->where(['mue_estado' => 0])->get()->getResult();
        $analisis = $analisis->get()->getResult(); 
        $validation = Services::validation();
        return view('funcionarios/remicion', [
            'analisis' => $analisis,
            'validation' => $validation,
            'muestreo_verifica' => $muestreo_verifica
        ]);
    }

    public function remicion_empresa(){
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
                ],
                'frm_direccion' => [
                    'required'      => 'Campo obligatorio.',
                    'max_length'    => 'La dirección no debe tener mas de 100 caracteres.'
                ]
            ];
        $rules = [
                'frm_nit'               => 'required|max_length[30]|is_unique[usuario.id]',
                'frm_nombre_empresa'    => 'required|max_length[30]|is_unique[usuario.name]',
                'frm_contacto_cargo'    => 'required',
                'frm_contacto_nombre'   => 'required|max_length[100]',
                'frm_telefono'          => 'required|max_length[20]',
                'frm_direccion'         => 'required|max_length[100]',
            ];

        $empresa = $this->request->getPost('frm_nombre_empresa');
        $buscar = $this->request->getPost('buscar');
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
            $empresas = $data->like(['name' => $empresa])->get()->getResult();
            if( empty($empresas[0]) ){
                $empresas['sucursal']                  = '';
                $empresas['id']                        = '';
                $empresas['use_cargo']                 = '';
                $empresas['use_nombre_encargado']      = '';
                $empresas['use_telefono']              = '';
                $empresas['use_fax']                   = '';
                $empresas['email']                     = '';
                $empresas['use_direccion']             = '';
                $empresas['fecha']                     = date('Y-m-d');
                $empresas['hora']                      = date('H:i:s');
                return json_encode($empresas);
            }
            $empresas[0]->password = null;
            $empresas[0]->fecha = date('Y-m-d', strtotime($empresas[0]->registerDate));
            $empresas[0]->hora  = date('H:i:s', strtotime($empresas[0]->registerDate));
            return json_encode($empresas[0]);
        }else if($buscar == 3){ // Creamos empresa
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
                    'registerDate' => $this->request->getPost('frm_fecha_muestra').' '.$this->request->getPost('frm_hora_muestra'),
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
        }else if($buscar == 4){ //Editamos empresa
            $id = $this->request->getPost('frm_nit');
            $rules['frm_nit']       = 'required|max_length[100]|is_unique[usuario.id, id, '.$id.']';
            $rules['frm_nombre_empresa']   = 'required|max_length[100]|is_unique[usuario.name, id, '.$id.']';
            $rules['frm_correo']     = 'required|valid_email|is_unique[usuario.email,id,'.$id.']|max_length[100]';
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

    public function remicion_muestra(){
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
            $data = guardar_remicion($forms);
            return json_encode($data);
        }else if($buscar == 5){
            $certificado = $this->request->getPost('id_certificacion');
            $data = delete_detail_list($certificado);
            return json_encode($data);
        }
    }
}