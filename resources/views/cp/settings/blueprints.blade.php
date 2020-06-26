@extends('statamic::layout')

@section('content')
    <breadcrumbs :crumbs='@json($crumbs)'></breadcrumbs>
    <publish-form
        title="Blueprints"
        action="{{ cp_route('aardvark-seo.blueprints.store') }}"
        :blueprint='@json($blueprint)'
        :meta='@json($meta)'
        :values='@json($values)'
    ></publish-form>
@stop
