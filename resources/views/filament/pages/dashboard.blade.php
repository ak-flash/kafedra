<x-filament::page>

    <div class="{{ auth()->user()->is_admin ? 'grid lg:grid-cols-2 gap-4 justify-between' : '' }}">
        <x-filament::section class="flex items-center justify-between">
            <div class="lg:px-4">
                <div class="">
                    Добро пожаловать,
                </div>
                <div class="font-semibold lg:text-lg">
                    {{ auth()->user()->name }}
                </div>
            </div>

            <div>

            </div>
        </x-filament::section>



            @if(auth()->user()->is_admin)
            <x-filament::section>
                <x-slot name="heading">
                    Сервер
                </x-slot>

                <div class="">
                    «{{ App::environment() }}» (PHP v{{ PHP_VERSION }})
                </div>
                <div class="">
                    Laravel v.{{ Illuminate\Foundation\Application::VERSION }}
                </div>
            </x-filament::section>
            @endif

    </div>



    <x-filament::section>
        <div class="pb-4">
            Вы являетесь сотрудником:
        </div>

        @forelse(auth()->user()->departments->sortBy('volume') as $department)
            <div class="px-4 pb-2">
                <span class="font-semibold lg:text-xl">
                    {{ $department->name }}
                </span>

                <span>
                    ({{ \App\Services\UserService::getWorkingVolume($department) }} ставка)
                </span>
            </div>
        @empty
            <div class="font-semibold text-red-700 text-lg">
                Кафедра не указана
            </div>
        @endforelse
    </x-filament::section>


</x-filament::page>
