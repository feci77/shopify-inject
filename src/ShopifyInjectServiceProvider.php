<?php
namespace Shopify\Inject;

use Illuminate\Support\ServiceProvider;

class ShopifyInjectServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }

    public function register()
    {

    }
}
