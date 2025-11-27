<?php

namespace App\Enums;

enum CourseStatus: string
{
    case DRAFT = 'draft';                 // Teacher membuat, belum diajukan
    case PENDING = 'pending';     // Menunggu admin review
    case APPROVED = 'approved';           // Disetujui admin, tampil ke student
    case REVISION = 'revision';      // Admin meminta revisi, teacher harus edit
    case REJECTED = 'rejected';           // Ditolak final, course tidak dipakai
    case HIDDEN = 'hidden';               // Disembunyikan admin setelah approved
    case ARCHIVED = 'archived';           // Course lama, disimpan sebagai arsip
}
