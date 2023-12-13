<?php

namespace App\Modules\Auth\Services\Interfaces;

interface AuthService
{


    public  function  crearteRole($data);
    public  function  createAccount($data);
    public  function  createPermission($data);

}
