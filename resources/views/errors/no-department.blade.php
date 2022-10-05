<x-app-layout>

    <div @class([
    'flex items-center justify-center min-h-screen bg-gray-100 text-gray-900 filament-breezy-auth-component filament-login-page',
    'dark:bg-gray-900 dark:text-white' => config('filament.dark_mode'),
])>

    <div
        class="p-4 max-w-{{ config('filament-breezy.auth_card_max_w') ?? 'md' }} w-screen bg-white rounded-md border border-red-600">

        <div class="flex items-center space-x-4">
            <div class="w-20">
                <x-filament::brand-icon />
            </div>

            <div class="space-y-4">
                <div class="font-semibold text-xl text-red-600">
                    Внимание, у вас не указана кафедра
                </div>
                <div class="text-gray-600">
                    Обратитесь к администратору
                </div>

                <x-button primary icon="home" href="/admin" label="Обновить"/>
            </div>
        </div>







    </div>



</div>

</x-app-layout>
