<?php
/**
* Plugin Name: Quiz Forger
* Plugin URI: https://iths-bananbyran.github.io/banana-agency-website/
* Description: Create quizes.
* Version: 1.0
* Author: Bananbyrån
*/

function activateQuizForger() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-quiz-forger-activator.php';
}

register_activation_hook( __FILE__, 'activateQuizForger' );