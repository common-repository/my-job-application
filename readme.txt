=== My Job Application ===
Contributors: customnetware.com
Support link: http://customnetware.com
Donate link: http://customnetware.com
Tags: job listings indeed.com database API search resume
Requires at least: 3.0.1
Tested up to: 3.0.1
Stable tag: Trunk

Utilizes the Indeed.com API XML Feed. 

== Description ==

My Job Application is a Wordpress plugin developed using PHP and MySQL. It provides a simple way of using the Indeed.com API feed in your Wordpress blog. It also allows you to create a job database using your own job sources. Installation is easy: unzip the downloaded file and upload it to your plugins directory. Activate the the plugin, then go to the settings page to configure. 

== Installation ==

These installation instructions for My Job Application assumes that you are already somewhat familiar with the operation of WordPress.  Install and activate the plugin. Go to Settings > My Job Application and enter your Indeed.com publisher's ID.

= Updating =

The best way to upgrade is to use the 'Upgrade Now' button on your plugins page (if your hosting situation is set up to handle that), or by following these steps:

1. Backup your database.
2. Deactivate My Job Application.
3. Delete the old version of My Job Application from your plugins directory (typically `wp-content/plugins`) via ftp.
4. Download the new version of the My Job Application from http://wordpress.org/extend/plugins/my-job-application/ and unzip it (Unzipping the downloaded zip file should produce a folder named `my-job-application`, and upload it to your plugins directory.
5. Reactivate My Job Application.


== Frequently Asked Questions ==

= Where can I find more detailed instructions on how to use this plugin? =
Go to  http://customnetware.wordpress.com and post a question or read the posts by the administrator.

For other plugins or custom web software solutions visit http://customnetware.com


== Screenshots ==

== Changelog ==

= 1.40 =
12/08/2010 - The application now uses cURL to retrive the XML file from Indeed.com.  SimpleXML is used to parse the file.  Registered users of the blog can save preferences.

= 1.30 = 
10/09/2010 - Change form layout.  Added the requirement for job page url.  Use {my_job_application} on pages or posts.

= 1.22 = 
10/09/2010 - Correct the hard coded "posted by" value.  The poster will be the name of the blog.

= 1.21 = 
10/08/2010 - Reverse previous fix, no problem with the url.  Change required and tested wp versions.

= 1.21 = 
10/08/2010 - Fixed problem where there is a "?" in the wordpress page url.

== Upgrade Notice ==

= 1.40 =
The application now uses cURL to retrive the XML file from Indeed.com.  This will help if you are receiving an error message.

= 1.30 =
Change form layout.  Added the requirement for job page url.  Use {my_job_application} on pages or posts.

= 1.22 =
Correct the hard coded "job posted by" value.  The poster will be the name of the blog when using the custom job listing.