<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="fw-bold mb-1" style="color: #0F3B2C;">
                All Tickets
            </h2>
            <p class="text-secondary small mb-0">
                Daftar seluruh ticket alert keamanan SOC.
            </p>
        </div>
    </x-slot>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"
                aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Notifikasi error --}}
    @if ($errors->any())
        <div class="alert alert-danger rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <div>
                    <h5 class="fw-bold mb-1" style="color: #0F3B2C;">
                        Daftar Ticket
                    </h5>
                    <p class="text-secondary small mb-0">
                        Ticket yang berhasil disimpan akan ditampilkan di sini.
                    </p>
                </div>

                <a href="{{ route('tickets.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-lg me-1"></i>
                    Analisis Alert
                </a>
            </div>

            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-secondary"></i>
                <p class="text-secondary mt-3 mb-0">
                    Tabel ticket akan ditampilkan setelah proses pengambilan data dibuat.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
