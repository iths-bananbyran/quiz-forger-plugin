<?php


function question_menu_markup() {
?>
  <div class="wrap">
    <h1>Quiz Forge QuestionMaker</h1>

    <div class="qf-form-wrapper">
      <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="qf-questionmaker-form" >
      <input type="hidden" name="action" value="submit_question">
      <!-- Add a QUIZ that you want to add questions to -->
        
        <!-- Get all the quizzes from db and throw them in the select input -->
        <label for="qf-quiz-select-input">Select your quiz</label>
        <select id="qf-quiz-select-input" form="qf-questionmaker-form" name="quiz-list" class="qf-text-input">
          <option selected disabled>Select a Quiz</option>
          <?php insertQuiz() ?>
        </select>

      <!-- Write the question -->
        <div class="qf-input-wrapper">
          <label for="qf-question-input">Write your question here</label>
          <input type="text" id="qf-question-input" class="qf-text-input" name="question" >
        </div>

        <div class="qf-input-wrapper">
          <label for="qf-question-input">Answer 1: </label>
          <input type="text" id="qf-answer1-input" class="qf-text-input" name="answer1" required>
          <input type="radio" name="right_answer" id="answer1"  checked  value="1">
          <label for="answer1">Right answer?</label>
        </div>

        <div class="qf-input-wrapper">
          <label for="qf-question-input">Answer 2: </label>
          <input type="text" id="qf-answer2-input" class="qf-text-input" name="answer2" required>
          <input type="radio" name="right_answer" id="answer2" value="2">
          <label for="answer2">Right answer?</label>
        </div>

        <div class="qf-input-wrapper">
          <label for="qf-question-input">Answer 3: </label>
          <input type="text" id="qf-answer3-input" class="qf-text-input" name="answer3" required>
          <input type="radio" name="right_answer" id="answer3" value="3">
          <label for="answer3">Right answer?</label>
        </div>

        <div class="qf-input-wrapper">
          <label for="qf-question-input">Answer 4: </label>
          <input type="text" id="qf-answer4-input" class="qf-text-input" name="answer4" required>
          <input type="radio" name="right_answer" id="answer4" value="4">
          <label for="answer4">Right answer?</label>
        </div>
        <label for="qf-explanation">Add explanation to the answer? (Optional)</label>
        <textarea name="qf-explanation" id="qf-explanation" style="overflow:auto;resize:none" cols="30" rows="10"></textarea>
        
        <input type="submit" value="Add question" name="submit">
      </form>
    </div>
  </div>
<?php
};

  // $question_id = $quiz_id = $question = $answer1 = $answer2 = $answer3 = $answer4 = $right_answer = $explanation =  "";


add_action( "admin_post_submit_question", "admin_prefix_submit_question" );

function admin_prefix_submit_question() {

  if (isset($_POST["submit"])) {
    global $wpdb;
    $quiz_table = $wpdb->prefix . 'quizforgequizes';
    $question_table = $wpdb->prefix . 'quizforgequestions';

    $wpdb->insert( $question_table, array(
        'question_id' => "0",
        'quiz_id' => $_POST['quiz-list'],
        'question' => $_POST["question"],
        'question_image' => isset($_POST["question_image"]) ? $_POST["question_image"] : "",
        'answer1' => $_POST["answer1"],
        'answer2' => $_POST["answer2"],
        'answer3' => $_POST["answer3"],
        'answer4' => $_POST["answer4"],
        'right_answer' => $_POST["right_answer"],
        'explanation' => $explanation
      )
    );
  } else {
    echo "Error submitting question";
  }

  wp_redirect(admin_url('/admin.php?page=question_maker'));

  exit;
 
}

// function add_question_to_quiz() {
//   global $wpdb;
//   $db_table = $wpdb->prefix . 'quizforgequizes';
//   $quiz_id = $_POST["select value"] // Pseudo kod
// }

function insertQuiz() {
  global $wpdb;
  $quizes_table = $wpdb->prefix . 'quizforgequizes';
  $quiz_query = $wpdb->get_results( "SELECT * FROM $quizes_table" );
  foreach ($quiz_query as $quiz_data) { echo "<option value='" . $quiz_data->quiz_id . "'>" . $quiz_data->title . "</option>"; }
}

add_action("admin_menu", "createQuestion_menu");

function createQuestion_menu() {
  add_menu_page(
    "QuizForge QuestionMaker", 
    "QuizForge QuestionMaker", 
    "manage_options",
    "question_maker",
    "question_menu_markup");
}
?>