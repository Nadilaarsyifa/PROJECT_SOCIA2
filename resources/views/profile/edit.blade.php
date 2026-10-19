<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="h4 fw-bold mb-1" style="color: #0F3B2C;">
                Pengaturan Profil
            </h2>
            <p class="text-muted small mb-0">
                Kelola informasi pribadi dan keamanan akunmu.
            </p>
        </div>
    </x-slot>

    <div class="container py-4" style="max-width: 900px;">

        {{-- Informasi Profil --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4 p-md-5">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Ubah Password --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-md-5">
                @include('profile.partials.update-password-form')
            </div>
        </div>

    </div>
</x-app-layout>
