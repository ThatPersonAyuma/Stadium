<?php

namespace App\Enums;

enum ContentType : string
{
    // ['text', 'image', 'code', 'quiz', 'gif', 'video']
    case TEXT = 'text';
    case IMAGE = 'image';
    case CODE = 'code';
    case QUIZ = 'quiz';
    case GIF = 'gif';
    case VIDEO = 'video'; // the video must less than 30 second
}
