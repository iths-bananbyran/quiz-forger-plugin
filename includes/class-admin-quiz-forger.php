<?php

class Admin_Quiz_Forger {

    public static function qf_admin_style() {

        wp_enqueue_style('superform_styles', plugins_url('/qf_styles/qf_admin_style.css', __FILE__));

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
        echo "<input type='hidden' name='eventid' value=$event>";
        echo '<label>Name your quiz: </label><input type:"text" name="quizName">';
        echo '<label>Describe your quiz: </label><textarea rows="5" cols="75" name="description"></textarea>';
        echo '<input type="submit" value="Submit" class="qf-btn-admin">';
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

    public static function add_questions() {

        echo '<h2 class="qf-heading">Create Questions</h2>';
    }

    public static function quiz_dashboard_menu() {

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