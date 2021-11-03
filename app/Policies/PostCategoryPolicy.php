<?php

namespace App\Policies;

use App\Models\PostCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PostCategory  $postCategory
     * @return mixed
     */
    public function view(User $user, PostCategory $postCategory)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create-post-category');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PostCategory  $postCategory
     * @return mixed
     */
    public function update(User $user, PostCategory $postCategory)
    {
        return $user->hasPermissionTo('update-post-category');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PostCategory  $postCategory
     * @return mixed
     */
    public function delete(User $user, PostCategory $postCategory)
    {
        // return $user->hasPermissionTo('delete-post-category');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PostCategory  $postCategory
     * @return mixed
     */
    public function restore(User $user, PostCategory $postCategory)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PostCategory  $postCategory
     * @return mixed
     */
    public function forceDelete(User $user, PostCategory $postCategory)
    {
        //
    }
}
