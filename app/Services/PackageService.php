<?php

namespace App\Services;

use App\Events\PackageAssigned;
use App\Package;
use App\User;

class PackageService
{
    /**
     * Assign package to user.
     *
     * @param  User $user
     * @param  Package $package
     */
    public function assignPackageToUser(User $user, Package $package)
    {
        if (!$package->is_purchased) {

            $purchases = $user->{'purchases_' . $package->language};

            $purchases = array_unique(array_merge(
                $purchases,
                $package->issues
            ), SORT_NUMERIC);

            asort($purchases);

            $user->{'purchases_' . $package->language} = array_values($purchases);
            $user->save();

            // Trigger events
            event(new PackageAssigned($user, $package));
        }
    }
}
