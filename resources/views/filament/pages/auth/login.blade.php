<x-filament-panels::page.simple>
    {{-- Slot buat nambahin logo atau teks di atas form --}}
    <div class="mb-6 text-center">
        {{-- Abang bisa naruh logo di sini nanti --}}
    </div>

    {{-- Ini bagian utama form login Filament --}}
    {{ $this->content }}

    {{-- Footer Custom VeloNet --}}
    <div class="mt-8 text-center text-xs text-gray-500 opacity-50">
        &copy; {{ date('Y') }} VeloNet Technology. All rights reserved.
    </div>
</x-filament-panels::page.simple>
