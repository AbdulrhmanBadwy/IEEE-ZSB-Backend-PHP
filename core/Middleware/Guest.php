<?php

namespace core\Middleware;

class Guest
{
    public function handel()
    {
        if ($_SESSION['user'] ?? false) {
            header('location: /');
            exit();
        }
    }
}