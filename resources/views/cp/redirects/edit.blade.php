@extends('statamic::layout')

@section('content')
    <breadcrumbs :crumbs='@json($crumbs)'></breadcrumbs>
    <aardvark-redirects-publish-form
        title="{{ $title }}"
        method="patch"
        action="{{ cp_route('aardvark-seo.redirects.manual-redirects.update', ['manual_redirect' => $redirect_id]) }}"
        redirect-url={{ cp_route('aardvark-seo.redirects.manual-redirects.index') }}
        :blueprint='@json($blueprint)'
        :meta='@json($meta)'
        :values='@json($values)'
    ></aardvark-redirects-publish-form>
@stop
