@extends('layout')
@section('content-class', 'publishing')

@section('content')

    <script>
        Statamic.Publish = {
            contentData: {!! json_encode($data) !!},
            fieldset: {!! json_encode($fieldset) !!},
            locale: '{!! $locale !!}'
        };
    </script>

    <publish
        title="{{ $title }}"
        submit-url="{{ $submitUrl }}"
        content-type="global"
        :meta-fields="false"
        :is-new="false"
        locale="{{ $locale }}"
        locales="{{ json_encode($locales) }}"
    ></publish>

@endsection
