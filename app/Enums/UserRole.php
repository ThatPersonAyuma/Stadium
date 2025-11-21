<?php

namespace App\Enums;

enum UserRole : string
{
    case STUDENT ='student';
    case TEACHER = 'teacher';
    case ADMIN = 'admin';
}
