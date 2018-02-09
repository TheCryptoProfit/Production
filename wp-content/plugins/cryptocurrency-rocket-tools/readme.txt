=== Cryptocurrency Rocket Tools ===
Contributors: liberteam, webstulle
Donate link: http://liberteam.org/
Tags: bitcoin, cryptocurrency, ethereum, ripple, exchange, cryptocurrencies, prices, ticker, rates, trading, token, btc, eth, etc, dash, nmc, nvc, ppc, dsh, xcp, candlestick, usd, eur
Requires at least: 3.0
Tested up to: 4.9.2
Stable tag: trunk
License: GPL2 or later

Price ticker, table, graph, converter, price list of all cryptocurrencies.

== Description ==

Price ticker, table, graph, converter, price list of all cryptocurrencies for your website.

= Features =
* Table of current cryptocurrency prices
* Graph of historical cryptocurrency prices
* Converter of all cryptocurrencies

**Available cryptocurrencies:** all cryptocurrencies from [CryptoCompare](https://www.cryptocompare.com) ([view full JSON list](https://www.cryptocompare.com/api/data/coinlist/)).
**Available currencies:** USD, AUD, BRL, CAD, CHF, CLP, CNY, CZK, DKK, EUR, GBP, HKD, HUF, IDR, ILS, INR, JPY, KRW, MXN, MYR, NOK, NZD, PHP, PKR, PLN, RUB, SEK, SGD, THB, TRY, TWD, ZAR


= Plugin uses =
[CryptoCompare](https://www.cryptocompare.com/api/) data (no API key required), Bootstrap v3.3.7, Datatebles v1.10.15, amCharts v3.21.6, Chart.js v2.7.0.

= Languages =
English, Germany, Russian, French.

= Authors =
**Powered by [Liberteam](https://profiles.wordpress.org/liberteam)**
**Designed by [Webstulle](http://webstulle.com/)**

= Donation =
We had invested great effort and time to make this plugin better. We will appreciate your support.
**[Donate cryptocoin](http://liberteam.org/)** or leave your **[review](https://wordpress.org/support/plugin/cryptocurrency-rocket-tools/reviews/)**

== Price List Instructions ==

**[Online Demo](http://crtools.liberteam.org/#pricelist)**

To show the Price List add a shortcode on your page.

= Shortcodes =

`[crtools-pricelist]`
`[crtools-pricelist coin="BTC,ETH" fiat="USD,EUR"]`
`[crtools-pricelist limit="20" fiat="USD,EUR"]`
`[crtools-pricelist limit="0"]`
`[crtools-pricelist cols="logo,symbol,price,change"]`

= Parameters =

**coin** - list of cryptocurrencies, separated by commas. If **limit** is set, then **coin** will be ignored.
**fiat** - list of currencies, separated by commas. (Default: all currencies)
**limit** - displays a certain amount of top cryptocurrency by market capitalization. (Default: 10; if 0, then displays all)
**cols** - list of table columns, separated by commas. (Default: "logo,symbol,name,price,change").


== Table Instructions ==

**[Online Demo](http://crtools.liberteam.org/#table)**

To show the Table add a shortcode on your page.

= Shortcodes =

`[crtools-table coin="BTC,ETH,XRP,XMR,LTC" fiat="USD,EUR" cols="price,cap,supply,volume,change,graph" search="false" pagination="10,20,30"]`
`[crtools-table coin="BTC" cols="price,change,graph"]`
`[crtools-table]`

= Parameters =

**coin** - list of cryptocurrencies, separated by commas. (Default: all cryptocurrencies)
**fiat** - list of currencies, separated by commas. (Default: all currencies)
**cols** - list of table columns, separated by commas. (Default: "price,cap,supply,volume,change,graph"). **Number** and **Name** are required.
**pagination** - displays entries, separated by commas. (Default: "10,25")
**search** - displays search field. (Default: "true")

= Table columns =

**number** - Number
**name** - Name
**price** - Prices
**cap** - Market Capitalizations
**supply** - Circulating Supply
**volume** - Volume 24H
**change** - % Change 24H
**graph** - Price Graph 7D.


== Graph Instructions ==

**[Online Demo](http://crtools.liberteam.org/#graph)**

To show the Graph add a shortcode on your page.

= Shortcodes =

`[crtools-graph coin="BTC" fiat="USD,EUR" period="1M,1Y"]`
`[crtools-graph coin="BTC" fiat="USD,EUR" period="1M,1Y"]`
`[crtools-graph coin="BTC" fiat="USD,EUR"]`
`[crtools-graph]`

= Parameters =

**coin** - cryptocurrency. (Default: BTC)
**fiat** - list of currencies, separated by commas. (Default: all currencies)
**period** - list of historical data display periods. (Default: "12H,1D,1W,1M,6M,ALL"). Available: 12H,1D,1W,1M,6M,ALL

== Converter Instructions ==

**[Online Demo](http://crtools.liberteam.org/#converter)**

To show the Converter add a shortcode on your page.

= Shortcodes =

`[crtools-converter from="BTC,ETH" to="USD" other="EUR,RUB,GBP"]`
`[crtools-converter from="BTC"]`
`[crtools-converter]`

= Parameters =

**from** - list of cryptocurrencies or currencies, separated by commas. (Default: all cryptocurrencies)
**fiat** - list of cryptocurrencies or currencies, separated by commas. (Default: all currencies)

== Localization ==

You can translate the plugin into your language. To make it take our file "/cryptocurrency-rocket-tools/languages/cryptocurrency-rocket-tools.pot", then translate and generate .PO and .MO files and put into the directory "/cryptocurrency-rocket-tools/languages/". [Read how to generate .PO and .MO files.](https://developer.wordpress.org/themes/functionality/localization/#translate-po-file)

Help us to translate the plugin. Send us your .PO and .MO file using email address info@webstulle.com.

== Installation ==

1. Unzip the `cryptocurrency-rocket-tools.zip` folder.
2. Upload the `cryptocurrency-rocket-tools` folder to your `/wp-content/plugins` directory.
3. In your WordPress dashboard, head over to the *Plugins* section.
4. Activate *Cryptocurrency Rocket Tools*.

== Screenshots ==

1. Price List.
2. Table (all cryptocurrencies are shown).
3. Graph (historical BTC prices).
4. Converter.
5. Admin panel.

== Changelog ==

= 1.4.1 =
* **Fixed:** IE support

= 1.4.0 =
* **Added:** New feature - Price List!
* **Added:** Translated into two more languages: Germany and French.
* **Fixed:** Minor fixes

= 1.3.2 =
* **Fixed:** Hotfix. Table styles

= 1.3.1 =
* **Fixed:** Hotfix. Table disappeared with no column of graph

= 1.3 =
* Happy New Year 2018! New features and bug fixes!
* **Added:** New awesome admin panel
* **Added:** Localization support
* **Added:** Links to coin pages in the Table
* **Fixed:** Volume 24H data in the Table
* **Fixed:** Change 24H sorting in the Table
* **Fixed:** Table width in desktop
* **Modified:** Parameters "graphColor" and "cursorColor" in the Graph shortcode are depricated (moved to admin panel)
* **Modified:** Converter title name is deleted

= 1.2 =
* New feature and BugFix! Now you can edit graph color in graph module using two option as graphColor and cursorColor".

= 1.1 =
* New feature! Now you can add new fields to your converter using parameter "other" into "crtools-converter".

= 1.0.1 =
* Table module bugFix (pagination, size, etc.)

= 1.0 =
* Plugin is released. Everything is new!

== Upgrade Notice ==

= 1.4.1 =
* **Fixed:** IE support

= 1.4.0 =
* **Added:** New useful feature - Price List!
* **Added:** Translated into two more languages: Germany and French.
* **Fixed:** Minor fixes

= 1.3.2 =
* **Fixed:** Hotfix. Table styles

= 1.3.1 =
* **Fixed:** Hotfix. Table disappeared with no column of graph

= 1.3 =
* Happy New Year 2018! New features and bug fixes!
* **Added:** New awesome admin panel
* **Added:** Localization support
* **Added:** Links to coin pages in the Table
* **Fixed:** Volume 24H data in the Table
* **Fixed:** Change 24H sorting in the Table
* **Fixed:** Table width in desktop
* **Modified:** Parameters "graphColor" and "cursorColor" in the Graph shortcode are depricated (moved to admin panel)
* **Modified:** Converter title name is deleted

= 1.2 =
* New feature and BugFix! Now you can edit graph color in graph module using two option as graphColor and cursorColor".

= 1.1 =
* New feature! Now you can add new fields to your converter using parameter "other" into "crtools-converter".

= 1.0.1 =
* Updated the plugin and library to handle issues related to invalid Table

= 1.0 =
* Hello, this is the first version.