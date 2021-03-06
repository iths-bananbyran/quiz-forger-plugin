<?php

class Render {
    
    private static function get_questions($id) {
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'quizforgequestions';
        $questions = $wpdb->get_results( "SELECT * FROM $table_name WHERE quiz_id=$id" );
        return $questions;
        
    }
    
    private static function get_quiz_title($id) {
        
        global $wpdb;
        $quiz_table_name = $wpdb->prefix . 'quizforgequizes';
        $quiz_info = $wpdb->get_results("SELECT * FROM $quiz_table_name WHERE quiz_id=$id");
        return $quiz_info;
        
    }

    public function quiz_object($id) {
        $questions = self::get_questions($id);
        $quiz_title = self::get_quiz_title($id);
        $object = array($quiz_title, $questions);
        return $object;
    }
    
    public static function quiz($id) {
        
        $questions = self::get_questions($id);
        $quiz_info = self::get_quiz_title($id);
        if ( !empty($questions) ) {
            
            foreach ($questions as $question) {
                
                require_once plugin_dir_path(__DIR__) . '/functions/unescape_str.php';
                
                $right_answer = intval($question->right_answer);
                echo "<div class='qf-quiz-wrapper'>";
                echo "<div class='qf-card-container qf-hidden'>";
                    echo "<div class='qf-status-wrapper'>
                                <span class='qf-quiz-title'>". unescape_str($quiz_info[0]->title)."</span>
                                <span class='qf-num-of-questions'></span>
                          </div>";
                        echo "<div class='qf-card'>";
                            if($question->question_image != null) {
                                echo "<div class='qf-question-image'>
                                <img src='$question->question_image'>
                                </div>";
                            }
                            echo "<div class='qf-question' id='$question->question_id'>";
                                echo "<p class='qf-p'>".unescape_str($question->question)."</p>";
                            echo "</div>";
                            echo "<div class='qf-options'>";
                    
                                echo '<div class="qf-answers" data-id="'.(($right_answer === 1) ? "1":"0").'">
                                <span class="qf-answer-text">'.unescape_str($question->answer_1).'</span></div>';
                                echo '<div class="qf-answers" data-id="'.(($right_answer === 2) ? "1":"0").'">
                                <span class="qf-answer-text">'.unescape_str($question->answer_2).'</span></div>';
                                echo '<div class="qf-answers" data-id="'.(($right_answer === 3) ? "1":"0").'">
                                <span class="qf-answer-text">'.unescape_str($question->answer_3).'</span></div>';
                                echo '<div class="qf-answers" data-id="'.(($right_answer === 4) ? "1":"0").'">
                                <span class="qf-answer-text">'.unescape_str($question->answer_4).'</span></div>';
                            echo "</div>";
                            
                            if($question->explanation) {
                            echo "<div class='qf-explanation-wrapper qf-hidden'>
                            <span class='qf-explanation-text'>".unescape_str($question->explanation)."</span>
                            </div>";}
                        echo "</div>";
                        echo "<footer class='qf-card-footer'><button class='qf-next-btn qf-hidden'>Nästa fråga</button></footer>";
                echo "</div>";
                echo "</div>";
            }
        }
    }
}