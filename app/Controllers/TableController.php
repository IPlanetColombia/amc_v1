<?php


namespace App\Controllers;


use App\Traits\Grocery;
use App\Models\MenuCliente;
use App\Models\MenuFuncionarios;
use CodeIgniter\Exceptions\PageNotFoundException;

class TableController extends BaseController
{
    use Grocery;

    private $crud;

    public function __construct()
    {
        $this->crud = $this->_getGroceryCrudEnterprise();
        $this->crud->setSkin('bootstrap-v3');
        $this->crud->setLanguage('Spanish');
    }

    public function index($data)
    {
        if (session('user')->funcionario) $menu = new MenuFuncionarios();
        else $menu = new MenuCliente();
        $component = $menu->where(['table' => $data, 'component' => 'table'])->get()->getResult();
        if($component) {
            $this->crud->setTable($component[0]->table);
            switch ($component[0]->table) {
                case 'usuario':
                    $this->crud->displayAs([
                        'name'                  => 'Nombre',
                        'username'              => 'Usuario',
                        'email'                 => 'Email',
                        'usertype'              => 'Rol',
                        'registerDate'          => 'Registro',
                        'lastvisitDate'         => 'Ultima visita',
                        'use_cargo'             => 'Cargo',
                        'use_nombre_encargado'  => 'Encargado',
                        'use_telefono'          => 'Teléfono',
                        'use_fax'               => 'Fax',
                        'use_direccion'         => 'Dirección',
                    ]);
                    $this->crud->columns(['name', 'username', 'email', 'usertype', 'registerDate', 'lastvisitDate', 'use_cargo','use_nombre_encargado','use_telefono','use_fax','use_direccion']);                
                    if (session('user')->username){
                        $this->crud->where(['usuario.id = ?' => session('user')->id ]);
                        $this->crud->unsetOperations();
                    }
                    break;
                
                default:
                    # code...
                    break;
            }
            $output = $this->crud->render();
            if (isset($output->isJSONResponse) && $output->isJSONResponse) {
                header('Content-Type: application/json; charset=utf-8');
                echo $output->output;
                exit;
            }

            $this->viewTable($output, $component[0]->title, $component[0]->description);
        } else {
            throw PageNotFoundException::forPageNotFound();
        }
    }
}