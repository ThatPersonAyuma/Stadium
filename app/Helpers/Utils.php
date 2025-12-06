<?php

namespace App\Helpers;

use App\Models\Student;
use App\Models\Rank;
use Illuminate\Support\Facades\Log;
class Utils 
{
    public static function add_exp_student(int $exp_gain, int $student_id)
    {
        $student = Student::find($student_id);
        if (!$student) return; 
        Log::info('exp: '. $student->experience);
        $student->experience += $exp_gain;
        Log::info('exp: '. $student->experience);
        $currentMaxXp = $student->rank?->max_xp;
        if ($currentMaxXp !== null && $student->experience > $currentMaxXp) {
            $newRank = Rank::where('min_xp', '>', $student->experience)
                            ->orderBy('min_xp')
                            ->first();
            if ($newRank) {
                $student->rank_id = $newRank->id;
            }
        }

        $student->save();
    }
}
