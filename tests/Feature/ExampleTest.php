<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user_can_add_a_task_and_is_redirected_home(): void
    {
        $response = $this->post('/tasks', [
            'description' => 'Ship release notes',
            'due_date' => now()->addDay()->toDateString(),
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('tasks', [
            'description' => 'Ship release notes',
            'done' => false,
        ]);
    }

    public function test_due_date_cannot_be_in_the_past(): void
    {
        $response = $this->from('/')->post('/tasks', [
            'description' => 'Old task',
            'due_date' => now()->subDay()->toDateString(),
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors('due_date');
    }

    public function test_marking_task_done_maintains_filter_state(): void
    {
        $task = Task::factory()->create(['done' => false]);

        $response = $this->patch('/tasks/'.$task->id, [
            'status' => 'overdue',
        ]);

        $response->assertRedirect('/?status=overdue');
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'done' => true,
        ]);
    }

    public function test_open_filter_shows_only_undone_tasks(): void
    {
        $open = Task::factory()->create(['description' => 'Still open', 'done' => false]);
        Task::factory()->create(['description' => 'Already done', 'done' => true]);

        $response = $this->get('/?status=open');

        $response->assertOk();
        $response->assertSeeText($open->description);
        $response->assertDontSeeText('Already done');
    }
}
