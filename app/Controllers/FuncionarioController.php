<?php

namespace App\Controllers;
use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\Muestreo;
use Config\Services;



class FuncionarioController extends BaseController
{
    public function remicion(){
        return view('funcionarios/remicion');
    }
}