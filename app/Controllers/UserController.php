<?php


namespace App\Controllers;


use App\Models\Cliente;
use App\Models\Funcionario;
use Config\Services;


class UserController extends BaseController
{
    public function perfile()
    {
        $validation = Services::validation();
        // $user = new User();
        // $data = $user->select('*, roles.name as role_name, users.name as name')
        //     ->join('roles', 'users.role_id = roles.id')
        //     ->where('users.id', session('user')->id)
        //     ->get()->getResult()[0];
        $data = session('user');
        return view('users/perfile',['data' => $data, 'validation' => $validation]);
    }

    public function updateUser()
    {
        if ($this->validate([
            'name'              => 'required|max_length[45]',
            'username'          => 'required|max_length[40]',
            'email'             => 'required|valid_email|max_length[100]',
        ], [
            'name' => [
                'required'      => 'El campo Nombres y Apellidos es obligatorio.',
                'max_length'    => 'El campo Nombres Y Apellidos no debe tener mas de 45 caracteres.'
            ],
            'username' => [
                'required'      => 'El campo Nombre de Usuario es obligatorio',
                'max_length'    => 'El campo Nombre de Usuario no puede superar mas de 20 caracteres.'
            ],
            'email' => [
                'required'      => 'El campo Correo Electronico es obligatorio.',
            ]

        ])) {
            if(session('user')->funcionario){
                $data = [
                    'nombre'          => $this->request->getPost('name'),
                    'usr_usuario'      => $this->request->getPost('username'),
                    'usr_correo'         => $this->request->getPost('email'),
                ];
                session('user')->nombre = $data['nombre'];
                session('user')->usr_usuario = $data['usr_usuario'];
                session('user')->usr_correo = $data['usr_correo'];
                $user = new Funcionario();
            }else{
                $data = [
                    'name'          => $this->request->getPost('name'),
                    'username'      => $this->request->getPost('username'),
                    'email'         => $this->request->getPost('email'),
                ];
                $user = new Cliente();
                session('user')->name = $data['name'];
                session('user')->username = $data['username'];
                session('user')->email = $data['email'];
            }
            $user->set($data)->where(['id' => session('user')->id])->update();
            
            return redirect()->back()->with('success', 'Datos guardado correctamente.');
        } else {
            return redirect()->back()->withInput();
        }
    }
    
    
    public function updatePhoto()
    {
        $user = new Funcionario();
        $newName = '';
        $img = $this->request->getFile('photo');
        if($img->getSize() > 0){
            $newName = $img->getRandomName();
            $img->move('assets/img/funcionarios', $newName);
        }
        $user->set(['usr_foto' => $newName])->where(['id' => session('user')->id])->update();
        session('user')->usr_foto = $newName;
        return redirect()->back()->with('success', 'Foto guardada correctamente.');
    }
}