<?php
/**
 * ACCOUNT SETTINGS
 */
add_settings_section(
    $sec = 'cats_cms_account',      // Unique ID for the section
    'Account Settings',             // Title of the section
    'cats_cms_account_desc',        // Callback to display section content
    $page = 'cats_cms_plugin'       // Page name (from do_settings_sections)
);

    /* Describe the section */
    function cats_cms_account_desc()
    {
        echo 'Sets your CATS API credentials.';
    }

    /* Add the section fields */
    add_settings_field($name = 'cats_cms_company_id', 'Company ID', $name, $page, $sec);
    function cats_cms_company_id()
    {
        $opt = 'company_id';
        $value = CATS_Utility::getOption($opt);
        $opt = 'cats_cms_' . $opt;
        echo '<input name="cats_cms_options[' . $opt . ']" size="20" '
            . 'type="text" value="' . $value . '"/> ';
        echo '<span class="description">When logged into CATS, this value shows up on the upper '
            . 'left corner of the screen near the product logo. Enter the text in <strong>bold</strong> '
            . 'only. For example, for <i>http://<strong>acme</strong>.catsone.com</i> you would '
            . 'enter ONLY "acme".</span>';
    }

    add_settings_field($name = 'cats_cms_trans_code', 'Transaction Code', $name, $page, $sec);
    function cats_cms_trans_code()
    {
        $opt = 'trans_code';
        $value = CATS_Utility::getOption($opt);
        $opt = 'cats_cms_' . $opt;
        echo '<input name="cats_cms_options[' . $opt . ']" size="35" '
            . 'type="text" value="' . $value . '"/> ';
        echo '<span class="description">Serves as a password. Get a code by logging into CATS, clicking '
            . 'on the Settings tab, then "User Management".</span>';
    }

    add_settings_field($name = 'cats_cms_url', 'External Job URL (CATS)', $name, $page, $sec);
    function cats_cms_url()
    {
        $postID = get_option($option = 'cats_cms_postid', false);

        $opt = 'url';

        printf('<input type="text" size="60" value="http://%s%s?cc=show&page_id=%s&id=%%JOBORDER_ID%%" '
            . 'onclick="this.select();" readonly/> '
            . '<span class="description">Paste this into "CATS -> Settings -> General Configuration '
            . '-> API / Custom Careers Website URL" to have all your jobs and RSS feeds point '
            . 'here.',
            $_SERVER['HTTP_HOST'],
            preg_replace('!/wp-admin/.*!', '/', $_SERVER['PHP_SELF']),
            $postID
        );
    }

/**
 * CONNECTION SETTINGS
 */
