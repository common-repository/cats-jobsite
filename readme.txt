=== Plugin Name ===
Contributors: catssoft
Tags: jobs, career, cats, jobsite, widget, employer, listing, ats, hr, recruiting
Requires at least: 2.6
Tested up to: 3.4.1
Stable tag: 1.4.5

Adds a careers page where visitors can view and apply to your jobs. Connects to the CATS Applicant Tracking System.

== Description ==

Adds job listings and allows visitors to search, register and apply to jobs on your WordPress site. Job applications can be customized and triggers can be added to perform actions based on applicant responses.

Requires a CATS Applicant Tracking System account as most of the functionality relies on their publicly available API. All jobs on the listings page are pulled in from your published jobs and use the job applications that you create. All visitor job applications are sent back to CATS which fires email notifications and adds them to your database.

Job Listings:

* Searchable/Sortable
* Retrieves public jobs from CATS with formatted descriptions
* Send-to-friend and apply-now functionality

Job Applications:

* Customizable questions and question types: checkboxes, text, file uploads, drop-down, etc..
* Triggers perform actions based on responses
* Duplicate detection

Sidebar Widgets:

* Login form
* List Hot Jobs
* List Recently Viewed Jobs

Applicant Self-Service:

* They can register for a login/password
* They can update their contact information or upload a new resume
* "Magic Preview" allows them to view their formatted resume on the web (w/out Word or PDF viewer)

CATS Integration:

* Create, delete and manage your jobs and job applications
* Set the fields for the job listings and the default sort order
* Track candidate applications though the hiring process
* Create reports and track the source of new applicants

== Installation ==

Installation is pretty simple:

1. Download `CATS_JobSite.zip`
1. Upload the file to your web server, and unzip the contents to the `/wp-content/plugins/` directory.
1. Make sure the `/wp-content/plugins/cats-jobsite/cache` directory is owned and writable by the web server user.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Browse to the 'Settings' menu in WordPress, and click 'CATS JobSite' to view the options.
1. To install the sidebar widget, browse to the 'Themes' menu, then 'Widgets' and drag 'CATS JobSite' onto a bar.

== Frequently Asked Questions ==

= I'm getting a "CURL Error: can't connect to https:// ..." =

This can happen if you enter a URL into the Company ID field in Settings. For example, if you enter: http://acme.catsone.com instead of just "acme". Login to WordPress admin, click on Settings, then CATS JobSite. Make sure the first field, Company ID, is set to the short name only as per the instructions there.

= I'm getting a "Permission Denied" error after installing. =

This is because the `/wp-content/plugins/cats-jobsite/cache` directory isn't owned and writable by the web server user. This can be accomplished on a Linux server by running a command similar to:

`
$ cd /path/to/wordpress/wp-content/plugins/cats-jobsite/cache
$ chown -R www-data .
$ chmod -R u+rw .
`

The default web server user is 'www-data' on Ubuntu, you should modify it accordingly if you're using another distro.

= The error messages contain a lot of information and traces. I don't want my visitors to see that. =

Backtrace and detailed debugging information is only shown if you're logged in as a WordPress administrator. Ordinary users will be simple, friendlier error messages.

= Can I change the layout? =

Yes. The minimal stylesheet is located at `/wp-content/plugins/cats-jobsite/style.css`. The templates are located in `/wp-content/plugins/cats-jobsite/templates`.

= Can I make changes to the code or add additional functionality? =

Yes. The plugin is licensed under the GPL v3 which allows you to modify it as you like. See the file `/wp-content/plugins/cats-jobsite/COPYING` for a copy of the license.

= Does it require a CATS account? =

Yes. CATS is used as the database for storing applications and retrieving jobs and is used to perform much of the logic and customization. You must have a valid CATS account and transaction key in order for the plugin to function properly.

= What versions of CATS will the plugin work with?  =

CATS Professional and the free CATS free trial hosted versions.

= Will it work with the open source version of CATS? Such as 0.9.1?  =

No. The open source version does not include an API or the website features required to power the plugin.

= Are there any restrictions when connecting to CATS through the API? =

Yes. There are limitations to the CATS Applicant Tracking System public API. One of these is 1,000 connections per day (which can be increased if you contact the support team). A list can be found in their documentation here: [Documentation](http://www.catsone.com/api/help.php?code=php5&file=README)

= How can I setup the plugin to use a different language? =

See the "lang/HOWTO.txt" file for more instructions about how to translate the plugin into another language. Your language may already be supported. If not, consider helping the community by writing a translation file.

== Screenshots ==

1. Jobs Listing

== Changelog ==

= 1.2.2 =
* First public release

= 1.2.3 =
* Cleaned up the documentation a bit and added some tags.

= 1.3.0 =
* Implemented i8n support through WordPress Gettext functions.

= 1.3.2 =
* Fixed issue with cookie ID generation causing a hang.

= 1.3.3 =
* BUG FIX: show controller would loop indefinitely (recursion) when # recent jobs is set to 0.

= 1.3.4 =
* Add top jobs, recent jobs and profile/login individual widgets.

= 1.3.5 =
* Enabled support for "mytemplates" and "mystyle.css" which are update-proof.
* Enabled Dutch language translations.

= 1.3.6 =
* See lang/HOWTO.txt for instructions on how to setup translations for your language.
* Added Hebrew/Dutch language support.

= 1.3.8 =
* Fixed issue with "Apply to Job" and "Send to Friend" buttons not working in IE 7/8.
* Added exception if plugin cannot write session files to the cache directory.

= 1.3.9 =
* Added RSS auto-discovery link tag to header.

= 1.4.0 =
* Fixed issue with domains having pair TLDs (i.e.: .co.uk) not generating valid cookies.

= 1.4.2 =
* Fixed permission errors if installing on a virtual private server.

= 1.4.5 =
* Fixed bug related to multiple checkbox fields when required.

== Upgrade Notice ==

= 1.2.2 =
First public release.

= 1.2.3 =
Cleaned up the documentation a bit and added some tags.

= 1.3.0 =
Implements Gettext i8n support.

= 1.3.2 =
IMPORTANT: Check your wp-content/plugins directory for a folder named "CATS_JobSite". If it exists, remove it. Install 1.3.2 which will be contained in a folder named "cats-jobsite" as per WordPress standards. Your settings won't be lost as they are saved in WordPress itself.

= 1.3.4 =
If you'd prefer to configure individual sidebar widgets for top jobs, recent jobs and profile/login this is now available. For backwards compatability, we've kept the existing all-in-one widget.

= 1.3.5 =
If you're customizing the templates/ and style.css folder/files and still want to be able to upgrade the plugin without losing your changes: copy the "templates" folder and name the copy "mytemplates". Copy the "style.css" file and name the copy "mystyle.css". If these files exist, they will be used instead of the default files and they will not be overwritten by an update.

If you're a Dutch user and want to enable the Dutch language file, copy cats-jobsite-nl_NL.mo to the wp-content/plugins folder, set your domain to catsone.nl (in Settings -> CATS JobSite) and finally make sure WPLANG is set to "nl_NL".

