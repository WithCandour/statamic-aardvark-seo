<?php

namespace WithCandour\AardvarkSeo\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Statamic\Auth\User;

class AardvarkSettingsPolicy
{
    use HandlesAuthorization;

    public function index(User $user, string $handle = 'general')
    {
        return $user->hasPermission("view aardvark {$handle} settings");
    }

    public function update(User $user, string $handle = 'general')
    {
        return $user->hasPermission("update aardvark {$handle} settings");
    }
}
