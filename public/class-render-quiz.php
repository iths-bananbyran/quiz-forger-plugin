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

                $right_answer = intval($question->right_answer);
                echo "<div class='qf-card-container'>
                <div class='qf-card'>
                    <div class='qf-question' id=$question->question_id>
                        <p class='qf-p'>$question->question</p>
                    </div>
                    <div class='qf-options'>";
                
                echo '<button class="qf-answers" data="'.(($right_answer === 1) ? "1":"0").'">'.$question->answer_1.'</button>';
                echo '<button class="qf-answers" data="'.(($right_answer === 2) ? "1":"0").'">'.$question->answer_2.'</button>';
                echo '<button class="qf-answers" data="'.(($right_answer === 3) ? "1":"0").'">'.$question->answer_3.'</button>';
                echo '<button class="qf-answers" data="'.(($right_answer === 4) ? "1":"0").'">'.$question->answer_4.'</button>';
                echo "</div>
                        </div>
                            </div>";
            }
        }
    }

}