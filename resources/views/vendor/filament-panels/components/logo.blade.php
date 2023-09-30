<div class="flex items-center gap-2">
    <img src="{{ asset('/images/logo-m.png') }}" alt="Кафедра ВолгГМУ" class="h-10">

    @auth
        <span class="lg:text-lg">
            {{ config('app.name', 'Laravel') }}
        </span>
    @endauth

</div>

