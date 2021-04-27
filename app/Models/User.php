<?php


namespace App\Models;


use CodeIgniter\Model;

class User extends Model
{
    protected $table            = 'cms_users';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name', 'username', 'email', 'password', 'status', 'role_id', 'photo', 'id'];

}