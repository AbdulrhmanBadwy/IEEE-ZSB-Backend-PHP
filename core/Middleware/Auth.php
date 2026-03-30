<?php

namespace core\Middleware;
class Auth
{
    public function handel()
    {
        if (!$_SESSION['user'] ?? false) {
            header('location: /');
            exit();
        }
    }
}