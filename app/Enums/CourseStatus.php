<?php

namespace App\Enums;

enum CourseStatus: string
{
    case Draft = 'draft';                 // Teacher membuat, belum diajukan
    case PendingApproval = 'pending';     // Menunggu admin review
    case Approved = 'approved';           // Disetujui admin, tampil ke student
    case NeedsRevision = 'revision';      // Admin meminta revisi, teacher harus edit
    case Rejected = 'rejected';           // Ditolak final, course tidak dipakai
    case Hidden = 'hidden';               // Disembunyikan admin setelah approved
    case Archived = 'archived';           // Course lama, disimpan sebagai arsip
}
