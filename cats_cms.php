<?php
/*
Plugin Name: CATS JobSite
Plugin URI: http://www.catsone.com/
Description: Adds a jobs page to your WordPress site where candidates can search, view and apply to your open jobs. They can register with a password so they can update their resume and information in the future. Widgets show top and recently viewed jobs. Integrates with the CATS Application Tracking System through its public API.
Author: CATS Software
Version: 1.4.5
*/

/**
 * @package CATS_JobSite
 * @author Andrew P. Kandels
 * @copyright 2009 - 2010 CATS Software, Inc.
 *
 * Wordpress Plug-in
 *
 * Registers the internal CATS_JobSite framework and hooks it into
 * Wordpress.
 *
 * This file is part of CATS JobSite.
 *
 * Copyright (C) 2009 - 2010 CATS Software, Inc.
 *
 * CATS JobSite is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * CATS JobSite is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with CATS JobSite in a file named "COPYING" in the root directory.
 * If not, see <http://www.gnu.org/licenses/>.
 */

/* Enable to display error messages on screen (not recommended for production) */
//ini_set('error_reporting', E_ALL);

require_once(dirname(__FILE__) . '/wrapper.php');

/**
 * Wordpress "plugins_loaded" action
 *
 * Includes the CATS_JobSite framework, instantiates the session object into a
 * global variable and registers widgets with Wordpress.
 *
 * @return void
 */
function cats_cms_onload()
{
    /**
     * jQuery Validation Plug-in (minified/packed)
     * http://bassistance.de/jquery-plugins/jquery-plugin-validation/
     */
    wp_enqueue_script(
        'jqueryvalidation',
        dirname(dirname(get_bloginfo('template_url')))
            . '/plugins/cats-jobsite/js/jquery-validate/jquery.validate.min.js',
        array('jquery'),
        false,
        true
    );

    wp_enqueue_script(
        'catsjobsite',
        dirname(dirname(get_bloginfo('template_url')))
            . '/plugins/cats-jobsite/js/cats-jobsite.js',
        array('jquery'),
        false,
        true
    );

    /* Registers our sidebar widget */
    wp_register_sidebar_widget(
        'cats_cms_widget',
        'CATS Sidebar',
        'cats_cms_widget',
        array(
            'classname' => 'cats_cms_widget',
            'description' => 'Combination of "Top Jobs", "Recent Jobs" and "Profile" JobSite '
                . 'widgets which are activated logically based on which page the visitor is '
                . 'viewing and whether or not they\'re logged in.'
        )
    );

    /* "Top Jobs" individual widget */
    wp_register_sidebar_widget(
        'cats_cms_widget_topjobs',
        'Top Jobs',
        'cats_cms_widget_topjobs',
        array(
            'classname' => 'cats_cms_widget_topjobs',
            'description' => 'Displays your most recently posted jobs. The number to show can be '
                . 'configured in Settings -> CATS JobSite -> # of Top Jobs.'
        )
    );

    /* "Recent Jobs" individual widget */
    wp_register_sidebar_widget(
        'cats_cms_widget_recentjobs',
        'Recent Jobs',
        'cats_cms_widget_recentjobs',
        array(
            'classname' => 'cats_cms_widget_recentjobs',
            'description' => 'Displays recent jobs the visitor has viewed. The number to show can be '
                . 'configured in Settings -> CATS JobSite -> # of Recent Jobs.'
        )
    );

    /* "Login Page / Profile" individual widget */
    wp_register_sidebar_widget(
        'cats_cms_widget_profile',
        'JobSite Login/Profile',
        'cats_cms_widget_profile',
        array(
            'classname' => 'cats_cms_widget_profile',
            'description' => 'Displays a login form for returning applicants to login and view '
                . 'the status of their job applications.'
        )
    );
}

/**
 * Wordpress "the_content" action
 *
 * If we're viewing our special page, then replace or modify page content with
 * CATS_JobSite framework controller's output.
 *
 * @param   string      Page content
 * @return  string      New content
 */
function cats_cms_director($content)
{
    /* Wordpress object storing page/post info */
    global $post;
    global $catsPageID;

    if ($post->post_type == 'page' && $post->ID == $catsPageID)
    {
        $content = CATS_Utility::getWrapper()->getContent($content);
    }

    return $content;
}

/**
 * Wordpress "wp_print_styles" action
 *
 * Add our CSS Stylesheet.
 *
 * @return void
 */
