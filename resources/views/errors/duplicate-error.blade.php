<div x-data="{ state: $wire.entangle('{{ $getStatePath() }}') }" x-show="state" class="border rounded-md p-4 bg-red-100 flex flex-col lg:flex-row items-center lg:space-x-4 space-y-4 lg:space-y-0 text-red-700">
    <div>
        Такой вопрос(ы) уже имеется в базе
    </div>
    <div x-html="state"></div>
    <div class="text-sm">
        (Проверьте отсутствие совпадения в ответах)
    </div>
</div>
