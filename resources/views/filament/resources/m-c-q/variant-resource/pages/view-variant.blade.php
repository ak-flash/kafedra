<x-filament::page>

    <x-filament::section>
        <div class="space-y-2 text-gray-800">
            <div class="flex flex-col lg:flex-row items-center space-y-2 lg:space-y-0 lg:space-x-4">
                <div class="text-lg font-semibold">
                    {{ $record->class_topic->discipline->name }}
                </div>
                <div>
                    {{ \App\Services\EducationService::getCourseNumber($record->class_topic->semester)  }} курс
                    {{ $record->class_topic->discipline->faculty->name }}
                    ({{ $record->class_topic->semester }} семестр)
                </div>
            </div>

            <div class="text-lg">
                Тема занятия №<b>{{ $record->class_topic->sort_order }}</b>: <b>{{ $record->class_topic->title }}</b>
            </div>


        </div>
    </x-filament::section>

    <x-filament::section>
        <div class="text-lg lg:text-xl">
            Вариант № <b>{{ $record->variant }}</b>
        </div>
        <div class="text-gray-700 text-sm">
            ID: <b class="select-all">{{ $record->id }}</b>
        </div>
    </x-filament::section>


    <x-filament::section>
        {{--<x-slot name="title">

        </x-slot>--}}

        <div class="mcq-questions px-4">
            @foreach($record->questions as $key => $question)
                <div class="mb-4">

                    <div class="flex items-center space-x-4 font-semibold">
                        {{ $loop->iteration }})
                        <span class="text-xs px-1">
                            {{ $question['id'] }}.
                        </span>
                        {{ $question['question'] }}
                    </div>

                    @foreach($question['answers'] as $key => $answer)
                        <div class="flex items-center space-x-4 pl-4 {{ in_array($key,[$question['correct']]) ? 'text-green-700 font-semibold' : '' }}">
                            {{ $loop->iteration }}) {{ $answer }}
                        </div>
                    @endforeach
                </div>

            @endforeach
        </div>


    </x-filament::section>


</x-filament::page>
