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

//Skapa adminvy för skapa frågor

//skapa adminvy för skapa quiz, ska kunna hämta skapade frågor

//skapa adminvy för alla befintliga quiz så man kan se dem och deras shortcode där id ingår

//skapa shortcode

//skapa api endpoints

register_activation_hook( __FILE__, 'activateQuizForger' );