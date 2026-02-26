<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Prometheus\Facades\Prometheus;

class PrometheusServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Prometheus::addGauge('laravel_users_total')
            ->helpText('Total de usuários cadastrados')
            ->value(function () {
                return \App\Models\User::count();
            });

        Prometheus::addGauge('laravel_transactions_total')
            ->helpText('Total de transações cadastradas')
            ->value(function () {
                return \App\Models\Transaction::count();
            });

        Prometheus::addGauge('laravel_categories_total')
            ->helpText('Total de categorias cadastradas')
            ->value(function () {
                return \App\Models\Category::count();
            });
    }
}
