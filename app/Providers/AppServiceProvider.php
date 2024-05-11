<?php

namespace App\Providers;

use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::created(function($user) { // send Email to each newly created user. 
            Mail::to($user)->send(new UserCreated($user));
        });

        User::updated(function($user) {
            if ($user->isDirty('email')) { // send Email to Verify the new Email that changed by the user.
                Mail::to($user)->send(new UserMailChanged($user));
            }
        });

        Product::updated(function($product) { // to automaticly change the status of the product to UNAVAILABLE_PRODUCT if its became a 0.
            if ($product->quantity == 0 && $product->isAvailable()) {
                $product->status = Product::UNAVAILABLE_PRODUCT;

                $product->save();
            }
        });
    }
}