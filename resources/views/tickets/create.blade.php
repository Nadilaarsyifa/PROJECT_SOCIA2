<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="fw-bold mb-1" style="color: #0F3B2C;">
                Analyze Ticket
            </h2>
            <p class="text-secondary mb-0 small">
                Analisis alert keamanan dari Magnus SOC
            </p>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">

                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="rounded-3 d-flex align-items-center justify-content-center"
                                 style="width: 48px; height: 48px; background: #E5F3EA; color: #17824A;">
                                <i class="bi bi-shield-check fs-3"></i>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1" style="color: #0F3B2C;">
                                    Input Alert
                                </h5>
                                <p class="text-secondary small mb-0">
                                    Tempelkan pesan alert Magnus untuk memulai analisis.
                                </p>
                            </div>
                        </div>

                        <form action="#" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="raw_message" class="form-label fw-semibold">
                                    Pesan Alert Magnus
                                </label>

                                <textarea
                                    id="raw_message"
                                    name="raw_message"
                                    class="form-control rounded-3"
                                    rows="10"
                                    placeholder="Tempelkan pesan alert Magnus di sini..."
                                    required
                                >{{ old('raw_message') }}</textarea>

                                <div class="form-text">
                                    Pastikan informasi alert yang ditempelkan sesuai dengan pesan aslinya.
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button
                                    type="submit"
                                    class="btn text-white px-4 py-2 rounded-3"
                                    style="background: #17824A;"
                                    disabled
                                >
                                    <i class="bi bi-stars me-2"></i>
                                    Analisis Alert
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-2" style="color: #0F3B2C;">
                            <i class="bi bi-info-circle me-2"></i>
                            Informasi
                        </h6>

                        <p class="text-secondary mb-0 small">
                            Setelah alert dianalisis, sistem akan menampilkan ringkasan alert,
                            poin perhatian, dan langkah penanganan yang relevan untuk membantu
                            engineer melakukan investigasi.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>