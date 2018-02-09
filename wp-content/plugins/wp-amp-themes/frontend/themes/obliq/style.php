<?php
namespace WP_AMP_Themes\Frontend\Themes\Obliq;

use \WP_AMP_Themes\Includes\Utils;
use \WP_AMP_Themes\Includes\Options;

$utils = new Utils();
$wp_amp_themes_options = new Options();

$base_text_color = sanitize_hex_color( $this->get_customizer_setting( 'base_text_color' ) );
$post_title_text_color = sanitize_hex_color( $this->get_customizer_setting( 'post_title_text_color' ) );
$post_meta_text_color = sanitize_hex_color( $this->get_customizer_setting( 'post_meta_text_color' ) );
$label_text_color = sanitize_hex_color( $this->get_customizer_setting( 'label_text_color' ) );
$label_bg_color = sanitize_hex_color( $this->get_customizer_setting( 'label_bg_color' ) );
$icons_color = sanitize_hex_color( $this->get_customizer_setting( 'icons_color' ) );
$sidemenu_bg_color = sanitize_hex_color( $this->get_customizer_setting( 'sidemenu_bg_color' ) );
$sidemenu_text_color = sanitize_hex_color( $this->get_customizer_setting( 'sidemenu_text_color' ) );
$article_bg_color_hex = sanitize_hex_color( $this->get_customizer_setting( 'article_bg_color' ) );
$twitter_bg_color = sanitize_hex_color( $this->get_customizer_setting( 'twitter_bg_color' ) );
$fb_bg_color = sanitize_hex_color( $this->get_customizer_setting( 'fb_bg_color' ) );
$gplus_bg_color = sanitize_hex_color( $this->get_customizer_setting( 'gplus_bg_color' ) );
$pinterest_bg_color = sanitize_hex_color( $this->get_customizer_setting( 'pinterest_bg_color' ) );
$email_bg_color = sanitize_hex_color( $this->get_customizer_setting( 'email_bg_color' ) );
$header_bg_color = sanitize_hex_color( $this->get_customizer_setting( 'header_bg_color' ) );

$article_bg_color_rgb = $utils->hex_to_rgb( $article_bg_color_hex );

?>
* Generic WP styling */

.alignright {
	float: right;
}

.alignleft {
	float: left;
}

.aligncenter {
	display: block;
	margin-left: auto;
	margin-right: auto;
}

.amp-wp-enforced-sizes {
	/** Our sizes fallback is 100vw, and we have a padding on the container; the max-width here prevents the element from overflowing. **/
	max-width: 100%;
	margin: 0 auto;
}

.amp-wp-unknown-size img {
	/** Worst case scenario when we can't figure out dimensions for an image. **/
	/** Force the image into a box of fixed dimensions and use object-fit to scale. **/
	object-fit: contain;
}

/* Captions */

.wp-caption {
	padding: 0;
}

.wp-caption.alignleft {
	margin-right: 16px;
}

.wp-caption.alignright {
	margin-left: 16px;
}

.wp-caption .wp-caption-text {
	border-bottom: 1px solid black;
	color: '#696969';
	font-size: .875em;
	line-height: 1.5em;
	margin: 0;
	padding: .66em 10px .75em;
}

/* AMP Media */

amp-iframe,
amp-youtube,
amp-vine {
	background: "#c2c2c2";
	margin: 0 -16px 1.5em;
}

/* Custom styling */

.list-reset,.list-style-none {
  list-style: none;
}

.relative {
  position: relative;
}

html {
  font-family: sans-serif;
  line-height: 1.15;
  -ms-text-size-adjust: 100%;
  -webkit-text-size-adjust: 100%;
}

body {
  margin: 0;
}

article,figcaption,figure,header,main,nav,section {
  display: block;
}

figure {
  margin: 1em 40px;
}

a {
  background-color: transparent;
  -webkit-text-decoration-skip: objects;
}

a:active,a:hover {
  outline-width: 0;
}

img {
  border-style: none;
}

svg:not(:root) {
  overflow: hidden;
}

button {
  font-family: sans-serif;
  font-size: 100%;
  line-height: 1.15;
  margin: 0;
}

button {
  text-transform: none;
}

.ampstart-label {
  text-transform: uppercase;
}

[type=reset],[type=submit],button,html [type=button] {
  -webkit-appearance: button;
}

