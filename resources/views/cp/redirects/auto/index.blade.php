@extends('statamic::layout')

@section('content')
    <breadcrumbs :crumbs='@json($crumbs)'></breadcrumbs>

    <div class="flex items-center mb-3">
        <h1 class="flex-1">{{ $title }}</h1>
        <div>
            <a href="{{ cp_route('aardvark-seo.redirects.manual-redirects.index') }}" class="btn">{{ __('aardvark-seo::redirects.actions.manual') }}</a>
        </div>
    </div>

    <aardvark-auto-redirects-listing
        :initial-redirects='@json($redirects)'
        :initial-columns='@json($columns)'
        :auto-redirects-enabled="{{ $auto_redirects_enabled ? 'true' : 'false' }}"
        bulk-actions-url='{{ cp_route('aardvark-seo.redirects.auto-redirects.actions') }}'
    ></aardvark-auto-redirects-listing>
@stop
