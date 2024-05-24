<?php
// File: includes/participants.php

function csp_add_participant_meta_boxes() {
    add_meta_box(
        'participant_details',
        __('Participant Details', 'custom-survey-plugin'),
        'csp_render_participant_meta_box',
        'participant',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'csp_add_participant_meta_boxes');

function csp_render_participant_meta_box($post) {
    wp_nonce_field('save_participant_details', 'participant_details_nonce');

    $custom_fields = get_post_meta($post->ID, '_custom_fields', true);
    $website = get_post_meta($post->ID, '_participant_website', true); // Retrieve the website link

    echo '<div id="custom-fields">';
    if ($custom_fields) {
        foreach ($custom_fields as $field) {
            echo '<div class="custom-field">';
            echo '<label>' . esc_html($field['name']) . ': </label>';
            echo '<input type="text" name="custom_fields[' . esc_attr($field['name']) . ']" value="' . esc_attr($field['value']) . '">';
            echo '<button type="button" class="button remove-field">Remove</button>';
            echo '</div>';
        }
    }
    echo '</div>';

    echo '<button type="button" class="button" id="add-field">Add Field</button>';
    echo '<br><br>';
    echo '<button type="button" class="button" id="add-custom-field">Add Custom Field</button>';

    // Add website field
    echo '<br><br>';
    echo '<label for="participant_website">' . __('Website', 'custom-survey-plugin') . '</label>';
    echo '<input type="text" id="participant_website" name="participant_website" value="' . esc_attr($website) . '" size="25" />';
}

function csp_save_participant_meta_box_data($post_id) {
    if (!isset($_POST['participant_details_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['participant_details_nonce'], 'save_participant_details')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $custom_fields = array();
    if (isset($_POST['custom_fields']) && is_array($_POST['custom_fields'])) {
        foreach ($_POST['custom_fields'] as $name => $value) {
            $custom_fields[] = array('name' => sanitize_text_field($name), 'value' => sanitize_text_field($value));
        }
    }
    update_post_meta($post_id, '_custom_fields', $custom_fields);

    // Enregistrer la sélection du survey
    if (isset($_POST['survey_id'])) {
        update_post_meta($post_id, '_survey_id', intval($_POST['survey_id']));
    } else {
        delete_post_meta($post_id, '_survey_id');
    }

    // Save website field
    if (isset($_POST['participant_website'])) {
        update_post_meta($post_id, '_participant_website', esc_url($_POST['participant_website']));
    }
}
add_action('save_post', 'csp_save_participant_meta_box_data');

// Ajouter la métabox de sélection du survey
function csp_add_participant_taxonomy_metabox() {
    remove_meta_box('tagsdiv-survey', 'participant', 'side'); // Supprimer la métabox par défaut

    add_meta_box(
        'participant_survey',
        __('Select Survey', 'custom-survey-plugin'),
        'csp_render_participant_survey_metabox',
        'participant',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'csp_add_participant_taxonomy_metabox');

function csp_render_participant_survey_metabox($post) {
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
