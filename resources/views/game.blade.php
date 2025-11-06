@extends('layout.layout')
@section('pageTitle', '')
@section('content')
    <div class="container">

        <div class="border border-primary rounded-5 p-3 text-center fs-3 mb-3">
            Pergunta: <span class="text-info fw-bolder"> {{ $currentQuestions + 1 }} / {{ $totalQuestions }}</span>
        </div>

        <div class="text-center fs-3 mb-3">
            Qual é a capital de <strong class="text-info">{{ $country }}</strong>
        </div>

        <div class="row">

            @foreach ($answers as $answer)
                <div class="col-6 text-center">
                    <p class="response-option">
                        {{ $answer }}
                    </p>
                </div>
            @endforeach
        </div>

    </div>

    <!-- cancel game -->
    <div class="text-center mt-5">
        <a href="#" class="btn btn-outline-danger mt-3 px-5">CANCELAR JOGO</a>
    </div>

@endsection
