<?php

namespace App\Policies;

use App\User;
use App\SourceGroup;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class SourceGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the sourceGroup.
     *
     * @param  \App\User  $user
     * @param  \App\SourceGroup  $sourceGroup
     * @return mixed
     */
    public function view(User $user, SourceGroup $sourceGroup)
    {
        return  Gate::allows($sourceGroup['SRC_GRP_CD']);
    }

    /**
     * Determine whether the user can create sourceGroups.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the sourceGroup.
     *
     * @param  \App\User  $user
     * @param  \App\SourceGroup  $sourceGroup
     * @return mixed
     */
    public function update(User $user, SourceGroup $sourceGroup)
    {
        //
    }

    /**
     * Determine whether the user can delete the sourceGroup.
     *
     * @param  \App\User  $user
     * @param  \App\SourceGroup  $sourceGroup
     * @return mixed
     */
    public function delete(User $user, SourceGroup $sourceGroup)
    {
        //
    }
}
