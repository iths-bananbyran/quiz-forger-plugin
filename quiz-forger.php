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
    wp_enqueue_script('quizScript', plugins_url('public/scripts/quizScript.js', __FILE__), array('jquery'), null, true);
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

// ENDPOINTS

// Get any number of posts/quizzes 
function get_num_of_posts($num_of_posts) {
    if ($num_of_posts['numberposts'] == "all" ) {
        $integer = -1;
    } else {
        $integer = intval($num_of_posts['numberposts']);
    };
    
    $args = array( 
        'post_content',
        'post_status' => 'publish',
        'post_title',
        'numberposts' => $integer,
        'post_type' => 'post' );

    $posts_arr = get_posts($args);

    $posts_info = array();

    forEach( $posts_arr as $post ){
        $thumb_url = get_the_post_thumbnail_url($post -> ID);
        $category = get_the_category($post -> ID);
        $result = array();
        $result["quiz_id"] = filter_quiz_id( $post -> post_content );
        $result["quiz_title"] = $post -> post_title;
        $result["quiz_thumbnail"] = $thumb_url;
        $result["quiz_category"] = $category;
        array_push( $posts_info, $result );
    }

    return $posts_info;
}

function filter_quiz_id($string) {
    preg_match("/=\d*]/", $string, $matches);
    $quiz_id = substr($matches[0], 1, -1);
    return $quiz_id;
}

add_action( 'rest_api_init', function() {
    register_rest_route( 'quizforge/v1', '/quizposts/(?P<numberposts>\S+)', array(
      'methods' => 'GET',
      'callback' => 'get_num_of_posts',
    ) );
} );

// Get specific quiz based on ID

function get_quiz($id) {
    require_once plugin_dir_path( __FILE__ ) . 'public/class-render-quiz.php';
    return Render::quiz_object($id['id']);
}

add_action( 'rest_api_init', function() {
    register_rest_route( 'quizforge/v1', '/quiz/(?P<id>\d+)', array(
      'methods' => 'GET',
      'callback' => 'get_quiz',
    ) );
} );

register_activation_hook( __FILE__, 'activate_quiz_forger' );
add_action('admin_menu', array($quiz_forge_admin, 'quiz_dashboard_menu'));
add_action('admin_enqueue_scripts', array($quiz_forge_admin, 'qf_admin_style'));
add_action('admin_post_create_quiz', array($quiz_forge_admin,'admin_prefix_create_quiz'));
add_action('admin_post_submit_question', array($quiz_forge_admin,'admin_prefix_submit_question'));
add_action('wp_enqueue_scripts','enqueue_public_scripts_and_styles');
add_shortcode('quiz', 'quiz_shortcode');