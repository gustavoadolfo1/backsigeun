@php
    $pageBackgroundStyle = "
        width: 297mm;
        height: 210mm;
        position: absolute;
        top: 0;
        left: 0;
        z-index: -1;
    ";

    $pageBackgroundImageStyle = "
        width: 297mm;
        height: 210mm;
    ";
@endphp

<div style="{{ $pageBackgroundStyle }}">
    <img src="{{ $image }}" style="{{ $pageBackgroundImageStyle }}}">
</div>
