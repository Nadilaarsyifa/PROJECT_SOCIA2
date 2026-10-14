<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="fw-bold mb-1" style="color: #0F3B2C;">
                Knowledge Base / SOP
            </h2>
            <p class="text-secondary small mb-0">
                Kelola referensi prosedur penanganan alert keamanan SOC.
            </p>
        </div>
    </x-slot>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center"
                     style="width: 48px; height: 48px; background: #E5F3EA; color: #17824A;">
                    <i class="bi bi-journal-text fs-3"></i>
                </div>

                <div>
                    <h5 class="fw-bold mb-1" style="color: #0F3B2C;">
                        Daftar Knowledge Base
                    </h5>
                    <p class="text-secondary small mb-0">
                        Referensi SOP untuk mendukung analisis dan penanganan alert.
                    </p>
                </div>
            </div>

            <hr>

            <p class="text-secondary mb-0">
                Belum ada data Knowledge Base yang ditampilkan.
                Fitur pengelolaan SOP akan dibuat pada tahap berikutnya.
            </p>
        </div>
    </div>
</x-app-layout>