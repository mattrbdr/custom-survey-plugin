<?php
// File: includes/questions.php

function csp_add_question_meta_boxes() {
    add_meta_box(
        'question_details',
        __('Question Details', 'custom-survey-plugin'),
        'csp_render_question_meta_box',
        'question',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'csp_add_question_meta_boxes');

function csp_render_question_meta_box($post) {
    wp_nonce_field('save_question_details', 'question_details_nonce');

    $question_text = get_post_meta($post->ID, '_question_text', true);

    echo '<label for="question_text">' . __('Question Text', 'custom-survey-plugin') . '</label>';
    echo '<input type="text" id="question_text" name="question_text" value="' . esc_attr($question_text) . '" size="25" />';
}

function csp_save_question_meta_box_data($post_id) {
    if (!isset($_POST['question_details_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['question_details_nonce'], 'save_question_details')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['question_text'])) {
        update_post_meta($post_id, '_question_text', sanitize_text_field($_POST['question_text']));
    }

    // Enregistrer la sélection du survey
    if (isset($_POST['survey_id'])) {
        update_post_meta($post_id, '_survey_id', intval($_POST['survey_id']));
    } else {
        delete_post_meta($post_id, '_survey_id');
    }
}
add_action('save_post', 'csp_save_question_meta_box_data');

// Ajouter la métabox de sélection du survey
function csp_add_question_taxonomy_metabox() {
    remove_meta_box('tagsdiv-survey', 'question', 'side'); // Supprimer la métabox par défaut

    add_meta_box(
        'question_survey',
        __('Select Survey', 'custom-survey-plugin'),
        'csp_render_question_survey_metabox',
        'question',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'csp_add_question_taxonomy_metabox');

function csp_render_question_survey_metabox($post) {
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
