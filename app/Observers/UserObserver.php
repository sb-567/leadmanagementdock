<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        Log::info('User created', [
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        Log::info('User updated', [
            'id'      => $user->id,
            'name'    => $user->name,
            'changes' => $user->getChanges(), // shows only changed fields
        ]);
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        Log::warning('User deleted', [
            'id'         => $user->id,
            'name'       => $user->name,
            'deleted_by' => auth()->id(),
        ]);
    }

    public function saving(User $user): void
    {
        if ($user->isDirty('is_active') && $user->is_active == false) {
            Log::warning('User deactivated', [
                'id'   => $user->id,
                'name' => $user->name,
            ]);
        }
    }


    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
