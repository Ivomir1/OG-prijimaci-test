<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Holiday;
use Illuminate\Support\Facades\Validator;

class WorkdayService
{
    public function isWorkday(Carbon $date, string $countryCode): array
    {     
        $validator = \Validator::make([ 'date' => $date->toDateString(), 'code' => $countryCode, ], [ 'date' => 'required|date', 'code' => 'required|string', ]); // zvaliduju vstupy       
        if ($validator->fails()) throw new \InvalidArgumentException($validator->errors()->first()); // chytám vyjímku
        $isHoliday = Holiday::where('code', $countryCode)->where('date', $date->format('Y-m-d'))->exists(); // na svátky čumím do DB
        if ($isHoliday) return ['isWorkday' => false, 'reason' => 'holiday']; // vracím svátek 
        $isWeekend = in_array($date->format('l'), ["Saturday", "Sunday"]); // estlivá je víkend
        if ($isWeekend) return ['isWorkday' => false, 'reason' => 'weekend'];  // vracím víkend     
        return ['isWorkday' => true, 'reason' => 'workday']; // pokud ani svátek ani víkend je pracovní den
    }
}
