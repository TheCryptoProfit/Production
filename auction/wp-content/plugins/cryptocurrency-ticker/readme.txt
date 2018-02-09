=== Cryptocurrency Ticker ===
Contributors: rbbrdckybk
Tags: bitcoin, ticker, litecoin, ethereum, cryptocurrency, quote, price
Requires at least: 2.8
Tested up to: 4.8.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Fetches, caches, and displays current cryptocurrency prices (bitcoin, ethereum, and litecoin, for now).

== Description ==

Cryptocurrency Ticker displays current cryptocurrency prices (bitcoin, ethereum, and/or litecoin) on your WordPress site. You may select which quotes to show, in either USD or EUR prices.

Prices are fetched from coinbase.com using their API (https://developers.coinbase.com/).

Ticker prices are cached for a duration that you specify in the widget menu, to improve performance and prevent your site from making a ton of requests to coinbase.com.

== Installation ==

1. Upload the contents of the plugin .zip to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. In the 'Widgets' menu in WordPress, place the widget where you want it to appear on your site
4. Verify that the plugin's settings are what you want in the widget menu

== Frequently Asked Questions ==

= Can I change the look of the widget? =

Yes, simply modify the included crypto-ticker.css file, located in the plugin's /css directory.

= What about support for other cryptocurrencies? =

I may add the smaller altcoins in the future if there is enough interest. If you can't wait, adding them yourself should be a trivial task; just keep in mind that because of the way the btc-e API is set up, each additional currency means an additional request, which will impact performance on cache misses. If I do decide to add the other altcoins, I'll probably re-write the widget so that the requests are asynchronous.

= I have a referrer ID at Coinbase. Can I use it? =

Yes! Version 1.2 and up allows you to enter your Coinbase referral ID in the widget settings. Please note that if you don't have your own referral ID, a default one will be used.

= The ticker is showing different prices on all of my site's pages, and/or doesn't seem to be updating - help! =

You most likely have a Wordpress caching plugin installed that is either misconfigured or out-of-date. Make sure that you're running the latest version. Older versions of W3 Total Cache will cause this problem (the most recent release should be fine).

== Changelog ==

= 1.4 =
* Litecoin price is now fetched from Coinbase instead of btc-e.com (which has been offline for over a week).

= 1.3 =
* Added support for Ethereum (ETH, fetched from Coinbase).
* Updated Coinbase requests to use their newer v2 API.

= 1.2 =
* Updated PHP4-style constructor to avoid deprecation warnings in Wordpress 4.3+.
* Added support for Coinbase referral IDs. Referral IDs will be used on the ticker links. Please note that if you don't have your own referral ID, a default one will be used.

= 1.1 =
* Added ability to display cryptocurrency values in EUR, in addition to USD.
* Bitcoin quote is now fetched from Coinbase, instead of Mt. Gox (due to the ongoing issues at Gox, the price is unreliable).

= 1.0 =
* Initial release.

== Donations ==

If this plugin helped you, feel free to tip me some beer money. =)

* BTC:  1Gryw6P4xDgpysdtPbBDYN8Vz78uCSmN2K 
* LTC:  LQGpAD5eQPXbR2NpHDzj77LBq7oQgeakqA 
* ETH:  0x32FD4a5DF96Af70b0D4644Cf4dce44cFc988BEE4

