<a href="{{route($module->resource . '.show', [$row->serial ?: $row->id])}}" class="kt-font-boldest kt-shape-font-color-4 d-inline-block direction-ltr">{{ $row->serialText ?: $row->serial ?: $row->id }}</a>
