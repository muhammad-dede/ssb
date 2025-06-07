<?php

namespace App\Traits;

trait HasPermissionCheck
{
    protected function checkPermission($permission)
    {
        if (!auth()->check()) {
            abort(401, 'Unauthenticated');
        }

        if (!auth()->user()->can($permission)) {
            abort(403, 'You do not have permission to access this resource');
        }
    }

    protected function hasPermission($permission)
    {
        return auth()->check() && auth()->user()->can($permission);
    }
}
