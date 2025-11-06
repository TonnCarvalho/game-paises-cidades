<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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

        //colocar jogo na sessão
        session()->put([
            'quiz' => $quiz,
            'total_questions' => $total_questoes,
            'current_question' => 1,
            'correct_answers' => 0,
            'wrong_answers' => 0
        ]);

        return redirect()->route('game');
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

    public function game(): View
    {
        $quiz = session('quiz');
        $total_questions = session('total_questions');
        $current_question = session('current_question') - 1;

        //prepara as pessoas para mostrar na view
        $answers = $quiz[$current_question]['wrong_answers'];
        $answers[] = $quiz[$current_question]['corrent_answer'];

        shuffle($answers);
        return view('game')->with([
            'country' => $quiz[$current_question]['country'],
            'totalQuestions' => $total_questions,
            'currentQuestions' => $current_question,
            'answers' => $answers

        ]);
    }

    /**
     *desencripta resposta, verifica a resposta, atualiza dados da session, mostra dados da pergunta/resposta
     * @param [string] $enc_answer
     * @return void
     */
    public function answer($enc_answer)
    {
        //tratamento para ver se a resposta é encriptada.
        try {
            //realoiza a desencriptação
            $answer = Crypt::decryptString($enc_answer);
        } catch (\Exception $e) {
            return redirect()->route('game');
        }

        //game loop
        $quiz = session('quiz'); //perguntas
        $current_question = session('current_question') - 1; //número da pergunta atual
        $corrent_answer = $quiz[$current_question]['corrent_answer']; //pergunta atual
        $correct_answers = session('correct_answers'); //número de totais de respostas corretas
        $wrong_answers = session('wrong_answers'); //número de totais de respostas errada

        //verifica se a resposta é correta.
        if ($answer === $corrent_answer) {
            $correct_answers++; //acrescenta +1 a número de resposta correta
            $quiz[$corrent_answer]['correct'] = true; //marca a pergunta como correta
        } else {
            $wrong_answers++; //acrescenta +1 a número de resposta errada
            $quiz[$corrent_answer]['correct'] = false;  //marca a resposta como errada
        }

        //atualiza a sessão
        session()->put([
            'quiz' => $quiz,
            'correct_answers' => $correct_answers,
            'wrong_answers' => $wrong_answers
        ]);

        //dados enviado para a view
        $data = [
            'country' => $quiz[$current_question]['country'],
            'corrent_answer' => $corrent_answer,
            'choice_answer' => $answer,
            'current_question' => $current_question,
            'totalQuestion' => session('total_questions')
        ];

        return view('answer_result')->with($data);
    }

    /**
     * 
     */
    public function nextQuestion()
    {
        $current_question = session('current_question'); //número da pergunta atual
        $total_questions = session('total_questions'); //número total de perguntas

        if ($current_question <  $total_questions) {
            $current_question++; //
            session()->put('current_question', $current_question);

            return redirect()->route('game');
        } else {
            return redirect()->route('show_results');
        }
    }

    public function showResults()
    {
        echo 'showResults';
        dd(session()->all());
    }
}
