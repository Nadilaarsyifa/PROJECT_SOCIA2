<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kelola User
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-semibold mb-4">
                        Daftar Pengguna SOCIA
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left">No.</th>
                                    <th class="px-4 py-3 text-left">Nama</th>
                                    <th class="px-4 py-3 text-left">Username</th>
                                    <th class="px-4 py-3 text-left">Role</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($users as $user)
                                    <tr class="border-t">
                                        <td class="px-4 py-3">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $user->nama }}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $user->username }}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $user->getRoleNames()->implode(', ') ?: 'Belum ada role' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-center">
                                            Belum ada pengguna.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <p class="mt-4 text-sm text-gray-500">
                        Total pengguna: {{ $users->count() }}
                    </p>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>