add_settings_section(
    $sec = 'cats_cms_connection',   // Unique ID for the section
    'Connection Settings',          // Title of the section
    'cats_cms_connection_desc',     // Callback to display section content
    'cats_cms_plugin'               // Page name (from do_settings_sections)
);

    /* Describe the section */
    function cats_cms_connection_desc()
    {
        echo 'Sets how to connect to CATS.';
    }

    /* Add the section fields */
    add_settings_field($name = 'cats_cms_domain', 'Domain', $name, $page, $sec);
    function cats_cms_domain()
    {
        $opt = 'domain';
        $value = CATS_Utility::getOption($opt);
        $opt = 'cats_cms_' . $opt;
        echo '<select name="cats_cms_options[' . $opt . ']">';
        echo '<option value="catsone.com"' . ($value == 'catsone.com' ? ' selected' : '') . '>catsone.com</option>';
        echo '<option value="catsone.nl"' . ($value == 'catsone.nl' ? ' selected' : '') . '>catsone.nl</option>';
        echo '</select> ';
        echo '<span class="description">If you connect to CATS through a domain other than '
            . 'www.catsone.com.</span>';
    }

    add_settings_field($name = 'cats_cms_ssl', 'Enable SSL', $name, $page, $sec);
    function cats_cms_ssl()
    {
        $opt = 'ssl';
        $value = CATS_Utility::getOption($opt);
        $opt = 'cats_cms_' . $opt;
        echo '<input type="checkbox" value="yes" name="cats_cms_options[' . $opt . ']"'
            . (!empty($value) ? ' checked' : '') . '/> Enable 128-bit SSL encryption '
            . '(slight speed decrease)';
    }

    add_settings_field($name = 'cats_cms_hash', 'Password Hash', $name, $page, $sec);
    function cats_cms_hash()
    {
        $opt = 'hash';
        $value = CATS_Utility::getOption($opt);
        $opt = 'cats_cms_' . $opt;

        echo '<select name="cats_cms_options[' . $opt . ']">';
        echo '<option value="">None</option>';
        echo '<option value="md5-salt"' . ($value == 'md5-salt' ? ' selected' : '') . '>MD5 (with salt)</option>';
        echo '<option value="md5"' . ($value == 'md5' ? ' selected' : '') . '>MD5</option>';
        echo '<option value="sha1"' . ($value == 'sha1' ? ' selected' : '') . '>SHA-1</option>';
        echo '<option value="crc32"' . ($value == 'crc32' ? ' selected' : '') . '>CRC32</option>';
        echo '</select> ';
        echo '<span class="description">Recommended when SSL is disabled. Hashes plain text candidate '
            . 'passwords. Requires PHP mcrypt library.</span>';
    }

    add_settings_field($name = 'cats_cms_timeout', 'Timeout', $name, $page, $sec);
    function cats_cms_timeout()
    {
        $opt = 'timeout';
        $value = CATS_Utility::getOption($opt);
        $opt = 'cats_cms_' . $opt;
        echo '<input name="cats_cms_options[' . $opt . ']" size="5" '
            . 'type="text" value="' . $value . '"/> ';
        echo '<span class="description">Maximum time to wait (in seconds) when connecting to CATS before an '
            . 'error is thrown or an old cache is used.</span>';
    }

    add_settings_field($name = 'cats_cms_cache', 'Cache Time-to-Live', $name, $page, $sec);
    function cats_cms_cache()
    {
        $opt = 'cache';
        $value = CATS_Utility::getOption($opt);
        $opt = 'cats_cms_' . $opt;
        echo '<input name="cats_cms_options[' . $opt . ']" size="5" '
            . 'type="text" value="' . $value . '"/> ';
        echo '<span class="description">Number of seconds to cache responses for before new data is '
            . 'requested. It is highly recommended to set this value higher than 300 (5 minutes). A '
            . 'good default value is 600 (10 minutes).</span>';
    }

/**
 * DISPLAY SETTINGS
 */
add_settings_section(
    $sec = 'cats_cms_display',      // Unique ID for the section
    'Display Settings',             // Title of the section
    'cats_cms_display_desc',        // Callback to display section content
    'cats_cms_plugin'               // Page name (from do_settings_sections)
);

    /* Describe the section */
    function cats_cms_display_desc()
    {
        echo 'Sets certain display preferences.';
    }

    add_settings_field($name = 'cats_cms_sidebar', 'Enable Sidebar', $name, $page, $sec);
    function cats_cms_sidebar()
    {
        $opt = 'sidebar';
        $value = CATS_Utility::getOption($opt);
        $opt = 'cats_cms_' . $opt;
        echo '<input type="checkbox" value="yes" name="cats_cms_options[' . $opt . ']"'
            . (!empty($value) ? ' checked' : '') . '/> Shows login, recently viewed and other '
            . 'items in a sidebar widget.';
    }

    add_settings_field($name = 'cats_cms_jobsperpage', 'Jobs Per Page', $name, $page, $sec);
    function cats_cms_jobsperpage()
    {
        $opt = 'jobsperpage';
        $value = CATS_Utility::getOption($opt);
        $opt = 'cats_cms_' . $opt;
        echo '<input name="cats_cms_options[' . $opt . ']" size="5" '
            . 'type="text" value="' . $value . '"/> ';
        echo '<span class="description">Number of jobs per page when viewing the job listings.</span>';
    }

    add_settings_field($name = 'cats_cms_recentjobs', '# of Recent Jobs', $name, $page, $sec);
    function cats_cms_recentjobs()
    {
        $opt = 'recentjobs';
        $value = CATS_Utility::getOption($opt);
        $opt = 'cats_cms_' . $opt;
        echo '<input name="cats_cms_options[' . $opt . ']" size="5" '
            . 'type="text" value="' . $value . '"/> ';
        echo '<span class="description">Number of "Recently Viewed Jobs" to show on the sidebar.</span>';
    }

    add_settings_field($name = 'cats_cms_topjobs', '# of Top Jobs', $name, $page, $sec);
    function cats_cms_topjobs()
    {
        $opt = 'topjobs';
        $value = CATS_Utility::getOption($opt);
        $opt = 'cats_cms_' . $opt;
        echo '<input name="cats_cms_options[' . $opt . ']" size="5" '
            . 'type="text" value="' . $value . '"/> ';
        echo '<span class="description">Number of "Top Viewed Jobs" to show on the sidebar.</span>';
    }

    add_settings_field($name = 'cats_cms_excerpt', 'Description Excerpt', $name, $page, $sec);
    function cats_cms_excerpt()
    {
        $opt = 'excerpt';
        $value = CATS_Utility::getOption($opt);
        $opt = 'cats_cms_' . $opt;
        echo '<input name="cats_cms_options[' . $opt . ']" size="5" '
            . 'type="text" value="' . $value . '"/> ';
        echo '<span class="description">Number of characters to show when truncating a job\'s description.</span>';
    }

    add_settings_field($name = 'cats_cms_attribution', 'Attribution', $name, $page, $sec);
    function cats_cms_attribution()
    {
        $opt = 'attribution';
        $value = CATS_Utility::getOption($opt);
        $opt = 'cats_cms_' . $opt;

        echo '<select name="cats_cms_options[' . $opt . ']">';
        echo '<option value="0"' . ($value == 0 ? ' selected' : '') . '>None</option>';
        echo '<option value="1"' . ($value == 1 ? ' selected' : '') . '>Text</option>';
        echo '<option value="2"' . ($value == 2 ? ' selected' : '') . '>Image</option>';
        echo '</select> ';
        echo '<span class="description">By using this plug-in, we ask that you provide attribution back '
            . 'to CATS.</span><br /><br />';
    }


