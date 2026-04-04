@extends('layouts.printpdf')

@section('title', 'Print Preview - Câu hỏi')

@section('content')

    <div class="text-justify mb-4">
        <span class="font-bold">Câu 1. </span>
        {!! $question->stem !!}
    </div>


    <div class="text-justify mb-4">
        <span class="font-bold">Hướng dẫn giải: </span>
        @if($question->explanation && !empty($question->explanation->content))
            {!! $question->explanation->content !!}
        @else
            <span style="font-style: italic;">Chưa có lời giải.</span>
        @endif
    </div>

@endsection