=== WP AMP Themes - Accelerated Mobile Pages Templates ===
Contributors: anghelalexandra, abarbulescu
Tags: amp, accelerated mobile pages, mobile, amp project, google amp, mobile web, mobile internet, smartphone, iphone, android, windows, webkit, chrome, safari, mobile web app, html5, responsive ui, themes
Requires at least: 4.4
Tested up to: 4.8
Stable tag: 1.2.1
License: GPLv2 or later

WP AMP Themes helps you improve mobile user engagement with fast, compelling experiences. Discover Accelerated Mobile Pages HTML themes and get up and running by integrating them into your WordPress with ease.

== Description ==

The WordPress Accelerated Mobile Pages Themes plugin helps bloggers, publishers and other content creators to easily add AMP support to their WordPress websites. The WP AMP Themes plugin includes one FREE AMP theme (OBLIQ) which is customizable (colors, fonts, appearance) via the WordPress admin area.

The Accelerated Mobile Pages ("AMP") Project is an open source initiative that came out of discussions between publishers and technology companies about the need to improve the entire mobile content ecosystem for everyone — publishers, consumer platforms, creators, and users.

Today, the expectation is that content should load super fast and be easy to explore. The reality is that content can take several seconds to load, or, because the user abandons the slow page, never fully loads at all. Accelerated Mobile Pages are web pages designed to load near instantaneously — they are a step towards a better mobile web for all.

Speed matters and instant is the ideal. Research has shown higher bounce rates associated with slower-loading web pages. Using the AMP format will make it far more compelling for people to consume and engage with more content. But this isn’t just about speed and performance. We also want to promote enhanced distribution so that publishers can take advantage of the open web’s potential for their content to appear everywhere quickly — across platforms and apps — which can lead to more revenue via ads and subscriptions.

Accelerated Mobile Pages are just like any other HTML page, but with a limited set of allowed technical functionality that is defined and governed by the open source AMP spec. Just like all web pages, Accelerated Mobile Pages will load in any modern browser or app webview.

AMP files take advantage of various technical and architectural approaches that prioritize speed to provide a faster experience for users. AMP developers can use a rich and growing library of web components that offer the ability to embed rich media objects like video and social posts, display advertising, or collect analytics. The goal is not to homogenize how content looks and feels, but instead to build a more common technical core between pages that speeds up load times.

In addition, AMP files can be cached in the cloud in order to reduce the time content takes to get to a user’s mobile device. By using the AMP format, content producers are making the content in AMP files available to be cached by third parties. Under this type of framework, publishers continue to control their content, but platforms can easily cache or mirror the content for optimal delivery speed to users. Google has provided the Google AMP Cache that can be used by anyone at no cost, and all AMPs will be cached by the Google AMP Cache. Other companies may build their own AMP cache as well.

In summary, the goal is that the combination of limited technical functionality with a distribution system built around caching will lead to better performing pages, and increased audience development for publishers.

The OBLIQ AMP theme (available for FREE) comes with support for post details and includes a side menu with categories and pages.

Some of the AMP components that we've used when building OBLIQ:

* amp-accordion
* amp-img
* amp-sidebar
* amp-carousel
* amp-social-share

