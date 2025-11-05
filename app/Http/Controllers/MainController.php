<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class MainController extends Controller
{
    private $app_data;

    public function __construct()
    {
        //carrega o arquivo app_data.php da pasta app
        $this->app_data =  require(app_path('app_data.php'));
    }
    /**
     * Retorna view para iniciar o jogo
     * @return View
     */
    public function startGame(): View
    {
        return view('home');
    }

    public function prepareGame(Request $request)
    {
        //validação do input
        $valitador = Validator::make($request->all(), [
            'total_questions' => 'required|integer|min:3|max:30'
        ], [
            'required' => 'Campo obrigatório',
            'min' => 'Minimo de :min de questões',
            'max' => 'Maximo de :max de questões',
            'integer' => 'O número tem que ser um valor inteiro'

        ]);

        //se error, retorne a mensagem de error
        if ($valitador->fails()) {
            return back()
                ->withErrors($valitador)
                ->withInput();
        }

        //total de questoes
        $total_questoes = intval($request->input('total_questions'));

        //prepara as quiz
        $quiz = $this->prepareQuiz($total_questoes);
        dd($quiz);
    }

    private function prepareQuiz($total_questoes)
    {
        $questions = [];
        $total_countries = count($this->app_data);

        //cria countrys com index unico
        $indexes = range(0, $total_countries - 1);

        //misturar os valores que tem no array $indexes
        shuffle($indexes);
        //busca do zero até o $total_questoes
        $indexes = array_slice($indexes, 0, $total_questoes);

        $question_number = 1;
        //cria array de questoes
        foreach ($indexes as $index) {
            $question['question_number'] = $question_number++; //número da pergunta
            $question['country'] = $this->app_data[$index]['country']; //country da pergunta
            $question['corrent_answer'] = $this->app_data[$index]['capital']; //resposta correta

            //respostas erradas
            $other_capitals = array_column($this->app_data, 'capital');

            //remove a capital que é a resposta correta
            $other_capitals = array_diff($other_capitals, [$question['corrent_answer']]);

            //mistura as respostas das capitais.
            //shuffle() mistura os index do array e não permitir se repetir os index.
            shuffle($other_capitals);

            //coloca 3 respostas erradas
            //array_slice busca as capitais, do index 0 a 3
            $question['wrong_answers'] = array_slice($other_capitals, 0, 3);

            $question['correct'] = null;

            $questions[] = $question;
        }

        return $questions;
    }
}