[type=button]::-moz-focus-inner,[type=reset]::-moz-focus-inner,[type=submit]::-moz-focus-inner,button::-moz-focus-inner {
  border-style: none;
  padding: 0;
}

[type=button]:-moz-focusring,[type=reset]:-moz-focusring,[type=submit]:-moz-focusring,button:-moz-focusring {
  outline: ButtonText dotted 1px;
}

.mr0 {
  margin-right: 0;
}

.mt0 {
  margin-top: 0;
}

.mb0 {
  margin-bottom: 0;
}

.p0 {
  padding: 0;
}

.list-reset,.pl0,.px0 {
  padding-left: 0;
}

.pr0,.px0 {
  padding-right: 0;
}

.py0 {
  padding-top: 0;
}

.py0 {
  padding-bottom: 0;
}

[type=checkbox],[type=radio] {
  box-sizing: border-box;
  padding: 0;
}

[type=number]::-webkit-inner-spin-button,[type=number]::-webkit-outer-spin-button {
  height: auto;
}

[type=search] {
  -webkit-appearance: textfield;
  outline-offset: -2px;
}

[type=search]::-webkit-search-cancel-button,[type=search]::-webkit-search-decoration {
  -webkit-appearance: none;
}

::-webkit-file-upload-button {
  -webkit-appearance: button;
  font: inherit;
}

[hidden] {
  display: none;
}

.h1 {
  font-size: 3rem;
}

.h2 {
  font-size: 2rem;
}

.h3 {
  font-size: 1.5rem;
}

.h4 {
  font-size: 1.125rem;
}

.h5 {
  font-size: .875rem;
}

.h6 {
  font-size: .75rem;
}

.text-decoration-none {
  text-decoration: none;
}

.bold {
  font-weight: 700;
}

.center {
  text-align: center;
}

.justify {
  text-align: justify;
}

.inline {
  display: inline;
}

.block {
  display: block;
}

.inline-block {
  display: inline-block;
}

.left {
  float: left;
}

.right {
  float: right;
}

.m0 {
  margin: 0;
}

.mr1 {
  margin-right: .5rem;
}

.mt1 {
  margin-top: .5rem;
}

.mb1 {
  margin-bottom: .5rem;
}

.m1 {
  margin: .5rem;
}

.mr2 {
  margin-right: 1rem;
}

.mt2 {
  margin-top: 1rem;
}

.mb2 {
  margin-bottom: 1rem;
}

.m2 {
  margin: 1rem;
}

.mr3 {
  margin-right: 1.5rem;
}

.mt3 {
  margin-top: 1.5rem;
}

.mb3 {
  margin-bottom: 1.5rem;
}

.m3 {
  margin: 1.5rem;
}

.mr4 {
  margin-right: 2rem;
}

.mt4 {
  margin-top: 2rem;
}

.mb4 {
  margin-bottom: 2rem;
}

.m4 {
  margin: 2rem;
}

.pl1,.px1 {
  padding-left: .5rem;
}

.pr1,.px1 {
  padding-right: .5rem;
}

.py1 {
  padding-top: .5rem;
}

.py1 {
  padding-bottom: .5rem;
}

.p1 {
  padding: .5rem;
}

.py2 {
  padding-top: 1rem;
}

.py2 {
  padding-bottom: 1rem;
}

.pl2,.px2 {
  padding-left: 1rem;
}

.pr2,.px2 {
  padding-right: 1rem;
}

.p2 {
  padding: 1rem;
}

.py3 {
  padding-top: 1.5rem;
}

.py3 {
  padding-bottom: 1.5rem;
}

.pl3,.px3 {
  padding-left: 1.5rem;
}

.pr3,.px3 {
  padding-right: 1.5rem;
}

.p3 {
  padding: 1.5rem;
}

.py4 {
  padding-top: 2rem;
}

.py4 {
  padding-bottom: 2rem;
}

.pl4,.px4 {
  padding-left: 2rem;
}

.pr4,.px4 {
  padding-right: 2rem;
}

.p4 {
  padding: 2rem;
}

h1,h2,h3,h4,h5,h6,p {
  margin: 0;
  padding: 0;
}

.flex {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
}

.flex-wrap {
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
}

.items-start {
  -webkit-box-align: start;
  -ms-flex-align: start;
  align-items: flex-start;
}

