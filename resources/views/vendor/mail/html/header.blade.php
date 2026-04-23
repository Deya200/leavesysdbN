@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ asset('logo3.png') }}" class="logo" alt="ABC Leave Management System Logo" style="max-width: 150px; height: auto;">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
