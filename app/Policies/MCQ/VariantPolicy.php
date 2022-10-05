<?php

namespace App\Policies\MCQ;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class VariantPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_m::c::q::variant');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->can('view_m::c::q::variant');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->can('create_m::c::q::variant');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @return bool
     */
    public function update(User $user)
    {
        return $user->can('update_m::c::q::variant');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @return Response|bool
     */
    public function delete(User $user)
    {
        return $user->can('delete_m::c::q::variant');
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param User $user
     * @return Response|bool
     */
    public function deleteAny(User $user)
    {
        return $user->can('delete_any_m::c::q::variant');
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param User $user
     * @return Response|bool
     */
    public function forceDelete(User $user)
    {
        return $user->can('force_delete_m::c::q::variant');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param User $user
     * @return Response|bool
     */
    public function forceDeleteAny(User $user)
    {
        return $user->can('force_delete_any_m::c::q::variant');
    }

    /**
     * Determine whether the user can restore.
     *
     * @param User $user
     * @return Response|bool
     */
    public function restore(User $user)
    {
        return $user->can('restore_m::c::q::variant');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param User $user
     * @return Response|bool
     */
    public function restoreAny(User $user)
    {
        return $user->can('restore_any_m::c::q::variant');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param User $user
     * @return Response|bool
     */
    public function replicate(User $user)
    {
        return $user->can('replicate_m::c::q::variant');
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param User $user
     * @return Response|bool
     */
    public function reorder(User $user)
    {
        return $user->can('reorder_m::c::q::variant');
    }

}