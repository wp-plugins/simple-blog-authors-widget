=== Simple Blog Authors Widget ===
Author: Stanko Metodiev
Contributors: metodiew
Author URI: http://metodiew.com
Plugin URI: http://metodiew.com/projects/simple-blog-authors-widget/
Donate link: http://metodiew.com
Tags: widget, widgets, authors, blog authors, simple
Requires at least: 2.8
Tested up to: 4.3
Stable tag: 1.5.0

This plugin lets provides a simple widget to list your blog's authors, including gravatar and post counts

== Description ==

This plugin will give you a simple widget that you can use to display your blog's authors, including their gravatars and post counts.

The widget uses universal widget styling, so should fit perfectly into any WordPress theme.

Features

1. Blog Authors Widget
2. Widget Title Option
3. Option to enable author gravatars
4. Option to enable author post counts

== Installation ==

1. Upload the 'simple-blog-authors-widget' folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Simple Blog Authors Widget is gone after I updated to version 1.0.3? =

The class name was changed, so you should add the widget again from Appearance => Widgets.

== Screenshots ==

1. Dashboard Widgets
2. Front End Widget List
3. Front End Widget Dropdown

== Changelog ==

= (09.08.2015) =
* Version updated to 1.5.0
* Move all functions to Class and update the OOP structure. 
* Fix "Deprecating PHP4 style constructors in WordPress 4.3".
	- See: https://make.wordpress.org/core/2015/07/02/deprecating-php4-style-constructors-in-wordpress-4-3/
* remove console.log from the main.js file
* Add SBAW_VERSION define variable
* Add SBAW_TEXT_DOMAIN define variable
	

= (26.10.2014) =
* Version was changed to 1.4.0
* Added option to display authors in dropdown menu

= (05.06.2014) =
* Version was changed to 1.0.3
* The plugin authorship was changed from Pippin Williamson to Stanko Metodiev.
* Fixed some notices for undefined variables.

* Fixed a bug that made it so you could not disable gravatars
* Only authors with at least one post are listed now
* Only pulls in users with a level greater than 0