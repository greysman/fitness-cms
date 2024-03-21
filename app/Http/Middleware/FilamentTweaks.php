<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Illuminate\Support\Facades\Vite;
use Illuminate\Validation\Rules\Password;
use JeffGreco13\FilamentBreezy\FilamentBreezy;
use Illuminate\View\View;

class FilamentTweaks
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        config([
            'filament-breezy.enable_registration' => app(\App\Settings\AppSettings::class)->allow_registration
        ]);

        Filament::serving(function() {
            Filament::registerTheme(
                mix('css/filament.css', '/backend')
            );
        });

        Filament::registerNavigationGroups([
            NavigationGroup::make()
                ->label(__('base.navigation_groups.crm.label'))
                ->collapsible(__('base.navigation_groups.crm.collapsible')),
            NavigationGroup::make()
                ->label(__('base.navigation_groups.store.label'))
                ->collapsible(__('base.navigation_groups.store.collapsible')),
            NavigationGroup::make()
                ->label(__('base.navigation_groups.finance.label'))
                ->collapsible(__('base.navigation_groups.finance.collapsible')),
            NavigationGroup::make()
                ->label(__('base.navigation_groups.reports.label'))
                ->collapsible(__('base.navigation_groups.report.collapsible')),
            NavigationGroup::make()
                ->label(__('base.navigation_groups.content.label'))
                ->collapsible(__('base.navigation_groups.content.collapsible')),
            NavigationGroup::make()
                ->label(__('filament-authentication::filament-authentication.section.group'))
                ->collapsible()
                ->collapsed(),
            NavigationGroup::make()
                ->label(__('base.navigation_groups.additional.label'))
                ->collapsible(__('base.navigation_groups.additional.collapsible'))
                ->collapsed(),
        ]);

        FilamentBreezy::setPasswordRules([
            Password::min(8)
                ->letters()
                ->numbers()
                ->mixedCase()
                ->uncompromised(3)
        ]);

        if (config('timex.mini.isCustomMiniCalendarEnabled')){
            Filament::registerRenderHook(
                'global-search.start',
                fn(): View => \view('timex.layout.heading')
            );
        }

        return $next($request);
    }
}
