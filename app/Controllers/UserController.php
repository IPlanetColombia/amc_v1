<?php


namespace App\Controllers;


use App\Models\User;
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

        return view('users/perfile',[ 'data' => $data, 'validation' => $validation]);
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
            $data = [
                'name'          => $this->request->getPost('name'),
                'username'      => $this->request->getPost('username'),
                'email'         => $this->request->getPost('email'),
                'phone'         => $this->request->getPost('phone'),
                'direction'     => $this->request->getPost('direction'),
                'id'            => session('user')->id,
            ];

            $url = API('perfile');

            $result = API_POST( $url, $data);

            // var_dump($result->success);

            if($result->success){
                $session = session();
                $session->set('user', $result->user);
                return redirect()->back()->with('success', 'Datos guardado correctamente.');
            }else{
                return redirect()->back()->with('error_username', 'El nombre de usuario ya existe.');
            }

            // $user = new User();
            // $user->set($data)->where(['id' => session('user')->id])->update();
            // return redirect()->back()->with('success', 'Datos guardado correctamente.');
        } else {
            return redirect()->back()->withInput();
        }
    }


    public function updatePhoto()
    {
        $user = new User();
        if($img = $this->request->getFile('photo')){
            $newName = $img->getRandomName();
            $img->move('upload/images', $newName);
        }
        if($user->update(['photo' => $newName], ['id' => session('user')->id])){
            session('user')->photo = $newName;
            return redirect()->to('/perfile');
        }
    }
}