<?php

class Admin_Quiz_Forger {

    public static function qf_admin_style() {

        if ( ! did_action( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        }

        wp_enqueue_style('superform_styles', plugins_url('/qf_styles/qf_admin_style.css', __FILE__));
        wp_enqueue_script('upload-image', plugins_url('/qf_admin_scripts/upload-image.js',__FILE__));


    }

    public static function quiz_dashboard() {
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'quizforgequizes';
        $retrieve_entries = $wpdb->get_results( "SELECT * FROM $table_name" );
        
        if(isset($retrieve_entries)) {
            
            echo "<h2 class='qf-heading'> These are your quizes.</h2>
                <p>Use the shortcode to insert them on a page.</p>";
            echo 
            "<table class='qf-table'>
                <tr>
                <th class='qf-th qf-td'>Quiz name</td>
                <th class='qf-th qf-td'>Description</td>
                <th class='qf-th qf-td'>Shortcode</td>
                </tr>
            ";
    
            foreach($retrieve_entries as $entry) {
    
                $title = $entry->title;
                $description = $entry->description;
                
                echo 
                "
                <tr>
                <td class='qf-td'>$title</td>
                <td class='qf-td'>$description</td>
                <td class='qf-td'>[quiz id=$entry->quiz_id]</td>
                </tr>
                ";
    
            }
            echo "</table>";
        } else {
            echo "<h2 class='qf-heading'>You haven't created any quizes yet! Start by creating a quiz, add the questions later.</h2>";
        }
    }

    public static function new_quiz() {

        $event = 'qf-new';

        echo '<div class="qf-wrap"><h2 class="qf-heading">Create Quiz</h2><p class="qf-preamble">This is where you will be able to create your own quiz. Are you excited?</p>';
        echo "<form action='admin-post.php' method='post' class='qf-form'>";
        wp_nonce_field( 'create_quiz_' . $event );
        echo '<input type="hidden" name="action" value="create_quiz">';
        echo "<input type='hidden' name='eventid' value='$event'>";
        echo '<label>Name your quiz: </label><input type:"text" name="quizName">';
        echo '<label>Describe your quiz: </label><textarea rows="5" cols="75" name="description"></textarea>';
        echo '<div class="qf-btn-wrapper"><input type="submit" value="Submit" class="qf-btn-admin"></div>';
        echo '</form> </div>';

    }

    public static function admin_prefix_create_quiz(){
        global $wpdb;
        $quiz_table = $wpdb->prefix . 'quizforgequizes';

        if (!empty($_POST['quizName']) && !empty($_POST['description'])){
            $quiz_name = $_POST['quizName'];
            $description = $_POST['description'];
            $event_id = $_POST['eventid'];
            check_admin_referer( 'create_quiz_' . $event_id );      

            $wpdb->insert($quiz_table, array(
                'title' => $quiz_name,
                'description' => $description,
            ));
            
        } else {
            echo "Error submitting question";
        }
        wp_redirect(admin_url('/admin.php?page=new-quiz'));

        exit;
    }

    public static function admin_prefix_submit_question() {
  
        global $wpdb;
        $question_table = $wpdb->prefix . 'quizforgequestions';
      
        if (isset($_POST["submit"])) {
          $quiz_id = $_POST['quiz-list'];
          $img_url = $_POST['question-img'];
          $answer1 = $_POST['answer1'];
          $answer2 = $_POST['answer2'];
          $answer3 = $_POST['answer3'];
          $answer4 = $_POST['answer4'];
          $question = $_POST['question'];
          $right_answer = $_POST['right_answer'];
          $explanation = $_POST['qf-explanation'];

          if (!strlen($img_url)>0){
              $img_url = null;
          }
      
          $wpdb->insert( $question_table, array(
              'quiz_id' => $quiz_id,
              'question' => $question,
              'question_image' => $img_url,
              'answer_1' => $answer1,
              'answer_2' => $answer2,
              'answer_3' => $answer3,
              'answer_4' => $answer4,
              'right_answer' => $right_answer,
              'explanation' => $explanation
            )
          );
        } else {
          echo "Error submitting question";
        }
        wp_redirect(admin_url('/admin.php?page=add-questions'));
        exit;
      }

    public static function add_questions() {

        echo '<div class="qf-wrap">
        <h2 class="qf-heading">Create the questions for your quiz!</h2>
    
        <div class="qf-form-wrapper">
          <form action="admin-post.php" method="post" id="qf-questionmaker-form" class="qf-form">
          <input type="hidden" name="action" value="submit_question">
            
            <label for="qf-quiz-select-input">Select your quiz</label>';

        echo '<select id="qf-quiz-select-input" form="qf-questionmaker-form" name="quiz-list" class="qf-text-input">
              <option selected disabled>Select a Quiz</option>';
        
        self::render_quiz_select();

        echo '</select>';
         
        echo '<a href="#" class="upload-question-img">Upload image</a>
                <input type="hidden" id="img-value" name="question-img" value="">';
        
        echo '<div class="qf-input-wrapper">
                <label for="qf-question-input">Write your question here</label>
                <input type="text" id="qf-question-input" class="qf-text-input" name="question" >
            </div>

            <div class="qf-input-wrapper">
                <label for="qf-question-input">Answer 1: </label>
                <input type="text" id="qf-answer1-input" class="qf-text-input" name="answer1" required>
                <input type="radio" name="right_answer" id="answer1"  checked  value="1">
                <label for="answer1">Correct</label>
            </div>

            <div class="qf-input-wrapper">
                <label for="qf-question-input">Answer 2: </label>
                <input type="text" id="qf-answer2-input" class="qf-text-input" name="answer2" required>
                <input type="radio" name="right_answer" id="answer2" value="2">
                <label for="answer2">Correct</label>
            </div>

            <div class="qf-input-wrapper">
                <label for="qf-question-input">Answer 3: </label>
                <input type="text" id="qf-answer3-input" class="qf-text-input" name="answer3" required>
                <input type="radio" name="right_answer" id="answer3" value="3">
                <label for="answer3">Correct</label>
            </div>

            <div class="qf-input-wrapper">
                <label for="qf-question-input">Answer 4: </label>
                <input type="text" id="qf-answer4-input" class="qf-text-input" name="answer4" required>
                <input type="radio" name="right_answer" id="answer4" value="4">
                <label for="answer4">Correct</label>
            </div>
            <label for="qf-explanation">Add explanation to the answer? (Optional)</label>
            <textarea name="qf-explanation" id="qf-explanation" style="overflow:auto;resize:none" cols="30" rows="10"></textarea>
            
            <div class="qf-btn-wrapper">
                <input type="submit" value="Add question" name="submit" class="qf-btn-admin">
            </div>
                </form>
                    </div>
                        </div>';
    }

    public static function render_quiz_select(){

        global $wpdb;
        $quizes_table = $wpdb->prefix . 'quizforgequizes';
        $quiz_query = $wpdb->get_results( "SELECT * FROM $quizes_table" );
        foreach ($quiz_query as $quiz_data) { echo "<option value='" . $quiz_data->quiz_id . "'>" . $quiz_data->title . "</option>"; }

    }

    public function quiz_dashboard_menu() {

        add_menu_page('Quiz Forger', 
        'Quiz Forger', 
        'manage_options',
        'Quizforger',
        array($this, 'quiz_dashboard'),
        'dashicons-slides');

        add_submenu_page(
            'Quizforger',
            'New Quiz',
            'Create Quiz',
            'manage_options',
            'new-quiz',
            array($this, 'new_quiz'));
        
        add_submenu_page(
            'Quizforger',
            'Add questions',
            'Add questions',
            'manage_options',
            'add-questions',
            array($this, 'add_questions'));
    }
}