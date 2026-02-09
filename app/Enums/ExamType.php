<?php

namespace App\Enums;

enum ExamType: string
{
    case Monthly = 'شهري';
    case Midterm = 'نصفي';
    case Final = 'نهائي';

}
