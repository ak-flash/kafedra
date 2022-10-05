<x-filament::page>

    <div class="p-4 bg-white rounded-md">
        <div class="">
            Добро пожаловать,
        </div>
        <div class="font-semibold text-lg lg:text-2xl">
            {{ auth()->user()->name }}
        </div>

        <div class="my-4">
            Вы являетесь сотрудником:
        </div>
        @forelse(auth()->user()->departments->sortBy('volume') as $department)
            <div class="px-4 pb-2">
                <span class="font-semibold lg:text-xl">
                    {{ $department->name }}
                </span>

                <span>
                    ({{ \App\Models\Common\Position::POSITIONS_RATE[$department->pivot->volume] ?? 0 }} ставка)
                </span>
            </div>
        @empty
            <div class="font-semibold text-red-700 text-lg">
                Кафедра не указана
            </div>
        @endforelse
    </div>


</x-filament::page>
