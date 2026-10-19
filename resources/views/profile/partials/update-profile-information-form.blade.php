
<section>
    <header class="mb-4">
        <h2 class="h5 fw-bold" style="color: #0F3B2C;">
            Informasi Profil
        </h2>
        <p class="text-muted small mb-0">
            Perbarui nama dan foto profil akunmu.
        </p>
    </header>

    <form method="POST"
          action="{{ route('profile.update') }}"
          enctype="multipart/form-data"
          class="d-flex flex-column gap-4">
        @csrf
        @method('PATCH')

        {{-- Nama --}}
        <div>
            <label for="nama" class="form-label fw-semibold">
                Nama Lengkap
            </label>

            <input
                type="text"
                name="nama"
                id="nama"
                class="form-control @error('nama') is-invalid @enderror"
                value="{{ old('nama', $user->nama) }}"
                maxlength="255"
                required
            >

            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Username: hanya ditampilkan --}}
        <div>
            <label for="username" class="form-label fw-semibold">
                Username
            </label>

            <input
                type="text"
                id="username"
                class="form-control"
                value="{{ $user->username }}"
                readonly
            >

            <div class="form-text">
                Username tidak dapat diubah melalui halaman profil.
            </div>
        </div>

        {{-- Role: hanya ditampilkan --}}
        <div>
            <label for="role" class="form-label fw-semibold">
                Role
            </label>

            <input
                type="text"
                id="role"
                class="form-control"
                value="{{ $user->role }}"
                readonly
            >

            <div class="form-text">
                Role akun tidak dapat diubah melalui halaman profil.
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn text-white"
                    style="background-color: #17824A;">
                <i class="bi bi-floppy me-1"></i>
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <span class="text-success small">
                    Perubahan berhasil disimpan.
                </span>
            @endif
        </div>
    </form>
</section>
