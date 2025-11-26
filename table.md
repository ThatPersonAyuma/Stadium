# Struktur Tabel & Relasi

## Relasi utama
- `users` 1–1 `students`/`teachers`/`admins` lewat `user_id` (hapus pengguna menghapus entri anak).
- `ranks` 1–n `students.rank_id` (hapus rank akan set kolom ke null).
- `users` 1–n `courses.teacher_id` (hapus pengajar menghapus course).
- `courses` 1–n `lessons`, `lessons` 1–n `contents`, `contents` 1–n `cards`, `cards` 1–n `blocks`.
- `sessions.user_id` hanya indeks (tanpa foreign key); tabel job/reset/cache berdiri sendiri.

## Detail tabel aplikasi

### ranks
Kolom | Tipe | Keterangan
----- | ---- | ----------
id | big integer, PK | auto increment
title | string(255) | nama peringkat
min_xp | integer | batas bawah XP
max_xp | integer, nullable | batas atas XP
created_at/updated_at | timestamps | otomatis

### users
Kolom | Tipe | Keterangan
----- | ---- | ----------
id | big integer, PK |
username | string, unique |
name | string |
email | string, unique |
email_verified_at | timestamp, nullable |
password | string |
avatar_filename | string(255), nullable |
role | enum(`student`,`teacher`), default `student` |
remember_token | string, nullable |
created_at/updated_at | timestamps |

### students
Kolom | Tipe | Keterangan
----- | ---- | ----------
id | big integer, PK |
user_id | fk -> users.id, cascade |
energy | unsigned small int, default 10 |
key | unsigned small int, default 0 |
experience | integer, default 0 |
rank_id | fk -> ranks.id, nullable, cascade update, set null on delete |

### teachers
Kolom | Tipe | Keterangan
----- | ---- | ----------
id | big integer, PK |
user_id | fk -> users.id, cascade |

### admins
Kolom | Tipe | Keterangan
----- | ---- | ----------
id | big integer, PK |
user_id | fk -> users.id, cascade |

### courses
Kolom | Tipe | Keterangan
----- | ---- | ----------
id | big integer, PK |
title | string(255), unique |
description | text |
teacher_id | fk -> users.id, cascade |
status | enum(`draft`,`pending`,`approved`,`revision`,`rejected`,`hidden`,`archived`), default `draft` |
created_at/updated_at | timestamps |

### lessons
Kolom | Tipe | Keterangan
----- | ---- | ----------
id | big integer, PK |
title | string(255), unique |
description | text |
order_index | unsigned small int | urutan dalam course
course_id | fk -> courses.id, cascade |
created_at/updated_at | timestamps |

### contents
Kolom | Tipe | Keterangan
----- | ---- | ----------
id | big integer, PK |
title | string(255), unique |
order_index | unsigned small int | urutan dalam lesson
lesson_id | fk -> lessons.id, cascade |

### cards
Kolom | Tipe | Keterangan
----- | ---- | ----------
id | big integer, PK |
order_index | unsigned small int | urutan dalam content
content_id | fk -> contents.id, cascade |

### blocks
Kolom | Tipe | Keterangan
----- | ---- | ----------
id | big integer, PK |
type | enum(`text`,`image`,`code`,`quiz`,`gif`,`video`), default `text` |
data | jsonb | payload sesuai tipe
order_index | unsigned small int | urutan dalam card
card_id | fk -> cards.id, cascade |

## Tabel sistem Laravel

### cache
Kolom | Tipe | Keterangan
----- | ---- | ----------
key | string, PK |
value | mediumText |
expiration | integer |

### cache_locks
Kolom | Tipe | Keterangan
----- | ---- | ----------
key | string, PK |
owner | string |
expiration | integer |

### jobs
Kolom | Tipe | Keterangan
----- | ---- | ----------
id | big integer, PK |
queue | string, index |
payload | longText |
attempts | unsigned tiny int |
reserved_at | unsigned int, nullable |
available_at | unsigned int |
created_at | unsigned int |

### job_batches
Kolom | Tipe | Keterangan
----- | ---- | ----------
id | string, PK |
name | string |
total_jobs/pending_jobs/failed_jobs | integer |
failed_job_ids | longText |
options | mediumText, nullable |
cancelled_at | integer, nullable |
created_at | integer |
finished_at | integer, nullable |

### failed_jobs
Kolom | Tipe | Keterangan
----- | ---- | ----------
id | big integer, PK |
uuid | string, unique |
connection | text |
queue | text |
payload | longText |
exception | longText |
failed_at | timestamp, default now |

### password_reset_tokens
Kolom | Tipe | Keterangan
----- | ---- | ----------
email | string, PK |
token | string |
created_at | timestamp, nullable |

### sessions
Kolom | Tipe | Keterangan
----- | ---- | ----------
id | string, PK |
user_id | big integer, index (tanpa fk) |
ip_address | string(45), nullable |
user_agent | text, nullable |
payload | longText |
last_activity | integer, index |
