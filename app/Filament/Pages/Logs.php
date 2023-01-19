<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Exception;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use FilipFonal\FilamentLogManager\LogViewer;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Logs extends Page
{
    use HasPageShield;

    protected static string $view = 'filament-log-manager::pages.logs';

    public ?string $logFile = 'laravel.log';
    public ?string $file = null;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws FileNotFoundException
     * @throws Exception
     */
    public function getLogs(): Collection
    {
        if (!$this->logFile) {
            return collect([]);
        }

        $logs = [];

        $this->file = LogViewer::pathToLogFile($this->logFile);

        if (File::exists($this->file)) {
            $logs = LogViewer::getAllForFile($this->logFile);
            Cache::put('logsErrorCount', count($logs));
        }

        return collect($logs);
    }

    /**
     * @throws Exception
     */
    public function download(): BinaryFileResponse
    {
        if (!config('filament-log-manager.allow_download')) {
            abort(403);
        }

        return response()->download(LogViewer::pathToLogFile($this->file));
    }

    /**
     * @throws Exception
     */
    public function delete(): bool
    {
        if (!config('filament-log-manager.allow_delete')) {
            abort(403);
        }

        if (File::delete($this->file)) {
            $this->logFile = null;

            Cache::forget('logsErrorCount');

            return true;
        }

        abort(404, __('filament-log-manager::translations.no_such_file'));
    }

    protected function getForms(): array
    {
        return [

        ];
    }

    protected function getFormSchema(): array
    {
        return [

        ];
    }

    protected function getFileNames($files): Collection
    {
        return collect($files)->mapWithKeys(function (SplFileInfo $file) {
            return [$file->getRealPath() => $file->getRealPath()];
        });
    }

    protected static function getNavigationIcon(): string
    {
        return config('filament-log-manager.navigation_icon');
    }

    protected static function getNavigationLabel(): string
    {
        return __('filament-log-manager::translations.navigation_label');
    }

    protected function getTitle(): string
    {
        return __('filament-log-manager::translations.title');
    }

    protected static function getNavigationGroup(): ?string
    {
        return config('filament-log-manager.navigation_group') ? __('filament-log-manager::translations.navigation_group') : null;
    }

    protected static function getNavigationBadge(): ?string
    {
        return Cache::get('logsErrorCount', 0);
    }
}
