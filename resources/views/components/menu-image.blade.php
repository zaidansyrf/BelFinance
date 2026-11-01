@props(['item', 'size' => 'h-12 w-12', 'text' => 'text-sm'])

{{-- Gambar menu; bila belum ada gambar tampil kotak berwarna berisi inisial nama menu --}}
@if ($item->image_url)
    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" loading="lazy"
        {{ $attributes->merge(['class' => "$size object-cover rounded-lg bg-gray-100"]) }}>
@else
    @php
        $palette = ['bg-[#2B7A78]', 'bg-[#3AAFA9]', 'bg-[#468585]', 'bg-[#537D5D]', 'bg-[#73946B]', 'bg-[#DB5461]'];
        $color = $palette[crc32($item->name) % count($palette)];
    @endphp
    <div {{ $attributes->merge(['class' => "$size $color rounded-lg flex items-center justify-center text-white font-bold $text select-none"]) }}
        aria-label="{{ $item->name }}">
        {{ $item->initials }}
    </div>
@endif
