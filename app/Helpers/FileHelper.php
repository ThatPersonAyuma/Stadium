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
    public static function getFolderName($courseId, $lessonId=NULL, $contentId=NULL, $cardId=NULL){
        $course = Course::findOrFail($courseId);
        $courseSlug = "{$course->id}-" . Str::slug(Str::limit($course->title, 20));

        $path = "courses/{$courseSlug}";

        if ($lessonId) {
            $lesson = Lesson::findOrFail($lessonId);
            $lessonSlug = "{$lesson->id}-" . Str::slug(Str::limit($lesson->title, 20));
            $path .= "/{$lessonSlug}";
        }

        if ($contentId) {
            $content = Content::findOrFail($contentId);
            $contentSlug = "{$content->id}-" . Str::slug(Str::limit($content->title, 20));
            $path .= "/{$contentSlug}";
        }

        if ($cardId){
            $path .="/{$cardId}-card";
        }

        return $path; 
    }

    public static function changeFolderName($new_path, $old_path)
    {
        if (file_exists($old_path)) {
        } else {
            return response()->json(['message' => 'Ini salah ew old path' . $old_path], 500);
        }
        $result = rename($old_path, $new_path);
        if ($result){
            return true;
        }else{
            return false;
        }
    }

    public static function deleteFolder($courseId, $lessonId=NULL, $contentId=NULL, $cardId=NULL)
    {
        $path = FileHelper::getFolderName($courseId, $lessonId, $contentId, $cardId);
        // return $path;
        return Storage::disk('public')->deleteDirectory($path);
    }

    public static function blockPath($courseId, $lessonId, $contentId, $cardId)
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
        $filename =  "{$blockId}-{$file->getClientOriginalName()}";
        $path = self::blockPath($courseId, $lessonId, $contentId, $cardId);
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

    public static function deleteBlockFile($courseId, $lessonId, $contentId, $cardId, $blockId)
    {
        $block = Block::findOrFail($blockId);

        // filename di DB tanpa prefix blockId
        $originalFilename = $block->data['filename'] ?? null;

        if (!$originalFilename) {
            return false; // tidak ada file untuk dihapus
        }

        // prefix yang dipakai saat upload
        $prefixed = "{$blockId}-{$originalFilename}";

        // folder path yang sama dipakai di storeBlockFile (tanpa "storage/")
        $course = Course::findOrFail($courseId);
        $lesson = Lesson::findOrFail($lessonId);
        $content = Content::findOrFail($contentId);

        $courseSlug = "{$course->id}-" . Str::slug(Str::limit($course->title, 20));
        $lessonSlug = "{$lesson->id}-" . Str::slug(Str::limit($lesson->title, 20));
        $contentSlug = "{$content->id}-" . Str::slug(Str::limit($content->title, 20));
        $cardSlug = "{$cardId}-card";

        // path relatif di disk('public')
        $filePath =
            "courses/{$courseSlug}/{$lessonSlug}/{$contentSlug}/{$cardSlug}/{$prefixed}";

        // Hapus file di storage/app/public/...
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
            return true;
        }

        return false;
    }


    public static function getBlockUrl($courseId, $lessonId, $contentId, $cardId, $blockId)
    {
        $course = Course::findOrFail($courseId);
        $lesson = Lesson::findOrFail($lessonId);
        $content = Content::findOrFail($contentId);
        $courseSlug = "{$course->id}-" . Str::slug(Str::limit($course->title, 20));
        $lessonSlug = "{$lesson->id}-" . Str::slug(Str::limit($lesson->title, 20));
        $contentSlug = "{$content->id}-" . Str::slug(Str::limit($content->title, 20));
        $cardSlug = "{$cardId}-card";
        $filename = Block::findOrFail($blockId)->data['filename'];
        return Storage::Url("courses/{$courseSlug}/{$lessonSlug}/{$contentSlug}/{$cardSlug}/{$blockId}-{$filename}");
    }
    public static function getBlockUrlPath($courseId, $lessonId, $contentId, $cardId = NULL)
    {
        $course = Course::findOrFail($courseId);
        $lesson = Lesson::findOrFail($lessonId);
        $content = Content::findOrFail($contentId);
        $courseSlug = "{$course->id}-" . Str::slug(Str::limit($course->title, 20));
        $lessonSlug = "{$lesson->id}-" . Str::slug(Str::limit($lesson->title, 20));
        $contentSlug = "{$content->id}-" . Str::slug(Str::limit($content->title, 20));
        if ($cardId === NULL){
            return  Storage::Url("courses/{$courseSlug}/{$lessonSlug}/{$contentSlug}");
        }
        $cardSlug = "{$cardId}-card";
        return Storage::Url("courses/{$courseSlug}/{$lessonSlug}/{$contentSlug}/{$cardSlug}");
    }
        // $cardSlug = "{$cardId}-card";
        // $filename = Block::findOrFail($blockId)->data['filename'];

    public static function storeAvatarFile($file, $userId)
    {
        $user = User::findOrFail($userId);
        $filename = "{$user->id}-{$file->getClientOriginalName()}";
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
        if ($user->avatar_filename == NULL){
            return asset('images/user.png');
        }
        return Storage::Url('avatar/' . "{$user->id}-{$user->avatar_filename}");
    }
}
