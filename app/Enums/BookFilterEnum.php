<?php

namespace App\Enums;

enum BookFilterEnum: string
{
    case popularLastMonth = 'Popular Last Month';
    case popularLastSixMonths = 'Popular Last 6 Months';
    case hiRatedLastMonth = 'Highest Rated Last Month';
    case hiRatedLastSixMonths = 'Highest Rated Last 6 Months';
}
