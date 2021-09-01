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
        $analisis = $analisis->get()->getResult(); 
        $validation = Services::validation();
        return view('funcionarios/remicion', [
            'analisis' => $analisis,
            'validation' => $validation
        ]);
    }

    public function remicion_empresa(){
        $validation = Services::validation();
        $message = [
                'nit' => [
                    'required'      => 'Campo obligatorio.',
                    'max_length'    => 'La identificación no debe tener mas de 30 caracteres.',
                    'is_unique'     => 'La identificación ya se encuentra registrada.'
                ],
                'empresa' => [
                    'required'      => 'Campo obligatorio.',
                    'max_length'    => 'El nombre de la empresa no debe tener mas de 100 caracteres.',
                    'is_unique'     => 'La empresa ya se encuentra registrada.'
                ],
                'username' => [
                    'required'      => 'Campo obligatorio.',
                    'max_length'    => 'El nombre de la empresa no debe tener mas de 100 caracteres.',
                    'is_unique'     => 'El usuario ya se encuentra registrado.'
                ],
                'email' => [
                    'required'  => 'Campo obligatorio.',
                    'is_unique' => 'El correo ya se encuentra registrado.'
                ],
                'cargo' => [
                    'required' => 'Campo obligatorio.'
                ],
                'name_contact'      => [
                    'required'      => 'Campo obligatorio.',
                    'max_length'    => 'El nombre del contacto no debe tener mas de 100 caracteres.'
                ],
                'phone' => [
                    'required'      => 'Campo obligatorio.',
                    'max_length'    => 'El telefono no debe tener mas de 20 caracteres.'
                ],
                'direction' => [
                    'required'      => 'Campo obligatorio.',
                    'max_length'    => 'La dirección no debe tener mas de 100 caracteres.'
                ]
            ];
        $rules = [
                'nit'           => 'required|max_length[30]|is_unique[usuario.id]',
                'empresa'       => 'required|max_length[30]|is_unique[usuario.name]',
                'cargo'         => 'required',
                'name_contact'  => 'required|max_length[100]',
                'phone'         => 'required|max_length[20]',
                'direction'     => 'required|max_length[100]',
            ];

        $empresa = $this->request->getPost('empresa');
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
            $empresas = $data->where(['name' => $empresa])->get()->getResult();
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
            if ($this->validate($rules, $message)){

                $rules['username']      = 'required|max_length[30]|is_unique[usuario.username]';
                $data = [
                    'id' => $this->request->getPost('nit'),
                    'name' => $this->request->getPost('empresa'),
                    'username' => $this->request->getPost('username'),
                    'email' => $this->request->getPost('email'),
                    'password' => md5( $this->request->getPost('nit') ),
                    'usertype' => 'Registered',
                    'block' => 1,
                    'registerDate' => $this->request->getPost('date').' '.$this->request->getPost('hora'),
                    'lastvisitDate' => '0000-00-00 00:00:00',
                    'use_cargo' => $this->request->getPost('cargo'),
                    'use_nombre_encargado' => $this->request->getPost('name_contact'),
                    'use_telefono' => $this->request->getPost('phone'),
                    'use_fax' => $this->request->getPost('fax'),
                    'use_direccion' => $this->request->getPost('direction'),
                    'pyme' => 'No'
                ];
                $cliente = new Cliente();
                $cliente->insert($data);
                return json_encode(['success' => 'Empresa creada con exito']);
            } else {
                return json_encode($validation->getErrors());
            }
        }else if($buscar == 4){
            $id = $this->request->getPost('nit');
            $rules['nit']       = 'required|max_length[100]|is_unique[usuario.id, id, '.$id.']';
            $rules['empresa']   = 'required|max_length[100]|is_unique[usuario.name, id, '.$id.']';
            $rules['email']     = 'required|valid_email|is_unique[usuario.email,id,'.$id.']|max_length[100]';
            if ($this->validate($rules, $message)){
                $data = [
                    'name' => $this->request->getPost('empresa'),
                    'email' => $this->request->getPost('email'),
                    'use_cargo' => $this->request->getPost('cargo'),
                    'use_nombre_encargado' => $this->request->getPost('name_contact'),
                    'use_telefono' => $this->request->getPost('phone'),
                    'use_fax' => $this->request->getPost('fax'),
                    'use_direccion' => $this->request->getPost('direction'),
                ];
                $cliente = new Cliente();
                $cliente
                    ->set($data)
                    ->where(['id' => $this->request->getPost('nit')])
                    ->update();
                return json_encode(['success' => 'Empresa actualizada con exito']);
            } else {
                return json_encode($validation->getErrors());
            }
        }
    }

    public function remicion_muestra(){
        $producto = $this->request->getPost('producto');
        $buscar = $this->request->getPost('buscar');
        if($buscar == 1){
            $tabla = new Producto();
            $productos = $tabla->like('pro_nombre', $producto)->select('pro_nombre')->orderBy('pro_nombre', 'ASC')->get()->getResult();
            $data = array();
            foreach($productos as $key => $producto){
                $data[$producto->pro_nombre] = null;
            }
            return json_encode($data);
        }
    }
}