<?php


namespace App\Controllers;


use App\Models\Configuration;
use App\Traits\Grocery;

class ConfigController extends BaseController
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

        $this->crud->setTable($data);
        switch ($data) {
            case 'cms_users':
                $title = 'Usuarios';
                $subtitle = 'Listado de usuarios.';
                $this->crud->unsetColumns(['usr_clave']);
                $this->crud->fieldType('usr_clave', 'password');
                $this->crud->setRelation('usr_rol', 'cms_rol', 'nombre');
                $this->crud->displayAs([
                    'usr_usuario'   => 'Usuario',
                    'usr_correo'    => 'Email',
                    'usr_rol'       => 'Rol',
                    'usr_estado'    => 'Estado',
                    'usr_foto'      => 'Foto',
                ]);
                break;
            case 'usuario':
                $title = 'Usuarios';
                $subtitle = 'Listado de usuarios.';
                $this->crud->unsetColumns(['password']);
                $this->crud->fieldType('password', 'password');
                $this->crud->callbackBeforeInsert(function ($stateParameters) {
                    $stateParameters->data['password'] = password_hash($stateParameters->data['password'], PASSWORD_DEFAULT);
                    return $stateParameters;
                });
                $this->crud->callbackBeforeUpdate(function ($stateParameters) {
                    if(strlen($stateParameters->data['password']) < 20) {
                        $stateParameters->data['password'] = password_hash($stateParameters->data['password'], PASSWORD_DEFAULT);
                    }
                    return $stateParameters;
                });
                // $this->crud->setRelation('role_id', 'roles', 'name');
                $this->crud->setFieldUpload('photo', 'assets/upload/images', '/assets/upload/images');

                break;
            case 'permissions_cliente':
                $title = 'Permisos';
                $subtitle = 'Listado de permisos.';
                //$this->crud->setRelation('role_id', 'roles', 'name');
                $this->crud->setRelation('menu_id', 'menus_cliente', '{option} - {type}');
                break;
            case 'permissions_funcionarios':
                $title = 'Permisos';
                $subtitle = 'Listado de permisos.';
                $this->crud->setRelation('usr_rol', 'cms_rol', '{nombre} - {usr_rol}');
                $this->crud->setRelation('menu_id', 'menus_funcionarios', '{option} - {type}');
                break;
            case 'menus_cliente':
                $title = 'Opciones del Menu';
                $subtitle = 'Listado de opciones de menu.';
                $this->crud->setTexteditor(['description']);
                $this->crud->setRelation('references', 'menus_cliente', 'option');
                break;
            case 'menus_funcionarios':
                $title = 'Opciones del Menu';
                $subtitle = 'Listado de opciones de menu.';
                $this->crud->setTexteditor(['description']);
                $this->crud->setRelation('references', 'menus_funcionarios', 'option');
                break;
            case 'roles':
                $title = 'Roles';
                $subtitle = 'Listado de roles.';
                break;
            case 'cms_rol':
                $title = 'Roles';
                $subtitle = 'Listado de roles.';
                break;
            case 'notifications':
                $title = 'Notificaciones';
                $subtitle = 'Listado de Notificaciones.';
                $id = session()->get('user');
                $this->crud->fieldType('user_id', 'number', session()->get('user')->id );
                break;
            case 'configurations':
                $title = 'Configuraciones';
                $subtitle = 'Listado de configuraciones.';
                $config = new Configuration();
                $data = $config->findAll();
                $this->crud->setTexteditor(['footer', 'intro']);
                $this->crud->setFieldUpload('background_image', 'assets/img', base_url().'/assets/img');
                $this->crud->setFieldUpload('favicon', 'assets/img', base_url().'/assets/img');
                $this->crud->setFieldUpload('background_img_vertical', 'assets/img', base_url().'/assets/img');

                if (count($data)  > 0) {
                    $this->crud->unsetAdd();
                    $this->crud->unsetDelete();
                }
                break;
        }
        $output = $this->crud->render();
        if (isset($output->isJSONResponse) && $output->isJSONResponse) {
            header('Content-Type: application/json; charset=utf-8');
            echo $output->output;
            exit;
        }

        $this->viewTable($output, $title, $subtitle);
    }


}