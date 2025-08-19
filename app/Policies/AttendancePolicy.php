<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendancePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_attendance');
    }

    public function view(User $user, Attendance $attendance): bool
    {
        return $user->can('view_attendance');
    }

    public function create(User $user): bool
    {
        return $user->can('create_attendance');
    }

    public function update(User $user, Attendance $attendance): bool
    {
        return $user->can('update_attendance');
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->can('delete_attendance');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_attendance');
    }
}
