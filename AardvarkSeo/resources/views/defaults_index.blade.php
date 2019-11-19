@extends('layout')
@section('content-class', 'publishing')

@section('content')

    <div class="w-full px-1 md:px-3">
        <h1>{{ $title }}</h1>
        @foreach($contentTypes as $type => $content)

        <h2>{{ $content['title'] }}</h2>

        <div class="card flush">
            <div class="dossier-table-wrapper">
                    <table class="dossier">
                        <tbody>
                            @foreach($content['items'] as $item)
                                <tr>
                                    <td class="cell-title first-cell">
                                        <div class="stat">
                                            <span class="icon icon-documents"></span>
                                            {{ $item['count'] }}
                                        </div>
                                        <a href="{{ route('aardvark-seo.defaults') }}/{{ $type }}/{{ $item['slug'] }}">{{ $item['title'] }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        </div>

        @endforeach
    </div>

@endsection
