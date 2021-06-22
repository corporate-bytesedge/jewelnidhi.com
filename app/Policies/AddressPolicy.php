<?php

namespace App\Policies;

use App\User;
use App\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
{
    use HandlesAuthorization;

    public function read(User $user)
    {
        return $this->getPermission($user, 117);
        
    }
    public function update(User $user)
    {
     
        return $this->getPermission($user, 118);
    }

    public function delete(User $user)
    {
        return $this->getPermission($user, 115);
    }

    public function view(User $user, Customer $customer)
    {
       
        return $user->id == $customer->user_id;
    }

    // public function update(User $user, Customer $customer)
    // {
    //     return $user->id == $customer->user_id;
    // }

    // public function delete(User $user, Customer $customer)
    // {
    //     return $user->id == $customer->user_id;
    // }

    private function getPermission($user, $permission_id)
    {
        if($user->role) {
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
