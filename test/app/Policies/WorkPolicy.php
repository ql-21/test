<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Work;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $user , Work $work)
    {
        return $user->id==$work->user_id;
    }

    public function destroy(User $user , Work $work)
    {
        return $user->id == $work->user_id;
    }

}
