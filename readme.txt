=== Plugin Name ===
Contributors: aliakro
Tags: weight, graph, track, stones, kg, table, data, plot, target, history, pounds, responsive, chart
Requires at least: 4.1.0
Tested up to: 4.4
Stable tag: 3.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An easy to use plugin that allows a user to keep track of their weight history in both tabular and chart format.

== Description ==

= Core Features =

An easy to use plugin that allows a user to keep track of their weight history in both tabular and chart format (Chart.js). Simply place the tag [weight-loss-tracker] on a given page and the user is presented with a tabbed form to enter a date (UK or US), weight and notes for that entry. When the person saves their entry the data table and graph are refreshed. The plugin also allows users to specify their target weight which is drawn on the chart as a comparison. If data is entered for an existing date, then the previous entry is simply updated. The graph is shown when there are two or more entries.

The following weight formats are supported:

- Metric (Kg)
- Imperial - Stones & Pounds
- Imperial - Pounds only

Also supports the following tags:

	[weightloss_weight_difference] - total weight lost by the logged in member
	[weightloss_weight_start] - start weight of the logged in member
	[weightloss_weight_most_recent] - end weight of the logged in member
	[weightloss_weight_difference_from_target] - difference from target

= Pro Version =

Our Pro version has now been released! If you upgrade, you get he additional features:

