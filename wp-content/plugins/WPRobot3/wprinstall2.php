<?php
if($_POST['email']) {
	$email = base64_decode($_POST['email']);
	$ger = $_POST['ger'];
	$wpr_modules = unserialize(str_replace('\"','"',$_POST['modules']));
	$wpr_modules = unserialize($_POST['modules']);
	$site = $_POST['site'];

	$num_rows = 1;
		
	if($num_rows == 0 || $num_rows == "0") {
		// If not found echo False	
		echo "false";
	} else {

		// Build SQL Query For Found Modules	
		if(!empty($wpr_modules)) {
			$row['clickbank'] = 1;
			$row['twitter'] = 1;
			$row['pressrelease'] = 1;
			$row['shopzilla'] = 1;	
			$row['plr'] = 1;

			$row['amazon'] = 1;
			$row['article'] = 1;
			$row['ebay'] = 1;
			$row['flickr'] = 1;
			$row['yahoonews'] = 1;
			$row['youtube'] = 1;
			$row['rss'] = 1;
			
			$core = "elite";	
			
			$mdcount = 0;
			$mdcount = 10;
			$mcount = 10;
			if($core == "advanced" || $core == "elite") {$mcount++;}
			
			if($mdcount >= 9) {$row['avantlink'] = 1;$row['shareasale'] = 1;$row['oodle'] = 1;$row['commissionjunction'] = 1;$row['eventful'] = 1;$row['linkshare'] = 1;$row['itunes'] = 1;$row['yelp'] = 1;}
			
			if($mcount >= 3) {$row['shareasale'] = 1;}
			if($mcount >= 4) {$row['yelp'] = 1;}
			if($mcount >= 5) {$row['itunes'] = 1;}		
			if($mcount >= 6) {$row['eventful'] = 1;}					
			$sql = "INSERT INTO {wpr_template} ( type, typenum, content, title, comments_amazon, comments_flickr, comments_yahoo, comments_youtube, name ) VALUES ";
			
			/* ENGLISH TEMPLATES */
			if($ger != 1) {
			
			// MODULE Templates
			if($row['amazon'] == 1 && in_array('amazon', $wpr_modules)) {$sql .= " ( 'amazon', '0', '<h3><a href=\"{url}\" rel=\"nofollow\">{title}</a></h3>\r\n{thumbnail}\r\n{features}\r\n{description}\r\n\r\n<p>\r\n<div style=\"float:right;\">{buynow-big}</div>\r\n[has_listprice]\r\nList Price: {listprice}\r\n[/has_listprice]\r\n<strong>Price: {price-updating}</strong>\r\n</p>\r\n{reviews-iframe}', '', '0', '0', '0', '0', '' ),";}	
			if($row['article'] == 1 && in_array('article', $wpr_modules)) {$sql .= " ( 'article', '0', '{article}\r\n<div>{authortext}</div>', '', '0', '0', '0', '0', '' ),";}
			if($row['ebay'] == 1 && in_array('ebay', $wpr_modules)) {$sql .= " ( 'ebay', '0', '<strong>{title}</strong>\r\n{descriptiontable}', '', '0', '0', '0', '0', '' ),";}
			if($row['clickbank'] == 1 && in_array('clickbank', $wpr_modules)) {$sql .= " ( 'clickbank', '0', '<strong>{title}</strong>\r\n{thumbnail}\r\n{description}\r\n{link}', '', '0', '0', '0', '0', '' ),";}
			if($row['flickr'] == 1 && in_array('flickr', $wpr_modules)) {$sql .= " ( 'flickr', '0', '<p><strong>{title}</strong>\r\n{image}\r\n<i>Image by <a href=\"{url}\">{owner}</a></i>\r\n{description}</p>', '', '0', '0', '0', '0', 'standard' ),";}
			if($row['flickr'] == 1 && in_array('flickr', $wpr_modules)) {$sql .= " ( 'flickr', '0', '<div style=\"float:left;margin:5px;font-size:80%;\">{image} by <a href=\"{url}\">{owner}</a></div>', '', '0', '0', '0', '0', 'thumbnail' ),";}
			if($row['yahoonews'] == 1 && in_array('yahoonews', $wpr_modules)) {$sql .= " ( 'yahoonews', '0', '<strong>{title}</strong>\r\n{summary}\r\n<i>{source}</i>\r\n', '', '0', '0', '0', '0', '' ),";}
			if($row['yahooanswers'] == 1 && in_array('yahooanswers', $wpr_modules)) {$sql .= " ( 'yahooanswers', '0', '<strong><i>Question by {user}</i>: {title}</strong>\r\n{question}\r\n\r\n<strong>Best answer:</strong>\r\n{answers:1}\r\n\r\n<strong>[select:Know better? Leave your own answer in the comments!|Add your own answer in the comments!|Give your answer to this question below!|What do you think? Answer below!]</strong>', '', '0', '0', '0', '0', '' ),";}
			if($row['youtube'] == 1 && in_array('youtube', $wpr_modules)) {$sql .= " ( 'youtube', '0', '{video}\r\n<p>[random:20]<div style=\"float:left;margin:5px;\">{thumbnail}</div>[/random]{description}\r\n[random:60]<strong>Video Rating: {rating} / 5</strong>[/random]</p>', '', '0', '0', '0', '0', '' ),";}
			if($row['rss'] == 1 && in_array('rss', $wpr_modules)) {$sql .= " ( 'rss', '0', '{content}\r\n{source}', '', '0', '0', '0', '0', '' ),";}
			if($row['commissionjunction'] == 1 && in_array('commissionjunction', $wpr_modules)) {$sql .= " ( 'commissionjunction', '0', '<h3><a href=\"{url}\" rel=\"nofollow\">{title}</a></h3>\r\n{thumbnail}\r\n{description}\r\nList Price: {listprice}\r\n<strong>Price: {price}</strong>\r\n', '', '0', '0', '0', '0', '' ),";}
			if($row['twitter'] == 1 && in_array('twitter', $wpr_modules)) {$sql .= " ( 'twitter', '0', '{tweet} - <i>by {author}</i>\r\n', '', '0', '0', '0', '0', '' ),";}
			if($row['oodle'] == 1 && in_array('oodle', $wpr_modules)) {$sql .= " ( 'oodle', '0', '<strong><a href=\"{url}\" rel=\"nofollow\">{title}</a></strong>\r\n{thumbnail}\r\n{content}\r\n\r\n<strong>Price: {price}</strong>\r\n\r\n<strong>Location</strong>\r\n{address}\r\n{city}', '', '0', '0', '0', '0', '' ),";}
			if($row['pressrelease'] == 1 && in_array('pressrelease', $wpr_modules)) {$sql .= " ( 'pressrelease', '0', '{thumbnail}\r\n{pressrelease}\r\n', '', '0', '0', '0', '0', '' ),";}													
			if($row['shopzilla'] == 1 && in_array('shopzilla', $wpr_modules)) {$sql .= " ( 'shopzilla', '0', '{thumbnail}<strong><a href=\"{url}\" rel=\"nofollow\">{title} [select:Price Comparison|Best Prices|Top Offers|Top Deals]</a></strong>\r\n{description}\r\n{offers}', '', '0', '0', '0', '0', '' ),";}
			if($row['eventful'] == 1 && in_array('eventful', $wpr_modules)) {$sql .= " ( 'eventful', '0', '<strong><a href=\"{url}\" rel=\"nofollow\">{title}</a></strong>\r\n<i>Event on {begin}</i>\r\n{description}\r\n\r\nat <a href=\"{venueurl}\" rel=\"nofollow\">{venuename}</a>\r\n{venueaddress}\r\n{city}, {country}', '', '0', '0', '0', '0', '' ),";}
			if($row['linkshare'] == 1 && in_array('linkshare', $wpr_modules)) {$sql .= " ( 'linkshare', '0', '<h3><a href=\"{url}\" rel=\"nofollow\">{title}</a></h3>\r\n{thumbnail}\r\n{description}\r\n\r\n<strong>Price: {price}</strong>\r\n<strong>Sold by {merchant}</strong>', '', '0', '0', '0', '0', '' ),";}
			if($row['itunes'] == 1 && in_array('itunes', $wpr_modules)) {$sql .= " ( 'itunes', '0', '{thumbnail}\r\n<strong><a href=\"{trackurl}\" rel=\"nofollow\">{artistname} - {trackname}</a></strong><br/>\r\nfrom {collectionname}<br/>\r\nPrice: {currency} {trackPrice}\r\n<a href=\"{artisturl}\">View Details about {artistname}</a>', '', '0', '0', '0', '0', '' ),";}			
			if($row['yelp'] == 1 && in_array('yelp', $wpr_modules)) {$sql .= " ( 'yelp', '0', '<strong><a href=\"{url}\" rel=\"nofollow\">{title} Reviews</a></strong>\r\n{thumbnail}\r\n{city}\r\n{address}\r\n<strong>Average Rating</strong>: {rating} out of 5 ({reviewscount} Reviews)\r\n\r\n{reviews:3}', '', '0', '0', '0', '0', '' ),";}			
			if($row['shareasale'] == 1 && in_array('shareasale', $wpr_modules)) {$sql .= " ( 'shareasale', '0', '<h3><a href=\"{url}\" rel=\"nofollow\">{title}</a></h3>\r\n{thumbnail}\r\n{description}\r\nSold by {merchant}\r\nList Price: {listprice}\r\n<strong>Price: {price}</strong>\r\n', '', '0', '0', '0', '0', '' ),";}

			if($row['avantlink'] == 1 && in_array('avantlink', $wpr_modules)) {$sql .= " ( 'avantlink', '0', '<h3><a href=\"{url}\" rel=\"nofollow\">{title}</a></h3>\r\n{thumbnail}\r\n{description}\r\nList Price: {listprice}\r\n<strong>Price: {price}</strong>\r\n', '', '0', '0', '0', '0', '' ),";}
			if($row['plr'] == 1 && in_array('plr', $wpr_modules)) {$sql .= " ( 'plr', '0', '{article}\r\n', '', '0', '0', '0', '0', '' ),";}

			// POST Templates						
			if($row['article'] == 1 && in_array('article', $wpr_modules)) {	
				$sql .= " ( 'post', '0', '";
				if($row['flickr'] == 1) {$sql .= "{thumbnail}\r\n";}
				$sql .= "{article}";
				if($row['youtube'] == 1) {$sql .= "\r\n[random:25]{youtube}[/random]\r\n";}
				$sql .= "\r\n[random:50][select:More <a href=\"{catlink}\">{Keyword} Articles</a>|Related <a href=\"{catlink}\">{Keyword} Articles</a>|Find More <a href=\"{catlink}\">{Keyword} Articles</a>][/random]', '{articletitle}', '0', '0', '0', '0', 'Article Default' ),";
			}
			if($row['amazon'] == 1 && in_array('amazon', $wpr_modules)) {
				$sql .= " ( 'post', '1', '{amazon}\r\n[random:15]{amazon}[/random]";
				if($row['ebay'] == 1) {$sql .= "\r\n[random:50]{ebay} {ebay}[/random]\r\n";}
				$sql .= "\r\n[random:50][select:More <a href=\"{catlink}\">{Keyword} Products</a>|Related <a href=\"{catlink}\">{Keyword} Products</a>|Find More <a href=\"{catlink}\">{Keyword} Products</a>][/random]', '{amazontitle}[random:20] Reviews[/random]', '0', '0', '0', '0', 'Amazon Default' ),";	
			}
			if($row['yahooanswers'] == 1 && in_array('yahooanswers', $wpr_modules)) {
				$sql .= " ( 'post', '2', '";
				if($row['flickr'] == 1) {$sql .= "[random:50]{thumbnail}[/random]\r\n";}
				$sql .= "{yahooanswers}', '[random:25]Q&A: [/random]{yahooanswerstitle}', '0', '0', '1', '0', 'Yahoo Answers Default' ),";	
			}
			if($row['yahoonews'] == 1 && in_array('yahoonews', $wpr_modules)) {
				$sql .= " ( 'post', '3', '";	
				if($row['flickr'] == 1) {$sql .= "[random:25]{flickr}[/random]\r\n";}	
				$sql .= "{yahoonews}\r\n\r\n{yahoonews}\r\n\r\n[random:50]{yahoonews}[/random]\r\n\r\n[random:25]{yahoonews}[/random]', '[select:{yahoonewstitle}|{yahoonewstitle}|Latest {Keyword} News]', '0', '0', '0', '0', 'Yahoo News Default' ),";			
			}
			if($row['clickbank'] == 1 && in_array('clickbank', $wpr_modules)) {
				$sql .= " ( 'post', '4', '[random:25]<p>[select:Check out these {keyword} products:|A few {keyword} products I can recommend:]</p>[/random]\r\n{clickbank}\r\n\r\n{clickbank}\r\n\r\n[random:25]{clickbank}[/random]";
				if($row['ebay'] == 1) {$sql .= "\r\n[random:25] {ebay} {ebay} [/random]";}
				$sql .= "', '{clickbanktitle}', '0', '0', '0', '0', 'Clickbank Default' ),";			
			}	
			if($row['youtube'] == 1 && in_array('youtube', $wpr_modules)) {$sql .= " ( 'post', '5', '{youtube}\r\n[random:50]{youtube}[/random]', '{youtubetitle}', '0', '0', '0', '1', 'Youtube Default' ),";}			
			if($row['ebay'] == 1 && in_array('ebay', $wpr_modules)) {$sql .= " ( 'post', '6', '<p>[select:Some recent {keyword} auctions on eBay:|{keyword} eBay auctions you should keep an eye on:|Most popular {keyword} eBay auctions:|{Keyword} on eBay:]</p>\r\n{ebay}\r\n{ebay}\r\n[random:50]{ebay}[/random]\r\n[random:25]{ebay}[/random]', '[select:{ebaytitle}|{ebaytitle}|Latest {Keyword} auctions|Most popular {Keyword} auctions]', '0', '0', '0', '0', 'Ebay Default' ),";}		
			if($row['flickr'] == 1 && in_array('flickr', $wpr_modules)) {$sql .= " ( 'post', '7', '<p>[select:Some cool {keyword} images:|A few nice {keyword} images I found:|Check out these {keyword} images:]</p>\r\n{flickr}\r\n{flickr}\r\n[random:50]{flickr}[/random]', '[select:{flickrtitle}|{flickrtitle}|Cool {Keyword} images|Nice {Keyword} photos]', '0', '0', '0', '0', 'Flickr Default' ),";}		

			if($row['commissionjunction'] == 1 && in_array('commissionjunction', $wpr_modules)) {
				$sql .= " ( 'post', '8', '{commissionjunction}";
				if($row['amazon'] == 1) {$sql .= "\r\n\r\n[random:60] {amazonlist:3} [/random]";} elseif($row['ebay'] == 1) {$sql .= "\r\n\r\n[random:25] {ebay} {ebay} [/random]";}
				$sql .= "', '{commissionjunctiontitle}', '0', '0', '0', '0', 'Commission Junction Default' ),";					
			}
			if($row['pressrelease'] == 1 && in_array('pressrelease', $wpr_modules)) {		
				$sql .= " ( 'post', '9', '{pressrelease}\r\n[random:50][select:More <a href=\"{catlink}\">{Keyword} Press Releases</a>|Related <a href=\"{catlink}\">{Keyword} Press Releases</a>|Find More <a href=\"{catlink}\">{Keyword} Press Releases</a>][/random]', '{pressreleasetitle}', '0', '0', '0', '0', 'Press Release Default' ),";
			}
			if($row['oodle'] == 1 && in_array('oodle', $wpr_modules)) {		
				$sql .= " ( 'post', '10', '{oodle}', '{oodletitle}', '0', '0', '0', '0', 'Oodle Default' ),";
			}	
			if($row['shopzilla'] == 1 && in_array('shopzilla', $wpr_modules)) {		
				$sql .= " ( 'post', '11', '{shopzilla}', '{shopzillatitle}', '0', '0', '0', '0', 'Shopzilla Default' ),";
			}			
			
			if($row['itunes'] == 1 && in_array('itunes', $wpr_modules)) {		
				$sql .= " ( 'post', '12', '{itunes}', '{itunestitle}', '0', '0', '0', '0', 'iTunes Default' ),";
			}			
			if($row['linkshare'] == 1 && in_array('linkshare', $wpr_modules)) {		
				$sql .= " ( 'post', '13', '{linkshare}";
				if($row['amazon'] == 1) {$sql .= "\r\n\r\n[random:30] {amazonlist:3} [/random]";} elseif($row['ebay'] == 1) {$sql .= "\r\n\r\n[random:25] {ebay} {ebay} [/random]";}				
				$sql .= "', '{linksharetitle}', '0', '0', '0', '0', 'Linkshare Default' ),";					
			}	
			if($row['eventful'] == 1 && in_array('eventful', $wpr_modules)) {		
				$sql .= " ( 'post', '14', '{eventful}', '{eventfultitle}', '0', '0', '0', '0', 'Eventful Default' ),";
			}	
			if($row['yelp'] == 1 && in_array('yelp', $wpr_modules)) {		
				$sql .= " ( 'post', '14', '{yelp}', '{yelptitle}', '0', '0', '0', '0', 'Yelp Default' ),";
			}	
			if($row['avantlink'] == 1 && in_array('avantlink', $wpr_modules)) {		
				$sql .= " ( 'post', '14', '{avantlink}', '{avantlinktitle}', '0', '0', '0', '0', 'Avantlink Default' ),";
			}
			
			if($row['articlebuilder'] == 1 && in_array('articlebuilder', $wpr_modules)) {		
				$sql .= " ( 'post', '14', '{articlebuilder}', '{articlebuildertitle}', '0', '0', '0', '0', 'ArticleBuilder Default' ),";
			}		

			/* CHINESE TEMPLATES */			
			} elseif($ger == 2) {

				// MODULE Templates
	/* TRANSLATE */			if($row['amazon'] == 1 && in_array('amazon', $wpr_modules)) {$sql .= " ( 'amazon', '0', '<h3><a href=\"{url}\" rel=\"nofollow\">{title}</a></h3>\r\n{thumbnail}\r\n{features}\r\n{description}\r\n\r\n<p>\r\n<div style=\"float:right;\">{buynow-big}</div>\r\n[has_listprice]\r\nList Price: {listprice}\r\n[/has_listprice]\r\n<strong>Price: {price-updating}</strong>\r\n</p>\r\n{reviews-iframe}', '', '0', '0', '0', '0', '' ),";}	
				if($row['article'] == 1 && in_array('article', $wpr_modules)) {$sql .= " ( 'article', '0', '{article}\r\n<div>{authortext}</div>', '', '0', '0', '0', '0', '' ),";}
	/* TRANSLATE */				if($row['ebay'] == 1 && in_array('ebay', $wpr_modules)) {$sql .= " ( 'ebay', '0', '<strong>{title}</strong>\r\n{descriptiontable}', '', '0', '0', '0', '0', '' ),";}
				if($row['clickbank'] == 1 && in_array('clickbank', $wpr_modules)) {$sql .= " ( 'clickbank', '0', '<strong>{title}</strong>\r\n{thumbnail}\r\n{description}\r\n{link}', '', '0', '0', '0', '0', '' ),";}
				if($row['flickr'] == 1 && in_array('flickr', $wpr_modules)) {$sql .= " ( 'flickr', '0', '<p><strong>{title}</strong>\r\n{image}\r\n<i>Image by <a href=\"{url}\">{owner}</a></i>\r\n{description}</p>', '', '0', '0', '0', '0', 'standard' ),";}
				if($row['flickr'] == 1 && in_array('flickr', $wpr_modules)) {$sql .= " ( 'flickr', '0', '<div style=\"float:left;margin:5px;font-size:80%;\">{image} by <a href=\"{url}\">{owner}</a></div>', '', '0', '0', '0', '0', 'thumbnail' ),";}
	/* TRANSLATE */				if($row['yahoonews'] == 1 && in_array('yahoonews', $wpr_modules)) {$sql .= " ( 'yahoonews', '0', '<strong>{title}</strong>\r\n{summary}\r\n<i>{source}</i>\r\n', '', '0', '0', '0', '0', '' ),";}
				if($row['yahooanswers'] == 1 && in_array('yahooanswers', $wpr_modules)) {$sql .= " ( 'yahooanswers', '0', '<strong><i>Question by {user}</i>: {title}</strong>\r\n{question}\r\n\r\n<strong>Best answer:</strong>\r\n{answers:1}\r\n\r\n<strong>[select:Know better? Leave your own answer in the comments!|Add your own answer in the comments!|Give your answer to this question below!|What do you think? Answer below!]</strong>', '', '0', '0', '0', '0', '' ),";}
				if($row['youtube'] == 1 && in_array('youtube', $wpr_modules)) {$sql .= " ( 'youtube', '0', '{video}\r\n<p>[random:20]<div style=\"float:left;margin:5px;\">{thumbnail}</div>[/random]{description}\r\n[random:60]<strong>Video Rating: {rating} / 5</strong>[/random]</p>', '', '0', '0', '0', '0', '' ),";}
	/* TRANSLATE */				if($row['rss'] == 1 && in_array('rss', $wpr_modules)) {$sql .= " ( 'rss', '0', '{content}\r\n{source}', '', '0', '0', '0', '0', '' ),";}
				if($row['commissionjunction'] == 1 && in_array('commissionjunction', $wpr_modules)) {$sql .= " ( 'commissionjunction', '0', '<h3><a href=\"{url}\" rel=\"nofollow\">{title}</a></h3>\r\n{thumbnail}\r\n{description}\r\nList Price: {listprice}\r\n<strong>Price: {price}</strong>\r\n', '', '0', '0', '0', '0', '' ),";}
	/* TRANSLATE */				if($row['twitter'] == 1 && in_array('twitter', $wpr_modules)) {$sql .= " ( 'twitter', '0', '{tweet} - <i>by {author}</i>\r\n', '', '0', '0', '0', '0', '' ),";}
				if($row['oodle'] == 1 && in_array('oodle', $wpr_modules)) {$sql .= " ( 'oodle', '0', '<strong><a href=\"{url}\" rel=\"nofollow\">{title}</a></strong>\r\n{thumbnail}\r\n{content}\r\n\r\n<strong>Price: {price}</strong>\r\n\r\n<strong>Location</strong>\r\n{address}\r\n{city}', '', '0', '0', '0', '0', '' ),";}
				if($row['pressrelease'] == 1 && in_array('pressrelease', $wpr_modules)) {$sql .= " ( 'pressrelease', '0', '{thumbnail}\r\n{pressrelease}\r\n', '', '0', '0', '0', '0', '' ),";}													
				if($row['shopzilla'] == 1 && in_array('shopzilla', $wpr_modules)) {$sql .= " ( 'shopzilla', '0', '{thumbnail}<strong><a href=\"{url}\" rel=\"nofollow\">{title} [select:Price Comparison|Best Prices|Top Offers|Top Deals]</a></strong>\r\n{description}\r\n{offers}', '', '0', '0', '0', '0', '' ),";}
				if($row['eventful'] == 1 && in_array('eventful', $wpr_modules)) {$sql .= " ( 'eventful', '0', '<strong><a href=\"{url}\" rel=\"nofollow\">{title}</a></strong>\r\n<i>Event on {begin}</i>\r\n{description}\r\n\r\nat <a href=\"{venueurl}\" rel=\"nofollow\">{venuename}</a>\r\n{venueaddress}\r\n{city}, {country}', '', '0', '0', '0', '0', '' ),";}
				if($row['linkshare'] == 1 && in_array('linkshare', $wpr_modules)) {$sql .= " ( 'linkshare', '0', '<h3><a href=\"{url}\" rel=\"nofollow\">{title}</a></h3>\r\n{thumbnail}\r\n{description}\r\n\r\n<strong>Price: {price}</strong>\r\n<strong>Sold by {merchant}</strong>', '', '0', '0', '0', '0', '' ),";}
				if($row['itunes'] == 1 && in_array('itunes', $wpr_modules)) {$sql .= " ( 'itunes', '0', '{thumbnail}\r\n<strong><a href=\"{trackurl}\" rel=\"nofollow\">{artistname} - {trackname}</a></strong><br/>\r\nfrom {collectionname}<br/>\r\nPrice: {currency} {trackPrice}\r\n<a href=\"{artisturl}\">View Details about {artistname}</a>', '', '0', '0', '0', '0', '' ),";}			
				if($row['yelp'] == 1 && in_array('yelp', $wpr_modules)) {$sql .= " ( 'yelp', '0', '<strong><a href=\"{url}\" rel=\"nofollow\">{title} Reviews</a></strong>\r\n{thumbnail}\r\n{city}\r\n{address}\r\n<strong>Average Rating</strong>: {rating} out of 5 ({reviewscount} Reviews)\r\n\r\n{reviews:3}', '', '0', '0', '0', '0', '' ),";}			
				if($row['shareasale'] == 1 && in_array('shareasale', $wpr_modules)) {$sql .= " ( 'shareasale', '0', '<h3><a href=\"{url}\" rel=\"nofollow\">{title}</a></h3>\r\n{thumbnail}\r\n{description}\r\nSold by {merchant}\r\nList Price: {listprice}\r\n<strong>Price: {price}</strong>\r\n', '', '0', '0', '0', '0', '' ),";}

				if($row['avantlink'] == 1 && in_array('avantlink', $wpr_modules)) {$sql .= " ( 'avantlink', '0', '<h3><a href=\"{url}\" rel=\"nofollow\">{title}</a></h3>\r\n{thumbnail}\r\n{description}\r\nList Price: {listprice}\r\n<strong>Price: {price}</strong>\r\n', '', '0', '0', '0', '0', '' ),";}
				if($row['plr'] == 1 && in_array('plr', $wpr_modules)) {$sql .= " ( 'plr', '0', '{article}\r\n', '', '0', '0', '0', '0', '' ),";}

				// POST Templates						
				if($row['article'] == 1 && in_array('article', $wpr_modules)) {	
					$sql .= " ( 'post', '0', '";
					if($row['flickr'] == 1) {$sql .= "{thumbnail}\r\n";}
					$sql .= "{article}";
					if($row['youtube'] == 1) {$sql .= "\r\n[random:25]{youtube}[/random]\r\n";}
					$sql .= "\r\n[random:50][select:More <a href=\"{catlink}\">{Keyword} Articles</a>|Related <a href=\"{catlink}\">{Keyword} Articles</a>|Find More <a href=\"{catlink}\">{Keyword} Articles</a>][/random]', '{articletitle}', '0', '0', '0', '0', 'Article Default' ),";
				}
				if($row['amazon'] == 1 && in_array('amazon', $wpr_modules)) {
					$sql .= " ( 'post', '1', '{amazon}\r\n[random:15]{amazon}[/random]";
					if($row['ebay'] == 1) {$sql .= "\r\n[random:50]{ebay} {ebay}[/random]\r\n";}
					$sql .= "\r\n', '{amazontitle}[random:20] Reviews[/random]', '0', '0', '0', '0', 'Amazon Default' ),";	
				}
				if($row['yahooanswers'] == 1 && in_array('yahooanswers', $wpr_modules)) {
					$sql .= " ( 'post', '2', '";
					if($row['flickr'] == 1) {$sql .= "[random:50]{thumbnail}[/random]\r\n";}
					$sql .= "{yahooanswers}', '[random:25]Q&A: [/random]{yahooanswerstitle}', '0', '0', '1', '0', 'Yahoo Answers Default' ),";	
				}
				if($row['yahoonews'] == 1 && in_array('yahoonews', $wpr_modules)) {
					$sql .= " ( 'post', '3', '";	
					if($row['flickr'] == 1) {$sql .= "[random:25]{flickr}[/random]\r\n";}	
					$sql .= "{yahoonews}\r\n\r\n{yahoonews}\r\n\r\n[random:50]{yahoonews}[/random]\r\n\r\n[random:25]{yahoonews}[/random]', '[select:{yahoonewstitle}|{yahoonewstitle}|Latest {Keyword} News]', '0', '0', '0', '0', 'Yahoo News Default' ),";			
				}
				if($row['clickbank'] == 1 && in_array('clickbank', $wpr_modules)) {
					$sql .= " ( 'post', '4', '[random:25]<p>[select:Check out these {keyword} products:|A few {keyword} products I can recommend:]</p>[/random]\r\n{clickbank}\r\n\r\n{clickbank}\r\n\r\n[random:25]{clickbank}[/random]";
					if($row['ebay'] == 1) {$sql .= "\r\n[random:25] {ebay} {ebay} [/random]";}
					$sql .= "', '{clickbanktitle}', '0', '0', '0', '0', 'Clickbank Default' ),";			
				}	
				if($row['youtube'] == 1 && in_array('youtube', $wpr_modules)) {$sql .= " ( 'post', '5', '{youtube}\r\n[random:50]{youtube}[/random]', '{youtubetitle}', '0', '0', '0', '1', 'Youtube Default' ),";}			
				if($row['ebay'] == 1 && in_array('ebay', $wpr_modules)) {$sql .= " ( 'post', '6', '<p>[select:Some recent {keyword} auctions on eBay:|{keyword} eBay auctions you should keep an eye on:|Most popular {keyword} eBay auctions:|{Keyword} on eBay:]</p>\r\n{ebay}\r\n{ebay}\r\n[random:50]{ebay}[/random]\r\n[random:25]{ebay}[/random]', '[select:{ebaytitle}|{ebaytitle}|Latest {Keyword} auctions|Most popular {Keyword} auctions]', '0', '0', '0', '0', 'Ebay Default' ),";}		
				if($row['flickr'] == 1 && in_array('flickr', $wpr_modules)) {$sql .= " ( 'post', '7', '<p>[select:Some cool {keyword} images:|A few nice {keyword} images I found:|Check out these {keyword} images:]</p>\r\n{flickr}\r\n{flickr}\r\n[random:50]{flickr}[/random]', '[select:{flickrtitle}|{flickrtitle}|Cool {Keyword} images|Nice {Keyword} photos]', '0', '0', '0', '0', 'Flickr Default' ),";}		

				if($row['commissionjunction'] == 1 && in_array('commissionjunction', $wpr_modules)) {
					$sql .= " ( 'post', '8', '{commissionjunction}";
					if($row['amazon'] == 1) {$sql .= "\r\n\r\n[random:60] {amazonlist:3} [/random]";} elseif($row['ebay'] == 1) {$sql .= "\r\n\r\n[random:25] {ebay} {ebay} [/random]";}
					$sql .= "', '{commissionjunctiontitle}', '0', '0', '0', '0', 'Commission Junction Default' ),";					
				}
				if($row['pressrelease'] == 1 && in_array('pressrelease', $wpr_modules)) {		
					$sql .= " ( 'post', '9', '{pressrelease}\r\n[random:50][select:More <a href=\"{catlink}\">{Keyword} Press Releases</a>|Related <a href=\"{catlink}\">{Keyword} Press Releases</a>|Find More <a href=\"{catlink}\">{Keyword} Press Releases</a>][/random]', '{pressreleasetitle}', '0', '0', '0', '0', 'Press Release Default' ),";
				}
				if($row['oodle'] == 1 && in_array('oodle', $wpr_modules)) {		
					$sql .= " ( 'post', '10', '{oodle}', '{oodletitle}', '0', '0', '0', '0', 'Oodle Default' ),";
				}	
				if($row['shopzilla'] == 1 && in_array('shopzilla', $wpr_modules)) {		
					$sql .= " ( 'post', '11', '{shopzilla}', '{shopzillatitle}', '0', '0', '0', '0', 'Shopzilla Default' ),";
				}			
				
				if($row['itunes'] == 1 && in_array('itunes', $wpr_modules)) {		
					$sql .= " ( 'post', '12', '{itunes}', '{itunestitle}', '0', '0', '0', '0', 'iTunes Default' ),";
				}			
				if($row['linkshare'] == 1 && in_array('linkshare', $wpr_modules)) {		
					$sql .= " ( 'post', '13', '{linkshare}";
					if($row['amazon'] == 1) {$sql .= "\r\n\r\n[random:30] {amazonlist:3} [/random]";} elseif($row['ebay'] == 1) {$sql .= "\r\n\r\n[random:25] {ebay} {ebay} [/random]";}				
					$sql .= "', '{linksharetitle}', '0', '0', '0', '0', 'Linkshare Default' ),";					
				}	
				if($row['eventful'] == 1 && in_array('eventful', $wpr_modules)) {		
					$sql .= " ( 'post', '14', '{eventful}', '{eventfultitle}', '0', '0', '0', '0', 'Eventful Default' ),";
				}	
				if($row['yelp'] == 1 && in_array('yelp', $wpr_modules)) {		
					$sql .= " ( 'post', '14', '{yelp}', '{yelptitle}', '0', '0', '0', '0', 'Yelp Default' ),";
				}	
				if($row['avantlink'] == 1 && in_array('avantlink', $wpr_modules)) {		
					$sql .= " ( 'post', '14', '{avantlink}', '{avantlinktitle}', '0', '0', '0', '0', 'Avantlink Default' ),";
				}
				
				if($row['articlebuilder'] == 1 && in_array('articlebuilder', $wpr_modules)) {		
					$sql .= " ( 'post', '14', '{articlebuilder}', '{articlebuildertitle}', '0', '0', '0', '0', 'ArticleBuilder Default' ),";
				}	
			
			/* GERMAN TEMPLATES */			
			} else {
			
			// MODULE Templates
			if($row['amazon'] == 1 && in_array('amazon', $wpr_modules)) {$sql .= " ( 'amazon', '0', '<h3><a href=\"{url}\" rel=\"nofollow\">{title}</a></h3>\r\n{thumbnail}\r\n{features}\r\n{description}\r\n\r\n<p>\r\n<div style=\"float:right;\">{buynow-ger}</div>\r\n[has_listprice]\r\nUnverb. Preisempf.: {listprice}\r\n[/has_listprice]\r\n<strong>Preis: {price-updating}</strong>\r\n</p>\r\n\r\n{reviews-iframe}', '', '0', '0', '0', '0', '' ),";}	
			if($row['article'] == 1 && in_array('article', $wpr_modules)) {$sql .= " ( 'article', '0', '{article}\r\n<div>{authortext}</div>', '', '0', '0', '0', '0', '' ),";}
			if($row['ebay'] == 1 && in_array('ebay', $wpr_modules)) {$sql .= " ( 'ebay', '0', '<strong>{title}</strong>\r\n{descriptiontable}', '', '0', '0', '0', '0', '' ),";}
			if($row['clickbank'] == 1 && in_array('clickbank', $wpr_modules)) {$sql .= " ( 'clickbank', '0', '<strong>{title}</strong>\r\n{description}\r\n{link}', '', '0', '0', '0', '0', '' ),";}
			if($row['flickr'] == 1 && in_array('flickr', $wpr_modules)) {$sql .= " ( 'flickr', '0', '<p><strong>{title}</strong>\r\n{image}\r\n<i>Bild von <a href=\"{url}\">{owner}</a></i>\r\n{description}</p>', '', '0', '0', '0', '0', 'standard' ),";}
			if($row['flickr'] == 1 && in_array('flickr', $wpr_modules)) {$sql .= " ( 'flickr', '0', '<div style=\"float:left;margin:5px;font-size:80%;\">{image} von <a href=\"{url}\">{owner}</a></div>', '', '0', '0', '0', '0', 'thumbnail' ),";}
			if($row['yahoonews'] == 1 && in_array('yahoonews', $wpr_modules)) {$sql .= " ( 'yahoonews', '0', '<strong>{title}</strong>\r\n{summary}\r\n<i>{source}</i>\r\n', '', '0', '0', '0', '0', '' ),";}
			if($row['yahooanswers'] == 1 && in_array('yahooanswers', $wpr_modules)) {$sql .= " ( 'yahooanswers', '0', '<strong><i>Frage von {user}</i>: {title}</strong>\r\n{question}\r\n\r\n<strong>Beste Antwort:</strong>\r\n{answers:1}\r\n\r\n<strong>[select:Wissen Sie es besser? Antworten Sie in den Kommentaren!|Geben Sie Ihre eigene Antwort in den Kommentaren!|Antworten Sie selbst in den Kommentaren!|Was denken Sie? Antworten Sie jetzt!]</strong>', '', '0', '0', '0', '0', '' ),";}
			if($row['youtube'] == 1 && in_array('youtube', $wpr_modules)) {$sql .= " ( 'youtube', '0', '{video}\r\n<p>[random:20]<div style=\"float:left;margin:5px;\">{thumbnail}</div>[/random]{description}\r\n[random:60]<strong>Video Bewertung: {rating} / 5</strong>[/random]</p>', '', '0', '0', '0', '0', '' ),";}
			if($row['rss'] == 1 && in_array('rss', $wpr_modules)) {$sql .= " ( 'rss', '0', '{content}\r\n{source}', '', '0', '0', '0', '0', '' ),";}
			if($row['commissionjunction'] == 1 && in_array('commissionjunction', $wpr_modules)) {$sql .= " ( 'commissionjunction', '0', '<h3><a href=\"{url}\" rel=\"nofollow\">{title}</a></h3>\r\n{thumbnail}\r\n{description}\r\nUVP: {listprice}\r\n<strong>Preis: {price}</strong>\r\n', '', '0', '0', '0', '0', '' ),";}
			if($row['twitter'] == 1 && in_array('twitter', $wpr_modules)) {$sql .= " ( 'twitter', '0', '{tweet} - <i>von {author}</i>\r\n', '', '0', '0', '0', '0', '' ),";}
			if($row['oodle'] == 1 && in_array('oodle', $wpr_modules)) {$sql .= " ( 'oodle', '0', '<strong><a href=\"{url}\" rel=\"nofollow\">{title}</a></strong>\r\n{thumbnail}\r\n{content}\r\n\r\n<strong>Preis: {price}</strong>\r\n\r\n<strong>Ort</strong>\r\n{address}\r\n{city}', '', '0', '0', '0', '0', '' ),";}
			if($row['pressrelease'] == 1 && in_array('pressrelease', $wpr_modules)) {$sql .= " ( 'pressrelease', '0', '{thumbnail}\r\n{pressrelease}\r\n', '', '0', '0', '0', '0', '' ),";}													
			if($row['shopzilla'] == 1 && in_array('shopzilla', $wpr_modules)) {$sql .= " ( 'shopzilla', '0', '{thumbnail}<strong><a href=\"{url}\" rel=\"nofollow\">{title} [select:Preisvergleich|Top Preise|Top Angebote|Top Deals]</a></strong>\r\n{description}\r\n{offers}', '', '0', '0', '0', '0', '' ),";}
			if($row['eventful'] == 1 && in_array('eventful', $wpr_modules)) {$sql .= " ( 'eventful', '0', '<strong><a href=\"{url}\" rel=\"nofollow\">{title}</a></strong>\r\n<i>Event on {begin}</i>\r\n{description}\r\n\r\nat <a href=\"{venueurl}\" rel=\"nofollow\">{venuename}</a>\r\n{venueaddress}\r\n{city}, {country}', '', '0', '0', '0', '0', '' ),";}
			if($row['linkshare'] == 1 && in_array('linkshare', $wpr_modules)) {$sql .= " ( 'linkshare', '0', '<h3><a href=\"{url}\" rel=\"nofollow\">{title}</a></h3>\r\n{thumbnail}\r\n{description}\r\n\r\n<strong>Price: {price}</strong>\r\n<strong>Sold by {merchant}</strong>', '', '0', '0', '0', '0', '' ),";}
			if($row['itunes'] == 1 && in_array('itunes', $wpr_modules)) {$sql .= " ( 'itunes', '0', '{thumbnail}\r\n<strong><a href=\"{trackurl}\" rel=\"nofollow\">{artistname} - {trackname}</a></strong><br/>\r\nfrom {collectionname}<br/>\r\nPrice: {currency} {trackPrice}\r\n<a href=\"{artisturl}\">View Details about {artistname}</a>			', '', '0', '0', '0', '0', '' ),";}			
			if($row['yelp'] == 1 && in_array('yelp', $wpr_modules)) {$sql .= " ( 'yelp', '0', '<strong><a href=\"{url}\" rel=\"nofollow\">{title} Reviews</a></strong>\r\n{thumbnail}\r\n{city}\r\n{address}\r\n<strong>Average Rating</strong>: {rating} out of 5 ({reviewscount} Reviews)\r\n\r\n{reviews:3}', '', '0', '0', '0', '0', '' ),";}			
			if($row['shareasale'] == 1 && in_array('shareasale', $wpr_modules)) {$sql .= " ( 'shareasale', '0', '<h3><a href=\"{url}\" rel=\"nofollow\">{title}</a></h3>\r\n{thumbnail}\r\n{description}\r\nVerkauf durch {merchant}\r\nUVP: {listprice}\r\n<strong>Preis: {price}</strong>\r\n', '', '0', '0', '0', '0', '' ),";}
			if($row['avantlink'] == 1 && in_array('avantlink', $wpr_modules)) {$sql .= " ( 'avantlink', '0', '<h3><a href=\"{url}\" rel=\"nofollow\">{title}</a></h3>\r\n{thumbnail}\r\n{description}\r\nList Price: {listprice}\r\n<strong>Price: {price}</strong>\r\n', '', '0', '0', '0', '0', '' ),";}
			if($row['plr'] == 1 && in_array('plr', $wpr_modules)) {$sql .= " ( 'plr', '0', '{article}\r\n', '', '0', '0', '0', '0', '' ),";}
			
			// POST Templates
			if($row['commissionjunction'] == 1 && in_array('commissionjunction', $wpr_modules)) {
				$sql .= " ( 'post', '5', '{commissionjunction}";
				if($row['amazon'] == 1) {$sql .= "\r\n\r\n[random:60] {amazonlist:3} [/random]";} elseif($row['ebay'] == 1) {$sql .= "\r\n\r\n[random:25] {ebay} {ebay} [/random]";}
				$sql .= "', '{commissionjunctiontitle}', '0', '0', '0', '0', 'Commission Junction Standard' ),";					
			}
			if($row['pressrelease'] == 1 && in_array('pressrelease', $wpr_modules)) {		
				$sql .= " ( 'post', '5', '{pressrelease}\r\n[random:50][select:More <a href=\"{catlink}\">{Keyword} Press Releases</a>|Related <a href=\"{catlink}\">{Keyword} Press Releases</a>|Find More <a href=\"{catlink}\">{Keyword} Press Releases</a>][/random]', '{pressreleasetitle}', '0', '0', '0', '0', 'Press Release Standard' ),";
			}
			if($row['oodle'] == 1 && in_array('oodle', $wpr_modules)) {		
				$sql .= " ( 'post', '5', '{oodle}', '{oodletitle}', '0', '0', '0', '0', 'Oodle Standard' ),";
			}	
			if($row['shopzilla'] == 1 && in_array('shopzilla', $wpr_modules)) {		
				$sql .= " ( 'post', '5', '{shopzilla}', '{shopzillatitle}', '0', '0', '0', '0', 'Shopzilla Standard' ),";
			}				
			
			if($row['article'] == 1 && in_array('article', $wpr_modules)) {	
				$sql .= " ( 'post', '0', '";
				if($row['flickr'] == 1) {$sql .= "{thumbnail}\r\n";}
				$sql .= "{article}";
				if($row['youtube'] == 1) {$sql .= "\r\n[random:25]{youtube}[/random]\r\n";}
				$sql .= "\r\n[random:50][select:More <a href=\"{catlink}\">{Keyword} Articles</a>|Related <a href=\"{catlink}\">{Keyword} Articles</a>|Find More <a href=\"{catlink}\">{Keyword} Articles</a>][/random]', '{articletitle}', '0', '0', '0', '0', 'Article Standard' ),";
			}
			if($row['amazon'] == 1 && in_array('amazon', $wpr_modules)) {
				$sql .= " ( 'post', '1', '{amazon}\r\n[random:15]{amazon}[/random]";
				if($row['ebay'] == 1) {$sql .= "\r\n[random:50]{ebay} {ebay}[/random]\r\n";}
				$sql .= "\r\n[random:50][select:Mehr <a href=\"{catlink}\">{Keyword} Produkte</a>|&Auml;hnliche <a href=\"{catlink}\">{Keyword} Produkte</a>|Finden Sie weitere <a href=\"{catlink}\">{Keyword} Produkte</a>][/random]', '{amazontitle}[random:20] Rezessionen[/random]', '1', '0', '0', '0', 'Amazon Standard' ),";	
			}
			if($row['yahooanswers'] == 1 && in_array('yahooanswers', $wpr_modules)) {
				$sql .= " ( 'post', '2', '";
				if($row['flickr'] == 1) {$sql .= "[random:50]{thumbnail}[/random]\r\n";}
				$sql .= "{yahooanswers}', '[random:25]Q&A: [/random]{yahooanswerstitle}', '0', '0', '1', '0', 'Yahoo Answers Standard' ),";	
			}
			if($row['yahoonews'] == 1 && in_array('yahoonews', $wpr_modules)) {
				$sql .= " ( 'post', '3', '";	
				if($row['flickr'] == 1) {$sql .= "[random:25]{flickr}[/random]\r\n";}	
				$sql .= "{yahoonews}\r\n\r\n{yahoonews}\r\n\r\n[random:50]{yahoonews}[/random]\r\n\r\n[random:25]{yahoonews}[/random]', '[select:{yahoonewstitle}|{yahoonewstitle}|Neueste {Keyword} Nachrichten]', '0', '0', '0', '0', 'Yahoo News Standard' ),";			
			}
			if($row['clickbank'] == 1 && in_array('clickbank', $wpr_modules)) {
				$sql .= " ( 'post', '4', '[random:25]<p>[select:Interessante {keyword} Produkte:|Einige empfehlenswerte {keyword} Produkte:]</p>[/random]\r\n{clickbank}\r\n\r\n{clickbank}\r\n\r\n[random:25]{clickbank}[/random]";
				if($row['ebay'] == 1) {$sql .= "\r\n[random:25] {ebay} {ebay} [/random]";}
				$sql .= "', '{clickbanktitle}', '0', '0', '0', '0', 'Clickbank Standard' ),";			
			}	
			if($row['youtube'] == 1 && in_array('youtube', $wpr_modules)) {$sql .= " ( 'post', '5', '{youtube}\r\n[random:50]{youtube}[/random]', '{youtubetitle}', '0', '0', '0', '1', 'Youtube Standard' ),";}			
			if($row['ebay'] == 1 && in_array('ebay', $wpr_modules)) {$sql .= " ( 'post', '5', '<p>[select:Neue {keyword} Auktionen auf eBay:|Interessante {keyword} eBay Auktionen:|Popul&auml;rste {keyword} eBay Auktionen:|{Keyword} auf eBay:]</p>\r\n{ebay}\r\n{ebay}\r\n[random:50]{ebay}[/random]\r\n[random:25]{ebay}[/random]', '[select:{ebaytitle}|{ebaytitle}|Neue {Keyword} Auktionen|Popul&auml;rste {Keyword} Auktionen]', '0', '0', '0', '0', 'Ebay Standard' ),";}		
			if($row['flickr'] == 1 && in_array('flickr', $wpr_modules)) {$sql .= " ( 'post', '5', '<p>[select:Sch&ouml;ne {keyword} Bilder:|Einige tolle {keyword} Bilder:|Gute {keyword} Photos:]</p>\r\n{flickr}\r\n{flickr}\r\n[random:50]{flickr}[/random]', '[select:{flickrtitle}|{flickrtitle}|Tolle {Keyword} Bilder|Sch&ouml;ne {Keyword} Photos]', '0', '0', '0', '0', 'Flickr Standard' ),";}		
			
			if($row['itunes'] == 1 && in_array('itunes', $wpr_modules)) {		
				$sql .= " ( 'post', '12', '{itunes}', '{itunestitle}', '0', '0', '0', '0', 'iTunes Default' ),";
			}			
			if($row['linkshare'] == 1 && in_array('linkshare', $wpr_modules)) {		
				$sql .= " ( 'post', '13', '{linkshare}";
				if($row['amazon'] == 1) {$sql .= "\r\n\r\n[random:30] {amazonlist:3} [/random]";} elseif($row['ebay'] == 1) {$sql .= "\r\n\r\n[random:25] {ebay} {ebay} [/random]";}				
				$sql .= "', '{linksharetitle}', '0', '0', '0', '0', 'Linkshare Default' ),";					
			}	
			if($row['eventful'] == 1 && in_array('eventful', $wpr_modules)) {		
				$sql .= " ( 'post', '14', '{eventful}', '{eventfultitle}', '0', '0', '0', '0', 'Eventful Default' ),";
			}
			if($row['yelp'] == 1 && in_array('yelp', $wpr_modules)) {		
				$sql .= " ( 'post', '14', '{yelp}', '{yelptitle}', '0', '0', '0', '0', 'Yelp Default' ),";
			}	
			if($row['avantlink'] == 1 && in_array('avantlink', $wpr_modules)) {		
				$sql .= " ( 'post', '14', '{avantlink}', '{avantlinktitle}', '0', '0', '0', '0', 'Avantlink Default' ),";
			}			
			}

			$sql = substr_replace($sql ,";",-1);
		} else {$sql = "";}

		// Output SQL and Core	
		echo $core."###".$sql;
	}
}

?>