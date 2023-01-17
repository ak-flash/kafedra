<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $departmentsStats = [];

        foreach (auth()->user()->departments_cache as $department) {
            $department = $department->loadCount('users','disciplines');

            $departmentsStats[] = Card::make('Сотрудников', $department->users_count)
                ->description($department->name)
                ->color('success');

            $departmentsStats[] = Card::make('Дисциплин', $department->disciplines_count)
                ->description($department->name)
                ->color('success');
        }

        return array_merge($departmentsStats, [

           /* Card::make('Average time on page', '3:12')
                ->description('3% increase')
                ->descriptionIcon('heroicon-s-trending-up')
                ->color('success'),*/

        ]);
    }

}
