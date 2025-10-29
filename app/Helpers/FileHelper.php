<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Content;
use App\Models\Block;
use App\Models\User;
use Illuminate\Support\Str;

class FileHelper
{
    public static function blockPath($courseId, $lessonId, $contentId, $cardId, $blockId, $filename)
    {
        $course = Course::findOrFail($courseId);
        $lesson = Lesson::findOrFail($lessonId);
        $content = Content::findOrFail($contentId);
        $courseSlug = "{$course->id}-" . Str::slug(Str::limit($course->title, 20));
        $lessonSlug = "{$lesson->id}-" . Str::slug(Str::limit($lesson->title, 20));
        $contentSlug = "{$content->id}-" . Str::slug(Str::limit($content->title, 20));
        $cardSlug = "{$cardId}-card";
        return "courses/{$courseSlug}/{$lessonSlug}/{$contentSlug}/{$cardSlug}/a"; // the last folder will be replace by filname so it doesnt matter
    }

    public static function storeBlockFile($file, $courseId, $lessonId, $contentId, $cardId, $blockId)
    {
        $filename = "{$blockId}-{$file->getClientOriginalName()}";
        $path = self::blockPath($courseId, $lessonId, $contentId, $cardId, $blockId, $filename);
        $stored = Storage::disk('public')->putFileAs(
            dirname($path),
            $file,
            $filename
        );
        return 'storage/' . $stored; // untuk ditampilkan di view
    }

    public static function getBlockFilePath($courseId, $lessonId, $contentId, $cardId, $blockId)
    {
        $course = Course::findOrFail($courseId);
        $lesson = Lesson::findOrFail($lessonId);
        $content = Content::findOrFail($contentId);
        $courseSlug = "{$course->id}-" . Str::slug(Str::limit($course->title, 20));
        $lessonSlug = "{$lesson->id}-" . Str::slug(Str::limit($lesson->title, 20));
        $contentSlug = "{$content->id}-" . Str::slug(Str::limit($content->title, 20));
        $cardSlug = "{$cardId}-card";
        $filename = Block::findOrFail($blockId)->data['filename'];
        return 'storage/' . "courses/{$courseSlug}/{$lessonSlug}/{$contentSlug}/{$cardSlug}/{$blockId}-{$filename}";
    }

    public static function storeAvatarFile($file, $userId)
    {
        $user = User::findOrFail($userId);
        $filename = "{$user->id}-{$user->avatar_filename}";
        $stored = Storage::disk('public')->putFileAs(
            dirname('avatar/a'),
            $file,
            $filename
        );
        return 'storage/' . $stored; // untuk ditampilkan di view
    }

    public static function getAvatarPath($userId)
    {
        $user = User::findOrFail($userId);
        return 'storage/avatar/' . "{$user->id}-{$user->avatar_filename}";
    }
}
