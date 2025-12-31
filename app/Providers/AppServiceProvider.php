<?php

namespace App\Providers;

use App\Events\CaseCreated;
use App\Events\CaseStatusChanged;
use App\Events\HearingScheduled;
use App\Events\ProsecutorAssigned;
use App\Listeners\LogCaseCreated;
use App\Listeners\LogCaseStatusChanged;
use App\Listeners\NotifyProsecutorOfAssignment;
use App\Models\CaseModel;
use App\Models\Hearing;
use App\Models\User;
use App\Policies\CasePolicy;
use App\Policies\HearingPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected array $policies = [
        CaseModel::class => CasePolicy::class,
        Hearing::class => HearingPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * The event to listener mappings.
     */
    protected array $listen = [
        CaseCreated::class => [
            LogCaseCreated::class,
        ],
        CaseStatusChanged::class => [
            LogCaseStatusChanged::class,
        ],
        ProsecutorAssigned::class => [
            NotifyProsecutorOfAssignment::class,
        ],
    ];

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
        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        $this->registerPolicies();
        $this->registerEvents();
        $this->registerBladeComponents();
        $this->registerBladeDirectives();
    }

    /**
     * Register the policies.
     */
    protected function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    /**
     * Register the events and listeners.
     */
    protected function registerEvents(): void
    {
        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }

    /**
     * Register Blade components.
     */
    protected function registerBladeComponents(): void
    {
        // UI Components
        Blade::component('ui.status-badge', \App\View\Components\StatusBadge::class);
        Blade::component('ui.card', \App\View\Components\Card::class);
        Blade::component('ui.button', \App\View\Components\Button::class);
        Blade::component('ui.empty-state', \App\View\Components\EmptyState::class);
        Blade::component('ui.alert', \App\View\Components\Alert::class);
        Blade::component('ui.modal', \App\View\Components\Modal::class);
    }

    /**
     * Register custom Blade directives.
     */
    protected function registerBladeDirectives(): void
    {
        // Role-based directives
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->isAdmin();
        });

        Blade::if('prosecutor', function () {
            return auth()->check() && auth()->user()->isProsecutor();
        });

        Blade::if('clerk', function () {
            return auth()->check() && auth()->user()->isClerk();
        });

        Blade::if('role', function (string ...$roles) {
            return auth()->check() && auth()->user()->hasAnyRole($roles);
        });
    }
}

