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
                case 'tecnica':
                    $this->crud->displayAs([
                        'nor_nombre'        => 'Nombre',
                        'nor_descripcion'   => 'Descripción'
                    ]);
                    break;
                case 'norma':
                    $this->crud->displayAs([
                        'nor_nombre'        => 'Nombre',
                        'nor_descripcion'   => 'Descripción'
                    ]);
                    break;
                case 'producto':
                    $this->crud->displayAs([
                        'pro_nombre'        => 'Nombre',
                        'pro_descripcion'   => 'Descripción',
                        'id_norma'          => 'Norma'
                    ]);
                    $this->crud->setRelation('id_norma', 'norma', 'nor_nombre');
                    break;
                case 'parametro':
                    $this->crud->displayAs([
                        'par_nombre'        => 'Nombre',
                        'par_descripcion'   => 'Descripción',
                        'par_estado'        => 'Estado',
                        'par_irca'          => 'Irca',
                    ]);
                    $this->crud->setRelation('id_tecnica', 'tecnica', 'nor_nombre');
                    break;
                case 'ensayo':
                    $this->crud->displayAs([
                        'id_producto'   => 'Producto',
                        'id_parametro'  => 'Parametro',
                        'refe_bibl'     => 'Referencia Bibliografica'
                    ]);
                    $this->crud->setRelation('id_producto', 'producto', 'pro_nombre');
                    $this->crud->setRelation('id_parametro', 'parametro', 'par_nombre');
                    break;
                
                default:
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