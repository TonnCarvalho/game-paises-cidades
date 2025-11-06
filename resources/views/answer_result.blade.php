@php
if($choice_answer === $corrent_answer) {
    $textColor = 'text-success';
} else {
    $textColor = 'text-danger';
}
@endphp
@extends('layout.layout')
@section('titlePage', 'Resultado da pergunta')
@section('content')
    <div class="container">

        <div class="border border-primary rounded-5 p-3 text-center fs-3 mb-3">
            Pergunta: <span class="text-info fw-bolder">{{ $current_question + 1 }} / {{ $totalQuestion }}</span>
        </div>

        <div class="text-center fs-3 mb-3">
            Qual é a capital de <strong class="text-info">{{ $country }}</strong>
        </div>

        <div class="text-center fs-3 mb-3">
            Resposta correta: <span class="text-info">{{ $corrent_answer }}</span>
        </div>

        <div class="text-center fs-3 mb-3">
            A sua resposta: <span class="{{ $textColor }}">{{ $choice_answer }}</span>
        </div>

    </div>

    <!-- cancel game -->
    <div class="text-center mt-5">
        <a href="{{ route('nextQuestion') }}" class="btn btn-primary mt-3 px-5">AVANÇAR</a>
    </div>
@endsection
