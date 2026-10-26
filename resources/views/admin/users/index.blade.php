<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="fw-bold mb-1" style="color: #0F3B2C;">
                Kelola User
            </h2>
            <p class="text-secondary small mb-0">
                Daftar dan manajemen pengguna sistem SOCIA.
            </p>
        </div>
    </x-slot>

    <style>
        .users-page {
            color: #24352d;
        }

        .users-page .user-card {
            border: 1px solid #dce6df;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(15, 59, 44, 0.04);
            overflow: hidden;
        }

        .users-page .page-title {
            color: #0F3B2C;
        }

        /* Desain Tabel Bergaris (Bordered Style) */
        .users-page .custom-table {
            border-color: #e2e8f0 !important;
        }

        .users-page .custom-table thead th {
            background-color: #f8fafc;
            color: #334155;
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 14px;
            border-bottom: 2px solid #cbd5e1 !important;
        }

        .users-page .custom-table tbody td {
            padding: 14px;
            border-color: #e2e8f0;
        }

        .users-page .custom-table tbody tr:hover {
            background-color: #f1f5f9;
        }

        .users-page .role-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 0.75rem;
            font-weight: 600;
            background-color: #e2e8f0;
            color: #334155;
            border: 1px solid #cbd5e1;
        }

        .users-page .role-admin {
            background-color: #fef2f2;
            color: #dc2626;
            border-color: #fecaca;
        }

        .users-page .role-engineer {
            background-color: #eff6ff;
            color: #2563eb;
            border-color: #bfdbfe;
        }

        .users-page .empty-state {
            padding: 52px 20px;
            text-align: center;
        }

        .users-page .empty-icon {
            width: 58px;
            height: 58px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #edf5ef;
            color: #176b45;
            border-radius: 16px;
            font-size: 1.7rem;
        }
    </style>

    <div class="users-page">
        {{-- Notifikasi --}}
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
        @endif

        {{-- Notifikasi Password Sementara --}}
        @if (session('temporary_password'))
        <div class="alert alert-warning border-warning rounded-3 mb-4" role="alert">
            <div class="d-flex align-items-start gap-3">
                <i class="bi bi-key-fill fs-4 text-warning"></i>

                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1">Reset Password Berhasil</h6>

                    <p class="small mb-2">
                        Password sementara untuk
                        <strong>{{ session('temporary_username', session('username', 'pengguna')) }}</strong>:
                    </p>

                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <code id="temporaryPassword"
                            class="fs-6 fw-bold bg-white px-3 py-2 rounded border text-dark">{{ session('temporary_password') }}</code>

                        <button type="button"
                            class="btn btn-sm btn-outline-dark"
                            onclick="navigator.clipboard.writeText(document.getElementById('temporaryPassword').innerText).then(() => { this.innerHTML = '<i class=\'bi bi-check2\'></i> Tersalin'; }).catch(() => alert('Gagal menyalin. Silakan salin password secara manual.'));">
                            <i class="bi bi-clipboard"></i> Salin Password
                        </button>
                    </div>

                    <p class="small mt-2 mb-0 text-secondary">
                        Sampaikan password ini kepada pengguna melalui saluran yang aman.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <div class="card user-card bg-white">
            <div class="card-body p-0">
                {{-- Header Card & Judul --}}
                <div class="p-4 border-bottom">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h5 class="page-title fw-bold mb-1">
                                Daftar Pengguna SOCIA
                            </h5>
                            <p class="text-secondary small mb-0">
                                Total pengguna terdaftar: <strong>{{ $users->count() }}</strong>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Tabel Pengguna --}}
                <div class="table-responsive p-4">
                    <table class="table table-bordered custom-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 60px;">No.</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th class="text-center" style="width: 200px;">Role</th>
                                <th class="text-center" style="width: 220px;">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($users as $user)
                            <tr>
                                <td class="text-center fw-semibold text-secondary">
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    <span class="fw-bold text-dark">
                                        {{ $user->nama }}
                                    </span>
                                </td>

                                <td>
                                    <span class="text-secondary font-monospace">
                                        {{ $user->username }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    @php
                                    $roles = $user->getRoleNames();
                                    @endphp
                                    @if ($roles->isNotEmpty())
                                    @foreach ($roles as $role)
                                    @php
                                    $roleClass = match (strtolower($role)) {
                                    'administrator', 'admin' => 'role-admin',
                                    'soc engineer', 'engineer' => 'role-engineer',
                                    default => '',
                                    };
                                    @endphp
                                    <span class="role-badge {{ $roleClass }} me-1">
                                        {{ $role }}
                                    </span>
                                    @endforeach
                                    @else
                                    <span class="role-badge">Belum ada role</span>
                                    @endif
                                </td>

                                <td class="text-center text-nowrap">
                                    <div class="d-inline-flex gap-2">
                                        <!-- Tombol Reset Password -->
                                        <form action="{{ route('users.reset-password', $user->getKey()) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Reset password pengguna ini?')">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-info text-white fw-medium d-inline-flex flex-column align-items-center justify-content-center p-2 rounded-3"
                                                style="min-width: 65px; line-height: 1.2; font-size: 0.75rem; background-color: #00c4cc; border: none;">
                                                <i class="bi bi-key-fill mb-1" style="font-size: 1.1rem;"></i>
                                                <span>Reset</span>
                                            </button>
                                        </form>

                                        <!-- Tombol Hapus -->
                                        @if (!auth()->user()->is($user))
                                        <form action="{{ route('users.destroy', $user->getKey()) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-danger text-white fw-medium d-inline-flex flex-column align-items-center justify-content-center p-2 rounded-3"
                                                style="min-width: 65px; line-height: 1.2; font-size: 0.75rem;">
                                                <i class="bi bi-trash-fill mb-1" style="font-size: 1.1rem;"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <h6 class="fw-bold mt-3 mb-2">Belum ada pengguna</h6>
                                        <p class="text-secondary small mb-0">
                                            Pengguna yang terdaftar akan ditampilkan di tabel ini.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>