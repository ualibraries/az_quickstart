<?php
/**
 * @file
 * theme-settings.php
 *
 * Provides theme settings for Arizona Barrio based themes when admin theme is not.
 *
 * @see ./includes/settings.inc
 */
include_once dirname(__FILE__) . '/includes/common.inc';

use Drupal\Core\Link;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Markup;

/**
 * Implements hook_form_system_theme_settings_alter() for settings form.
 *
 * Replace Barrio setting options with subtheme ones.
 *
 * Example on how to alter theme settings form
 *
 */
function az_barrio_form_system_theme_settings_alter(&$form, FormStateInterface $form_state) {
  $form['footer_logo']['#open'] = FALSE;

  // AZ Barrio settings.
  $form['az_settings'] = [
    '#type' => 'details',
    '#title' => t('Arizona Barrio'),
    '#group' => 'bootstrap',
    '#weight' => -10,
  ];

  // Institutional logo.
  $form['az_settings']['settings']['institutional_logo'] = array(
    '#type' => 'fieldset',
    '#title' => t('Institutional Logo Settings'),
  );
  $form['az_settings']['settings']['institutional_logo']['wordmark'] = array(
    '#type' => 'checkbox',
    '#title' => t('Institutional header wordmark logo'),
    '#description' => t('With few exceptions, this should always be enabled.'),
    '#default_value' => theme_get_setting('wordmark'),
  );

  // Main menu.
  $form['az_settings']['settings']['main_menu'] = array(
    '#type' => 'fieldset',
    '#title' => t('Main Menu Settings'),
  );
  $form['az_settings']['settings']['main_menu']['az_main_menu_style'] = array(
    '#type' => 'radios',
    '#options' => array(
      'bootstrap' => t('Render the main menu element using AZ Bootstrap\'s Dropdown Navbar component.'),
      'superfish' => t('Render the main menu element using Superfish (requires AZQS Navigation & Superfish modules).'),
    ),
    '#title' => t('Main menu style'),
    '#default_value' => theme_get_setting('az_main_menu_style'),
    '#prefix' =>  t('AZ Barrio can render the \'Main menu\' page element in a number of different styles. The AZ Bootstrap Dropdown Navbar component style will be used as the fallback option if the dependencies for the other styles are missing.'),
  );
  $form['az_settings']['settings']['main_menu']['az_main_menu_style']['menu_style_enhancements'] = array(
    '#type' => 'fieldset',
    '#weight' => 100,
    '#title' => t('Menu Style Enhancements'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#states' => array(
      'visible' => array(
        ':input[name="az_main_menu_style"]' => array('value' => 'bootstrap'),
      )
    )
  );
  $form['az_settings']['settings']['main_menu']['az_main_menu_style']['menu_style_enhancements']['az_bs_overlay_menu_scroll'] = array(
    '#type' => 'checkbox',
    '#title' => t('Overlay Menu Scroll'),
    '#default_value' => theme_get_setting('az_bs_overlay_menu_scroll'),
    '#description' => t('Render the main menu element using AZQS customized Bootstrap Overlay Menu Scroll Navigation.'),
  );

  // Information security and privacy link.
  $form['az_settings']['settings']['info_security_privacy'] = array(
    '#type' => 'checkbox',
    '#title' => t('University Information Security and Privacy link'),
    '#description' => t('With few execeptions, this should always be enabled.'),
    '#default_value' => theme_get_setting('info_security_privacy'),
  );

  // Copyright notice.
  $form['az_settings']['settings']['copyright_notice'] = array(
    '#type' => 'textfield',
    '#title' => t('Copyright notice'),
    '#maxlength' => 512,
    '#description' => t('A copyright notice for this site. The value here will appear after a "Copyright YYYY" notice (where YYYY is the current year).'),
    '#default_value' => theme_get_setting('copyright_notice'),
  );

  // Hide front page title.
  $form['az_settings']['settings']['az_hide_front_title'] = array(
    '#type' => 'checkbox',
    '#title' => t('Hide title of front page node'),
    '#description' => t('If this is checked, the title of the node being displayed on the front page will not be visible'),
    '#default_value' => theme_get_setting('az_hide_front_title'),
  );

  // Back-to-top button.
  $form['az_settings']['settings']['az_back_to_top'] = array(
    '#type' => 'checkbox',
    '#title' => t('Add back to top button to pages longer than 4 screens (browser window height).'),
    '#default_value' => theme_get_setting('az_back_to_top'),
  );

  // AZ Bootstrap settings.
  $form['azbs_settings'] = [
    '#type' => 'details',
    '#title' => t('Arizona Bootstrap'),
    '#group' => 'bootstrap',
    '#weight' => -9,
  ];
  $form['azbs_settings']['settings']['az_bootstrap_source'] = array(
    '#type' => 'radios',
    '#title' => t('AZ Bootstrap Source'),
    '#options' => array(
      'local' => t('Use local copy of AZ Bootstrap packaged with AZ Barrio (%stableversion).', array('%stableversion' => AZ_BOOTSTRAP_STABLE_VERSION)),
      'cdn' => t('Use external copy of AZ Bootstrap hosted on the AZ Bootstrap CDN.'),
    ),
    '#default_value' => theme_get_setting('az_bootstrap_source'),
    '#prefix' => t('AZ Barrio requires the <a href="@azbootstrap">AZ Bootstrap</a> front-end framework. AZ Bootstrap can either be loaded from the local copy packaged with UA Zen or from the <a href="@azbootstrapcdn">AZ Bootstrap CDN</a>.', array(
      '@azbootstrap' => 'http://uadigital.arizona.edu/ua-bootstrap',
      '@azbootstrapcdn' => 'https://cdn.uadigital.arizona.edu/lib/ua-bootstrap',
    )),
    '#description' => '<div class="alert alert-info messages info">' . t('<strong>NOTE:</strong> The AZ Bootstrap CDN is the preferred method for providing huge performance gains in load time.') . '</div>',
  );
  $form['azbs_settings']['settings']['az_bootstrap_cdn'] = array(
    '#type' => 'fieldset',
    '#title' => t('AZ Bootstrap CDN Settings'),
    '#states' => array(
      'visible' => array(
        ':input[name="az_bootstrap_source"]' => array('value' => 'cdn'),
      )
    ),
  );
  $form['azbs_settings']['settings']['az_bootstrap_cdn']['az_bootstrap_cdn_version'] = array(
    '#type' => 'radios',
    '#title' => t('AZ Bootstrap CDN version'),
    '#options' => array(
      'stable' => t('Stable version: This option has undergone the most testing within the az_barrio theme. Currently: %stableversion (Recommended).', array('%stableversion' => AZ_BOOTSTRAP_STABLE_VERSION)),
      'latest' => t('Latest tagged version. The most recently tagged stable release of AZ Bootstrap. While this has not been explicitly tested on this version of az_barrio, it’s probably OK to use on production sites. Please report bugs to the AZ Digital team.'),
      'master' => t('Latest dev version. This is the tip of the master branch of AZ Bootstrap. Please do not use on production unless you are following the ua-bootstrap project closely. Please report bugs to the AZ Digital team.'),
    ),
    '#default_value' => theme_get_setting('az_bootstrap_cdn_version'),
  );
  $form['azbs_settings']['settings']['az_bootstrap_minified'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Use minified version of AZ Bootstrap.'),
    '#default_value' => theme_get_setting('az_bootstrap_minified'),
  );
  $form['azbs_settings']['settings']['az_bootstrap_style'] = array(
    '#type' => 'fieldset',
    '#title' => t('AZ Bootstrap Style Settings'),
  );
  $form['azbs_settings']['settings']['az_bootstrap_style']['az_brand_icons_source'] = array(
    '#type' => 'radios',
    '#title' => t('AZ Brand Icons Source'),
    '#options' => array(
      'local' => t('Use local copy of <a href="@azbrandicons">UA Brand Icons</a> packaged with AZ Barrio (%stableversion).', array(
        '@azbrandicons' => 'http://uadigital.arizona.edu/ua-bootstrap/components.html#ua-brand-icons',
        '%stableversion' => AZ_BRAND_ICONS_STABLE_VERSION,
      )),
      'cdn' => t('Use external copy of <a href="@azbrandicons">UA Brand Icons</a> hosted on the CDN.', array(
        '@azbrandicons' => 'http://uadigital.arizona.edu/ua-bootstrap/components.html#ua-brand-icons',
      )),
    ),
    '#default_value' => theme_get_setting('az_brand_icons_source'),
  );
  $form['azbs_settings']['settings']['az_bootstrap_style']['az_brand_icons_class'] = array(
    '#type' => 'checkbox',
    '#title' => t('Add <code>ua-brand-icons</code> class to <code>html</code> element.'),
    '#default_value' => theme_get_setting('az_brand_icons_class'),
  );
  $form['azbs_settings']['settings']['az_bootstrap_style']['external_links'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use AZ Bootstrap external links styling.'),
    '#default_value' => theme_get_setting('external_links'),
  );
  $form['azbs_settings']['settings']['az_bootstrap_style']['sticky_footer'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use the AZ Bootstrap sticky footer template.'),
    '#default_value' => theme_get_setting('sticky_footer'),
  );

  // Components.
  $form['components']['navbar']['bootstrap_barrio_navbar_top_background']['#options'] = array(
    'bg-primary' => t('Primary'),
    'bg-secondary' => t('Secondary'),
    'bg-light' => t('Light'),
    'bg-dark' => t('Dark'),
    'bg-white' => t('White'),
    'bg-transparent' => t('Transparent'),
  );
  $form['components']['navbar']['bootstrap_barrio_navbar_background']['#options'] = array(
    'bg-primary' => t('Primary'),
    'bg-secondary' => t('Secondary'),
    'bg-light' => t('Light'),
    'bg-dark' => t('Dark'),
    'bg-white' => t('White'),
    'bg-transparent' => t('Transparent'),
  );

  // Primary logo.
  $form['logo']['primary_logo_alt_text'] = array(
    '#type' => 'textfield',
    '#title' => t('Custom primary logo alt text'),
    '#description' => t('Alternative text is used by screen readers, search engines, and when the image cannot be loaded. By adding alt text you improve accessibility and search engine optimization.'),
    '#default_value' => theme_get_setting('primary_logo_alt_text'),
    '#element_validate' => array('token_element_validate'),
  );
  $form['logo']['primary_logo_title_text'] = array(
    '#type' => 'textfield',
    '#title' => t('Custom primary logo title text'),
    '#description' => t('Title text is used in the tool tip when a user hovers their mouse over the image. Adding title text makes it easier to understand the context of an image and improves usability.'),
    '#default_value' => theme_get_setting('primary_logo_title_text'),
    '#element_validate' => array('token_element_validate'),
  );
  $form['logo']['tokens'] = array(
    '#theme' => 'token_tree_link',
    '#global_types' => TRUE,
    '#click_insert' => TRUE,
  );

  // Footer logo.
  $form['footer_logo'] = array(
    '#type' => 'details',
    '#title' => t('Logo Footer Image'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['footer_logo']['footer_default_logo'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use the default logo'),
    '#default_value' => theme_get_setting('footer_default_logo'),
    '#tree' => FALSE,
    '#description' => t('Check here if you want the theme to use the logo supplied with it.'),
  );
  $form['footer_logo']['settings'] = array(
    '#type' => 'container',
    '#states' => array(
      // Hide the logo settings when using the default logo.
      'invisible' => array(
        'input[name="footer_default_logo"]' => array(
          'checked' => TRUE,
        ),
      ),
    ),
  );
  $form['footer_logo']['footer_logo_link_destination'] = array(
    '#type' => 'textfield',
    '#title' => t('Footer logo link destination'),
    '#description' => t('Where should the footer logo link to. Example: &#x3C;front&#x3E;'),
    '#default_value' => theme_get_setting('footer_logo_link_destination'),
  );
  $form['footer_logo']['footer_logo_alt_text'] = array(
    '#type' => 'textfield',
    '#title' => t('Footer logo alt text'),
    '#description' => t('Alternative text is used by screen readers, search engines, and when the image cannot be loaded. By adding alt text you improve accessibility and search engine optimization.'),
    '#default_value' => theme_get_setting('footer_logo_alt_text'),
    '#element_validate' => array('token_element_validate'),
  );
  $form['footer_logo']['footer_logo_title_text'] = array(
    '#type' => 'textfield',
    '#title' => t('Footer logo title text'),
    '#description' => t('Title text is used in the tool tip when a user hovers their mouse over the image. Adding title text makes it easier to understand the context of an image and improves usability.'),
    '#default_value' => theme_get_setting('footer_logo_title_text'),
    '#element_validate' => array('token_element_validate'),
  );
  $form['footer_logo']['tokens'] = array(
    '#theme' => 'token_tree_link',
    '#global_types' => TRUE,
    '#click_insert' => TRUE,
  );
  $form['footer_logo']['settings']['footer_logo_path'] = array(
    '#type' => 'textfield',
    '#title' => t('Path to custom footer logo'),
    '#description' => t('The path to the file you would like to use as your footer logo file instead of the logo in the header.'),
    '#default_value' => theme_get_setting('footer_logo'),
  );
  $form['footer_logo']['settings']['footer_logo_upload'] = array(
    '#type' => 'file',
    '#title' => t('Upload footer logo image'),
    '#maxlength' => 40,
    '#description' => t('If you don\'t have direct file access to the server, use this field to upload your footer logo.'),
  );
}
