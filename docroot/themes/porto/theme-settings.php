<?php
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Theme\ThemeSettings;
use Drupal\system\Form\ThemeSettingsForm;
use Drupal\Core\Form;

function porto_form_system_theme_settings_alter(&$form, Drupal\Core\Form\FormStateInterface $form_state) {

    // ----------- GENERAL -----------
    $form['options']['general'] = array(
        '#type' => 'fieldset',
        '#title' => t('General'),
    );
    // Breadcrumbs
    $form['options']['general']['breadcrumbs'] = array(
        '#type' => 'checkbox',
        '#title' => 'Breadcrumbs',
        '#default_value' => theme_get_setting('breadcrumbs'),
    );

    // Loader Setting
    $form['options']['general']['loader'] = array(
        '#type' => 'checkbox',
        '#title' => 'On/Off Loader',
        '#default_value' => theme_get_setting('loader'),
    );

    // RTL
   /* $form['options']['general']['rtl'] = array(
        '#type' => 'checkbox',
        '#title' => 'On/Off RTL',
        '#default_value' => theme_get_setting('rtl'),
    );*/

    // ----------- DESIGN -----------
    $form['options']['design'] = array(
        '#type' => 'fieldset',
        '#title' => 'Design',
    );


    // Header Option
    $form['options']['design']['header_style'] = array(
        '#type' => 'fieldset',
        '#title' => '<div class="plus"></div><h3 class="options_heading">Header Style</h3>',
    );
    $form['options']['design']['header_style']['header_option'] = array(
        '#type' => 'select',
        '#title' => 'Select a header style option:',
        '#default_value' => theme_get_setting('header_option'),
        '#options' => array(
            'none' => 'None',
            'h_default' => 'Header Default',
            'h_default_language' => 'Header Default + Language Dropdown',
            'h_default_big_logo' => 'Header Default + Big Logo',
            'h_flat' => 'Header Flat',
            'h_flat_topbar' => 'Header Flat + Top Bar',
            'h_flat_colored_topbar' => 'Header Flat + Colored Top Bar',
            'h_flat_topbar_search' => 'Header Flat + Topbar Search',
            'h_center' => 'Header Center',
            'h_narrow' => 'Header Narrow',
            'h_transparent' => 'Header Transparent',
            'h_transparent_bottom_border' => 'Header Transparent bottom border',
            'h_semi_transparent' => 'Header Semi Transparent',
            'h_semi_transparent_light' => 'Header Semi Transparent Light',
            'h_navbar' => 'Header Navbar',
            'h_navbar_extra_info' => 'Header Navbar + Extra Info',
            'h_without_menu' => 'Header Without Menu',
            'h_fullwidth' => 'Header FullWidth',
            'h_below_slider' => 'Header Below Slider',
            'h_left' => 'Header Left Side',
            'h_right' => 'Header Right Side',
        ),
    );
// Page Header
    $form['options']['design']['page_header'] = array(
        '#type' => 'fieldset',
        '#title' => '<div class="plus"></div><h3 class="options_heading">Page Header Style</h3>',
    );
    $form['options']['design']['page_header']['page_header_option'] = array(
        '#type' => 'select',
        '#title' => 'Select a header style option:',
        '#default_value' => theme_get_setting('page_header_option'),
        '#options' => array(
            'none' => 'None',
            'ph_default' => 'Default',
            'ph_light' => 'Light',
            'ph_light_reverse' => 'Light - Reverse',
            'ph_parallax' => 'Parallax',
            'ph_center' => 'Center',
        ),
    );
    // Page Header
    $form['options']['design']['page_header_color'] = array(
        '#type' => 'fieldset',
        '#title' => '<div class="plus"></div><h3 class="options_heading">Page Header Color</h3>',
        '#description' => 'This Feature just support for Page Header Default',
    );
    $form['options']['design']['page_header_color']['page_header_color_option'] = array(
        '#type' => 'select',
        '#title' => 'Select a header style option:',
        '#default_value' => theme_get_setting('page_header_color_option'),
        '#options' => array(
            'none' => 'None',
            'page-header-primary' => 'Primary Color',
            'page-header-secondary' => 'Secondary Color',
            'page-header-tertiary' => 'Tertiary Color',
            'page-header-quaternary' => 'Quaternary Color',
        ),

        '#states' => array (
            'visible' => array(
                'select[name = page_header_option]' => array('value' => 'ph_default')
            )
        )
    );
    // Header Below Slider
    $form['options']['design']['below_slider'] = array(
        '#type' => 'fieldset',
        '#title' => '<div class="plus"></div><h3 class="options_heading">Header Below Slider</h3>',
        '#description' => 'This Feature just support for Header Below Slider',
    );
    $form['options']['design']['below_slider']['below_slider_option'] = array(
        '#type' => 'checkbox',
        '#title' => 'Show Header Below Slider',
        '#default_value' => theme_get_setting('below_slider_option'),
        '#states' => array (
            'visible' => array(
                'select[name = header_option]' => array('value' => 'h_below_slider')
            )
        )
    );
    //Side Header Semi Transparent
   $form['options']['design']['h_semi_transparent'] = array(
        '#type' => 'fieldset',
        '#title' => '<div class="plus"></div><h3 class="options_heading">Side Header Semi Transparent</h3>',
        '#description' => 'This Feature just support for Header Left Side',
    );
    $form['options']['design']['h_semi_transparent']['h_semi_transparent_option'] = array(
        '#type' => 'checkbox',
        '#title' => 'Side Header Semi Transparent',
        '#default_value' => theme_get_setting('h_semi_transparent_option'),
        '#states' => array (
            'visible' => array(
                'select[name = header_option]' => array('value' => 'h_left'),
            )
        )
    );
    // Footer Option
    $form['options']['design']['footer_style'] = array(
        '#type' => 'fieldset',
        '#title' => '<div class="plus"></div><h3 class="options_heading">Footer Style</h3>',
    );

    // Footer Option
    $form['options']['design']['footer_style']['footer_option'] = array(
        '#type' => 'select',
        '#title' => 'Select a footer style option:',
        '#default_value' => theme_get_setting('footer_option'),
        '#options' => array(
            'none' => 'None',
            'f_default' => 'Footer Default (1)',
            'f_advanced' => 'Footer Advanced (2)',
            'f_simple' => 'Footer Simple (3)',
            'f_light' => 'Footer Light (4)',
            'f_light_narrow' => 'Footer Light Narrow (5)',
            'f_latest_work' => 'Footer Latest Work (6)',
            'f_contact' => 'Footer Contact Form (7)',
        ),
    );
    // Footer Colour
    $form['options']['design']['footer_color'] = array(
        '#type' => 'fieldset',
        '#title' => '<div class="plus"></div><h3 class="options_heading">Footer color</h3>',
    );

    // Footer Colour
    $form['options']['design']['footer_color']['footer_color_option'] = array(
        '#type' => 'select',
        '#title' => 'Select a footer style option:',
        '#default_value' => theme_get_setting('footer_color_option'),
        '#options' => array(
            'none' => 'None',
            'color-primary' => 'Primary Color',
            'color-secondary' => 'Secondary Color',
            'color-tertiary' => 'Tertiary Color',
            'color-quaternary' => 'Quaternary Color',
        ),
    );

    // Sticky Header
    $form['options']['design']['header_sticky'] = array(
        '#type' => 'fieldset',
        '#title' => '<div class="plus"></div><h3 class="options_heading">Header Sticky</h3>',
    );
    // Sticky Header Option
    $form['options']['design']['header_sticky']['header_sticky_option'] = array(
        '#type' => 'select',
        '#title' => 'Select a style option:',
        '#default_value' => theme_get_setting('header_sticky_option'),
        '#options' => array(
            'none' => 'Disable',
            'always_sticky' => 'Always Sticky',
        ),
    );
    // Navigation
    $form['options']['design']['navigation'] = array(
        '#type' => 'fieldset',
        '#title' => '<div class="plus"></div><h3 class="options_heading">Setting Navigation</h3>',
    );
    // Navigation Option
    $form['options']['design']['navigation']['navigation_option'] = array(
        '#type' => 'select',
        '#title' => 'Select a style option:',
        '#default_value' => theme_get_setting('navigation_option'),
        '#options' => array(
            'none' => 'None',
            'header-nav-dark-dropdown' => 'Dark Dropdown',
            'header-nav-top-line' => 'Top line'
        ),
    );

    // Layout Option
    $form['options']['design']['layout_style'] = array(
        '#type' => 'fieldset',
        '#title' => '<div class="plus"></div><h3 class="options_heading">Layout Style</h3>',
    );

    $form['options']['design']['layout_style']['layout_option'] = array(
        '#type' => 'select',
        '#title' => 'Select a layout style:',
        '#default_value' => theme_get_setting('layout_option'),
        '#options' => array(
            'boxed' => 'Boxed',
            'dark' => 'Dark',
            'widescreen' => 'Widescreen (default)',

        ),
    );
    // Skins
    $form['options']['design']['skin'] = array(
        '#type' => 'fieldset',
        '#title' => '<div class="plus"></div><h3 class="options_heading">Skins</h3>',
    );

    $form['options']['design']['skin']['skin_option'] = array(
        '#type' => 'select',
        '#title' => 'Select a Skin Style:',
        '#default_value' => theme_get_setting('skin_option'),
        '#options' => array(
            'none' => 'None',
            'default' => 'Default',
            'skin-corporate-3' => 'Corporate 3',
            'skin-corporate-4' => 'Corporate 4',
            'skin-corporate-5' => 'Corporate 5',
            'skin-corporate-6' => 'Corporate 6',
            'skin-corporate-7' => 'Corporate 7',
            'skin-corporate-8' => 'Corporate 8',
            'custom' => 'Custom color',
        ),
    );
    $form['options']['design']['skin']['custom_color'] = array(
        '#type' => 'textfield',
        '#title' => 'Enter code color',
        '#description' => 'Example : #ccc . Get code color at link : https://www.w3schools.com/colors/colors_hex.asp',
        '#default_value' => theme_get_setting('custom_color'),
        '#states' => array (
            'visible' => array(
                'select[name = skin_option]' => array('value' => 'custom')
            )
        )
    );
    // Contact
    $form['options']['design']['contact'] = array(
        '#type' => 'fieldset',
        '#title' => '<div class="plus"></div><h3 class="options_heading">Contact</h3>',
        '#description' => 'Show in header : Default + Language Dropdown',
    );
    $form['options']['design']['contact']['contact_option'] = array(
        '#type' => 'textfield',
        '#default_value' => theme_get_setting('contact_option'),
        '#description' => 'Show in header : Default + Language Dropdown',
    );
    $form['options']['design']['contact']['contact_about_link'] = array(
        '#type' => 'textfield',
        '#title' => 'Link',
        '#default_value' => theme_get_setting('contact_about_link'),
        '#description' => 'Show in header : Default + Language Dropdown',
    );
    $form['options']['design']['contact']['contact_about'] = array(
        '#type' => 'textfield',
        '#title' => 'Title',
        '#default_value' => theme_get_setting('contact_about'),
        '#description' => 'Show in header : Default + Language Dropdown',
    );
    $form['options']['design']['contact']['contact_us_link'] = array(
        '#type' => 'textfield',
        '#title' => 'Link',
        '#default_value' => theme_get_setting('contact_us_link'),
        '#description' => 'Show in header : Default + Language Dropdown',
    );
    $form['options']['design']['contact']['contact_us'] = array(
        '#type' => 'textfield',
        '#title' => 'Title',
        '#default_value' => theme_get_setting('contact_us'),
    );

    $form['options']['design']['your_css'] = array(
        '#type' => 'textarea',
        '#title' => 'Add Your Css',
        '#default_value' => theme_get_setting('your_css'),
    );

}