<x-filament::widget>
    <x-wire-ui-init />

    <x-filament::card>

        <x-select
            label="Выберите ДИСЦИПЛИНУ"
            placeholder="Выберите..."
            wire:model="disciplineId"
            :clearable="false"
            class="mb-4"
        >
            @forelse($disciplinesList as $value => $name)
                <x-select.option label="{{ $name }}" value="{{ $value }}" />
            @empty

            @endforelse

        </x-select>

    </x-filament::card>
</x-filament::widget>
