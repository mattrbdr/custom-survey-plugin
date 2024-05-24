<?php
// File: includes/surveys.php

function csp_add_survey_meta_boxes() {
    add_meta_box(
        'survey_data',
        __('Survey Data', 'custom-survey-plugin'),
        'csp_render_survey_data_meta_box',
        'survey',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'csp_add_survey_meta_boxes');

function csp_render_survey_data_meta_box($post) {
    $survey_id = $post->ID;

    // Fetch questions, participants, and responses linked to the survey
    $questions = get_posts(array(
        'post_type' => 'question',
        'meta_key' => '_survey_id',
        'meta_value' => $survey_id,
        'numberposts' => -1
    ));

    $participants = get_posts(array(
        'post_type' => 'participant',
        'meta_key' => '_survey_id',
        'meta_value' => $survey_id,
        'numberposts' => -1
    ));

    $responses = get_posts(array(
        'post_type' => 'response',
        'meta_key' => '_survey_id',
        'meta_value' => $survey_id,
        'numberposts' => -1
    ));

    echo '<div class="wrap survey-data">';
    echo '<h2>' . __('Survey Data', 'custom-survey-plugin') . '</h2>';

    if ($questions) {
        foreach ($questions as $question) {
            echo '<h3 class="question-title">' . esc_html($question->post_title) . ' <span class="toggle-arrow">&#9654;</span></h3>';
            echo '<div class="responses" style="display: none;">';
            foreach ($responses as $response) {
                $response_question = get_post_meta($response->ID, '_question_id', true);
                $response_participant = get_post_meta($response->ID, '_participant_id', true);

                if ($response_question == $question->ID) {
                    $participant_name = get_the_title($response_participant);
                    echo '<div class="response">';
                    echo '<strong>' . esc_html($participant_name) . ':</strong> ';
                    echo esc_html($response->post_title);
                    echo '</div>';
                }
            }
            echo '</div>';
        }
    } else {
        echo '<p>' . __('No questions found for this survey.', 'custom-survey-plugin') . '</p>';
    }

    echo '</div>';
}
?>
