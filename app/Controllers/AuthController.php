<?php


namespace App\Controllers;


use App\Models\User;
use App\Models\Usuarios;
use Config\Services;


class AuthController extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function validation()
    {

        $errors = $this->validate([
            'username' => 'required|min_length[1]',
            'password' => 'required|max_length[20]'
        ]);

        if ($errors) {
            $username   = $this->request->getPost('username');
            $password   = $this->request->getPost('password');
            $rol        = $this->request->getPost('rol');
            if ($rol == 1) {
                $user = new User();
                $data = $user
                    // ->select('usuario.*, roles.name as role_name')
                    // ->join('roles', 'roles.id = users.role_id')
                    ->where('usr_usuario', $username)->get()->getResult();
            }else{
                $user = new Usuarios();
                $data = $user
                    ->where('username', $username)->get()->getResult();
            }
            if ($data) {
                if ($rol == 1) {
                    if ($data[0]->usr_estado == 'ACTIVO') {
                        if ( $password === $data[0]->usr_clave ) {
                            $array = array(
                                'frm_user' => $username,
                                'frm_pwd' => $password,
                                'frm_tipo' => $rol
                            );
                            return redirect()->to(base_url().'/')->with('success', $array );
                        } else {
                            return redirect()->to(base_url().'/')->with('errors', 'Las credenciales no concuerdan.');
                        }
                    }else{
                        return redirect()->to(base_url().'/')->with('errors', 'La cuenta no se encuentra activa.');
                    }
                }else{
                    if (md5($password) == $data[0]->password) {
                        $session = session();
                        $session->set('user', $data[0]);
                        return redirect()->to(base_url().'/amc-laboratorio/home');
                    } else {
                        return redirect()->to(base_url().'/')->with('errors', 'Las credenciales no concuerdan.');
                    }
                }
            } else {
                return redirect()->to(base_url().'/')->with('errors', 'Las credenciales no concuerdan.');
            }
        } else {
            return redirect()->to(base_url().'/')->with('errors', 'Las credenciales no concuerdan.');
        }


    }

    public function resetPassword()
    {
        return view('auth/reset_password');
    }

    public function forgotPassword()
    {
        $request = Services::request();
        $user = new User();
        $data = $user->where('usr_usuario', $request->getPost('username'))->get()->getResult();
        if (!isset($data[0])) {
            $user = new Usuarios();
            $data = $user->where('username', $request->getPost('username'))->get()->getResult();
            if (!isset($data[0])) {
                return redirect()->to(base_url().'/reset_password')
                    ->with('danger', 'Las credenciales no coinciden con los datos ingresados.');
            }else{
                // Usuario cliente
                $email = new EmailController();
                $password = $this->encript();
                $user->set(['password' => md5($password)]);
                $user->where('id', $data[0]->id);
                $user->update();
                $email->send('wabox324@gmail.com', 'wabox', $request->getPost('email'), 'Recuperacion de contraseña', password($password));
                return redirect()->to('/reset_password')
                    ->with('success', 'Valida el correo te enviamos una nueva contraseña');
            }
        } else{
            // Usuario Funcionario
            $email = new EmailController();
            $password = $this->encript();
            $user->set(['usr_clave' => $password ]);
            $user->where('id', $data[0]->id);
            $user->update();
            $email->send('wabox324@gmail.com', 'wabox', $request->getPost('email'), 'Recuperacion de contraseña', password($password));
            return redirect()->to('/reset_password')
                ->with('success', 'Valida el correo te enviamos una nueva contraseña');
        }
        // if (count($data) > 0) {
        // } else {
        //     return redirect()->to(base_url().'/reset_password')
        //         ->with('danger', 'Las credenciales no coinciden con los datos ingresados.');
        // }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url().'/');
    }

    public function encript($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


}