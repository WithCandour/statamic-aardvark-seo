@extends('layout')
@section('content-class', 'publishing')

@section('content')

    <script>
        Statamic.Publish = {
            contentData: {!! json_encode($data) !!},
            fieldset: {!! json_encode($fieldset) !!}
        };
    </script>

    @if(!empty($aardvarkErrors))
        <div class="aardvark-notices mb-4 pt-3 px-3">
            @foreach($aardvarkErrors as $error)
                <div class="aardvark-notice aardvark-notice--{{ $error['level'] }} px-2 py-1 text-white rounded">
                    {!! $error['message'] !!}
                </div>
            @endforeach
        </div>
    @endif

    <publish
        title="{{ $title }}"
        submit-url="{{ $submitUrl }}"
        content-type="addon"
        :meta-fields="false"
        :is-new="false"
    ></publish>

@endsection
