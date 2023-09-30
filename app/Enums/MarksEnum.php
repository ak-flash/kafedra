<?php

namespace App\Enums;

use ArchTech\Enums\Meta\Meta;
use ArchTech\Enums\Metadata;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;
use App\Enums\MetaProperties\{Description, Color};
#[Meta(Description::class, Color::class)]

enum MarksEnum: int
{
    use Metadata;
    use Options;
    use Values;
    use Names;

    #[Description('Неудовлетворительно')] #[Color('red')]
    case BAD = 2;

    #[Description('Удовлетворительно')] #[Color('yellow')]
    case SATISFACTORY = 3;

    #[Description('Хорошо')] #[Color('gray')]
    case GOOD = 4;

    #[Description('Отлично')] #[Color('green')]
    case EXCELLENT = 5;

    public static function getPositive(): array
    {
        return [
            MarksEnum::SATISFACTORY->value => MarksEnum::SATISFACTORY->value,
            MarksEnum::GOOD->value => MarksEnum::GOOD->value,
            MarksEnum::EXCELLENT->value => MarksEnum::EXCELLENT->value,
        ];
    }

    public static function getPositiveWithDescriptions(): array
    {

        return [
            MarksEnum::SATISFACTORY->value => MarksEnum::SATISFACTORY->value.' - '.MarksEnum::SATISFACTORY->description(),

            MarksEnum::GOOD->value => MarksEnum::GOOD->value.' - '.MarksEnum::GOOD->description(),

            MarksEnum::EXCELLENT->value => MarksEnum::EXCELLENT->value.' - '.MarksEnum::EXCELLENT->description(),
        ];
    }
}
