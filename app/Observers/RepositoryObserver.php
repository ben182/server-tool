<?php

namespace App\Observers;

use App\Repository;
use Illuminate\Support\Str;

class RepositoryObserver
{
    /**
     * Handle the repository "saving" event.
     *
     * @param  \App\Repository  $repository
     * @return void
     */
    public function saving(Repository $repository)
    {
        $repository->secret = Str::random(random_int(15, 30));
    }
}
