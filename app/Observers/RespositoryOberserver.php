<?php

namespace App\Observers;

use App\Repository;

class RepositoryObserver
{
    /**
     * Listen to the Respository saving event.
     *
     * @param  \App\Respository  $oRespository
     * @return void
     */
    public function saving(Repository $oRespository)
    {
        $oRespository->_setSecret();
    }
}
