<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Usuarios;
use Config\Services;



class HomeController extends BaseController
{

	public function index()
	{
		return  view('pages/home');
	}

	public function about()
    {
        return view('pages/about');
    }

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
     //    if (isset(session('user')->usr_usuario)) {
     //    	echo "funcionario";
     //    }else{
     //    	$pwd_session = session('user')->password;
     //    	if (md5($pwd_actual) != $pwd_session ) {
     //    		echo "No son iguales";
     //    	}else{
     //    		echo "Son iguales";
     //    	}
     //    }
    }
}