.items-end {
  -webkit-box-align: end;
  -ms-flex-align: end;
  align-items: flex-end;
}

.items-center {
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}

.justify-start {
  -webkit-box-pack: start;
  -ms-flex-pack: start;
  justify-content: flex-start;
}

.justify-end {
  -webkit-box-pack: end;
  -ms-flex-pack: end;
  justify-content: flex-end;
}

.justify-center {
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
}

.justify-around {
  -ms-flex-pack: distribute;
  justify-content: space-around;
}

.content-start {
  -ms-flex-line-pack: start;
  align-content: flex-start;
}

.content-end {
  -ms-flex-line-pack: end;
  align-content: flex-end;
}

.content-center {
  -ms-flex-line-pack: center;
  align-content: center;
}

.content-around {
  -ms-flex-line-pack: distribute;
  align-content: space-around;
}

.flex-none {
  -webkit-box-flex: 0;
  -ms-flex: none;
  flex: none;
}

.absolute {
  position: absolute;
}

.fixed {
  position: fixed;
}

.top-0 {
  top: 0;
}

.top-8 {
  top: 8rem;
}

.right-0 {
  right: 0;
}

.bottom-0 {
  bottom: 0;
}

.left-0 {
  left: 0;
}

.vw {
  width: 100vw;
}

.vh {
  height: 100vh;
}

.vh30 {
  height: 30vh;
}

.w-100 {
  width: 100%;
}

.z1 {
  z-index: 1;
}

.z2 {
  z-index: 2;
}

.z3 {
  z-index: 3;
}

.z4 {
  z-index: 4;
}

* {
  box-sizing: border-box;
}

body {
  background: #fff;
  color: <?php echo $base_text_color; ?>;
  font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Oxygen, Ubuntu, Cantarell, Fira Sans, Droid Sans, Helvetica Neue, Arial, sans-serif;
  min-width: 315px;
  overflow-x: hidden;
  font-smooth: always;
  -webkit-font-smoothing: antialiased;
}

main {
  max-width: 700px;
  margin: 0 auto;
}

amp-carousel .ampstart-image-with-caption {
  margin-bottom: 0;
}
#content {
	background-color: <?php echo $article_bg_color_hex; ?>;
}
#content:target {
  margin-top: calc(0px - 3.5rem);
  padding-top: 3.5rem;
}

.ampstart-subtitle,body {
  line-height: 1.5rem;
  letter-spacing: normal;
}

.ampstart-subtitle {
  color: #003f93;
  font-size: 1rem;
}

.ampstart-label {
  font-size: .875rem;
  color: #4f4f4f;
  line-height: 1.125rem;
  letter-spacing: .06rem;
}

.h1,h1 {
  font-size: 3rem;
  line-height: 3.5rem;
}

.h2,h2 {
  font-size: 2rem;
  line-height: 2.5rem;
}

.h3,h3 {
  font-size: 1.5rem;
  line-height: 2rem;
}

.h4,h4 {
  font-size: 1.125rem;
  line-height: 1.5rem;
}

.h5,h5 {
  font-size: .875rem;
  line-height: 1.125rem;
}

.h6,h6 {
  font-size: .75rem;
  line-height: 1rem;
}

h1,h2,h3,h4,h5,h6 {
  font-weight: 400;
  letter-spacing: .06rem;
}

a,a:active,a:visited {
  color: inherit;
}

.ampstart-nav a,.ampstart-navbar-trigger {
  cursor: pointer;
  text-decoration: none;
}

.ampstart-image-fullpage-hero {
  color: #fff;
}

.ampstart-image-fullpage-hero figcaption.has-mt-2 {
  margin-top: 2rem;
}

.ampstart-image-fullpage-hero .ampstart-image-credit {
  -webkit-box-decoration-break: clone;
  box-decoration-break: clone;
  background: #000;
  padding: 0 1rem .2rem;
}

.ampstart-image-fullpage-hero>amp-img {
  max-height: 100vh;
}

.ampstart-article-image {
  height: 55vh;
}

.ampstart-article-image>amp-img {
  height: 30vh;
}

.ampstart-article-image>figcaption {
  z-index: 333;
}

.ampstart-image-fullpage-hero>amp-img img {
  -o-object-fit: cover;
  object-fit: cover;
}

