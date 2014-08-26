=== Advanced Google Analytics Tracking for WordPress ===

Contributors: AramZS, robflaherty, cfo-publishing
Tags: analytics, Google Analytics, tracking, engagement, testing, scroll depth, time engaged, viewability
Requires at least: 2.7
Tested up to: 3.9.1
Stable tag: 1.0.2
License: GPL2

Creates user controls to allow tracking of detailed engagement metrics with Google Analytics.

== Description ==

The Advanced Google Analytics Tracking plugin allows users to set and track a variety of events that indicate engagement and send those metrics to Google Analytics. 

* Time user spends actively engaging the site (clicking, scrolling, using the keyboard).
* Depth to which users scroll on a page, as either a percentage or a pixel value.
* Viewability of individual elements, defined with your own number of seconds on element and percentage of element visible.
* Clicks on individual elements on the page. Great for A/B testing!

You can contribute to this project at our [GitHub](https://github.com/CFOPublishing/wp-advanced-ga-tracking).

This plugin rolls in the Rivited plugin and will not work if you are already running that plugin. 

== Installation == 

* Insure that the Rivited plugin is not already installed.
* Install and activate this plugin.
* Configure click and viewability tracking in your Tools menu - http://yoursite.com/wp-admin/tools.php?page=agatt-menu
* Configure engagement time tracking in your Settings menu - http://yoursite.com/wp-admin/options-general.php?page=riveted
* You're good to go! 

Note: *You must already have your Google Analytics code active on page.*

This plugin is not yet compatible with Google's Universal Analytics, but soon will be.

== Frequently Asked Questions ==

= Will this plugin transition all my selections to Google's Universal Analytics when that functionality is ready? =

Yes, that's the plan. 

= Why are the menus on different pages? =

The two menus are due to two plugins rolled together. They will eventually be unified, while keeping your options in place. 

= Why did my site crash when I tried to install this plugin? =

You probably had the Rivited plugin installed and active. 

== Changelog ==

= 1.0.1 =
* Rolled in Rivited Plugin, with permission from Rob Flaherty.
