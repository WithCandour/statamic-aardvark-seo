@extends('statamic::layout')

@section('content')
    <breadcrumbs :crumbs='@json($crumbs)'></breadcrumbs>
    <publish-form
        title="{{ $repo->title() }} Defaults"
        action="{{ cp_route('aardvark-seo.defaults.update', ['default' => $content_type]) }}"
        method="patch"
        :blueprint='@json($blueprint)'
        :meta='@json($meta)'
        :values='@json($values)'
    ></publish-form>
@stop
