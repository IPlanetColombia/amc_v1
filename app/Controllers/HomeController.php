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
    
}
