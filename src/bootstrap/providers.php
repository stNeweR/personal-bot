<?php

use App\AppServiceProvider;
use App\Core\CoreServiceProvider;
use App\Modules\User\UserServiceProvider;

return [
    AppServiceProvider::class,
    CoreServiceProvider::class,
    UserServiceProvider::class,
];
