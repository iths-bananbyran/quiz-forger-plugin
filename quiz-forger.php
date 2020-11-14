<?php
/**
* Plugin Name: Quiz Forger
* Plugin URI: https://iths-bananbyran.github.io/banana-agency-website/
* Description: Create quizes.
* Version: 1.0
* Author: Bananbyrån
*/

//Aktivera pluginet - sätta upp tabeller
require_once plugin_dir_path( __FILE__ ) . 'includes/class-admin-quiz-forger.php';
$quiz_forge_admin = new Admin_Quiz_Forger();

function activateQuizForger() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-quiz-forger-activator.php';
    Quiz_Forger_Activator::do_activate();
    
}

register_activation_hook( __FILE__, 'activateQuizForger' );
add_action('admin_menu', array($quiz_forge_admin, 'quiz_dashboard_menu'));
add_action('admin_enqueue_scripts', array($quiz_forge_admin, 'qf_admin_style'));
add_action('admin_post_create_quiz', array($quiz_forge_admin,'admin_prefix_create_quiz'));