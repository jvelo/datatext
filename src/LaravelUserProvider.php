<?php

namespace Jvelo\Paidia;

class LaravelUserProvider implements UserProvider {

    public function getCurrentUserId()
    {
        $user = Auth::getUser();
        if (!is_null($user)) {
            return $user->email;
        } else {
            return 'guest';
        }
    }
}