@media (min-width: 52.06rem) {
  .ampstart-image-fullpage-hero>amp-img {
    height: 60vh;
  }
}

.ampstart-image-with-caption figcaption {
  color: #4f4f4f;
  line-height: 1.125rem;
}

@-webkit-keyframes a {
  to {
    opacity: 1;
  }
}

@keyframes a {
  to {
    opacity: 1;
  }
}
.ampstart-headerbar {
  color: #000;
  z-index: 999;
	background-color: <?php echo $header_bg_color; ?>;
}
.ampstart-headerbar.has-image {
  background-color: transparent;
}
.ampstart-headerbar+:not(amp-sidebar) {
  margin-top: 0;
}

.ampstart-headerbar+amp-sidebar+* {
  margin-top: 0;
}

.ampstart-headerbar-nav .ampstart-nav-item {
  padding: 0 1rem;
  background: 0 0;
  opacity: .8;
}

.ampstart-headerbar-nav {
  line-height: 3.5rem;
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
}

.ampstart-header-inner {
  background: -webkit-linear-gradient(bottom, <?php echo $article_bg_color_hex; ?>, rgba(<?php echo $article_bg_color_rgb; ?>, .8) 70%, rgba(<?php echo $article_bg_color_rgb; ?>, .7));
  background: -moz-linear-gradient(bottom, <?php echo $article_bg_color_hex; ?>, rgba(<?php echo $article_bg_color_rgb; ?>, .8) 70%, rgba(<?php echo $article_bg_color_rgb; ?>, .7));
  background: -o-linear-gradient(bottom, <?php echo $article_bg_color_hex; ?>, rgba(<?php echo $article_bg_color_rgb; ?>, .8) 70%, rgba(<?php echo $article_bg_color_rgb; ?>, .7));
  background: linear-gradient(bottom, <?php echo $article_bg_color_hex; ?>, rgba(<?php echo $article_bg_color_rgb; ?>, .8) 70%, rgba(<?php echo $article_bg_color_rgb; ?>, .7));
  background: -ms-linear-gradient(bottom, <?php echo $article_bg_color_hex; ?> 0, rgba(<?php echo $article_bg_color_rgb; ?>, .8) 70%, rgba(<?php echo $article_bg_color_rgb; ?>, .7));
}
.ampstart-header-inner h3 {
	color: <?php echo $post_title_text_color; ?>;
}

.ampstart-header-inner a {
  color: <?php echo $label_bg_color; ?>;
}
.ampstart-header-category {
  padding: 1px 3px;
  color: <?php echo $label_text_color; ?>;
  background: <?php echo $label_bg_color; ?>;
}
.ampstart-header-meta {
	color: <?php echo $post_meta_text_color; ?>;
}
.ampstart-header-obliq {
  width: 0;
  height: 0;
  border-bottom-width: 1rem;
  border-right: 100vw solid transparent;
  border-bottom-style: solid;
  border-bottom-color: rgba(<?php echo $article_bg_color_rgb; ?>, .7);
}

.ampstart-header-title {
  border-bottom: 1px solid #f0f0f0;
}

.ampstart-article-content {
	color: <?php echo $base_text_color; ?>;
}

.ampstart-navbar-trigger {
  line-height: 3.5rem;
  font-size: 2rem;
}

.ampstart-nav-dropdown {
  min-width: 200px;
}

.ampstart-nav-dropdown amp-accordion header {
  background-color: #fff;
  border: none;
}

.ampstart-nav-dropdown amp-accordion ul {
  background-color: transparent;
}

.ampstart-nav-dropdown .ampstart-dropdown-item,.ampstart-nav-dropdown .ampstart-dropdown>section>header {
  background-color: transparent;
  color: <?php echo $sidemenu_text_color; ?>;
}

.ampstart-nav-dropdown .ampstart-dropdown-item {
  color: <?php echo $sidemenu_text_color; ?>;
}

.ampstart-nav-dropdown .ampstart-dropdown-item a {
  color: <?php echo $sidemenu_text_color; ?>;
  font-weight: 900;
  padding-left: 1.5rem;
  padding-right: 1.5rem;
  bottom: 1rem;
}

.ampstart-sidebar {
  background-color: <?php echo $sidemenu_bg_color; ?>;
  color: <?php echo $sidemenu_text_color; ?>;
  min-width: 300px;
  width: 300px;
}

