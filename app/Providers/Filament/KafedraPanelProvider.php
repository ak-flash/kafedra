<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Pages\User\EditProfile;
use App\Filament\Resources\Common\GroupResource;
use App\Models\Common\Department;
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Forms\Components\FileUpload;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class KafedraPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('kafedra')
            ->path('')
            ->login()
            //->registration()
            ->requiresEmailVerification()
            ->passwordReset()
            ->profile(EditProfile::class)
            ->userMenuItems([
                'profile' => MenuItem::make()->label('Редактировать профиль'),
            ])
            ->passwordReset()
            ->emailVerification()
            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Emerald,
                'success' => Color::Green,
                'warning' => Color::Orange,
            ])
            ->viteTheme('resources/css/filament/kafedra/kafedra.css')
            ->discoverResources(in: app_path('Filament/Resources/Kafedra'), for: 'App\\Filament\\Resources\\Kafedra')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([

            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                QuickCreatePlugin::make()
                    ->includes($this->getQuickCreateResources()),
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->tenant(Department::class, ownershipRelationship: 'departments')
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Тесты'),
                NavigationGroup::make()
                    ->label('Кафедра')
                    ->collapsed(),

                NavigationGroup::make()
                    ->label('Управление'),
                NavigationGroup::make()
                    ->label('Сервер')
                    ->collapsed(),
            ])
            ->sidebarCollapsibleOnDesktop();
    }

    public function getQuickCreateResources(): array
    {
        return [
            \App\Filament\Resources\Kafedra\ClassTopicResource::class,
        ];
    }
}
