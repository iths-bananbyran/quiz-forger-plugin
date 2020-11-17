<?php
/**
* Plugin Name: Quiz Forger
* Plugin URI: https://iths-bananbyran.github.io/banana-agency-website/
* Description: Create quizes.
* Version: 1.0
* Author: Bananbyrån
*/

require_once plugin_dir_path( __FILE__ ) . 'includes/class-admin-quiz-forger.php';
$quiz_forge_admin = new Admin_Quiz_Forger();

function activate_quiz_forger() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-quiz-forger-activator.php';
    Quiz_Forger_Activator::do_activate();
    
}

function enqueue_public_scripts_and_styles(){
    wp_enqueue_style('qf_style', plugins_url('public/css/qf_style.css', __FILE__));
    wp_enqueue_style( 'google_web_fonts', 'https://fonts.googleapis.com/css2?family=Signika:wght@400;700&display=swap' );
}

function load_quiz_script() {
    wp_enqueue_script( 'quizScript', plugins_url('public/scripts/quizScript.js', __FILE__));
}

function render_quiz($id) {
    require_once plugin_dir_path( __FILE__ ) . 'public/class-render-quiz.php';
    Render::quiz($id);
}

function quiz_shortcode($atts, $content = null) {

    $a = shortcode_atts( array(
        'id' => ''
    ), $atts );
    ob_start();
    render_quiz($a['id']);
    return ob_get_clean();
}

register_activation_hook( __FILE__, 'activate_quiz_forger' );
add_action('admin_menu', array($quiz_forge_admin, 'quiz_dashboard_menu'));
add_action('admin_enqueue_scripts', array($quiz_forge_admin, 'qf_admin_style'));
add_action('admin_post_create_quiz', array($quiz_forge_admin,'admin_prefix_create_quiz'));
add_action('admin_post_submit_question', array($quiz_forge_admin,'admin_prefix_submit_question'));
add_action('wp_enqueue_scripts','enqueue_public_scripts_and_styles');
add_action('wp_loaded', 'load_quiz_script');
add_shortcode('quiz', 'quiz_shortcode');