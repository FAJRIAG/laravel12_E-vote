@props(['type' => 'info'])

@php
  $styles = [
    'success' => 'bg-green-100 text-green-800 border-green-200',
    'error'   => 'bg-red-100 text-red-800 border-red-200',
    'info'    => 'bg-blue-100 text-blue-800 border-blue-200',
  ];
@endphp

<div {{ $attributes->merge(['class' => 'p-3 rounded border '.$styles[$type]]) }}>
  {{ $slot }}
</div>
