<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\InternetPackage;
use Illuminate\Auth\Access\HandlesAuthorization;

class InternetPackagePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:InternetPackage');
    }

    public function view(AuthUser $authUser, InternetPackage $internetPackage): bool
    {
        return $authUser->can('View:InternetPackage');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:InternetPackage');
    }

    public function update(AuthUser $authUser, InternetPackage $internetPackage): bool
    {
        return $authUser->can('Update:InternetPackage');
    }

    public function delete(AuthUser $authUser, InternetPackage $internetPackage): bool
    {
        return $authUser->can('Delete:InternetPackage');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:InternetPackage');
    }

    public function restore(AuthUser $authUser, InternetPackage $internetPackage): bool
    {
        return $authUser->can('Restore:InternetPackage');
    }

    public function forceDelete(AuthUser $authUser, InternetPackage $internetPackage): bool
    {
        return $authUser->can('ForceDelete:InternetPackage');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:InternetPackage');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:InternetPackage');
    }

    public function replicate(AuthUser $authUser, InternetPackage $internetPackage): bool
    {
        return $authUser->can('Replicate:InternetPackage');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:InternetPackage');
    }

}