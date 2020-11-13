<?php
/**
* Plugin Name: Quiz Forger
* Plugin URI: https://iths-bananbyran.github.io/banana-agency-website/
* Description: Create quizes.
* Version: 1.0
* Author: Bananbyrån
*/

//Aktivera pluginet - sätta upp tabeller
function activateQuizForger() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-quiz-forger-activator.php';
    Quiz_Forger_Activator::do_activate();
}

//infoga styles & scripts
function enqueue_related_pages_scripts_and_styles(){
    wp_enqueue_style('qf_style', plugins_url('/css/qf_style.css', __FILE__));
}

//Aktivera render

function render_quiz($id) {
    require_once plugin_dir_path( __FILE__ ) . 'public/class-render-quiz.php';
    Render::quiz($id);
}

//Skapa adminvy för skapa frågor
require_once plugin_dir_path( __FILE__ ) . 'includes/qf_createQuestions.php';
// require_once plugin_dir_path(__FILE__) . 'includes/create-quiz.php';

//Skapa adminvy för skapa frågor

// function display_quiz_dashboard(){
//     global $wpdb;
//     $table_name = $wpdb->prefix . 'quizforgequizes';
//     $retrieve_entries = $wpdb->get_results( "SELECT * FROM $table_name" );

//     if(isset($retrieve_entries)) {
//         foreach($retrieve_entries as $entry) {
//             echo "<p>$entry->title</p>";
//         }
//     } else {
//         echo "<p>No entries found</p>";
//     }
// }

// function quiz_dashboard() {
//     add_menu_page(
//         "Quiz Dashboard", 
//         "Quiz Dashboard", 
//         "manage_options",
//         "Quizforger",
//         "display_quiz_dashboard");
// }

function quiz_shortcode($atts, $content = null) {
    //add attr
    $a = shortcode_atts( array(
        'id' => ''
    ), $atts );
    ob_start();
    render_quiz($a['id']);
    return ob_get_clean();
}

// add_action('admin_menu', 'quiz_dashboard');

//skapa adminvy för skapa quiz, ska kunna hämta skapade frågor

//skapa adminvy för alla befintliga quiz så man kan se dem och deras shortcode där id ingår

//skapa shortcode

//skapa api endpoints

register_activation_hook( __FILE__, 'activateQuizForger' );
add_action('wp_enqueue_scripts','enqueue_related_pages_scripts_and_styles');
add_shortcode('quiz', 'quiz_shortcode');