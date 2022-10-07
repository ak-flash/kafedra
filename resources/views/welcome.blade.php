<x-app-layout>

    <section class="py-24 flex items-center min-h-screen justify-center bg-primary-50 p-4">
        <div class="mx-auto max-w-[43rem]">
            <div class="text-center">
                <p class="text-lg font-medium leading-8 text-indigo-600/95">Управление кафедрой университета</p>
                <h1 class="mt-3 text-[3.5rem] font-bold leading-[4rem] tracking-tight text-black">
                    Кафедра ERP
                </h1>
                <p class="mt-3 text-lg leading-relaxed text-slate-400">
                    Проверка и создание тестов, работа с документами, сотрудниками кафедры
                </p>
            </div>

            <div class="mt-6 flex flex-col lg:flex-row lg:items-center lg:justify-center gap-4">
                <a href="/admin/" class="transform rounded-md bg-indigo-600/95 px-5 py-3 font-medium text-white transition-colors hover:bg-indigo-700 inline-flex justify-center">
                    <x-icon name="login" class="w-5 mr-2" />
                    Войти
                </a>

                <a href="#" class="transform rounded-md border border-slate-300 px-5 py-3 font-medium text-slate-900 transition-colors hover:bg-slate-100 inline-flex justify-center">
                    <x-icon name="clipboard-check" class="w-5 mr-2" />
                    Отправить заявку на регистрацию</a>
            </div>
        </div>
    </section>
</x-app-layout>
