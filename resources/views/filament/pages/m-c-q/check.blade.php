<x-filament::page>
    <x-wire-ui-init />

    <div class="p-4 bg-white rounded-lg">
        <x-toggle label="Поиск по ID варианта" wire:model="selectById" />

        <div class="{{ $selectById ? '' : 'hidden' }} flex items-center space-x-4 mt-4">

                <x-input placeholder="Укажите вариант"
                         wire:model.lazy="variantId" autocomplete="off" />

                <x-button primary icon="check-circle" spinner="loadVariant" wire:click="loadVariant" label="Загрузить" class="" />

        </div>

        <div class="{{ $selectById ? 'hidden' : '' }} flex flex-col lg:flex-row lg:items-center lg:space-x-4 mt-4 w-full">
            <x-select
                label="Дисциплина"
                placeholder="Выберите..."
                wire:model="disciplineId"
                :clearable="false"
                class=""
            >
                @forelse($disciplinesList as $value => $name)
                    <x-select.option label="{{ $name }}" value="{{ $value }}" />
                @empty

                @endforelse

            </x-select>

            @if($disciplineId)
                <x-select
                    label="Занятие"
                    placeholder="Выберите..."
                    wire:model="topicId"
                    class=""
                >
                    @forelse($topicsList as $topic)
                        <x-select.option label="{{ $topic->sort_order.'. '.$topic->title }}" value="{{ $topic->id }}" />
                    @empty

                    @endforelse
                </x-select>
            @endif

            @if($topicId)
                <x-select
                    label="Вариант"
                    placeholder="Выберите..."
                    wire:model="variantId"
                    :options="$variantsList"
                    option-label="name"
                    option-value="id"
                    class=""
                />
            @endif
        </div>



    </div>





    @if($variant)
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between lg:space-x-4 p-4 bg-white rounded-lg border border-gray-200 space-y-4 lg:space-y-0">
            <div>
                <div class="text-lg">
                    Вариант:
                    <span class="font-semibold lg:text-xl">
                    {{ $variant->variant }}
                </span>
                </div>
                <div class="text-gray-600">
                    ID: <span class="select-all">{{ $variant->id }}</span>
                </div>
            </div>

            <div class="border-l-2 border-gray-300 px-4">
                {{ App\Services\UserService::getDisciplinesWithFaculties()[$variant->class_topic->discipline_id] }}

                <div class="">
                    {{ $variant->class_topic->sort_order }})
                    <span class="font-semibold">
                        {{ $variant->class_topic->title }}
                    </span>
                </div>
            </div>

            <x-button secondary label="Посмотреть вопросы и ответы" href="{{ route('filament.resources.m-c-q/variants.view', $variant->id) }}" target="_blank" />

        </div>


        @if($result)


            <div id="result" class="p-4 bg-white rounded-lg">
                <div class="flex items-center justify-between space-x-4 px-4 pb-4">
                    <div class="text-lg">
                        Результат:
                        <span class="font-semibold lg:text-2xl {{ $this->calculatePoints($result) < 61 ? 'text-red-700' : '' }}">
                            {{ $this->calculatePoints($result) }}
                        </span>
                        %
                    </div>

                    <x-button primary label="Очистить" wire:click="clearStudentValues()" spinner="clearStudentValues" />
                </div>

                <div class="lg:w-2/3 border border-gray-300 rounded-md divide-y">
                    @foreach($variant->questions as $key => $question)

                        <div class="grid grid-cols-3 gap-4 p-4">
                            <div>
                                Вопрос <b>{{ ($key + 1) }}</b>
                            </div>

                            @if(isset($result[$question['id']]))

                                @switch($result[$question['id']]['points'])
                                    @case(0)
                                        <span class="text-red-700">НЕ правильно</span>
                                        @break
                                    @case(1)
                                        <span class="text-green-700">Правильно</span>
                                        @break

                                    @default
                                        <span class="text-gray-700">Частично правильно</span>
                                @endswitch

                                <span>
                                Баллы: {{ $result[$question['id']]['points'] }}
                            </span>

                            @endif
                        </div>


                    @endforeach
                </div>

                <div class="mt-4">
                    <x-button secondary wire:click="openResultModal()" label="Посмотреть ответы студента"  />
                </div>

            </div>

            <script>
                document.getElementById("result").scrollIntoView();
            </script>
        @endif


        <div class="p-4 bg-white rounded-lg">

            @if($variant->questions)

                <div class="flex items-center justify-between space-x-4 pb-4">
                    <x-toggle wire:model="mode"  label="Альтернативный" />
                    <x-button primary label="Очистить" wire:click="clearStudentValues()" spinner="clearStudentValues" />
                </div>


            @if($mode)

                @foreach($variant->questions as $key => $question)
                    <div class="flex items-center space-x-4 pb-4">
                        <div class="font-semibold px-2 lg:text-lg">
                            {{ ($key + 1) }})
                        </div>

                        @for($i = 0; $i < count($question['answers']); $i++)
                            <x-checkbox label="{{ ($i + 1) }}" wire:model="studentValues.{{ $question['id'] }}.answers.{{ $i }}" value="{{ ($i + 1) }}" wire:key="answer-{{ $key }}-{{ $i }}" />
                        @endfor

                        @error('studentValues.'.$question['id'].'.answers')
                            <span class="text-red-600 text-sm">
                                {{ $message }}
                            </span>
                        @enderror


                    </div>
                @endforeach
            @else
                <div x-data x-init="setAutoFocusNextInput()" id="autojump" class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-10 items-center gap-4">
                    @foreach($variant->questions as $key => $question)
                        <div class="text-center w-20">
                            <div class="font-semibold pb-2 lg:text-lg">
                                {{ ($key + 1) }}
                            </div>

                            <x-input type="number" maxlength="1" wire:model.lazy="studentValues.{{ $question['id'] }}.answers.0" wire:key="value-{{ $question['id'] }}" autocomplete="off" class="form-control text-center w-20 h-14 text-lg lg:text-xl" required />
                        </div>
                    @endforeach

                </div>


            @endif

            <x-button wire:click="check" primary icon="check-circle" spinner="check" label="Проверить" class="mt-4" />



            @else
                <span class="text-red-700">
                    Вопросы не найдены...
                </span>
            @endif

        </div>


        <x-modal blur wire:model.defer="showResultVariant">

            <x-card title="Бланк тестирования">

                <div class="grid grid-cols-3 gap-4 items-center">
                    <div class="col-span-2 font-semibold text-xl">
                        Вопросы ({{ count($variant->questions) }} шт.)
                    </div>
                    <div class="font-semibold flex justify-center text-xl">
                        Баллы
                    </div>
                    <hr class="col-span-3">
                    @foreach($variant->questions as $key => $question)
                        <div class="col-span-2">

                            <div class="flex items-center space-x-4 font-semibold">
                                {{ $loop->iteration }}) {{ $question['question'] }}
                            </div>

                            @foreach($question['answers'] as $key => $answer)
                                <div class="flex items-center space-x-4 pl-4 {{ in_array($key,$question['correct']) ? 'text-green-700 font-semibold' : '' }} {{ in_array($key,$studentValues[$question['id']]['answers']) ? 'text-red-700' : '' }}">

                                    @if(in_array($key,$studentValues[$question['id']]['answers']))
                                        <div title="Выбрано студентом">
                                            <x-icon name="arrow-narrow-right" class="h-5 mr-2 text-gray-700" />
                                        </div>
                                    @endif

                                    {{ $loop->iteration }}) {{ $answer }}
                                </div>

                            @endforeach
                        </div>
                        <div class="text-lg lg:text-2xl flex items-center justify-center">
                            {{ $result[$question['id']]['points'] ?? 0 }}
                        </div>
                    @endforeach
                </div>



                <x-slot name="footer">
                    <div class="flex justify-end gap-x-4">
                        <x-button flat label="Закрыть" x-on:click="close" />
                    </div>
                </x-slot>

            </x-card>
        </x-modal>

    @endif

    @push('scripts')
        <script>

            function setAutoFocusNextInput() {

                const wrapper = document.getElementById("autojump");
                const el = wrapper.querySelectorAll(".form-control");
                const inputLength = el.length;

                Array.prototype.forEach.call(el, function(e, index){
                    e.addEventListener("keyup", function(e){
                        const maxlength = e.target.getAttribute("maxlength");
                        const length = e.target.value.length;
                        if (maxlength == length && index < (inputLength-1)) {
                            el[index + 1].focus();
                        }
                    });
                });
            }

        </script>
    @endpush

</x-filament::page>