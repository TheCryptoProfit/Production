=== Steemit Feed ===
Contributors: minitekgr
Tags: Steemit, Steemit feed, Steemit posts, Steemit articles, Steemit widget, Steem, Steem posts, cryptocurrency, bitcoin, blockchain
Requires at least: 4.6
Tested up to: 4.8
Stable tag: 1.1.1
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

A simple Wordpress plugin that displays a feed of Steemit posts.

== Description ==

Display a feed of Steemit posts by any Steem author.

== Installation ==

= Instructions =

1. Install the Steemit Feed plugin either via the WordPress plugin directory, or by uploading the files to your web server (in the `/wp-content/plugins/` directory).
2. Activate the Steemit Feed plugin through the 'Plugins' menu in WordPress.
3. Navigate to the 'Steemit Feed' Feeds page and create a new Feed.
4. Use the shortcode `[steemitfeed id="feed_id"]` in your page, post or widget to display a feed.

== Frequently Asked Questions ==

= Where can I find documentation? =

For help setting up and configuring Steemit Feed please refer to our [user guide](https://steemit.com/wordpress/@wordpress-tips/update-steemit-feed-for-wordpress-v-1-1-0-new-backend-many-improvements)

= Where can I get support? =

If you need support, please [open a support ticket](https://wordpress.org/support/plugin/steemit-feed).

= Will Steemit Feed work with my theme? =

Yes, Steemit Feed should work with any theme.

== Changelog ==

= 1.1.1 =
* Added option to exclude specific posts.
* Added button to load more items in the feed dynamically.
* Small changes in template files.

= 1.1.0 =
* WARNING: Feeds created with previous versions will no longer work. You must create new feeds and use the new shortcodes.
* New strict file organization scheme based on the WordPress Plugin Boilerplate.
* You can now create and save many feeds without using cumbersome shortcode parameters.
* Two different layouts.
* You can now limit the feed height and display a vertical scrollbar.
* You can now select image size (small/big) and display a fallback image for posts that don't have images.
* You can now limit the title word count.
* You can now display the post tags.

= 1.0.5 =
* You can now include or exclude posts from specific tags.
* Fixed bug where the posts count was not correct when excluding tags.
* Fixed bug where the author reputation did not display on synchronous load.
* Changed method of asynchronous loading.
* Small changes in admin layout.

= 1.0.4 =
* Fixes stripslashes bug in body text.
* Strips markdown from body text.
* Added option to add a referral username to the urls.
* Added option to show author reputation.
* Added breakpoint parameter for responsive layout.
* Added parameter to change title and body font sizes.
* Added parameter to select whether the feed will load asynchronously.
* Small changes in admin layout.

= 1.0.3 =
* Fixes the reward sum (displayed as $0.00 when payout has not started).
* Fixes the upvotes count.
* Changes the votes icon (user icon has been replaced by an arrow icon to match the Steemit layout).
* Small changes in admin layout.

= 1.0.2 =
* Updated to work with the latest Steem.js API.

= 1.0.1 =
* Launched the Steemit Feed plugin.