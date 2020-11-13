<?php

class Render {

    private static function get_questions($id) {

        global $wpdb;
        $table_name = $wpdb->prefix . 'quizforgequestions';
        $questions = $wpdb->get_results( "SELECT * FROM $table_name WHERE quiz_id=$id" );

        return $questions;

    }

    public static function quiz($id) {

        $questions = self::get_questions($id);

        if ( !empty($questions) ) {

            foreach ($questions as $question) {
                echo "<div class='card-container'>
                <div class='card'>
                    <div class='question' id=$question->question_id>
                        <p>$question->title</p>
                    </div>
                    <div class='options'>
                        <button class='answers'>$question->answer_1</button>
                        <button class='answers'>$question->answer_2</button>
                        <button class='answers'>$question->answer_3</button>
                        <button class='answers'>$question->answer_4</button>
                    </div>
                    <p>Right answer: $question->right_answer</p>
                </div>
            </div>";
            }
        }
    }

}