<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Buyer;
use App\Policies\BuyerPolicy;
use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Buyer::class => BuyerPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        Passport::enableImplicitGrant();

        Passport::tokensCan([
            'purchase-product' => 'Create transactions to buy determinate products',
            'manage-products' => 'Create, read, update and delete products',
            'manage-account' => 'Get account information, name, email, status (without password), modify data like email, name and password. Can not delete the account',
            'read-general' => 'Get general information, buyed or sold categories, buyed or sold products, transactions, buys and sales'
        ]);
        //
    }
}