There are dozens of premium AMP themes available at AMPThemes.io: [BASE](https://ampthemes.io/downloads/base-amp-theme/), [ELEVATE](https://ampthemes.io/downloads/elevate-amp-theme/), [FOLIO](https://ampthemes.io/downloads/folio-amp-theme/), [INVISION](https://ampthemes.io/downloads/invision-amp-theme/), [POPSICLE](https://ampthemes.io/downloads/popsicle-amp-theme/), [PULSE](https://ampthemes.io/downloads/pulse-amp-theme/), [GHOST](https://ampthemes.io/downloads/ghost-amp-theme/), [PHANTOM](https://ampthemes.io/downloads/phantom-amp-theme/), [LUCID](https://ampthemes.io/downloads/lucid-amp-theme/), [EXTRUDE](https://ampthemes.io/downloads/extrude-amp-theme/), [VEDI](https://ampthemes.io/downloads/vedi-amp-theme/), [BLEND](https://ampthemes.io/downloads/blend-amp-theme/), [PURE](https://ampthemes.io/downloads/pure-amp-theme/), [GOTHAM](https://ampthemes.io/downloads/gotham-amp-theme/), [FUTURE](https://ampthemes.io/downloads/future-amp-theme/) & [PALM](https://ampthemes.io/downloads/palm-amp-theme/).

== How Does AMP Work? ==

Essentially a framework for creating mobile web pages, AMP consists of three basic parts:

* AMP HTML: A subset of HTML, this markup language has some custom tags and properties and many restrictions. But if you are familiar with regular HTML, you should not have difficulty adapting existing pages to AMP HTML. For more details on how it differs from basic HTML, check out AMP Project’s list of required markup that your AMP HTML page “must” have.

* AMP JS: A JavaScript framework for mobile pages. For the most part, it manages resource handling and asynchronous loading. It should be noted that third-party JavaScript is not permitted with AMP.

* AMP CDN: An optional Content Delivery Network, it will take your AMP-enabled pages, cache them and automatically make some performance optimizations.

== What Will AMP Look Like On Google? ==
Google has provided a demo of what an AMP feature would look like in the SERP. You can try it out by navigating to [g.co/ampdemo](http://g.co/ampdemo) on your mobile phone (or emulate it within Chrome Developer Tools). Then, search for something like “Donald Trump”.

You will see a carousel toward the top with AMP articles. Click on one for a reading experience embedded in the SERP. You can swipe right or left to read another AMP-enabled article. It’s a different experience from simply navigating to a publisher’s AMP page.


Have fun on your mobile adventures!

== Installation ==

= Simple installation for WordPress v4.4 and later =

This plugin requires the [AMP plugin](https://wordpress.org/plugins/amp/) to be installed.

1.  Go to the 'Plugins' / 'Add new' menu
1.  Upload wp-amp-themes.zip then press 'Install now'.
1.  Enjoy.

= Comprehensive setup =

A more comprehensive setup process and guide to configuration is as follows.

1. Locate your WordPress install on the file system
1. Extract the contents of `wp-amp-themes.zip` into `wp-content/plugins`
1. In `wp-content/plugins` you should now see a directory named `wp-amp-themes`
1. Login to the WordPress admin panel at `http://yoursite.com/wp-admin`
1. Go to the 'Plugins' menu.
1. Click 'Activate' for the plugin.
1. Go to the ‘WP AMP Themes' admin panel.
1. Edit your settings.
1. You're all done!

= Testing your installation =

Open a post details page and add '/amp' at the end of the URL. You should see the AMP template being displayed.

Once your AMP posts are cached by Google, they can be easily recognized on mobile devices by the AMP lightning symbol that shows up next to them in the Google search results.

If a user clicks on an AMP link, the AMP content is loaded directly in the browser from the Google AMP Cache.
After viewing the AMP page, users can click on links in the article or return to Google search by clicking the back arrow.

== FAQ ==

= What are the consequences of using Accelerated Mobile Pages? =
By using the AMP format, content producers are making the content in AMP files available to be crawled, indexed & displayed (subject to the robots exclusion protocol) and cached by third parties.

= What type of content works best using Accelerated Mobile Pages? =
The goal is for all published content, from news stories to videos and from blogs to photographs and GIFs, to work using Accelerated Mobile Pages.

= Is AMP only for mobile? =
AMP was designed with responsiveness in mind, to work across all screen sizes.

== Changelog ==

= 1.2.1 =
* Improved integration with OneSignal for push notifications and simplified set up process.

= 1.2 =
* Added integration with OneSignal for push notifications.

= 1.1 =
* Added possibility to insert new themes.
* Added default theme option.

= 1.0 =
* Added colors and logo to theme customizer.

= 0.5 =
* Beta release

== Upgrade Notice ==

= 1.1 =
* WP AMP Themes now offers the possibility of using the default AMP theme and adding new themes.

== Screenshots ==

1. AMP Theme Picker.
2. Settings.
3. Premium Themes.

== Repositories ==

Here's our Github development repository:
* [https://github.com/appticles/wp-amp-themes](https://github.com/appticles/wp-amp-themes) - The plugin files, same as you will find for download on WordPress.org.
