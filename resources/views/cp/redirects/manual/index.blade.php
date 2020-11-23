@extends('statamic::layout')

@section('content')

    <div class="flex items-center mb-3">
        <h1 class="flex-1">Manual redirects</h1>
        <div>
            <a href="{{ cp_route('aardvark-seo.redirects.auto.index') }}" class="btn">Auto Redirects</a>
            <a href="{{ cp_route('aardvark-seo.redirects.manual-redirects.create') }}" class="btn-primary">Create Redirect</a>
        </div>
    </div>

    <aardvark-redirects-listing
        :initial-redirects='@json($redirects)'
        :columns='@json($columns)'
    ></aardvark-redirects-listing>
@stop
