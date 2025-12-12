<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Quiz;
use App\Models\QuizPArticipant;
use App\Enums\UserRole;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('quiz.{quiz_id}', function ($user, $quiz_id) {
    switch ($user->role){
        case UserRole::STUDENT:
            $isParticipant = QuizParticipant::where('quiz_id', $quiz_id)
                ->where('participant_id', $user->student->id)
                ->exists();
            return $isParticipant;
        case UserRole::TEACHER:
            $isCreator = Quiz::where('id', $quiz_id)
                ->where('creator_id', $user->teacher->id)
                ->exists();
            if ($isCreator) {
                return true;
            }
    }
});