<?php

namespace Tests\Unit\Actions\Teacher;

use App\Actions\Teacher\ReadFeedbackAction;
use App\Models\Feedback;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadFeedbackTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_feedback_factory_and_relationship_works(): void
    {
        $user = User::factory()->create();
        $feedback = Feedback::factory()->for($user)->create();

        $this->assertEquals($user->id, $feedback->user->id);
    }

    public function test_feedback_cannot_be_read_by_students(): void
    {
        $this->seed();
        $user = User::factory()->create();

        $user->roles()->sync(Role::where('name', 'Student')->get());
        $this->assertEquals('Student', $user->roles->first->get()->name);

        $feedback = Feedback::factory()->for($user)->create();

        $action = new ReadFeedbackAction();

        $this->assertThrows(fn () => $action->handle($user, $feedback));
    }

    public function test_feedback_cannot_be_read_by_monitors(): void
    {
        $this->seed();
        $user = User::factory()->create();

        $user->roles()->sync(Role::where('name', 'Monitor')->get());
        $this->assertEquals('Monitor', $user->roles->first->get()->name);

        $feedback = Feedback::factory()->for($user)->create();

        $action = new ReadFeedbackAction();

        $this->assertThrows(fn () => $action->handle($user, $feedback));
    }

    public function test_feedback_can_be_read_by_teachers(): void
    {
        $this->seed();
        $user = User::factory()->create();

        $user->roles()->sync(Role::where('name', 'Teacher')->get());
        $this->assertEquals('Teacher', $user->roles->first->get()->name);

        $feedback = Feedback::factory()->for($user)->create();

        $this->assertFalse($feedback->status);

        $action = new ReadFeedbackAction();
        $action->handle($user, $feedback);

        $this->assertTrue($feedback->status);
    }
}
