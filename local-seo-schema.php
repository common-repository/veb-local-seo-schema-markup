<?php
/**
 * Plugin Name: Veb Local SEO Schema Markup
 * Description: Adds Local Business Schema Markup using JSON-LD.
 * Version: 1.0.1
 * Author: Veblogy Innovative Technology Pvt. Ltd.
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Tested up to: 6.6
 * Requires PHP: 7.4
 * Requires WP: 5.7
 * Author URI: https://veblogy.com/milind
 * Plugin URI: https://veblogy.com/
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Hook into WordPress 'wp_footer' to inject the JSON-LD Schema
add_action('wp_footer', 'add_local_business_schema');

function add_local_business_schema() {
    if (is_singular() || is_front_page() || is_home()) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => esc_html(get_option('business_name')),
            'address' => array(
                '@type' => 'PostalAddress',
                'streetAddress' => esc_html(get_option('street_address')),
                'addressLocality' => esc_html(get_option('locality')),
                'addressRegion' => esc_html(get_option('region')),
                'postalCode' => esc_html(get_option('postal_code')),
                'addressCountry' => esc_html(get_option('country'))
            ),
            'telephone' => esc_html(get_option('phone')),
            'openingHours' => esc_html(get_option('opening_hours')),
            'priceRange' => esc_html(get_option('price_range')),
            'url' => esc_url(get_home_url()),
        );

        // Use wp_json_encode() for safe JSON output
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
    }
}

// Add admin menu for Local SEO Schema options
add_action('admin_menu', 'local_seo_schema_menu');

function local_seo_schema_menu() {
    add_menu_page('Local SEO Schema', 'Local SEO Schema', 'manage_options', 'veb-local-seo-schema-markup', 'local_seo_schema_options_page');
}

// Display the settings page
function local_seo_schema_options_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Local SEO Schema Markup Settings', 'veb-local-seo-schema-markup'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('local_seo_schema_options_group');
            do_settings_sections('veb-local-seo-schema-markup');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings
add_action('admin_init', 'local_seo_schema_register_settings');

function local_seo_schema_register_settings() {
    register_setting('local_seo_schema_options_group', 'business_name');
    register_setting('local_seo_schema_options_group', 'street_address');
    register_setting('local_seo_schema_options_group', 'locality');
    register_setting('local_seo_schema_options_group', 'region');
    register_setting('local_seo_schema_options_group', 'postal_code');
    register_setting('local_seo_schema_options_group', 'country');
    register_setting('local_seo_schema_options_group', 'phone');
    register_setting('local_seo_schema_options_group', 'opening_hours');
    register_setting('local_seo_schema_options_group', 'price_range');

    add_settings_section('local_seo_schema_section', esc_html__('Business Details', 'veb-local-seo-schema-markup'), null, 'veb-local-seo-schema-markup');

    add_settings_field('business_name', esc_html__('Business Name', 'veb-local-seo-schema-markup'), 'local_seo_schema_text_field', 'veb-local-seo-schema-markup', 'local_seo_schema_section', array('label_for' => 'business_name'));
    add_settings_field('street_address', esc_html__('Street Address', 'veb-local-seo-schema-markup'), 'local_seo_schema_text_field', 'veb-local-seo-schema-markup', 'local_seo_schema_section', array('label_for' => 'street_address'));
    add_settings_field('locality', esc_html__('City', 'veb-local-seo-schema-markup'), 'local_seo_schema_text_field', 'veb-local-seo-schema-markup', 'local_seo_schema_section', array('label_for' => 'locality'));
    add_settings_field('region', esc_html__('State/Region', 'veb-local-seo-schema-markup'), 'local_seo_schema_text_field', 'veb-local-seo-schema-markup', 'local_seo_schema_section', array('label_for' => 'region'));
    add_settings_field('postal_code', esc_html__('Postal Code', 'veb-local-seo-schema-markup'), 'local_seo_schema_text_field', 'veb-local-seo-schema-markup', 'local_seo_schema_section', array('label_for' => 'postal_code'));
    add_settings_field('country', esc_html__('Country', 'veb-local-seo-schema-markup'), 'local_seo_schema_text_field', 'veb-local-seo-schema-markup', 'local_seo_schema_section', array('label_for' => 'country'));
    add_settings_field('phone', esc_html__('Phone Number', 'veb-local-seo-schema-markup'), 'local_seo_schema_text_field', 'veb-local-seo-schema-markup', 'local_seo_schema_section', array('label_for' => 'phone'));
    add_settings_field('opening_hours', esc_html__('Opening Hours', 'veb-local-seo-schema-markup'), 'local_seo_schema_text_field', 'veb-local-seo-schema-markup', 'local_seo_schema_section', array('label_for' => 'opening_hours'));
    add_settings_field('price_range', esc_html__('Price Range', 'veb-local-seo-schema-markup'), 'local_seo_schema_text_field', 'veb-local-seo-schema-markup', 'local_seo_schema_section', array('label_for' => 'price_range'));
}

// Generate input fields for the settings with escaping
function local_seo_schema_text_field($args) {
    $option = get_option($args['label_for']);
    echo '<input type="text" id="' . esc_attr($args['label_for']) . '" name="' . esc_attr($args['label_for']) . '" value="' . esc_attr($option) . '" />';
}
