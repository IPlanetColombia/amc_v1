<?php


namespace App\Filters;


use App\Models\PermissionCliente;
use App\Models\PermissionFuncionarios;
use CodeIgniter\Config\Services;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PermissionFilter implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {

        $request = Services::request();
        $url = $request->uri->getSegment(1);
        $method =  $request->uri->getSegment(2);
        if ( session('user')->funcionario ){
            $permission = new PermissionFuncionarios();
            $aux_menu = 'menus_funcionarios';
            $aux_perm = 'permissions_funcionarios';
        }
        else{
            $permission = new PermissionCliente();
            $aux_menu = 'menus_cliente';
            $aux_perm = 'permissions_cliente';
        }
        if($url == 'table' || $url == 'config') {
            $permission->select('*')->join($aux_menu, $aux_menu.'.id = '.$aux_perm.'.menu_id');
            if(session('user')->funcionario)
                $permission->where([$aux_menu.'.url' =>  $method, 'usr_rol' => session('user')->usr_rol ] );
            else
                $permission->where([$aux_menu.'.url' =>  $method, 'typeUser' => session('user')->usertype ] );
                // ->join('roles', 'roles.id = permissions.role_id')
            $data = $permission->get()->getResult();
            if(session('user')->funcionario){
                if(count($data) == 0 && session('user')->usr_rol != 1){
                    echo  view('errors/html/error_401');
                   exit;
               }
            }else if(count($data) == 0 && session('user')->usertype != 'Administrador') {
               echo view('errors/html/error_401');
               exit;
            }
        } else {
            if($method != 'home') {
                $permission->select('*')->join($aux_menu, $aux_menu.'.id = '.$aux_perm.'.menu_id');
                if( session('user')->funcionario )
                    $permission->where([$aux_menu.'.url' => $method, 'usr_rol' => session('user')->usr_rol ]);
                else
                    $permission->where([$aux_menu.'.url' => $method, 'typeUser' => session('user')->usertype ]);
                $data = $permission->get()->getResult();
                if( session('user')->funcionario ){
                    if(!$data && session('user')->usr_rol != 1){
                        echo view('errors/html/error_401');
                       exit;
                   }
                }else{
                    if(!$data && session('user')->usertype != 'Administrador') {
                       echo view('errors/html/error_401');
                       exit;
                   }
                }
            }
        }


    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // TODO: Implement after() method.
    }
}