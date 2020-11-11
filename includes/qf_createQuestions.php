<?php


function question_menu_markup() {
?>
  <div class="wrap">
    <h1>Quiz Forge QuestionMaker</h1>

    <div class="qf-form-wrapper">
      <form action="admin-post.php" method="post" id="qf-questionmaker-form" >
      <!-- Add a QUIZ that you want to add questions to -->
        <label for="qf-quiz-select-input">Select your quiz</label>
        <select id="qf-quiz-select-input" form="qf-questionmaker-form" name="qf-quizzes" class="qf-text-input">
          <option selected>Select a Quiz</option>
        </select>
        <!-- Get all the quizzes from db and throw them in the select input -->
        <?php
          global $wpdb;
          $quizes_table = $wpdb->prefix . 'quizforgequizes';
          $quiz_query = $wpdb->get_results(
            "
              SELECT ID, NAME
              FROM $quizes_table
            "
          );
          foreach ($quiz_query as $quiz_info) {
            
            echo "<option value='" . $quiz_info->id . "'>" . $quiz_info->title . "</option>";
            
          }
        ?>
      <!-- Write the question -->
        <div class="qf-input-wrapper">
          <label for="qf-question-input">Write your question here</label>
          <input type="text" id="qf-question-input" class="qf-text-input" >
        </div>

      <!-- From th start there's always four answer options. 
      Can be changed to a more dynamic solution later -->
        <div class="qf-input-wrapper">
          <label for="qf-question-input">Answer 1: </label>
          <input type="text" id="qf-answer1-input" class="qf-text-input" required>
          <input type="radio" name="answer" id="answer1" value="1">
          <label for="answer1">Right answer?</label>
        </div>

        <div class="qf-input-wrapper">
          <label for="qf-question-input">Answer 2: </label>
          <input type="text" id="qf-answer2-input" class="qf-text-input" required>
          <input type="radio" name="answer" id="answer2" value="2">
          <label for="answer2">Right answer?</label>
        </div>

        <div class="qf-input-wrapper">
          <label for="qf-question-input">Answer 3: </label>
          <input type="text" id="qf-answer3-input" class="qf-text-input" required>
          <input type="radio" name="answer" id="answer3" value="3">
          <label for="answer3">Right answer?</label>
        </div>

        <div class="qf-input-wrapper">
          <label for="qf-question-input">Answer 4: </label>
          <input type="text" id="qf-answer4-input" class="qf-text-input" required>
          <input type="radio" name="answer" id="answer4" value="4">
          <label for="answer4">Right answer?</label>
        </div>
        <label for="qf-explanation">Add explanation to the answer? (Optional)</label>
        <textarea name="qf-explanation" id="qf-explanation" style="overflow:auto;resize:none" cols="30" rows="10"></textarea>

      </form>
    </div>
  </div>
<?php
}

// Lägg till i admin menyn (alla menyer borde finnas som submenyer under en huvudmeny QuizForge)
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