<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function my_check_workday_db()
    {
        $response = $this->post('/api/check-workdayDB?date=2024-08-10&code=ZA');
        $response->assertStatus(200);
        $response->assertJsonStructure(['date', 'isWorkday', 'reason', 'country']);
    }

    /** @test */
    public function my_check_list_of_tasks()
    {
        Task::factory()->count(3)->create();

        $response = $this->get('/api/tasks');
        $response->assertStatus(200);
        $response->assertJsonStructure(['*' => ['id', 'title', 'start_datetime', 'duration_minutes', 'consider_workdays', 'workday_start', 'workday_end', 'created_at', 'updated_at']]);
    }

    /** @test */
    public function my_check_single_task()
    {
        $task = Task::factory()->create();

        $response = $this->get("/api/tasks/{$task->id}");
        $response->assertStatus(200);
        $response->assertJsonStructure([ 'id', 'title', 'start_datetime', 'duration_minutes', 'consider_workdays', 'workday_start', 'workday_end', 'created_at', 'updated_at' ]);
    }

    /** @test */
    public function my_check_create_a_task()
    {
        $data = [ 'title' => 'Test Task', 'start_datetime' => '2024-08-10 09:00:00', 'duration_minutes' => 180, 'consider_workdays' => true, 'workday_start' => '09:00:00', 'workday_end' => '17:00:00', ];

        $response = $this->post('/api/tasks', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure([ 'id', 'title', 'start_datetime', 'duration_minutes', 'consider_workdays', 'workday_start', 'workday_end', 'created_at', 'updated_at' ]);
        $this->assertDatabaseHas('tasks', ['title' => 'Test Task']);
    }

    /** @test */
    public function my_check_update_a_task()
    {
        $task = Task::factory()->create();
        $data = [ 'title' => 'Updated Task Title', 'duration_minutes' => 240, ];
        $response = $this->put("/api/tasks/{$task->id}", $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([ 'title' => 'Updated Task Title', 'duration_minutes' => 240, ]);
        $this->assertDatabaseHas('tasks', ['title' => 'Updated Task Title']);
    }

    /** @test */
    public function my_check_delete_a_task()
    {
        $task = Task::factory()->create();
        $response = $this->delete("/api/tasks/{$task->id}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function my_check_calculate_task_duration()
    {
        $data = [ 'start_datetime' => '2024-08-10 09:00:00', 'duration_minutes' => 180, 'consider_workdays' => true, 'workday_start' => '09:00:00', 'workday_end' => '17:00:00', 'country_code' => 'ZA', ];
        $response = $this->post('/api/tasks/task-duration', $data);
        $response->assertStatus(200);
        $response->assertJsonStructure([ 'TaskDuration_datetime', ]);
    }
}
