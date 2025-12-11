<?php

namespace App\Helpers;

use App\Models\Student;
use App\Models\Rank;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class Utils 
{
    public static function add_exp_student(int $exp_gain, int $student_id)
    {
        $student = Student::find($student_id);
        if (!$student) return;

        $student->experience += $exp_gain;

        // Cari rank yang cocok dengan XP terbaru
        $newRank = Rank::where('min_xp', '<=', $student->experience)
                    ->where(function ($q) use ($student) {
                        $q->where('max_xp', '>=', $student->experience)
                            ->orWhereNull('max_xp');
                    })
                    ->orderBy('min_xp', 'desc')
                    ->first();
        $oldRank = $student->rank;
        if ($newRank && $student->rank_id !== $newRank->id) {
            $student->rank_id = $newRank->id;
        }

        $student->save();
        return [
            'old_rank'      => $oldRank->id,
            'old_rank_name' => $oldRank->title,
            'new_rank'      => $newRank->id,
            'new_rank_name' => $newRank->title,
        ];
    }

    /**
     * Paginate a Collection instance.
     *
     * @param Collection $collection
     * @param int $perPage
     * @param int|null $page
     * @param array $options
     * @return LengthAwarePaginator
     */
    public static function paginateCollection(Collection $collection, int $perPage = 10, ?int $page = null, array $options = []): LengthAwarePaginator
    {
        $pageName = $options['pageName'] ?? 'page';
        $page = $page ?? Paginator::resolveCurrentPage($pageName) ?: 1;

        $results = $collection->forPage($page, $perPage)->values();

        return new LengthAwarePaginator(
            $results,
            $collection->count(),
            $perPage,
            $page,
            array_merge(
                ['path' => Paginator::resolveCurrentPath(), 'pageName' => $pageName],
                $options
            )
        );
    }

}
