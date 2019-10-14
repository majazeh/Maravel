@if (count($breadcrumbs))
    <div class="kt-subheader__breadcrumbs">
        @foreach ($breadcrumbs as $breadcrumb)

            @if ($breadcrumb->url && $loop->first)
                <a href="{{ $breadcrumb->url }}" class="kt-subheader__breadcrumbs-link"><i class="flaticon2-shelter"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>

            @elseif ($breadcrumb->url && !$loop->last)
                <a href="{{ $breadcrumb->url }}" class="kt-subheader__breadcrumbs-link">{{ $breadcrumb->title }}</a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
            @else
                <a class="kt-subheader__breadcrumbs-link">{{ $breadcrumb->title }}</a>
            @endif

        @endforeach
    </div>
@endif
