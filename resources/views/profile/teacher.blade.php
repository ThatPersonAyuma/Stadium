@extends('layouts.dashboard')
@section('title', 'Edit Profil')

@section('content')
<div class="px-6 py-8 text-white max-w-3xl mx-auto">

    <x-dashboard-header title="Edit Profil Anda" />

    <form action="{{ route('profile.update') }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-white/10 p-6 rounded-xl space-y-8 mt-5">
        @csrf
        @method('PUT')
        <div class="hidden">
            <label class="font-semibold">Email</label>
            <input type="text"
                   name="role"
                   class="w-full mt-2 p-3 bg-white/5 rounded-lg"
                   value="{{ auth()->user()->role->value }}"
                   required>
        </div>
        <div class="flex flex-col items-center space-y-3">
            <div class="w-28 h-28 rounded-full overflow-hidden border-2 border-white/30">
                <img src="{{ asset(App\Helpers\FileHelper::getAvatarPath(auth()->id())) }}"
                     alt="Avatar"
                     class="w-full h-full object-cover">
            </div>

            <label class="font-semibold">Foto Profil</label>
            <input type="file"
                   name="avatar"
                   accept="image/*"
                   class="mt-1 bg-white/10 p-2 rounded w-full cursor-pointer">
            <p class="text-xs text-white/60">Biarkan kosong jika tidak ingin mengubah avatar.</p>
        </div>
        {{-- Nama Lengkap --}}
        <div>
            <label class="font-semibold">Nama Lengkap</label>
            <input type="text"
                   name="fullname"
                   class="w-full mt-2 p-3 bg-white/5 rounded-lg"
                   value="{{ old('name', auth()->user()->name) }}"
                   required>
        </div>

        {{-- Email --}}
        <div>
            <label class="font-semibold">Email</label>
            <input type="email"
                   name="email"
                   class="w-full mt-2 p-3 bg-white/5 rounded-lg"
                   value="{{ old('email', auth()->user()->email) }}"
                   required>
        </div>

        {{-- Username --}}
        <div>
            <label class="font-semibold">Username</label>
            <input type="text"
                   name="username"
                   class="w-full mt-2 p-3 bg-white/5 rounded-lg"
                   value="{{ old('username', auth()->user()->username) }}">
        </div>

        {{-- Phone Number --}}
        <div>
            <label class="font-semibold">Nomor Telepon</label>
            <input type="text"
                   name="phone_number"
                   class="w-full mt-2 p-3 bg-white/5 rounded-lg"
                   value="{{ old('phone_number', auth()->user()->teacher->phone_number) }}"
                   placeholder="Contoh: 08123456789">
        </div>

        {{-- Social Media --}}
        <div>
            <label class="font-semibold">Social Media (username/link)</label>
            <input type="text"
                   name="social_media"
                   class="w-full mt-2 p-3 bg-white/5 rounded-lg"
                   value="{{ old('social_media', auth()->user()->teacher->social_media) }}"
                   placeholder="@username Anda">
        </div>

        {{-- Social Media Type (Dropdown Enum) --}}
        <div>
            <label class="font-semibold">Jenis Media Sosial</label>
            <select name="social_media_type"
                    class="w-full mt-2 p-3 bg-white/5 rounded-lg">

                <option value="">Pilih Media Sosial</option>

                @foreach (App\Enums\SocialMediaType::cases() as $media)
                    <option value="{{ $media->value }}"
                        {{ old('social_media_type', auth()->user()->teacher->social_media_type) === $media->value ? 'selected' : '' }}>
                        {{ ucfirst($media->value) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Institution --}}
        <div>
            <label class="font-semibold">Institusi / Sekolah</label>
            <input type="text"
                   name="institution"
                   class="w-full mt-2 p-3 bg-white/5 rounded-lg"
                   value="{{ old('institution', auth()->user()->teacher->institution) }}"
                   placeholder="Nama sekolah / universitas">
        </div>

        {{-- Password (Opsional) --}}
        <div class="space-y-3">
            <h3 class="text-xl font-bold">Ubah Password <span class="text-sm text-white/60">(opsional)</span></h3>

            <div>
                <label class="font-semibold">Password Baru</label>
                <input type="password"
                       name="password"
                       class="w-full mt-2 p-3 bg-white/5 rounded-lg"
                       placeholder="Biarkan kosong jika tidak ingin mengubah">
            </div>

            <div>
                <label class="font-semibold">Konfirmasi Password</label>
                <input type="password"
                       name="password_confirmation"
                       class="w-full mt-2 p-3 bg-white/5 rounded-lg"
                       placeholder="Ulangi password baru">
            </div>
        </div>

        {{-- Tombol Submit --}}
        <button class="bg-blue-600 hover:bg-blue-500 px-6 py-3 rounded-lg font-bold w-full">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection
