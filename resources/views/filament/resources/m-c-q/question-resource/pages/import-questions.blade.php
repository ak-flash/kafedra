<x-filament::page>


    @if($importedQuestionsIds)
        <div class="border bg-green-200 p-4 mb-4 rounded-md">
            <span class="font-semibold text-gray-700 pb-2">
                Успешно импортированы вопросы:
            </span>

            <div class="mt-2 flex flex-wrap space-x-2">
                @foreach($importedQuestionsIds as $id)
                    <x-filament::button href="{{ route('filament.kafedra.resources.kafedra.questions.edit', [ Filament\Facades\Filament::getTenant()->id, $id ]) }}" size="sm" icon="heroicon-m-document-check" tag="a" target="_blank"
                    >
                        № {{ $id }}
                    </x-filament::button>
                @endforeach
            </div>

        </div>
    @endif


    <form wire:submit="previewQuestions">
        {{ $this->form }}

        <x-filament::button size="xl" type="submit" icon="heroicon-m-check-badge" class="mt-4">
            Обработать
        </x-filament::button>

    </form>

    <div class="mt-4 rounded-lg border p-4 bg-white">

        <div class="flex items-center justify-between space-x-4 mb-4 px-4">
            <div>
                <div class="font-semibold text-green-700 pb-2">
                    В первой строке должен идти ВОПРОС и далее ОТВЕТЫ.
                </div>
                Допустимо <b>только 4 ответа для каждого вопроса!</b>
                <div class="font-semibold text-end">
                    Пример:
                </div>
            </div>
            <div class="text-sm text-gray-700 border p-4 rounded-md">
                <div class="font-semibold whitespace-nowrap">
                    Состояние иммунитета определяется функциями <span class="text-green-700 text-sx">(вопрос)</span>
                </div>
                центральной нервной системы <span class="text-green-700 text-sx">(ответ 1)</span> <br>
                эндокринной системы <span class="text-green-700 text-sx">(ответ 2)</span> <br>
                лимфоидной системы <span class="text-green-700 text-sx">(ответ 3)</span> <br>
                кроветворной системы <span class="text-green-700 text-sx">(ответ 4)</span>
            </div>
        </div>

        <div class="mt-4 lg:mt-8 px-4 pt-4 border-t">
            Правильные ответы необходимо будет указать после обработки вопросов. Можно добавлять сразу несколько вопросов подряд.
        </div>

        <div class="text-red-600 mt-4 p-4 border-t">
            Не добавляйте вопросы с вариантом: "Все выше перечисленные" и т.д.!
            <small>(Логичнее вместо этого сделать 2-3 одинаковых вопроса с разными ответами)</small>
        </div>
        <div class="flex items-center space-x-4 m-4 p-4 border bg-red-50 rounded-md">
            <span>
                Для более сложных типов вопросов воспользуйтесь формой ручного добавления вопроса
            </span>

            <x-filament::button href="{{ route('filament.kafedra.resources.kafedra.questions.create', Filament\Facades\Filament::getTenant()->id) }}" icon="heroicon-m-home" tag="a" target="_blank">
                Создать
            </x-filament::button>
        </div>
    </div>

    <x-filament::modal id="preview-questions" :close-by-clicking-away="false" :close-button="false"  width="screen">
        <x-slot name="heading">
            Предпросмотр импортируемых вопросов
        </x-slot>


        <form wire:submit="submit">
            <div class="grid grid-cols-1 sm:grid-cols-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-white rounded-md gap-4 px-4 py-2 shadow-md">
                <div class="font-semibold">
                    Вопрос
                </div>

                <div class="flex items-center space-x-4">
                    <div class="font-semibold">
                        Ответы
                    </div>
                    <div class="text-sm">
                        Отметьте правильный
                    </div>
                </div>
            </div>


            @forelse($importedQuestions as $index => $record)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 dark:bg-gray-700 p-2 mb-4 mt-2 border border-gray-300 rounded-md">
                    <div>
                        <div class="grid lg:grid-cols-4 gap-4 w-full">
                            <label class="lg:col-span-3 w-full">
                                <textarea wire:model.blur="importedQuestions.{{ $index }}.question" wire:key="{{ $index }}" class="rounded-md border border-gray-300 w-full">
                                </textarea>
                            </label>

                            <label class="" title="Укажите оценку для вопроса">
                                <div class="text-xs mb-2">
                                   Сложность
                                </div>
                                <x-filament::input.wrapper class="w-20">
                                    <x-filament::input.select wire:model="importedQuestions.{{ $index }}.difficulty" wire:key="difficulty{{ $index }}" class="w-20">
                                        @foreach(App\Enums\MarksEnum::getPositive() as $mark)
                                            <option value="{{ $mark }}">{{ $mark }}</option>
                                        @endforeach
                                    </x-filament::input.select>
                                </x-filament::input.wrapper>
                            </label>


                        </div>


                        @if(isset($record['duplicated']))
                            <div class="text-sm mt-2">

                                <div class="flex items-center space-x-2 text-danger-700 my-4 border-t pt-4">
                                    <x-filament::icon
                                        icon="heroicon-m-exclamation-triangle"
                                        class="h-5 w-5 text-gray-500 dark:text-gray-400"
                                    />
                                    <span>
                                        Внимание, такой вопрос уже существует с ответами:
                                    </span>
                                </div>

                                <div class="p-2 text-sm border rounded-md space-y-2 bg-red-50">
                                    @foreach($record['duplicated'] as $duplicate)
                                        <div class="pb-2">
                                            <a href="{{ route('filament.kafedra.resources.kafedra.questions.edit', [ Filament\Facades\Filament::getTenant()->id, $duplicate['id'] ]) }}" target="_blank" class="font-semibold hover:underline">
                                                {{ '[ID-'.$duplicate['id'].'] ' }}
                                            </a>

                                            {{  $duplicate['question'] }}
                                        </div>

                                        @forelse($duplicate['answers'] as $answer)
                                            {{ $loop->iteration.')'.( isset($answer['is_correct']) && $answer['is_correct'] ? '+' : '').' '.$answer['answer'] }}
                                        @empty
                                            Пусто
                                        @endforelse

                                    @endforeach
                                </div>

                            </div>
                        @endif
                    </div>


                    @if(isset($record['answers']))
                        <div class="flex space-x-4 lg:space-x-8 w-full">
                            <div class="space-y-2 w-full">
                                @forelse($record['answers'] as $key => $answer)
                                    <div class=" flex w-full items-center space-x-4">
                                        <span class="text-sm">
                                            {{ $loop->iteration }})
                                        </span>
                                        <label>
                                            <x-filament::input.checkbox wire:model="importedQuestions.{{ $index }}.answers.{{ $key }}.is_correct" />
                                        </label>
                                        <x-filament::input.wrapper class=" w-full">
                                            <x-filament::input type="text" wire:model.blur="importedQuestions.{{ $index }}.answers.{{ $key }}.answer" class="w-full"/>
                                        </x-filament::input.wrapper>


                                    </div>
                                @empty
                                    <div class="p-4">
                                        Пусто
                                    </div>
                                @endforelse
                            </div>

                            <div class="border-l border-gray-200 pl-4 flex items-center">
                                <x-filament::button wire:click="excludeQuestion({{ $index }})" size="sm" icon="heroicon-m-x-circle">
                                    Убрать
                                </x-filament::button>
                            </div>


                        </div>


                    @else
                        <div>
                            <div class="text-red-700 font-semibold">
                                Ошибка импорта.
                            </div>
                            <div class="text-sm">
                                Проверьте корректность введенных данных
                            </div>
                        </div>

                    @endif

                </div>

            @empty
                <div class="text-red-600 font-semibold p-4">
                    Пусто
                </div>
            @endforelse

        </form>


        <x-slot name="footer">
            <div class="flex justify-between gap-x-4">

                <x-filament::button color="warning" x-on:click="close" size="sm" icon="heroicon-m-x-circle">
                    {{ __('Cancel') }}
                </x-filament::button>
                <x-filament::button wire:click="store" icon="heroicon-m-check">
                    {{ __('Save') }}
                </x-filament::button>

            </div>

        </x-slot>
    </x-filament::modal>



</x-filament::page>
