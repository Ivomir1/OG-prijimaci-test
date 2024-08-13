<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\WorkdayService;

class WorkdayController extends Controller
{
    protected $workdayService;
    
    public function __construct(WorkdayService $workdayService)
    {
        $this->workdayService = $workdayService;
    }

    public function checkWorkdayDB(Request $request)
    {               
            $date = Carbon::parse($request->input('date')); // vytáhnu si datum
            $countryId = $request->input('code'); // vytáhnu si CODE země        
            Log::info('Checking workday for date: ' . $date->toDateString() . ' and country: ' . $countryId); // pro sichr loguju  
            $result = $this->workdayService->isWorkday($date, $countryId);     //funckionalitu sem vrznul do service, abych ji mohl volat servisou z druhého úkolu           
            return response()->json([ 'date' => $date->toDateString(), 'isWorkday' => $result['isWorkday'], 'reason' => $result['reason'], 'country' => $countryId, ]);           
    }
}

