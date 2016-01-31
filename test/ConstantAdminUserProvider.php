<?php

namespace Jvelo\Paidia;

class ConstantAdminUserProvider implements UserProvider
{
    public function getCurrentUserId()
    {
        return 'admin@example.com';
    }
}