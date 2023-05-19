@extends('statamic::layout')

@section('content')
    <breadcrumbs :crumbs='@json($crumbs)'></breadcrumbs>
    <h1>Content Defaults</h1>

    <div class="mt-4">
        @foreach($content_types as $content_type => $content)
            <div class="mb-2">
                <h2>{{ $content_type }}</h2>
            </div>

            <ul class="card p-0 mb-4">
                @foreach($content as $repository)
                    <li class="flex items-center justify-between py-2 px-4 border-b group">
                        <a href="{{ cp_route('aardvark-seo.defaults.edit', ['default' => strtolower($content_type) . '_' . $repository['handle']]) }}" class="flex items-end">
                            <span>{{ $repository['title'] }}</span>
                            <span class="text-xs ml-2">{{ $repository['handle'] }}</span>
                        </a>
                        <strong>{{ $repository['count'] }}</strong>
                    </li>
                @endforeach
            </ul>
        @endforeach
    </div>

@stop
