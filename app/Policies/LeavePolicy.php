<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Leave;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeavePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_leave');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Leave $leave)
    {
        return $user->can('view_leave');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create_leave');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Leave $leave)
    {
        return $user->can('update_leave');
        
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Leave $leave)
    {
        return $user->can('delete_leave');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Leave $leave)
    {
        // Opsional: Jika Anda menggunakan soft deletes
        return $user->can('restore_leave');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Leave $leave)
    {
        // Opsional: Jika Anda menggunakan soft deletes
        return $user->can('force_delete_leave');
    }

    /**
     * Determine whether the user can approve a leave request.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function approve(User $user)
    {
        return $user->can('approve_leave');
    }

    /**
     * Determine whether the user can reject a leave request.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function reject(User $user)
    {
        return $user->can('reject_leave');
    }
}