- Widgets. Widgets that allow you to display the graph and quick weight entry form within any widget area.
- Chart and form Shortcodes. That allow you to display the graph and quick weight entry form by placing a shortcode on any post or page.
- Text Shortcodes. Additional shortcodes for earliest and most recent dates entered.
- Admin: View / Delete user data. Admin will be able to view and delete existing user data.
- User preferences. If enabled, the user will be able to select which unit they wish to store their weight in Metric or Imperial. They will also be able to specify date format and clear all their weight data.
- Bar Charts. Fancy something different to a line chart? The plugin will also suppor`````t Bar Charts.
- Decimals. Decimals will be allowed weight in Pounds only or Kg modes.
- Delete existing entry. A logged in user will be able to delete or edit an existing weight entry.
- Better Tables.. Data tables in front end and admin will support paging and sorting.
- Admin: Extra Settings. Extra settings to customise the plugin will be added e.g. number of plot points on graph, rows per page, etc.

= Languages support =

Fully supported (have the majority of translations):

- English (UK)
- German (thanks Michael @ Activate the Beast)
- Dutch (thanks Dennis)
- Finish (thanks Ari)

Languages partially supported (have some of the translations):

- Portuguese-Brazil (thanks Team Jota)
- French (thanks @alexiarna)
- Spanish (thanks @idelfonsog2)
- Romanian (thanks @alexiarna)
- Danish (thanks @kfasterholdt)

Need a translation? Email us: email@YeKen.uk

* Developed by YeKen.uk *

= Documentation =

Need further help? Please visit the Wiki:
https://github.com/yekenuk/Weight-Loss-Tracker/wiki

= Donate =

Paypal Donate: email@YeKen.uk

== Installation ==

1. Install "Weight Loss Tracker" via the "Plugins" page in WordPress Admin (or download zip and upload).
2. Setup the plugin in WordPress Admin panel by goto to Settings > Weight Loss Tracker
3. Create a page that users will visit. Within the page content add the shortcode [weightlosstracker].
4. Voila

== Frequently Asked Questions ==

= Do you have any guides / documentation? =

Yes, I'm currently expanding my Wiki: https://github.com/yekenuk/Weight-Loss-Tracker/wiki

= Does it create any custom mySQL tables =

Yes it creates two. One to store weight information per user and another to store target weights:

- WP_WS_LS_DATA_TARGETS - Stores user target data
- WP_WS_LS_DATA - Stores weight history

= What date formats doe it support? =

Currently it supports both UK (dd/mm/yyy) and US (mm/dd/yyyy) date formats.

= Does it support the Avada theme? =

Prior to version 3.0, yes, this plugin did support the Avada theme. However, wanting to be independent of any theme support this has now been removed.

= How do I switch it from Metric (Kg) to Imperial (Stones / Pounds)? =

In WordPress Admin goto Settings > Weight Loss Tracker and change the setting "Weight Units" to the desired type.

= How do I enable tabs? =

Yes. In WordPress Admin goto Settings > Weight Loss Tracker and change the setting "Display data in tabs?" to Yes.

= Can I change measurement units while the site is live? =

It is only recommended if you first installed the plugin at version 1.6+ (as it stores measurements in Kg / Pound). Before this, you may find data isn't present for previous date entries.

= How do I disable "Target weight" =

Yes. In WordPress Admin goto Settings > Weight Loss Tracker and change the setting "Allow targets?" to No.

== Screenshots ==

1. All three tabs of the main [weight-loss-tracker] shortcode
2. Tab one of [weight-loss-tracker]: Chart, Target and Weight form.
3. Tab two of [weight-loss-tracker]: Target Weight and Weight History
4. Tab three of [weight-loss-tracker]: User preferences page
5. Examples of random placements of [weight-loss-tracker-chart] and [weight-loss-tracker-form]
6. Examples of the Chart and Form widgets
7. More examples of Chart and Form widgets
8. Tab one of settings page
9. Tab two of settings page
10. Tab three of settings page
11. Admin - managing user data
12. Admin - Delete ALL data
13. Examples of the [weight-loss-tracker-chart] and [weight-loss-tracker-form] shortcodes
14. Examples of the shortcodes [weightloss_weight_difference], [weightloss_weight_start], [weightloss_weight_most_recent] and [weightloss_weight_difference_from_target]

== Changelog ==

= 3.3 =

* Added some additional Romanian translations (although not complete for plugin).
* BUG FIX: Finnish translation files were given the wrong code so not included as expected.

= 3.2 =

* Updated Finnish translations.

= 3.1.1 =

* Modified the main charts ([weight-loss-tracker-chart] and [weight-loss-tracker]) so they display the most recent date entries as opposed to the earliest.
* BUG FIX: Fixed difference in date ordering of widget and main charts. Previously the Widget displayed dates in desc as opposed to asc.

= 3.1 =

**Pro Changes:**

* Added new Shortcode for displaying a data table: [weight-loss-tracker-table] - By default it will display data for the current user logged in. Specifying the attribute "user-id" will allow you to display a table for the given user e.g. [weight-loss-tracker-table user-id="5"]
* Admin can now specify a message to be displayed in the place of widgets when the user is not logged in.
* BUG FIX:  Charts and Form shortcodes now display a login message when the user isn't logged. Previously they would see the data weight data for admin account.

**Core changes:**

 * Can now specify the minimum number of weight entries needed before chart is displayed e.g. [weight-loss-tracker min-chart-points=5]
 * Improved jQuery loading of tabs. Tabs are hidden until jQuery has rendered them correctly (this will stop the unstyled tabs appearing).
 * "Clear Target" button added to target forms.

= 3.0.1 =

- Extended MySQL table checker to ensure the data table for user preferences has been created.
- Removed CSS import statements for Open Sans and Lato.

= 3.0 =

Wey hey! Our first Pro version, if you upgrade you get the following additional features:

- Widgets. Widgets that allow you to display the graph and quick weight entry form within any widget area.
- Chart and form Shortcodes. That allow you to display the graph and quick weight entry form by placing a shortcode on any post or page.
- Text Shortcodes. Additional shortcodes for earliest and most recent dates entered.
- Admin: View / Delete user data. Admin will be able to view and delete existing user data.
- User preferences. If enabled, the user will be able to select which unit they wish to store their weight in Metric or Imperial. They will also be able to specify date format and clear all their weight data.
- Bar Charts. Fancy something different to a line chart? The plugin will also support Bar Charts.
- Decimals. Decimals will be allowed weight in Pounds only or Kg modes.
- Delete existing entry. A logged in user will be able to delete or edit an existing weight entry.
- Better Tables.. Data tables in front end and admin will support paging and sorting.
- Admin: Extra Settings. Extra settings to customise the plugin will be added e.g. number of plot points on graph, rows per page, etc.

Core changes:

- Removed support for Avada theme
- Changed jQuery to document ready instead of window ready (jQuery kicks in quicker)

= 2.0.1 =

BUG FIX: Target weight was appearing twice on non-tabbed mode.

- Removed some reundant jQuery regarding old tab library.
- Added an additional CSS rule to override some theme CSS causing issues.

= 2.0 =

This is a major release of the plugin and a complete rewrite of its core. As the plugin has grown unexpectedly the code base has became unmaintainable and each upgrade was a pain in the a$$. So, this version fixes that and provides a solid base for the future Pro version. Changes in this release:

New features:

* Better out the box styling and look - looks sharper and more presentable with no need for CSS knowledge.
* Supports UK and US date formats
* jQuery UI tabs replaced with cleaner, animated and responsive tabs.
* Shortcode name added [weight-loss-tracker] (works in exactly the same way as [weightlosstracker]).
* Admin menu has been expanded (ready for Pro version).
* Improved form validation and added more visual cues to prompt the user to correct the form.

Bug fixes:

* Other shortcodes (like weightloss_weight_difference) return the weight unit but no value when not logged in. Fixed to return nothing at all.
* Tab index order fixed on forms.

Underlying changes:

* Added caching to database queries - all queries are cached for upto 15 minutes. This will reduce the number of queries against the MySQL database and reduce overall load. It is estimated that DB queries will reduce by two thirds.
* All JavaScript (e.g. Chart) has been removed from inline statements to external files.
* All JavaScript and CSS files have been minified.
* Redundant / development copies of JavaScript, PHP and CSS files have been removed. Folder structures have been simplified and tidied.
* Increased chart height. Will be configurable in future releases.
* Major refactoring of ALL code.
* Various PHP performance tweaks to increase load time (e.g. replacing all double quotes for strings and replacing with single).
* Added additional CSS class names to HTML elements allowing developers to target their CSS better.
* Support added for multiple charts to be placed (the base to build shortcodes / widgets in the Pro version).

= 1.20.1 =

BUG FIX: Previous release broke the "Delete all data" button on admin page. Fixed.

= 1.20 =

* Changed input fields for weight to: type="number".
* Settings page: Added logic to check the required MySQL tables are present. If not, an option to rebuild them is available.
* Removed redundant upgrade script to add column to main data table.
* BUG FIX: If DateInterval class is not present (i.e. older version of PHP) disable date range selection on weight history table
* BUG FIX: Stopped non numeric values being entered for target weights.
* BUG FIX: When in Stones & Pounds mode, maximum pounds that can be entered is limited to 14.

= 1.19.2 =

* BUG FIX: Rookie mistake! Left debug data in!

= 1.19.1 =

* BUG FIX: Pound Only value not being saved correctly.

= 1.19 =

* Ability to specify the line and fill colours used on the graph. (thanks for the suggestion @Hardware Guru)
* Added Spanish translations - THANKS @idelfonsog2
* BUG FIX: In some cases the MySQL Target table was not being created correctly. The activate logic has been modified to correct this.

= 1.18 =

* BUG FIX: Gave the language function a unique name as clashing with some other plugins.
* IMPROVEMENT: Removed -50px off width of graph.

= 1.17 =

* Updated Romanian translations for admin page - THANKS @alexiarna
* Removed some redundant PHP files.
* BUG FIX: All text translated on admin page. One piece of text wasn't being translated.

= 1.16 =

* Added Danish translations - THANKS @kfasterholdt
* Updated Romanian translations (front end) - THANKS @alexiarna

= 1.15 =

- Removed upgrade check. This was trying to recreate the MySQL table for target data and throwing errors on some installs.
- Added versions numbers when enqueuing Js and CSS scripts.
- Tidied up includes statements.

= 1.14 =

- Chart.new.js (library used for charts) has been reverted back to Chart.js. Unfortunately this means labels are lost on graphs. However, Chart.js is still maintained by the developers.
- New option to display plot points on graph. If enabled, target and recorded weight will be displayed on hover.
- Chart JavaScript tidied up.
- BUG FIX: Removed references to jQuery Tabs when Tabs disabled.

= 1.13 =

- BUG FIX: Some third party plugins / installs insert additional input fields within <form>. Added additional code to remove any non expected ones.
- BUG FIX: Target form appears when tabs enbaled - regardless of whether or not Targets are enabled.

= 1.12 =

- Added a button on admin page to delete all existing user data.

= 1.11 =

- Added an admin page to manage settings for plugin

= 1.10 =

- Weight History data presented on seperate tab

= 1.9 =

- Users can now specify their target weight
- New short code [weightloss_weight_difference_from_target] - end weight of the logged in member to display the difference between target and latest weight
- New filters to filter data by week

= 1.8 =

* Upgraded Datepicker to use jquery (as opposed to HTML5 control) for better browser support

= 1.7 =

* Added Dutch translations

= 1.6 =

* Now supports pounds only. As opposed to just Stones / Pounds
* Translations added for:
  - Romanian
  - French
* Minor tweaks to conversions between stones / pounds
* Corrected ws_ls_to_stone_pounds to calculate pounds correctly
* [weightloss_weight_difference] corrected to display pounds
* Small bug fixes

= 1.5.1 =

Minor bug fix for new tags. Writing out values in the wrong place.

= 1.5 =

* Replaced chart.js with chartNew.js (https://github.com/FVANCOP/ChartNew.js/) to allow graph axis labels
* Axis labels added to graph
* Bug fix. Imperial measurements are now displayed on the graph in pounds (as opposed to stones / lbs) due to this bug:

	The problem is plotting imperial values on a graph. Say you have the following imperial data (stones / pounds) data:

	'15 7','15 3','15 0','14 12','14 10','14 7'

	I originally added a decimal place to stones / ozs so I can graph it:

	'15.7','15.3','15.0','14.12','14.10','14.7'

	At a glance it looks like this should work.

	However, the 14.7 is treated as 14.70 and therefore comes higher than 14.10.

  I couldn't see a quick way to correct this. So to be safe (and save me time), I've changed it to display in pounds only

= 1.4 =

* Added the following tags:

	[weightloss_weight_difference] - total weight lost by the logged in member
	[weightloss_weight_start] - start weight of the logged in member
	[weightloss_weight_most_recent] - end weight of the logged in member

= 1.2 / 1.3 =
* Various changes made upon feedback from WordPress submission

= 1.1 =
* Support imperial as well as metric weights

= 1.0 =
* Initial Build
