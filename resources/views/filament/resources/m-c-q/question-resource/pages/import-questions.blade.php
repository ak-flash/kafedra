<x-filament::page>

    <x-wire-ui-init />

    @if($importedQuestionsIds)
        <div class="border bg-green-200 p-4 mb-4 rounded-md">
            <span class="font-semibold text-gray-700">
                Успешно импортированы вопросы
            </span>

            <div class="mt-2 flex flex-wrap space-x-2">
                @foreach($importedQuestionsIds as $id)
                    <x-button secondary sm href="{{ route('filament.resources.m-c-q/questions.edit', $id) }}" target="_blank" >
                        № {{ $id }}
                    </x-button>
                @endforeach
            </div>

        </div>
    @endif


    <form wire:submit.prevent="previewQuestions">
        {{ $this->form }}

        <x-button type="submit" primary icon="home" label="Обработать" class="mt-4" />

    </form>

    <div class="mt-4 rounded-lg border p-4 bg-white">

        <div class="flex items-center justify-between space-x-4 mb-4 px-4">
            <div>
                В первой строке должен идти вопрос и далее ответы. <br>
                Допустимо <b>только 4 ответа для каждого вопроса</b>.
                <div class="font-semibold pt-2">
                    Пример:
                </div>
            </div>
            <div class="text-sm text-gray-600">
                Состояние иммунитета определяется функциями <br>
                центральной нервной системы <br>
                эндокринной системы <br>
                лимфоидной системы <br>
                кроветворной системы
            </div>
        </div>

        Правильные ответы необходимо будет указать после обработки вопросов. Можно добавлять сразу несколько вопросов подряд.
        <div class="text-red-600 my-2">
            Не добавляйте вопросы с вариантом: "Все выше перечисленные" и т.д.!
            <small>(Логичнее вместо этого сделать 2-3 одинаковых вопроса с разными ответами)</small>
        </div>
        <div class="flex items-center space-x-4">
            <span>
                Для более сложных типов вопросов воспользуйтесь формой ручного добавления вопроса
            </span>
            <x-button secondary label="Создать" href="{{ route('filament.resources.m-c-q/questions.create') }}" target="_blank" />
        </div>
    </div>


    <x-modal.card title="Предпросмотр импортируемых вопросов" blur wire:model.defer="previewQuestionsModal">


        <form wire:submit.prevent="submit">
            <div class="grid grid-cols-1 sm:grid-cols-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-white rounded-md gap-4 px-4 py-2 font-semibold shadow-md">
                <div class="">
                    Вопрос
                </div>

                <div class="flex items-center justify-between space-x-4">
                    <div>
                        Ответы
                    </div>
                    <div class="text-sm">
                        Отметьте правильный
                    </div>
                </div>
            </div>


            @forelse($importedQuestions as $index => $record)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 dark:bg-gray-700 rounded p-2 mb-4 mt-2 border border-gray-300 rounded-md">
                    <div>
                        <div>
                            <x-textarea wire:model.lazy="importedQuestions.{{ $index }}.question" wire:key="{{ $index }}" />
                            <x-select
                                label="Сложность"
                                placeholder="Укажите оценку для вопроса"
                                :options="App\Enums\MarksEnum::getPositive()"
                                wire:model="importedQuestions.{{ $index }}.difficulty" wire:key="difficulty{{ $index }}"
                                class="my-2"
                            />
                        </div>


                        @if(isset($record['duplicated']))
                            <div class="text-sm mt-2">

                                <div class="flex items-center space-x-2 text-danger-700 mb-2">
                                    <x-icon name="information-circle" class="w-8" />
                                    <span>Внимание, такой вопрос уже существует с ответами:</span>
                                </div>

                                <div class="p-2 border rounded space-y-2">
                                    @foreach($record['duplicated'] as $duplicate)
                                        <div class="pb-2">
                                            <a href="{{ route('filament.resources.m-c-q/questions.edit', $duplicate['id']) }}" target="_blank" class="font-semibold hover:underline">
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
                        <div class="flex space-x-4 lg:space-x-8">
                            <div class="space-y-2">
                                @forelse($record['answers'] as $key => $answer)
                                    <div class=" flex items-center space-x-4">
                                        <x-input wire:model.lazy="importedQuestions.{{ $index }}.answers.{{ $key }}.answer" wire:key="answers-key-{{ $key }}" />
                                        <x-checkbox wire:model.defer="importedQuestions.{{ $index }}.answers.{{ $key }}.is_correct" />
                                    </div>
                                @empty
                                    Пусто
                                @endforelse
                            </div>

                            <div class="flex items-start">
                                <x-button outline red sm icon="x" title="Убрать" wire:click="excludeQuestion({{ $index }})" spinner="excludeQuestion({{ $index }})" />
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
                <div class="text-red-700 font-semibold">
                    Пусто
                </div>
            @endforelse

        </form>


        <x-slot name="footer">
            <div class="flex justify-end gap-x-4">

                <div class="flex space-x-4">
                    <x-button flat label="{{ __('Cancel') }}" x-on:click="close" />
                    <x-button primary label="{{ __('Save') }}" wire:click="store" spinner="store" />
                </div>

            </div>
        </x-slot>

    </x-modal.card>







</x-filament::page>
