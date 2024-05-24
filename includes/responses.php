<?php
// File: includes/responses.php

function csp_add_response_meta_boxes() {
    add_meta_box(
        'response_details',
        __('Response Details', 'custom-survey-plugin'),
        'csp_render_response_meta_box',
        'response',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'csp_add_response_meta_boxes');

function csp_render_response_meta_box($post) {
    wp_nonce_field('save_response_details', 'response_details_nonce');

    $participant_id = get_post_meta($post->ID, '_participant_id', true);
    $question_id = get_post_meta($post->ID, '_question_id', true);

    // Fetch participants and questions
    $participants = get_posts(array('post_type' => 'participant', 'numberposts' => -1));
    $questions = get_posts(array('post_type' => 'question', 'numberposts' => -1));

    echo '<label for="participant_id">' . __('Participant', 'custom-survey-plugin') . '</label>';
    echo '<select id="participant_id" name="participant_id">';
    foreach ($participants as $participant) {
        echo '<option value="' . esc_attr($participant->ID) . '"' . selected($participant_id, $participant->ID, false) . '>' . esc_html($participant->post_title) . '</option>';
    }
    echo '</select><br>';

    echo '<label for="question_id">' . __('Question', 'custom-survey-plugin') . '</label>';
    echo '<select id="question_id" name="question_id">';
    foreach ($questions as $question) {
        echo '<option value="' . esc_attr($question->ID) . '"' . selected($question_id, $question->ID, false) . '>' . esc_html($question->post_title) . '</option>';
    }
    echo '</select><br>';
}

function csp_save_response_meta_box_data($post_id) {
    if (!isset($_POST['response_details_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['response_details_nonce'], 'save_response_details')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['participant_id'])) {
        update_post_meta($post_id, '_participant_id', intval($_POST['participant_id']));
    }
    if (isset($_POST['question_id'])) {
        update_post_meta($post_id, '_question_id', intval($_POST['question_id']));
    }

    // Enregistrer la sélection du survey
    if (isset($_POST['survey_id'])) {
        update_post_meta($post_id, '_survey_id', intval($_POST['survey_id']));
    } else {
        delete_post_meta($post_id, '_survey_id');
    }
}
add_action('save_post', 'csp_save_response_meta_box_data');

// Ajouter la métabox de sélection du survey
function csp_add_response_taxonomy_metabox() {
    remove_meta_box('tagsdiv-survey', 'response', 'side'); // Supprimer la métabox par défaut

    add_meta_box(
        'response_survey',
        __('Select Survey', 'custom-survey-plugin'),
        'csp_render_response_survey_metabox',
        'response',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'csp_add_response_taxonomy_metabox');

function csp_render_response_survey_metabox($post) {
    $surveys = get_posts(array('post_type' => 'survey', 'numberposts' => -1));
    $selected_survey = get_post_meta($post->ID, '_survey_id', true);

    echo '<label for="survey_id">' . __('Select a Survey', 'custom-survey-plugin') . '</label>';
    echo '<select name="survey_id" id="survey_id">';
    echo '<option value="">' . __('None', 'custom-survey-plugin') . '</option>';
    foreach ($surveys as $survey) {
        echo '<option value="' . esc_attr($survey->ID) . '"' . selected($selected_survey, $survey->ID, false) . '>' . esc_html($survey->post_title) . '</option>';
    }
    echo '</select>';
}
?>
