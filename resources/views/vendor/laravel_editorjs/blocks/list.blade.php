@php
$tag = 'ul';
if(isset($data['type']) && 'ordered' === $data['type']){
$tag = 'ol';
}
@endphp

<{{ $tag }}>
    @foreach($data['items'] as $item)
    <li>{{ $item }}</li>
    @endforeach
</{{ $tag }}>
