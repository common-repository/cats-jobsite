<?php
/**
 * @package CATS_JobSite
 * @author Andrew P. Kandels
 * @copyright 2009 - 2010 CATS Software, Inc.
 *
 * Wrapper
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

/* Include the wrapper script which translates our requests into the framework */
include_once(dirname(__FILE__) . '/libs/Wrapper.php');

/* Creates our custom wrapper object which can be extended for integration into websites or CMS's */
class WordPressWrapper extends CATS_Wrapper
{
    public function __construct()
    {
        parent::__construct();

        $id = 'cats-jobsite';

        /* Loads our mock language file into the session (see getLocalizedString method) */
        $this->_session->loadMockLang('__("VALUE", "' . $id . '");');

        /* Allow the loading of .mo files for localization */
        load_plugin_textdomain('cats-jobsite');
    }

    /**
     * Returns a URL that points to a file relative to the plugin path.
     *
     * @param   string      Name of the file
     */
    public function getRelativeURL($name)
    {
        $path = preg_replace('!/wp-content/.*!', '', get_bloginfo('template_directory'));

        return sprintf('%s/wp-content/plugins/cats-jobsite/images/%s',
            $path,
            $name
        );
    }

    /**
     * Have to use JavaScript redirection since WordPress has already sent headers.
     *
     * @param   string      URI
     */
    public function redirect($uri)
    {
        $this->javaScriptRedirect($uri);
    }

    /**
     * Converts a URI to incorporate any saved parameters used by the external
     * website or CMS (if any).
     *
     * @param   string      URI
     * @return  string
     */
    public function getURI($str)
    {
        global $catsPageID;

        if (empty($str)) $str = 'page_id=' . $catsPageID;
        else $str .= '&page_id=' . $catsPageID;

        $params = CATS_Utility::getParams($_SERVER['QUERY_STRING']);
        $params = CATS_Utility::getParams($str, $params);

        foreach ($params as $name => $value)
        {
            if (empty($q)) $q = '?'; else $q .= '&';
            $q .= sprintf('%s=%s', $name, $value);
        }

        return sprintf('%s%s',
            $_SERVER['PHP_SELF'],
            !empty($q) ? $q : ''
        );
    }

    /**
     * Is the current user considered an administrator?
     *
     * @return  boolean
     */
    public function isAdmin()
    {
        return current_user_can('manage_options');
    }

    /**
     * Retrieves the value for a preconfigured option.
     *
     * @param   string      Option name (usually prefixed with "cats_cms_")
     * @return  mixed
     */
    public function getOption($name)
    {
        $name = 'cats_cms_' . $name;

        $options = get_option('cats_cms_options');

        if (isset($options[$name]))
        {
            return $options[$name];
        }

        switch ($name)
        {
            case 'cats_cms_hash': return '';
            case 'cats_cms_timeout': return 45;
            case 'cats_cms_cache': return 600;
            case 'cats_cms_jobsperpage': return 20;
            case 'cats_cms_recentjobs': return 5;
            case 'cats_cms_topjobs': return 5;
            case 'cats_cms_excerpt': return 150;
            case 'cats_cms_attribution': return 2;
            case 'cats_cms_sidebar': return true;
            default: return false;
        }
    }

    /**
     * Loads a controller based on the cc URL parameter.
     *
     * @return  string      Page contents
     */
    public function getContent()
    {
        $param = CATS_Utility::getPost('cc');

        if (false === ($content = CATS_Utility::getControllerOutput($param)))
        {
            $content = CATS_Utility::getControllerOutput();
        }

        return $content;
    }

    /**
     * Pass all strings through the WordPress Gettext function. This is
     * trickier than you think, because we can't just call __() with a
     * variable name. It needs to be typed string so the scanner can
     * detect all of our strings. Here's how we fix it:
     *
     * We mainain a "mock" PHP file of translated strings that looks
     * like:
     *
     * <?php _e("String #1"); ?>
     * <?php _e("String #2"); ?>
     *
     * On session load, we load this file and put it into a handy
     * array. If we get a request that doesn't exist, we add it
     * to the file.
     *
     * Bingo bango, we can now use Gettext and have our translations
     * get detected.
     *
     * @param   string      Text to translate
     */
    public function getLocalizedString($text)
    {
        /* Replace quotes for storage. */
        $text = str_replace('"', '&quot;', $text);

        /* Replace backslashes for storage. */
        $text = str_replace('\\', '&92;', $text);

        /* Standardize line returns */
        $text = preg_replace('/[\r\n]+/', "\n", $text);

        /* Make sure it exists in our mock language file so it can be detected */
        $this->_session->useMockLang($text);

        /* WordPress Gettext function */
        $text = __($text, 'cats-jobsite');

        /* Reinstate quotes */
        $text = str_replace('&quot;', '"', $text);

        /* Reinstate backslashes */
        $text = str_replace('&92;', '\\', $text);

        return $text;
    }
}

$cats_wrapper = new WordPressWrapper();
