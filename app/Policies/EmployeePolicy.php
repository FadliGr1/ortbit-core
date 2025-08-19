<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_employee');
    }

    public function view(User $user, Employee $employee): bool
    {
        return $user->can('view_employee');
    }

    public function create(User $user): bool
    {
        return $user->can('create_employee');
    }

    public function update(User $user, Employee $employee): bool
    {
        return $user->can('update_employee');
    }

    public function delete(User $user, Employee $employee): bool
    {
        return $user->can('delete_employee');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_employee');
    }
}
