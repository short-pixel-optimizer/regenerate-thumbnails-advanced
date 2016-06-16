=== reGenerate Thumbnails Advanced ===
Contributors: turcuciprian
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=MU4RJNNF74QKY
Tags: regenerate, thumbnails, advanced, easy, day, weeek, month
Requires at least: 3.1
Tested up to: 4.4.1
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Description: A plugin that makes regenerating thumbnails even easier than before and more flexible.

== Description ==

Built with : http://admin-builder.com


A plugin that makes regenerating thumbnails even easier than before and more flexible.
If you install a new theme, it might have different image sizes it wants to use. those image sizes are cropped and resized into thumbnails only when you upload new images. So what happens with your old images?
the ones you already uploaded? They need to be regenerated, this is what this plugin does. It takes each image and generates the thumbnails for each and every one of them.


Features:

*   You can select a period in time for the images to be regenerated from
*   The period is last day, last week, last month or all
*   Clean simple interface where you have a progress bar showing you the percentage of images regenerated
*   You get to see the total number of images affected by your period, as well as the images afected so far when processing
*   Regenerate thubnails on the fly

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload regenerate-thumbnails-advanced directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Can I regenerate just a few images =

You have the option to select from: all, past day, past week, past month

= What happens if I close the page and the script is loading? =

The script stops, it does not run in the background

== Screenshots ==

1. The plugin page where it all happens


== Changelog ==

= 0.7 =
* The first upload of the plugin

= 0.8 =
*Shows errors
*GD library error missing library present
*added jquery ui images for
*added jquery ui min css file
*removed the incrementation of processed images if there are none (it was showing one was being processed when it wasn't)

= 0.8.1 =
*images did not get added in the previous version. Neither did the css file for jquery ui

= 0.8.2 =
*log section showing what's been processed (image name)

= 0.8.2.1 =
*Images processed gets updated on regenerate request (it was frozen before if no images where available to be processed)
*Added a extra image

= 0.8.2.2 =
*Added a "no js or js error" message to the page if it's the case

= 0.8.2.3 =
*Progress and errors aligned with containers
*javascript changed so that it shows processing for errors when processing starts

= 0.8.2.4 =
*Added time (in seconds) to each processed image - You can see how long each image took
*Error log status changes at the end from Processing to "No errors" if there are no errors.

= 0.8.2.5 =
*Added Settings link in plugins info page
*Renamed submenu page to Regenerate Thumbnails under Settings

= 0.8.2.6 =
*Added the possibility to choose a start and end date from where to regenerate the thumbnails from
* Added new style
* Added datepickers

= 1.0 =
*Regenerate on the fly

= 1.0.1 =
Removed unnecesary functionality. Just the checkbox for OTF is now available.

= 1.0.2 =
Fixes a critical bug from the previous version

= 1.0.3 =
Added donate button

= 1.1 =
Added Admin builder plugin dependency and functionality.

=1.1.2=
Important bugfixes

=1.1.3=
Important bugfixes

=1.1.4=
Extra bugfixes

=1.1.5=
admin-builder.php turned to admin_builder.php on admin builder verification check

=1.1.6=
Admin builder export functionality update

=1.1.7=
Admin builder export functionality update (again) Fixed a issue in the notice admin message

=1.1.8=
Admin builder export file update

=1.1.9=
Admin builder export file update

= 1.2 =
Removed admin_builder dependency and included the plugin inside the Scroll To Top core, so you are no longer required to install an extra plugin.


== Upgrade Notice ==

= 0.7 =
No issues have been detected since this is the first version that's out there. Please report any issues

= 0.8 =
No issuse with this version that require attention

= 0.8.1 =
This is a very minor change . Practically fixing the previous change incomplete commit

= 0.8.2 =
No issuse with this version that require attention

= 0.8.2.1 =
No issuse with this version that require attention. Small update change

= 0.8.2.2 =
No issuse with this version that require attention. Small update change. It's basically a feature, so unless you want it, the older version works great.

= 0.8.2.3 =
No issuse with this version that require attention. Small update change. It's basically a feature, so unless you want it, the older version works great.

= 0.8.2.4 =
No issuse with this version that require attention. Small update change. It's basically a feature, so unless you want it, the older version works great.

= 0.8.2.5 =
No issuse with this version that require attention. Small update change. It's basically a feature, so unless you want it, the older version works great.

= 0.8.2.6 =
This version changes the style and adds a bit of functionality to the general settings (a date start-end option to choose from)

= 1.0 =
This version changes the style and adds a bit of functionality to the general settings (a date start-end option to choose from)

= 1.0.1 =
nothing that affect the previous data is changed. Update is save

= 1.0.2 =
nothing that affect the previous data is changed. Update is save

= 1.0.3 =
nothing that affect the previous data is changed. Update is save

= 1.1 =
Database field is saved someplace else now. It needs to be configured again. Basically just go into settings, click to activate and click save.

=1.1.2=
Important bugfixes

=1.1.3=
Important bugfixes

=1.1.4=
Extra bugfixes

=1.1.5=
nothing effected in db

=1.1.6=
nothing effected in db

=1.1.7=
nothing effected in db

=1.1.8=
nothing effected in db

=1.1.9=
nothing effected in db
