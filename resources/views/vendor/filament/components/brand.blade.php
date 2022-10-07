<div class="flex items-center space-x-4">
    <img src="{{ asset('/images/logo-m.png') }}" alt="Кафедра ВолгГМУ" class="h-10">

    @auth
        <span class="text-xs">
            {{ auth()->user()->main_department?->name ?? 'Не указана кафедра' }}
        </span>
    @endauth

</div>

