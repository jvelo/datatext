<?php

namespace Jvelo\Datatext;

class ConstantAdminUserProvider implements UserProvider
{
    public function getCurrentUserId()
    {
        return 'admin@example.com';
    }
}
