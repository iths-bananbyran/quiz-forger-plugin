<?php

class Quiz_Forger_Activator {
    
    private static function activate() {

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        global $wpdb;

        $questions_table = $wpdb->prefix . 'quizforgequestions';
        $quizes_table = $wpdb->prefix . 'quizforgequizes';
        $quiz_categories = $wpdb->prefix . 'quizforgecategories';

        $charset_collate = $wpdb->get_charset_collate();

        //skapa tabell för kategorier
        $sql = "CREATE TABLE `".$quiz_categories."` (
            `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` VARCHAR(256) NOT NULL,
            `description` TEXT NOT NULL,
            `published` TINYINT(1) UNSIGNED NOT NULL,
            PRIMARY KEY (`id`)
        )$charset_collate;";

        dbDelta( $sql );

        //skapa tabell för quizes
        $sql = "CREATE TABLE `".$quizes_table."` (
            `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` VARCHAR(256) NOT NULL,
            `description` TEXT NOT NULL,
            `quiz_category_id` INT(11) UNSIGNED NOT NULL,
            `question_ids` TEXT NOT NULL,
            PRIMARY KEY (`id`)
        )$charset_collate;";

        dbDelta( $sql );

        //skapa tabell för frågorna

        $sql = "CREATE TABLE `".$questions_table."` (
            `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
            `question` TEXT NOT NULL,
            `question_image` TEXT NULL DEFAULT NULL,
            `wrong_answer_1` TEXT DEFAULT NULL,
            `wrong_answer_2` TEXT DEFAULT NULL,
            `wrong_answer_3` TEXT DEFAULT NULL,
            `right_answer` TEXT DEFAULT NULL,
            `explanation` TEXT DEFAULT NULL,
            PRIMARY KEY (`id`)
        )$charset_collate;";
        
        dbDelta( $sql );
    }

    public static function do_activate() {
        self::activate();
    }
}