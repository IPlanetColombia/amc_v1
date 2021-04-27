<?php

use App\Models\Menu;
use App\Models\Permission;


function menu()
{
    $menu = new Menu();
    if (session()->get('user')->usertype == 'Super Administrator') {
        $data = $menu->where(['type' => 'primario', 'status' => 'active'])
            ->get()
            ->getResult();
    } else {
        $permission = new Permission();
        $data = $permission->select('menus.*')
            ->where('typeUser', session()->get('user')->usertype)
            ->where('menus.type', 'primario')
            ->join('menus', 'menus.id = permissions.menu_id')
            // ->join('roles', 'roles.id = permissions.role_id')
            ->get()
            ->getResult();
    }
    return $data;
}

function submenu($refences)
{
    $menu = new Menu();
    if (session()->get('user')->usertype == 'Super Administrator') {
        $data = $menu->where(['type' => 'secundario', 'status' => 'active', 'references' => $refences])
            ->get()
                ->getResult();
    } else {
        $permission = new Permission();
        $data = $permission->select('menus.*')
            ->where('typeUser', session()->get('user')->usertype)
            ->where('menus.type', 'secundario')
            ->where('menus.references', $refences)
            ->join('menus', 'menus.id = permissions.menu_id')
            // ->join('roles', 'roles.id = permissions.role_id')
            ->get()
            ->getResult();
    }
    return $data;
}

function countMenu($references)
{
    $menu = new Menu();
    $data = $menu->where(['type' => 'secundario', 'status' => 'active', 'references' => $references])
        ->get()
        ->getResult();
    if (count($data) > 0) {
        return true;
    }
    return false;
}

function urlOption($references = null)
{
    if ($references) {
        $menu = new Menu();
        $data = $menu->find($references);
        if ($data['component'] == 'table') {
            return base_url().'/table/' . $data['url'];
        } else if ($data['component'] == 'controller') {
            return base_url().'/' . $data['url'];
        }
    } else {
        return 'JavaScript:void(0)';
    }

}

function isActive($data)
{
    if(base_url(uri_string()) == base_url($data)) {
        return 'active';
    }
}