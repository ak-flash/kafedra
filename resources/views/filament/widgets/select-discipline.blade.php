<x-filament::widget>
    <x-filament::section>
        <div class="text-sm pb-2">
            Дисциплина:
        </div>
        <x-filament::input.wrapper>
            <x-filament::input.select wire:model.live="disciplineId">
                <option value="">Выберите...</option>
                @forelse($disciplinesList as $value => $name)
                    <option value="{{ $value }}">{{ $name }}</option>
                @empty
                @endforelse
            </x-filament::input.select>
        </x-filament::input.wrapper>

    </x-filament::section>
</x-filament::widget>
