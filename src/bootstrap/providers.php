<?php

use App\Core\CoreServiceProvider;
use App\Modules\User\UserServiceProvider;

return [
    App\AppServiceProvider::class,
    CoreServiceProvider::class,
    UserServiceProvider::class
];
