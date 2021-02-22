<?php

namespace App\Controller\ApiPlatform;


use App\Entity\Users\User;
use Symfony\Component\HttpFoundation\Request;

class Register
{
    public function __invoke(User $user, Request $request)
    {
    
        return $user;
    }
}
