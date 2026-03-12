<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\MonthlyIncome;
use App\Models\Saving;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Spatie\Prometheus\Facades\Prometheus;

class PrometheusServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (! $this->app->runningInConsole() && ! Schema::hasTable('users')) {
            return;
        }

        // Usuários
        Prometheus::addGauge('laravel_users_total')
            ->helpText('Total de usuários cadastrados')
            ->value(fn () => User::count());

        // Categorias
        Prometheus::addGauge('laravel_categories_total')
            ->helpText('Total de categorias cadastradas')
            ->value(fn () => Category::count());

        Prometheus::addGauge('laravel_categories_active_total')
            ->helpText('Total de categorias ativas')
            ->value(fn () => Category::where('is_active', true)->count());

        Prometheus::addGauge('laravel_categories_archived_total')
            ->helpText('Total de categorias arquivadas')
            ->value(fn () => Category::where('is_active', false)->count());

        // Transações
        Prometheus::addGauge('laravel_transactions_total')
            ->helpText('Total de transações cadastradas')
            ->value(fn () => Transaction::count());

        Prometheus::addGauge('laravel_transactions_avg_amount')
            ->helpText('Valor médio das transações')
            ->value(fn () => round((float) Transaction::avg('amount'), 2));

        // Receitas
        Prometheus::addGauge('laravel_incomes_total')
            ->helpText('Total de receitas cadastradas')
            ->value(fn () => MonthlyIncome::count());

        Prometheus::addGauge('laravel_incomes_amount_total')
            ->helpText('Soma total de todas as receitas')
            ->value(fn () => round((float) MonthlyIncome::sum('amount'), 2));

        // Caixinhas
        Prometheus::addGauge('laravel_savings_total')
            ->helpText('Total de caixinhas criadas')
            ->value(fn () => Saving::count());

        Prometheus::addGauge('laravel_savings_balance_total')
            ->helpText('Saldo total acumulado em todas as caixinhas')
            ->value(fn () => round((float) Saving::sum('balance'), 2));
    }
}
