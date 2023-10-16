<?php

namespace App\Actions\Teacher;

use App\Models\Feedback;
use App\Models\User;
use Error;

class ReadFeedbackAction
{
    public function handle(User $user, Feedback $feedback)
    {
        if(!$user->isTeacher()){
            throw new Error("User must be a Teacher to read feedbacks!");
        }

        $feedback->status = true;

        $feedback->saveOrFail();
    }
}