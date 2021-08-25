<?php


namespace App\Models;


use CodeIgniter\Model;

class Cliente extends Model
{
    protected $table            = 'usuario';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name', 'username', 'email', 'password', 'status', 'role_id', 'photo', 'id'];

}