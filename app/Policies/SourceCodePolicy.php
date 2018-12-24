<?php

namespace App\Policies;

use App\User;
use App\SourceCodeModel;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class SourceCodePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the sourceCodeModel.
     *
     * @param  \App\User  $user
     * @param  \App\SourceCodeModel  $sourceCodeModel
     * @return mixed
     */
    public function view(User $user, SourceCodeModel $sourceCodeModel)
    {
        return  Gate::allows($sourceCodeModel['SRC_CD']);
    }

    /**
     * Determine whether the user can create sourceCodeModels.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the sourceCodeModel.
     *
     * @param  \App\User  $user
     * @param  \App\SourceCodeModel  $sourceCodeModel
     * @return mixed
     */
    public function update(User $user, SourceCodeModel $sourceCodeModel)
    {
        //
    }

    /**
     * Determine whether the user can delete the sourceCodeModel.
     *
     * @param  \App\User  $user
     * @param  \App\SourceCodeModel  $sourceCodeModel
     * @return mixed
     */
    public function delete(User $user, SourceCodeModel $sourceCodeModel)
    {
        //
    }
}
