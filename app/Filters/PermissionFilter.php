<?php


namespace App\Filters;


use App\Models\Permission;
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
        $permission = new Permission();
        if($url == 'table' || $url == 'config') {
            $data = $permission->select('*')
                ->join('menus', 'menus.id = permissions.menu_id')
                // ->join('roles', 'roles.id = permissions.role_id')
                ->where(['menus.url' =>  $method, 'usertype' => session('user')->usertype ] )
                ->get()
                ->getResult();
            if(count($data) == 0 && session('user')->usertype != 'Registered') {
            // if(count($data) == 0 && session('user')->usertype != 'Super Administrator') {
               echo  view('errors/html/error_401');
               exit;
            }
        } else {
            if($url != 'amc-laboratorio') {

                $data = $permission->select('*')
                    ->join('menus', 'menus.id = permissions.menu_id')
                    // ->join('roles', 'roles.id = permissions.role_id')
                    ->where(['menus.url' => $url . '/' . $method, 'role_id' => session('user')->usertype])
                    ->get()
                    ->getResult();
                if (!$data && session('user')->usertype != 'Super Administrator') {
                    echo  view('errors/html/error_401');
                    exit;
                }
            }
        }


    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // TODO: Implement after() method.
    }
}