<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function read(User $user)
    {
         
        return $this->getPermission($user, 113);
    }

    public function create(User $user)
    {
        return $this->getPermission($user, 114);
    }

    public function update(User $user)
    {
        return $this->getPermission($user, 112);
    }

    public function delete(User $user)
    {
        
        return $this->getPermission($user, 111);
    }

    private function getPermission($user, $permission_id)
    {
         
        if($user->role->permissions) {
            foreach($user->role->permissions as $permission) {
               
                if($permission->id == $permission_id) {
                    return true;
                }
            }
        }
         
        return false;
    }

    public function before($user, $ability)
    {
        if($user->isSuperAdmin()) {
            return true;
        }
    }
}