/**
 * VALIDATION
 */
function cats_cms_options_validate($input)
{
    $return = array();

    if (isset($input[$id = 'cats_cms_trans_code']))
    {
        $value = trim($input[$id]);
        if (preg_match('/[a-z0-9]/', $value) && strlen($value) < 40)
        {
            $return[$id] = $value;
        }
    }

    if (isset($input[$id = 'cats_cms_company_id']))
    {
        $value = trim($input[$id]);
        if (preg_match('/[a-z0-9]/', $value) && strlen($value) < 40)
        {
            $return[$id] = $value;
        }
    }

    if (isset($input[$id = 'cats_cms_domain']))
    {
        $value = trim($input[$id]);

        if (in_array($value, array('catsone.com', 'catsone.nl', 'catswebsite.com', 'catsbeta.com')))
        {
            $return[$id] = $value;
        }
    }

    if (isset($input[$id = 'cats_cms_ssl']) && $input[$id] == 'yes')
    {
        $return[$id] = true;
    }
    else
    {
        $return[$id] = false;
    }

    if (isset($input[$id = 'cats_cms_hash']))
    {
        $value = trim($input[$id]);
        if (in_array($value, array('md5', 'md5-salt', 'sha1', 'crc32')))
        {
            $return[$id] = $value;
        }
        else
        {
            $return[$id] = '';
        }
    }

    if (isset($input[$id = 'cats_cms_timeout']))
    {
        $value = intval(trim($input[$id]));

        if ($value > 5 && $value < 90)
        {
            $return[$id] = $value;
        }
    }

    if (isset($input[$id = 'cats_cms_cache']))
    {
        $value = intval(trim($input[$id]));

        /* must be less than one week */
        if ($value < 60*60*24*7)
        {
            $return[$id] = $value;
        }
    }

    if (isset($input[$id = 'cats_cms_jobsperpage']))
    {
        $value = intval(trim($input[$id]));

        if ($value >= 1)
        {
            $return[$id] = $value;
        }
    }

    if (isset($input[$id = 'cats_cms_recentjobs']))
    {
        $value = intval(trim($input[$id]));

        if ($value >= 0)
        {
            $return[$id] = $value;
        }
    }

    if (isset($input[$id = 'cats_cms_topjobs']))
    {
        $value = intval(trim($input[$id]));

        if ($value >= 0)
        {
            $return[$id] = $value;
        }
    }

    if (isset($input[$id = 'cats_cms_excerpt']))
    {
        $value = intval(trim($input[$id]));

        if ($value >= 0)
        {
            $return[$id] = $value;
        }
    }

    if (isset($input[$id = 'cats_cms_attribution']))
    {
        $value = intval(trim($input[$id]));
        $return[$id] = $value;
    }

    if (isset($input[$id = 'cats_cms_sidebar']) && $input[$id] == 'yes')
    {
        $return[$id] = true;
    }
    else
    {
        $return[$id] = false;
    }

    CATS_Utility::clearCache();

    return $return;
}
