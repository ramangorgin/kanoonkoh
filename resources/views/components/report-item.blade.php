@props(['label', 'value'])

<div class="col-md-6 mb-3">
<strong>{{ $label }}:</strong>
<div class="text-muted">{!! nl2br(e($value)) !!}</div>
</div>
