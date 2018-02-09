<?php
/***************************************************************************
*
*	AuctionTheme - copyright (c) - sitemile.com
*	The most popular auction theme for wordress on the internet. Launch your
*	auction site in minutes from purchasing. Turn-key solution.
*
*	Coder: Andrei Dragos Saioc
*	Email: sitemile[at]sitemile.com | andreisaioc[at]gmail.com
*	More info about the theme here: http://sitemile.com/p/auctionTheme
*	since v4.4.7.1
*
***************************************************************************/


add_action('wp_ajax_autosuggest_it', 				'AuctionTheme_autosuggest_it');
add_action('wp_ajax_nopriv_autosuggest_it', 		'AuctionTheme_autosuggest_it');

function AuctionTheme_autosuggest_it()
{

	include('classes/stem.php');
	include('classes/cleaner.php');
	global $wpdb;
	
	$string			 	= $_POST['queryString']; 
	$stemmer 			= new Stemmer;
	$stemmed_string 	= $stemmer->stem ( $string );
	
		$clean_string = new jSearchString();
		$stemmed_string = $clean_string->parseString ( $stemmed_string );		
		
		$new_string = '';
		foreach ( array_unique ( split ( " ",$stemmed_string ) ) as $array => $value )
		{
			if(strlen($value) >= 1)
			{
				$new_string .= ''.$value.' ';
			}
		}
	
	
	$new_string = substr ( $new_string,0, ( strLen ( $new_string ) -1 ) );
		
		$new_string = htmlspecialchars($_POST['queryString']);
		
		if ( strlen ( $new_string ) > 0 ):
		
			$split_stemmed = split ( " ",$new_string );
		        
		    
			$sql = "SELECT DISTINCT COUNT(*) as occurences, ".$wpdb->prefix."posts.post_title, ".$wpdb->prefix."posts.ID FROM ".$wpdb->prefix."posts,
			".$wpdb->prefix."postmeta WHERE ".$wpdb->prefix."posts.post_status='publish' and 
			".$wpdb->prefix."posts.post_type='auction' 
			
					AND ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_id 
					AND ".$wpdb->prefix."postmeta.meta_key = 'closed' 
					AND ".$wpdb->prefix."postmeta.meta_value = '0' 
			
			AND (";
		             
			while ( list ( $key,$val ) = each ( $split_stemmed ) )
			{
		              if( $val!='' && strlen ( $val ) > 0 )
		              {
		              	$sql .= "(".$wpdb->prefix."posts.post_title LIKE '%".$val."%' OR ".$wpdb->prefix."posts.post_content LIKE '%".$val."%') OR";
		              }
			}
			
			$sql=substr ( $sql,0, ( strLen ( $sql )-3 ) );//this will eat the last OR
			$sql .= ") GROUP BY ".$wpdb->prefix."posts.post_title ORDER BY occurences DESC LIMIT 10";
		
		
			//echo $sql; 
			global $wpdb;
			$query = $wpdb->get_results($sql, ARRAY_A );// or die ( mysql_error () );
			//$row_sql = mysql_fetch_assoc ( $query );
			$total = count($query) ; // ( $query );
			 
			if($total>0):
					
					foreach ( $query as $row ): 
						echo '<ul id="small_search_res">';	
						$prm = get_permalink($row['ID']);			
								echo '<li onClick="window.location=\''.$prm.'\';"> <h2>'.AuctionTheme_get_first_post_image($row['ID'],36,36).' </h2> <p>'.$row['post_title'].'</p></li>';
						echo '</ul>';			
					endforeach;
			else:
			
			
			echo '<ul>';				
	         		echo '<li onClick="fill(\''.$new_string.'\');">'.__('No results found','AuctionTheme').'</li>';
	        echo '</ul>';
					
				
			endif;
		endif;

	
		
}

?>