function cats_cms_stylesheets()
{
    $id = 'cats_cms_stylesheet';

    /* Check for custom style-sheet (upgrade safe) */
    if (false === file_exists($file = sprintf('%s/cats-jobsite/mystyle.css', WP_PLUGIN_URL)))
    {
        $file = sprintf('%s/cats-jobsite/style.css', WP_PLUGIN_URL);
    }

    wp_register_style($id, $file);
    wp_enqueue_style($id);
}

/**
 * Wordpress action "wp_pre_posts"
 *
 * Check for our special page, add it if it doesn't exist.
 *
 * @return void
 */
function cats_cms_page()
{
    global $catsPageID;

    /* We save the page as an option to prevent a bigger query each page load */
    $postID = get_option($option = 'cats_cms_postid', false);

    /* Option exists, but does the page exist? */
    if (!empty($postID))
    {
        $post = get_page($postID);
        if (empty($post)) $postID = 0;
    }

    /* If no page exists, we need to add it */
    if (empty($postID))
    {
        global $user_ID;

        $postID = wp_insert_post(array(
            'post_title' => 'Jobs',
            'post_content' => ' ',
            'post_status' => 'publish',
            'post_author' => $user_ID,
            'post_type' => 'page',
            'post_parent' => 0,
            'guid' => 'cats_cms',
            'comment_status' => 'closed',
            'ping_status' => 'closed'
        ));

        if (empty($postID))
        {
            printf('<h3>CATS CMS Plugin Error</h3><p>Failed to create jobs page.');
        }
        else
        {
            update_option($option, $postID);
        }
    }

    $catsPageID = $postID;
}

/**
 * Widget callback for our sidebar.
 *
 * @param   object      Arguments
 * @return  void
 */
function cats_cms_widget($args)
{
    if (is_object($args))
    {
        printf('%s%s%s', $args->before_widget, $args->before_title, $args->after_title);
    }
    CATS_Utility::loadController('sidebar');
}

/**
 * Individual sidebar widget for "Top Jobs".
 *
 * @param   object      Arguments
 * @return  void
 */
function cats_cms_widget_topjobs($args)
{
    if (is_object($args))
    {
        printf('%s%s%s', $args->before_widget, $args->before_title, $args->after_title);
    }
    CATS_Utility::loadController('sidebartopjobs');
}

/**
 * Individual sidebar widget for "Recent Jobs".
 *
 * @param   object      Arguments
 * @return  void
 */
function cats_cms_widget_recentjobs($args)
{
    if (is_object($args))
    {
        printf('%s%s%s', $args->before_widget, $args->before_title, $args->after_title);
    }
    CATS_Utility::loadController('sidebarrecentjobs');
}

/**
 * Individual sidebar widget for "Profile / Login".
 *
 * @param   object      Arguments
 * @return  void
 */
function cats_cms_widget_profile($args)
{
    if (is_object($args))
    {
        printf('%s%s%s', $args->before_widget, $args->before_title, $args->after_title);
    }
    CATS_Utility::loadController('sidebarprofile');
}

/**
 * Registers a hidden footer for SEO.
 *
 * @param   string      Page contents
 */
function cats_cms_footer($contents)
{
    CATS_Utility::loadController('globalfooter');
}

/**
 * Wordpress action "admin_init"
 *
 * Creates a new settings section to configure CATS_JobSite.
 *
 * @return void
 */
function cats_cms_settings()
{
    /* Register our settings */
    register_setting(
        'cats_cms_options',             // Group Name (same as settings_fields in options.tpl.php)
        'cats_cms_options',             // Name of the options
        'cats_cms_options_validate'     // Callback for validating user input
    );

    include(dirname(__FILE__) . '/settings_fields.php');
}

function cats_cms_add_options_page()
{
    /* Create an options page in Wordpress -> Admin -> Setup */
    add_options_page(
        'CATS JobSite Configuration',   // Title
        'CATS JobSite',                 // Menu Title
        'manage_options',               // Access Level
        'CATS_JobSite',                 // Plugin Name
        'cats_cms_options_page'         // Callback
    );
}

function cats_cms_options_page()
{
    CATS_Utility::loadController('options');
}

function cats_cms_head()
{
    CATS_Utility::getWrapper()->printHeadIncludes();
}

/* Register Wordpress hooks for above functions */
add_action('plugins_loaded', 'cats_cms_onload');
add_action('the_content', 'cats_cms_director');
add_action('wp_print_styles', 'cats_cms_stylesheets');
add_action('pre_get_posts', 'cats_cms_page');
add_action('wp_footer', 'cats_cms_footer');
add_action('admin_menu', 'cats_cms_add_options_page');
add_action('admin_init', 'cats_cms_settings');
add_action('wp_head', 'cats_cms_head');

