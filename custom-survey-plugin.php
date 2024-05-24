<?php
/*
Plugin Name: Custom Survey Plugin
Description: A custom plugin for handling surveys. Easily manage participants, questions, and responses with an intuitive interface and customizable display options. Supports Gutenberg block for seamless integration.
Version: B.3.0.1
Author: Mattéo Ribardiere
Author URI: https://bento.matteorbdr.com
Plugin URI: https://github.com/mattrbdr/custom-survey-plugin
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Handle special characters
function csp_handle_special_characters($text) {
    return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Create custom post types on plugin activation
function csp_register_post_types() {
    // Participants
    $labels = array(
        'name'               => 'Participants',
        'singular_name'      => 'Participant',
        'menu_name'          => 'Participants',
        'name_admin_bar'     => 'Participant',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Participant',
        'new_item'           => 'New Participant',
        'edit_item'          => 'Edit Participant',
        'view_item'          => 'View Participant',
        'all_items'          => 'All Participants',
        'search_items'       => 'Search Participants',
        'not_found'          => 'No participants found.',
        'not_found_in_trash' => 'No participants found in Trash.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => false,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'participant'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title'),
        'show_in_rest'       => true,
    );

    register_post_type('participant', $args);

    // Questions
    $labels = array(
        'name'               => 'Questions',
        'singular_name'      => 'Question',
        'menu_name'          => 'Questions',
        'name_admin_bar'     => 'Question',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Question',
        'new_item'           => 'New Question',
        'edit_item'          => 'Edit Question',
        'view_item'          => 'View Question',
        'all_items'          => 'All Questions',
        'search_items'       => 'Search Questions',
        'not_found'          => 'No questions found.',
        'not_found_in_trash' => 'No questions found in Trash.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => false,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'question'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor'),
        'show_in_rest'       => true,
    );

    register_post_type('question', $args);

    // Responses
    $labels = array(
        'name'               => 'Responses',
        'singular_name'      => 'Response',
        'menu_name'          => 'Responses',
        'name_admin_bar'     => 'Response',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Response',
        'new_item'           => 'New Response',
        'edit_item'          => 'Edit Response',
        'view_item'          => 'View Response',
        'all_items'          => 'All Responses',
        'search_items'       => 'Search Responses',
        'not_found'          => 'No responses found.',
        'not_found_in_trash' => 'No responses found in Trash.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => false,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'response'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor'),
        'show_in_rest'       => true,
    );

    register_post_type('response', $args);

    // Surveys
    $labels = array(
        'name'               => 'Surveys',
        'singular_name'      => 'Survey',
        'menu_name'          => 'Surveys',
        'name_admin_bar'     => 'Survey',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Survey',
        'new_item'           => 'New Survey',
        'edit_item'          => 'Edit Survey',
        'view_item'          => 'View Survey',
        'all_items'          => 'All Surveys',
        'search_items'       => 'Search Surveys',
        'not_found'          => 'No surveys found.',
        'not_found_in_trash' => 'No surveys found in Trash.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => false, // Change to false
        'query_var'          => true,
        'rewrite'            => array('slug' => 'survey'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title'),
        'show_in_rest'       => true,
    );

    register_post_type('survey', $args);
}
add_action('init', 'csp_register_post_types');

// Register custom taxonomies
function csp_register_taxonomies() {
    // Attributes
    $labels = array(
        'name'              => 'Attributes',
        'singular_name'     => 'Attribute',
        'search_items'      => 'Search Attributes',
        'all_items'         => 'All Attributes',
        'parent_item'       => 'Parent Attribute',
        'parent_item_colon' => 'Parent Attribute:',
        'edit_item'         => 'Edit Attribute',
        'update_item'       => 'Update Attribute',
        'add_new_item'      => 'Add New Attribute',
        'new_item_name'     => 'New Attribute Name',
        'menu_name'         => 'Attributes'
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'attribute')
    );

    register_taxonomy('attribute', array('participant'), $args);

    // Surveys
    $labels = array(
        'name'              => 'Surveys',
        'singular_name'     => 'Survey',
        'search_items'      => 'Search Surveys',
        'all_items'         => 'All Surveys',
        'parent_item'       => 'Parent Survey',
        'parent_item_colon' => 'Parent Survey:',
        'edit_item'         => 'Edit Survey',
        'update_item'       => 'Update Survey',
        'add_new_item'      => 'Add New Survey',
        'new_item_name'     => 'New Survey Name',
        'menu_name'         => 'Surveys'
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'survey')
    );

    register_taxonomy('survey', array('participant', 'question', 'response'), $args);
}
add_action('init', 'csp_register_taxonomies');

// Add submenu pages under a single Surveys menu
function csp_add_submenus() {
    add_menu_page('Surveys', 'Surveys', 'manage_options', 'csp_surveys', 'csp_surveys_page', 'dashicons-chart-bar', 6);
    add_submenu_page('csp_surveys', 'All Surveys', 'All Surveys', 'manage_options', 'edit.php?post_type=survey');
    add_submenu_page('csp_surveys', 'Add New Survey', 'Add New', 'manage_options', 'post-new.php?post_type=survey');
    add_submenu_page('csp_surveys', 'Participants', 'Participants', 'manage_options', 'edit.php?post_type=participant');
    add_submenu_page('csp_surveys', 'Questions', 'Questions', 'manage_options', 'edit.php?post_type=question');
    add_submenu_page('csp_surveys', 'Responses', 'Responses', 'manage_options', 'edit.php?post_type=response');
    add_submenu_page('csp_surveys', 'Attributes', 'Attributes', 'manage_options', 'edit-tags.php?taxonomy=attribute&post_type=participant');
}
add_action('admin_menu', 'csp_add_submenus');

// Enqueue styles and scripts.
function csp_enqueue_assets() {
    wp_enqueue_style('csp-styles', plugin_dir_url(__FILE__) . 'assets/css/survey-styles.css');
    wp_enqueue_script('csp-scripts', plugin_dir_url(__FILE__) . 'assets/js/survey-scripts.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'csp_enqueue_assets');

// Enqueue frontend script.
function csp_enqueue_frontend_assets() {
    wp_enqueue_style('csp-frontend-styles', plugin_dir_url(__FILE__) . 'assets/css/survey-styles.css');
    wp_enqueue_script(
        'csp-frontend-scripts',
        plugin_dir_url(__FILE__) . 'assets/js/survey-frontend.js',
        array('jquery'),
        filemtime(plugin_dir_path(__FILE__) . 'assets/js/survey-frontend.js'),
        true
    );

    wp_localize_script('csp-frontend-scripts', 'wpApiSettings', array(
        'root' => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest')
    ));
}
add_action('wp_enqueue_scripts', 'csp_enqueue_frontend_assets');

// Register block editor scripts and styles
function csp_register_block_assets() {
    wp_register_script(
        'csp-block-editor-script',
        plugins_url('build/index.js', __FILE__),
        array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-data'),
        filemtime(plugin_dir_path(__FILE__) . 'build/index.js')
    );

    wp_register_style(
        'csp-block-editor-style',
        plugins_url('src/blocks/editor.css', __FILE__),
        array('wp-edit-blocks'),
        filemtime(plugin_dir_path(__FILE__) . 'src/blocks/editor.css')
    );

    wp_register_style(
        'csp-block-style',
        plugins_url('src/blocks/style.css', __FILE__),
        array(),
        filemtime(plugin_dir_path(__FILE__) . 'src/blocks/style.css')
    );

    register_block_type('custom-survey-plugin/survey-block', array(
        'editor_script' => 'csp-block-editor-script',
        'editor_style'  => 'csp-block-editor-style',
        'style'         => 'csp-block-style',
        'render_callback' => 'csp_render_survey_block',
        'attributes'    => array(
            'surveyId' => array(
                'type' => 'string',
                'default' => '',
            ),
            'showResponses' => array(
                'type' => 'boolean',
                'default' => false,
            ),
            'showQuestions' => array(
                'type' => 'boolean',
                'default' => false,
            ),
            'showParticipants' => array(
                'type' => 'boolean',
                'default' => false,
            ),
            'filterQuestion' => array(
                'type' => 'string',
                'default' => '',
            ),
            'showFilterQuestion' => array(
                'type' => 'boolean',
                'default' => false,
            ),
            'filterParticipant' => array(
                'type' => 'string',
                'default' => '',
            ),
            'showFilterParticipant' => array(
                'type' => 'boolean',
                'default' => false,
            ),
            'filterAttribute' => array(
                'type' => 'string',
                'default' => '',
            ),
            'showFilterAttribute' => array(
                'type' => 'boolean',
                'default' => false,
            ),
            'layout' => array(
                'type' => 'string',
                'default' => 'list',
            ),
            'blockWidth' => array(
                'type' => 'number',
                'default' => 100,
            ),
        ),
    ));
}
add_action('init', 'csp_register_block_assets');

// Render callback for the survey block
function csp_render_survey_block($attributes) {
    if (!isset($attributes['surveyId']) || empty($attributes['surveyId'])) {
        return '<p>Select a survey from the block settings.</p>';
    }

    $surveyId = intval($attributes['surveyId']);
    $showQuestions = isset($attributes['showQuestions']) ? $attributes['showQuestions'] : false;
    $showResponses = isset($attributes['showResponses']) ? $attributes['showResponses'] : false;
    $showParticipants = isset($attributes['showParticipants']) ? $attributes['showParticipants'] : false;
    $filterQuestion = isset($attributes['filterQuestion']) ? sanitize_text_field($attributes['filterQuestion']) : '';
    $showFilterQuestion = isset($attributes['showFilterQuestion']) ? $attributes['showFilterQuestion'] : false;
    $filterParticipant = isset($attributes['filterParticipant']) ? sanitize_text_field($attributes['filterParticipant']) : '';
    $showFilterParticipant = isset($attributes['showFilterParticipant']) ? $attributes['showFilterParticipant'] : false;
    $filterAttribute = isset($attributes['filterAttribute']) ? sanitize_text_field($attributes['filterAttribute']) : '';
    $showFilterAttribute = isset($attributes['showFilterAttribute']) ? $attributes['showFilterAttribute'] : false;
    $blockWidth = isset($attributes['blockWidth']) ? intval($attributes['blockWidth']) : 100;
    $layout = isset($attributes['layout']) ? sanitize_text_field($attributes['layout']) : 'list';

    // Fetch questions
    $questions = get_posts(array(
        'post_type' => 'question',
        'meta_key' => '_survey_id',
        'meta_value' => $surveyId,
        'numberposts' => -1,
    ));

    // Fetch participants
    $participants = get_posts(array(
        'post_type' => 'participant',
        'meta_key' => '_survey_id',
        'meta_value' => $surveyId,
        'numberposts' => -1,
    ));

    // Fetch attributes
    $attributes = get_terms(array(
        'taxonomy' => 'attribute',
        'hide_empty' => false,
        'parent' => 0,
    ));

    if (empty($questions)) {
        return '<p>No questions found for this survey.</p>';
    }

    ob_start();
    echo '<div class="questions-list layout-' . esc_attr($layout) . '" style="width: ' . esc_attr($blockWidth) . '%;">';
    
    // Ajout du menu déroulant pour les questions si l'attribut showFilterQuestion est vrai
    if ($showFilterQuestion) {
        echo '<div class="filter-container filter-question">';
        echo '<label for="filter-question">' . __('Filter Questions', 'custom-survey-plugin') . '</label>';
        echo '<select id="filter-question">';
        echo '<option value="">' . __('Select a question', 'custom-survey-plugin') . '</option>';
        foreach ($questions as $question) {
            echo '<option value="' . esc_attr($question->ID) . '">' . esc_html($question->post_title) . '</option>';
        }
        echo '</select>';
        echo '</div>';
    }
    
    // Ajout du menu déroulant pour les participants si l'attribut showFilterParticipant est vrai
    if ($showFilterParticipant) {
        echo '<div class="filter-container filter-participant">';
        echo '<label for="filter-participant">' . __('Filter Participants', 'custom-survey-plugin') . '</label>';
        echo '<select id="filter-participant">';
        echo '<option value="">' . __('Select a participant', 'custom-survey-plugin') . '</option>';
        foreach ($participants as $participant) {
            echo '<option value="' . esc_attr($participant->ID) . '">' . esc_html($participant->post_title) . '</option>';
        }
        echo '</select>';
        echo '</div>';
    }

    // Ajout du menu déroulant pour les attributs si l'attribut showFilterAttribute est vrai
    if ($showFilterAttribute) {
        echo '<div class="filter-container filter-attribute">';
        echo '<label for="filter-attribute">' . __('Filter Attributes', 'custom-survey-plugin') . '</label>';
        echo '<select id="filter-attribute">';
        echo '<option value="">' . __('Select an attribute', 'custom-survey-plugin') . '</option>';
        foreach ($attributes as $attribute) {
            echo '<optgroup label="' . esc_html($attribute->name) . '">';
            $child_terms = get_terms(array(
                'taxonomy' => 'attribute',
                'hide_empty' => false,
                'parent' => $attribute->term_id,
            ));
            foreach ($child_terms as $child) {
                echo '<option value="' . esc_attr($child->term_id) . '">' . esc_html($child->name) . '</option>';
            }
            echo '</optgroup>';
        }
        echo '</select>';
        echo '</div>';
    }
    
    foreach ($questions as $question) {
        echo '<div class="question-card" data-question-id="' . esc_attr($question->ID) . '">';
        echo '<div class="question-header">' . esc_html($question->post_title) . '</div>';
        if ($showResponses || $showParticipants) {
            echo '<div class="responses-container" style="display: none;">';

            // Fetch responses for the question
            $responses = get_posts(array(
                'post_type' => 'response',
                'meta_key' => '_question_id',
                'meta_value' => $question->ID,
                'numberposts' => -1,
            ));

            if (!empty($responses)) {
                echo '<div class="responses-list">';
                foreach ($responses as $response) {
                    $participant_id = get_post_meta($response->ID, '_participant_id', true);
                    $participant_name = $participant_id ? get_the_title($participant_id) : 'Unknown Participant';
                    echo '<div class="response-card" data-participant-id="' . esc_attr($participant_id) . '" data-attribute-ids="' . implode(',', wp_get_post_terms($participant_id, 'attribute', array('fields' => 'ids'))) . '">';
                    if ($showParticipants) {
                        echo '<strong>' . esc_html($participant_name) . ':</strong> ';
                    }
                    if ($showResponses) {
                        echo esc_html($response->post_title);
                    }
                    echo '</div>';
                }
                echo '</div>';
            } else {
                echo '<p>No responses found.</p>';
            }

            echo '</div>';
        }
        echo '</div>';
    }
    echo '</div>';
    return ob_get_clean();
}

// Include additional files
include_once plugin_dir_path(__FILE__) . 'includes/participants.php';
include_once plugin_dir_path(__FILE__) . 'includes/questions.php';
include_once plugin_dir_path(__FILE__) . 'includes/responses.php';
include_once plugin_dir_path(__FILE__) . 'includes/surveys.php';

// Define the csp_surveys_page function
function csp_surveys_page() {
    echo '<div class="wrap">';
    echo '<h1>' . __('Surveys', 'custom-survey-plugin') . '</h1>';
    echo '<p>' . __('Welcome to the Surveys management page. Here you can manage all your surveys.', 'custom-survey-plugin') . '</p>';
    echo '</div>';
}
?>
