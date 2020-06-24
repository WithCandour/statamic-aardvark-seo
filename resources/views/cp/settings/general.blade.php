@extends('statamic::layout')

@section('content')
    <breadcrumbs :crumbs='@json($crumbs)'></breadcrumbs>
    <publish-form
        title="General SEO Settings"
        action="{{ cp_route('aardvark-seo.general.store') }}"
        :blueprint='@json($blueprint)'
        :meta='@json($meta)'
        :values='@json($values)'
    ></publish-form>
@stop