.ampstart-sidebar .ampstart-icon {
  fill: <?php echo $icons_color; ?>;
}

.ampstart-sidebar-header {
  line-height: 3.5rem;
  min-height: 3.5rem;
}
.ampstart-logo-wrap {
	width: 60px;
	height: auto;
	max-height: 100px;
	margin: 3rem auto;
}

.ampstart-sidebar .ampstart-nav-item {
  margin: 0 0 2rem;
}

.ampstart-social-follow {
  height: 6rem;
  width: 100%;
}

.ampstart-sidebar .ampstart-nav-dropdown {
  margin: 0;
}

.ampstart-sidebar .ampstart-navbar-trigger {
  line-height: inherit;
}

.ampstart-dropdown {
  min-width: 200px;
}

.ampstart-dropdown.absolute {
  z-index: 100;
}

.ampstart-dropdown.absolute>section,.ampstart-dropdown.absolute>section>header {
  height: 100%;
}

.ampstart-dropdown>section>header {
  background-color: transparent;
  border: 0;
  color: #fff;
}

.ampstart-dropdown>section>header:after {
  display: inline-block;
  content: "+";
  padding: 0 0 0 1.5rem;
  color: <?php echo $icons_color; ?>;
  float: right;
}

.ampstart-dropdown>[expanded]>header:after {
  content: "–";
}

.absolute .ampstart-dropdown-items {
  z-index: 200;
}

.ampstart-dropdown-item {
  background-color: #000;
  color: #003f93;
  opacity: .9;
}

.ampstart-dropdown-item:active,.ampstart-dropdown-item:hover,.ampstart-nav-item:active,.ampstart-nav-item:focus,.ampstart-nav-item:hover {
  opacity: 1;
}

.ampstart-article-inner {
  background-color: #fff;
}

.ampstart-article-obliq {
  width: 0;
  height: 0;
  border-bottom-width: 2rem;
  border-right: 100vw solid transparent;
  border-bottom-style: solid;
  border-bottom-color: rgba(<?php echo $article_bg_color_rgb; ?>, 1);
}

.ampstart-navbar-trigger {
  color: <?php echo $icons_color; ?>;
}

.ampstart-navbar-close {
  color: $<?php echo $icons_color; ?>;
}

.ampstart-static-close {
  color: <?php echo $icons_color; ?>;
  top: 0.1rem;
  right: 0.1rem;
}

.ampstart-navbar-trigger:focus {
  outline: 0;
}

.ampstart-social-box {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
}

.ampstart-icon {
  fill: #003f93;
}

amp-social-share {
  margin-right: 5px;
}
.amp-social-share-twitter {
  background-color: <?php echo $twitter_bg_color; ?>;
}
.amp-social-share-facebook {
  background-color: <?php echo $fb_bg_color; ?>;
}
.amp-social-share-gplus {
  background-color: <?php echo $gplus_bg_color; ?>;
}
.amp-social-share-pinterest {
  background-color: <?php echo $pinterest_bg_color; ?>;
}
.amp-social-share-email {
    background-color: <?php echo $email_bg_color; ?>;
}


<?php if ( $wp_amp_themes_options->get_setting( 'push_notifications_enabled' ) ) : ?>

	.web-push {
		margin-top: 16px;
	}

	amp-web-push-widget button.subscribe {
				display: inline-flex;
				align-items: center;
				border-radius: 2px;
				border: 0;
				box-sizing: border-box;
				margin: 0;
				padding: 10px 15px;
				cursor: pointer;
				outline: none;
				font-size: 15px;
				font-weight: 400;
				background: #4A90E2;
				color: white;
				box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.5);
				-webkit-tap-highlight-color: rgba(0, 0, 0, 0);
			}

	amp-web-push-widget button.subscribe .subscribe-icon {
		margin-right: 10px;
	}

	amp-web-push-widget button.subscribe:active {
		transform: scale(0.99);
	}

	amp-web-push-widget button.unsubscribe {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		height: 45px;
		border: 0;
		margin: 0;
		cursor: pointer;
		outline: none;
		font-size: 15px;
		font-weight: 400;
		background: transparent;
		color: #B1B1B1;
		-webkit-tap-highlight-color: rgba(0, 0, 0, 0);
	}

<?php endif; ?>
