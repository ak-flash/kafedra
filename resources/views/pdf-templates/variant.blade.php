@php
    $questions = collect($variant->questions);
    $half = round($questions->count() / 2);
    $questionsSplit = $questions->chunk($half);
@endphp



<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Вариант №{{ $variant->id }}</title>

    <style type="text/css">

        @font-face {
            font-family: 'Arial';
            src:    url({{ url('/fonts/arial.ttf') }}) format("truetype");
            font-weight: normal;
            font-style: normal;
            font-stretch: normal;
            font-variant: normal;
        }

        @font-face {
            font-family: 'ArialBold';
            src: url({{ url('/fonts/arialbd.ttf') }}) format("truetype");
            font-weight: bold;
            font-style: normal;
            font-stretch: normal;
            font-variant: normal;
        }

        b {
            font-family: ArialBold, serif;
        }

        /* To remove margin while generating PDF. */
        * {
            margin:0;
            padding:0
        }

        body {
            font-family: arial,ArialBold, serif;
            margin: 0;
            height: 11.69in;
            width: 8.27in;
            font-size: {{ $fontSize }}px;
        }
    </style>
</head>
<body>
    <div style="padding: 10px; text-align: center;">
        <div style="margin-bottom: 5px;">
            Дисциплина «{{ $variant->class_topic->discipline->name }}». Занятие № <b style="font-size: 1.2em;">{{ $variant->class_topic->sort_order }}</b>
        </div>
        <div style="margin-bottom: 5px;">
            Тестовый контроль по теме <b>«{{ $variant->class_topic->title }}»</b> для студентов
            {{ \App\Services\EducationService::getCourseNumber($variant->class_topic->semester) }} курса <b>{{ $variant->class_topic->discipline->faculty->speciality }}</b>

        </div>

        <div style="padding-top: 5px;">
            Вариант № <b style="font-size: 1.2em;">{{ $variant->variant }}</b>
        </div>

    </div>


    <table style="border-spacing: 20px; padding: 10px;">
        <tr>
            <td style="vertical-align: top;">
                @forelse($questionsSplit[0] as $question)
                    <div style="padding-bottom: 20px;">
                        <div style="padding-bottom: 5px;">
                            <b>{{ $loop->iteration }}</b>)
                            {{ $question['id'] }}.
                            <b>{{ $question['question'] }}</b>
                        </div>

                        @foreach($question['answers'] as $key => $answer)
                            <div style="padding-left: 10px;">
                                {{ $loop->iteration }}. {{ $answer }}
                            </div>
                        @endforeach
                    </div>
                @empty
                    Вопросы не добавлены
                @endforelse
            </td>

            <td style="vertical-align: top;">
                @forelse($questionsSplit[1] as $question)
                    <div style="padding-bottom: 20px;">
                        <div style="padding-bottom: 5px;">
                            <b>{{ $loop->iteration + $half }}</b>)
                            {{ $question['id'] }}.
                            <b>{{ $question['question'] }}</b>
                        </div>

                        @foreach($question['answers'] as $key => $answer)
                            <div style="padding-left: 10px;">
                                {{ $loop->iteration }}. {{ $answer }}
                            </div>
                        @endforeach
                    </div>
                @empty
                    Вопросы не добавлены
                @endforelse
            </td>
        </tr>

    </table>

    <div style="position: fixed; left: 0; bottom: 0; width: 100%; font-size: 12px; padding: 5px; color: gray;">
        ID: {{ $variant->id }}
    </div>

</body>
</html>
