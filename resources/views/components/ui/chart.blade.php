@props([
    'type' => 'line',
    'data' => [],
    'options' => [],
    'width' => null,
    'height' => null,
])

@php
$chartId = 'chart-' . Str::random(8);
@endphp

<div {{ $attributes }}>
    <canvas
        id="{{ $chartId }}"
        @if($width) width="{{ $width }}" @endif
        @if($height) height="{{ $height }}" @endif
    ></canvas>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('{{ $chartId }}').getContext('2d');
    new Chart(ctx, {
        type: @js($type),
        data: @js($data),
        options: @js($options)
    });
});
</script>
@endpush