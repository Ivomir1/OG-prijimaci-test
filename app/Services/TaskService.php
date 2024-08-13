<?php

namespace App\Services;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class TaskService
{
    protected $workdayService;
   
    public function __construct(WorkdayService $workdayService)
    {
        $this->workdayService = $workdayService;
    }


    // vstupy ze zadání úkolu č.2 
    // Počáteční datum a čas. Jedná se o čas vzniku tasku. $startDateTime
    // Předpokládaná délka trvání úkolu. Jedná se počet minut potřebných k dokončení tasku. $durationMinutes
    // Příznak, zda zohledňovat pracovní dny. $allowWorkdays
    // Počátek pracovní doby - čas ve formátu hh:mm:ss. $workdayStart
    // Konec pracovní doby - čas ve formátu hh:mm:ss. $workdayEnd
    // Přidal jsem si proměnou na zohlednění země provádění úkolu $countryCode
    // Výstupem bude předpokládaný datum a čas dokončení tasku. $currentDateTime v mém případě typu Carbon

    public function calculateTaskDuration(Carbon $startDateTime, int $durationMinutes, bool $allowWorkdays, string $workdayStart, string $workdayEnd, string $countryCode): Carbon
    {        
        // Validace vstupů
        $validator = Validator::make([ 'start_datetime' => $startDateTime, 'duration_minutes' => $durationMinutes, 'workday_start' => $workdayStart, 'workday_end' => $workdayEnd, 'country_code' => $countryCode, ], [ 'start_datetime' => 'required|date', 'duration_minutes' => 'required|integer|min:1', 'workday_start' => 'required|date_format:H:i:s', 'workday_end' => 'required|date_format:H:i:s|after:workday_start', 'country_code' => 'required|string', ]);
        if ($validator->fails())  throw new \InvalidArgumentException($validator->errors()->first()); 
        
        $currentDateTime = $startDateTime->copy(); // datum a čas, od kterého to začínám počítat.
        $workdayStartTime = Carbon::parse($workdayStart); // Začátek pracovní doby.
        $workdayEndTime = Carbon::parse($workdayEnd); // Konec pracovní doby.
        $remainingMinutes = $durationMinutes; // Kolik minut zbývá do dokončení úkolu, tuto proměnnou budu postupně snižovat
    
        while ($remainingMinutes > 0) { // Dokud zbývají minuty, pokračuji.
            
            if ($allowWorkdays) { // pokud je true, kontroluji, jestli je pracovní den.
                $workdayInfo = $this->workdayService->isWorkday($currentDateTime, $countryCode); // Zjišťuji, jestli je dneska pracovní den. volám servisu
                if (!$workdayInfo['isWorkday']) { // Pokud není pracovní den, skočí na další den a začne od začátku pracovní doby.
                    $currentDateTime->addDay()->setTimeFrom($workdayStartTime); //přidám další den
                    continue; // ukončím cyklus a začnu znova s novým datem
                }
            }  
            if ($currentDateTime->hour < $workdayStartTime->hour) { // pokud je před pracovní dobou, nastavím čas na začátek pracovní doby ten stejnej den
                $currentDateTime->setTimeFrom($workdayStartTime);
                continue;
            } elseif ($currentDateTime->hour >= $workdayEndTime->hour) { // pokud je po pracovní době, nastavím čas na začátek pracovní doby další den
                $currentDateTime->addDay()->setTimeFrom($workdayStartTime);
                continue;
            }
    
            $endOfDay = $currentDateTime->copy()->setTimeFrom($workdayEndTime); // nastavím si aktuální den na konec prac. doby
            $minutesUntilEndOfDay = $currentDateTime->diffInMinutes($endOfDay); // vytáhnu si, kolik zbývá minut do konce akt. dne
    
            if ($remainingMinutes <= $minutesUntilEndOfDay) { // Pokud se to dá stihnout dneska, přičte zbývající minuty a hotovo.
                $currentDateTime->addMinutes($remainingMinutes);  // přidám zbývající minuty a finíto
                break; // ukončím cyklus while
            } else { // Pokud ne, odečte, co se stihlo, a přeskočí na další den.
                $remainingMinutes -= $minutesUntilEndOfDay;
                $currentDateTime->addDay()->setTimeFrom($workdayStartTime);
            }
        }    
        return $currentDateTime; // Vrátí čas, kdy bude úkol hotový - viz zadání funkce vrací Carbon
    }
    
}

