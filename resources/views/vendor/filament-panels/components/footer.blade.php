{{ \Filament\Facades\Filament::renderHook('footer.before') }}

<div class="flex items-center justify-center filament-footer">
    {{ \Filament\Facades\Filament::renderHook('footer.start') }}

    @if (config('filament.layout.footer.should_show_logo'))
        {{--Custom text--}}
    @endif

    {{ \Filament\Facades\Filament::renderHook('footer.end') }}
</div>

{{ \Filament\Facades\Filament::renderHook('footer.after') }}
