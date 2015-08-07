=== reGenerate Thumbnails Advanced ===
Contributors: turcuciprian
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=MU4RJNNF74QKY
Tags: regenerate, thumbnails, advanced, easy, day, weeek, month
Requires at least: 4.0 
Tested up to: 4.2.4
Stable tag: 0.8.2.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Description: A plugin that makes regenerating thumbnails even easier than before and more flexible.

== Description ==

A plugin that makes regenerating thumbnails even easier than before and more flexible.
If you install a new theme, it might have different image sizes it wants to use. those image sizes are cropped and resized into thumbnails only when you upload new images. So what happens with your old images? 
the ones you already uploaded? They need to be regenerated, this is what this plugin does. It takes each image and generates the thumbnails for each and every one of them. 


Features:

*   You can select a period in time for the images to be regenerated from
*   The period is last day, last week, last month or all
*   Clean simple interface where you have a progress bar showing you the percentage of images regenerated
*   You get to see the total number of images affected by your period, as well as the images afected so far when processing

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
2. View of the dropdown options
3. Where you find everything

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