<x-dashboard-layout title="Pengguna">
    <x-page-header title="Pengguna" subtitle="Kelola akun Owner, Kasir, dan Barber.">
        <x-slot:action>
            <a href="{{ route('owner.users.create') }}"
               class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-neutral-900 transition-colors hover:bg-neutral-200">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah User
            </a>
        </x-slot:action>
    </x-page-header>

    <div class="rise" style="animation-delay:90ms">
        <x-card>
            <div class="overflow-x-auto">
                <table class="rtable w-full text-sm text-center [&_th]:text-center sm:[&_td]:text-center">
                    <thead class="border-b border-neutral-200 text-xs uppercase tracking-wider text-neutral-400">
                        <tr>
                            <th class="py-2.5 pr-4 font-medium">Nama</th>
                            <th class="py-2.5 pr-4 font-medium">Email</th>
                            <th class="py-2.5 pr-4 font-medium">Role</th>
                            <th class="py-2.5 pr-4 font-medium">Telepon</th>
                            <th class="py-2.5 pr-4 font-medium">Status</th>
                            <th class="py-2.5 pr-4 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach ($users as $user)
                            <tr class="transition-colors hover:bg-neutral-50">
                                <td data-label="Nama" class="py-3 pr-4 font-medium text-neutral-900">{{ $user->name }}</td>
                                <td data-label="Email" class="py-3 pr-4 text-neutral-600">{{ $user->email }}</td>
                                <td data-label="Role" class="py-3 pr-4 capitalize text-neutral-600">{{ $user->role }}</td>
                                <td data-label="Telepon" class="py-3 pr-4 text-neutral-600">{{ $user->phone ?? '-' }}</td>
                                <td data-label="Status" class="py-3 pr-4">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-neutral-200 text-neutral-600' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td data-label="Aksi" class="py-3 pr-4 whitespace-nowrap">
                                    <div class="inline-flex items-center justify-center gap-1">
                                        <a href="{{ route('owner.users.edit', $user) }}" title="Edit"
                                           class="grid h-8 w-8 place-items-center rounded-lg text-neutral-600 transition-colors hover:bg-neutral-100 hover:text-neutral-900">
                                            <x-icon name="edit" class="h-4 w-4" />
                                        </a>
                                        <form action="{{ route('owner.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                            @csrf @method('DELETE')
                                            <button title="Hapus" class="grid h-8 w-8 place-items-center rounded-lg text-rose-600 transition-colors hover:bg-rose-50">
                                                <x-icon name="trash" class="h-4 w-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $users->links() }}</div>
        </x-card>
    </div>
</x-dashboard-layout>
