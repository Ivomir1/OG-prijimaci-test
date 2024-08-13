<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Task; 
use Carbon\Carbon;
use App\Services\TaskService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{   protected $taskService;
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }
    //REST záležitosti, vyjímky chytávám v middlevéru  
    public function index()
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

    public function store(Request $request)
    {
        $data = $request->validate([ 'title' => 'required|string|max:255', 'start_datetime' => 'required|date', 'duration_minutes' => 'required|integer', 'consider_workdays' => 'required|boolean', 'workday_start' => 'required|date_format:H:i:s', 'workday_end' => 'required|date_format:H:i:s', ]);
        $task = Task::create($data);
        return response()->json($task, 201); // 201 Created
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $data = $request->validate([ 'title' => 'sometimes|required|string|max:255', 'start_datetime' => 'sometimes|required|date', 'duration_minutes' => 'sometimes|required|integer', 'consider_workdays' => 'sometimes|required|boolean', 'workday_start' => 'sometimes|required|date_format:H:i:s', 'workday_end' => 'sometimes|required|date_format:H:i:s', ]);
        $task->update($data);
        return response()->json($task);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(null, 204); // 204 No Content
    }

    public function TaskDuration(Request $request)
    {        
        // data zvaliduji až uvnitř servisi, zavolam service 
        $TaskDurationDateTime = $this->taskService->calculateTaskDuration( new Carbon($request->input('start_datetime')), $request->input('duration_minutes'), $request->input('consider_workdays'), $request->input('workday_start'), $request->input('workday_end'), $request->input('country_code') );        // zalohuju po volání 
        Log::info('Calculating task duration', [ 'start_datetime' => $request->input('start_datetime'), 'duration_minutes' => $request->input('duration_minutes'), 'consider_workdays' => $request->input('consider_workdays'), 'workday_start' => $request->input('workday_start'), 'workday_end' => $request->input('workday_end'), 'country_code' => $request->input('country_code'), 'calculated_completion_datetime' => $TaskDurationDateTime->toDateTimeString() ]);  //zaloguju volání
        return response()->json(['TaskDuration_datetime' => $TaskDurationDateTime->toDateTimeString()]);
    }
}
