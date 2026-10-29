<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="fw-bold mb-1" style="color: #0F3B2C;">
                Knowledge Base
            </h2>
            <p class="text-secondary small mb-0">
                Referensi prosedur dan panduan penanganan alert keamanan SOC.
            </p>
        </div>
    </x-slot>

    <style>
        .kb-page {
            color: #24352d;
        }

        .kb-page .kb-card {
            border: 1px solid #dce6df;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(15, 59, 44, 0.04);
            overflow: hidden;
        }

        .kb-page .page-title {
            color: #0F3B2C;
        }

        .kb-page .form-control,
        .kb-page .input-group-text {
            border-color: #dce6df;
            min-height: 42px;
        }

        .kb-page .form-control:focus {
            border-color: #6caa8a;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.1);
        }

        /* Desain Tabel Rapi Bergaris (Bordered Style) */
        .kb-page .custom-table {
            border-color: #e2e8f0 !important;
        }

        .kb-page .custom-table thead th {
            background-color: #f8fafc;
            color: #334155;
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 14px;
            border-bottom: 2px solid #cbd5e1 !important;
        }

        .kb-page .custom-table tbody td {
            padding: 14px;
            border-color: #e2e8f0;
        }

        .kb-page .custom-table tbody tr:hover {
            background-color: #f1f5f9;
        }

        .kb-page .kb-title {
            color: #0F3B2C;
            font-weight: 700;
            text-decoration: none;
        }

        .kb-page .kb-title:hover {
            color: #198754;
        }

        .kb-page .empty-state {
            padding: 52px 20px;
            text-align: center;
        }

        .kb-page .empty-icon {
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

    <div class="kb-page">
        {{-- Notifikasi --}}
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
        @endif

        @if ($errors->has('file'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>
            <strong>Upload Knowledge Base gagal!</strong>
            <ul class="mb-0 mt-2 ps-3">
                @foreach ($errors->get('file') as $message)
                <li>{{ $message }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
        @endif

        <div class="card kb-card bg-white">
            <div class="card-body p-0">

                {{-- Header Card & Tombol Tambah Referensi --}}
                <div class="p-4 border-bottom">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h5 class="page-title fw-bold mb-1">
                                Daftar Referensi
                            </h5>
                        </div>

                        {{-- Tombol Aksi Tambah Referensi --}}
                        <div class="d-flex align-items-center gap-2">
                            <button type="button"
                                class="btn btn-success px-3 fw-semibold"
                                data-bs-toggle="modal"
                                data-bs-target="#addKnowledgeModal">
                                <i class="bi bi-plus-lg me-1"></i>
                                Tambah Referensi
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Search Otomatis --}}
                <div class="p-4 border-bottom bg-light-subtle">
                    <form id="kbSearchForm" action="{{ route('knowledge-base.index') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-12 col-md-6 col-lg-5">
                                <label for="kbSearchInput" class="form-label small fw-semibold text-dark">
                                    Cari Referensi
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-search text-secondary"></i>
                                    </span>

                                    <input type="text"
                                        id="kbSearchInput"
                                        name="search"
                                        class="form-control"
                                        placeholder="Judul, kategori, atau sumber..."
                                        value="{{ $search }}"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Informasi jumlah data --}}
                <div class="px-4 py-3 bg-white">
                    <p class="text-secondary small mb-0">
                        Menampilkan <strong>{{ $knowledgeBases->firstItem() ?? 0 }} – {{ $knowledgeBases->lastItem() ?? 0 }}</strong> dari <strong>{{ $knowledgeBases->total() }}</strong> referensi
                    </p>
                </div>

                {{-- Tabel Knowledge Base --}}
                <div class="table-responsive p-4 pt-0">
                    <table class="table table-bordered custom-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">No.</th>
                                <th>Judul Referensi</th>
                                <th class="text-center" style="width: 160px;">Kategori</th>
                                <th>Sumber</th>
                                <th class="text-center" style="width: 170px;">Terakhir Diperbarui</th>
                                <th class="text-center" style="width: 150px;">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($knowledgeBases as $index => $item)
                            <tr>
                                <td class="text-center fw-semibold text-secondary">
                                    {{ $knowledgeBases->firstItem() + $index }}
                                </td>

                                <td>
                                    <a href="javascript:void(0)"
                                        class="kb-title"
                                        data-bs-toggle="modal"
                                        data-bs-target="#knowledgeModal{{ $item->id_knowledge }}">
                                        {{ $item->title }}
                                    </a>
                                </td>

                                <td class="text-center text-dark small">
                                    {{ $item->category ?: '—' }}
                                </td>

                                <td>
                                    <span class="text-secondary small">
                                        {{ $item->source ?: 'Belum dicantumkan' }}
                                    </span>
                                </td>

                                <td class="text-center text-nowrap text-secondary small">
                                    {{ $item->updated_at?->format('d M Y') ?? '—' }}
                                </td>

                                <td class="text-center text-nowrap">
                                    <div class="d-inline-flex gap-2">
                                        <!-- Tombol Lihat/Detail -->
                                        <button type="button"
                                            class="btn btn-info text-white fw-medium d-inline-flex flex-column align-items-center justify-content-center p-2 rounded-3"
                                            style="min-width: 65px; line-height: 1.2; font-size: 0.75rem; background-color: #00c4cc; border: none;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#knowledgeModal{{ $item->id_knowledge }}"
                                            title="Lihat detail referensi">
                                            <i class="bi bi-eye-fill mb-1" style="font-size: 1.1rem;"></i>
                                            <span>Lihat</span>
                                        </button>

                                        <!-- Tombol Hapus (Khusus Administrator) -->
                                        @hasrole('Administrator')
                                        <form action="{{ route('knowledge-base.destroy', $item->id_knowledge) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus referensi ini?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="btn btn-danger text-white fw-medium d-inline-flex flex-column align-items-center justify-content-center p-2 rounded-3"
                                                style="min-width: 65px; line-height: 1.2; font-size: 0.75rem;"
                                                title="Hapus referensi">
                                                <i class="bi bi-trash-fill mb-1" style="font-size: 1.1rem;"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                        @endhasrole
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="bi bi-journal-x"></i>
                                        </div>
                                        <h6 class="fw-bold mt-3 mb-2">Referensi tidak ditemukan</h6>
                                        <p class="text-secondary small mb-3">
                                            @if ($search !== '')
                                            Coba ubah kata kunci pencarian Anda.
                                            @else
                                            Belum ada referensi yang tersimpan di database.
                                            @endif
                                        </p>
                                        @if ($search !== '')
                                        <a href="{{ route('knowledge-base.index') }}" class="btn btn-outline-success btn-sm">
                                            Reset Pencarian
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Rapi Bootstrap 5 --}}
                @if ($knowledgeBases->hasPages())
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 p-4 border-top">
                    <div class="text-secondary small">
                        Halaman <strong>{{ $knowledgeBases->currentPage() }}</strong> dari <strong>{{ $knowledgeBases->lastPage() }}</strong>
                    </div>
                    <div>
                        {{ $knowledgeBases->links('pagination::bootstrap-5') }}
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Modal Popups Detail Referensi --}}
    @foreach ($knowledgeBases as $item)
    <div class="modal fade"
        id="knowledgeModal{{ $item->id_knowledge }}"
        tabindex="-1"
        aria-labelledby="knowledgeModalLabel{{ $item->id_knowledge }}"
        aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 rounded-4 shadow">

                <div class="modal-header px-4 py-3" style="background: #F0F7F2; border-bottom: 1px solid #E1EBE3;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 44px; height: 44px; background: #DDEFE3; color: #17663A;">
                            <i class="bi bi-journal-text fs-4"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-1" id="knowledgeModalLabel{{ $item->id_knowledge }}" style="color: #0F3B2C;">
                                Detail Referensi
                            </h5>
                            <small class="text-secondary">Informasi Knowledge Base SOC</small>
                        </div>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-4">
                    <h5 class="fw-bold mb-3" style="color: #174C32;">
                        {{ $item->title }}
                    </h5>

                    <div class="d-flex flex-wrap gap-2 mb-4">
                        @if ($item->category)
                        <span class="badge rounded-pill text-bg-light border fw-normal py-2 px-3">
                            Kategori: {{ $item->category }}
                        </span>
                        @endif

                        <span class="badge rounded-pill text-bg-light border fw-normal py-2 px-3">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $item->updated_at?->format('d M Y') ?? '—' }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-secondary">SUMBER REFERENSI</label>
                        <div class="rounded-3 p-3" style="background: #F8FAF8; border: 1px solid #E6ECE7;">
                            {{ $item->source ?: 'Belum dicantumkan' }}
                        </div>
                    </div>

                    <div>
                        <label class="form-label fw-bold small text-secondary">ISI REFERENSI</label>
                        <div class="rounded-3 p-3" style="background: #F8FAF8; border: 1px solid #E6ECE7; white-space: pre-line; overflow-wrap: anywhere; line-height: 1.8;">
                            {{ $item->content }}
                        </div>
                    </div>
                </div>

                <div class="modal-footer px-4 py-3">
                    <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i> Tutup
                    </button>
                </div>

            </div>
        </div>
    </div>
    @endforeach

    {{-- Modal Tambah Referensi melalui Excel --}}
    <div class="modal fade"
        id="addKnowledgeModal"
        tabindex="-1"
        aria-labelledby="addKnowledgeModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">

                <div class="modal-header px-4 py-3" style="background: #F0F7F2; border-bottom: 1px solid #E1EBE3;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 44px; height: 44px; background: #DDEFE3; color: #17663A;">
                            <i class="bi bi-file-earmark-spreadsheet fs-4"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-1" id="addKnowledgeModalLabel" style="color: #0F3B2C;">
                                Tambah Referensi
                            </h5>
                        </div>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <form action="{{ route('knowledge-base.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">

                        <div class="mb-4">
                            <label class="form-label fw-bold">Format Excel</label>
                            <p class="small text-secondary mb-2">
                                Gunakan template resmi agar kolom data sesuai dengan format Knowledge Base.
                            </p>
                            <a href="{{ route('knowledge-base.template') }}" class="btn btn-outline-success btn-sm fw-semibold">
                                <i class="bi bi-download me-1"></i> Unduh Template Excel
                            </a>
                        </div>

                        <div class="p-3 border rounded-3 bg-light">
                            <label for="knowledgeExcelFile" class="form-label fw-bold">Pilih File Excel</label>
                            <input type="file" class="form-control" id="knowledgeExcelFile" name="file" accept=".xlsx,.xls,.csv" required>
                            <small class="text-secondary d-block mt-2">Format: XLSX, XLS, atau CSV. Maksimal 5 MB.</small>
                        </div>

                        <div class="alert alert-info small mt-3 mb-0">
                            <strong>Perhatikan:</strong> kolom <code>title</code> dan <code>content</code> wajib diisi. Kolom <code>category</code> dan <code>source</code> boleh kosong.
                        </div>

                    </div>

                    <div class="modal-footer px-4 py-3">
                        <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-success fw-semibold">
                            <i class="bi bi-cloud-arrow-up me-1"></i> Unggah dan Simpan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Script Modal Reopen --}}
    @if (session('upload_failed'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalElement = document.getElementById('addKnowledgeModal');
            if (modalElement && window.bootstrap) {
                bootstrap.Modal.getOrCreateInstance(modalElement).show();
            }
        });
    </script>
    @endif

    {{-- Script Search Otomatis --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('kbSearchForm');
            const searchInput = document.getElementById('kbSearchInput');

            if (!form || !searchInput) return;

            let searchTimeout;

            const shouldFocusSearch = sessionStorage.getItem('kbSearchFocus') === 'true';
            const savedCursor = sessionStorage.getItem('kbSearchCursor');

            if (shouldFocusSearch) {
                searchInput.focus({ preventScroll: true });
                if (savedCursor !== null) {
                    const position = Math.min(Number(savedCursor), searchInput.value.length);
                    searchInput.setSelectionRange(position, position);
                }
                sessionStorage.removeItem('kbSearchFocus');
                sessionStorage.removeItem('kbSearchCursor');
            }

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    sessionStorage.setItem('kbSearchFocus', 'true');
                    sessionStorage.setItem('kbSearchCursor', searchInput.selectionStart);
                    form.requestSubmit();
                }, 600);
            });
        });
    </script>
</x-app-layout>