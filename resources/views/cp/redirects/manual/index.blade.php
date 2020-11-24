@extends('statamic::layout')

@section('content')

    <div class="flex items-center mb-3">
        <h1 class="flex-1">{{ $title }}</h1>
        <div>
            <a href="{{ cp_route('aardvark-seo.redirects.auto.index') }}" class="btn">{{ __('aardvark-seo::redirects.actions.auto') }}</a>
            <a href="{{ cp_route('aardvark-seo.redirects.manual-redirects.create') }}" class="btn-primary">{{ __('aardvark-seo::redirects.actions.create') }}</a>
        </div>
    </div>

    <aardvark-redirects-listing
        :initial-redirects='@json($redirects)'
        :initial-columns='@json($columns)'
        create-url='{{ cp_route('aardvark-seo.redirects.manual-redirects.create') }}'
        bulk-actions-url='{{ cp_route('aardvark-seo.redirects.manual-redirects.actions') }}'
    ></aardvark-redirects-listing>
@stop
