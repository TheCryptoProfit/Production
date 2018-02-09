<?php
global $wp_query, $post;
$title = ''; $subtitle = ''; $lable = '';
if (is_home()) return;
?>
<div id="titlebar">
    <div class="container">
	
<?php 
if ( is_category() ) {
    $label = __('Category archive','wi');
    $this_cat = get_category(get_query_var('cat'), false);
    $title = single_cat_title('', false);
    $subtitle = do_shortcode( trim($this_cat->description) );
} elseif ( is_search() ) {
    $label = __('Search result','wi');
    $title  = get_search_query();
    $subtitle = sprintf(__('%s result(s) found.','wi'), $wp_query->found_posts);
} elseif ( is_day() ) {
    $label = __('Daily archive','wi');
    $title = get_the_time('F d, Y');
} elseif ( is_month() ) {
    $label = __('Monthly archive','wi');
    $title = get_the_time('F Y');
} elseif ( is_year() ) {
    $label = __('Yearly archive','wi');
    $title = get_the_time('Y');
} elseif ( is_tag() ) {
    $label = __('Tag archive','wi');
    $tag_id = intval(get_query_var('tag_id'));
    $this_tag = get_term($tag_id , 'post_tag');
    $title = sprintf(__('%s','wi'), single_tag_title('', false) );
    $subtitle = do_shortcode ( trim ($this_tag->description));
} elseif ( is_author() ) {
    $label = __('Author','wi');
    global $author;
    $userdata = get_userdata($author);
    $title = $userdata->display_name;
    $count = count_user_posts($userdata->ID);
    $subtitle = sprintf( __('<span>%1$s</span> has %2$s articles published.','wi'), $title, $count );
} elseif ( is_404() ) {
    $label = __('Not found','wi');
    $title = __('404','wi');
}

if ( get_query_var('paged') ) {			
    $page_text = sprintf(__(' - page %d','wi') , get_query_var('paged') );
}	else $page_text = '';

$title = $title . $page_text;
?>
        <div class="title-area">
            <?php if ($label) { ?>
            <span class="title-label"><span><?php echo esc_html($label);?></span></span>
            <?php } ?>
            <h1 class="archive-title"><span><?php echo wp_kses($title,'');?></span></h1>
            <?php if ( $subtitle ) {?>
            <h2 class="page-subtitle"><?php echo wp_kses($subtitle,'');?></h2>
            <?php } ?>
            
            <?php if ( is_author() ) : ?>
	
            <div class="headline-authorbox">
                
                <div class="heading-author-avatar">
                    <?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'wi_author_bio_avatar_size', 120 ) ); ?>
                </div>
                
                <div class="desc">
                    <?php the_author_meta( 'description', $userdata->ID ); ?>
                </div>
                <div class="author-social social-list">
                    <ul>
                        <?php $short_social_arr = 'twitter, facebook-square, google-plus, tumblr, instagram, pinterest-p, linkedin, youtube, vimeo, soundcloud, flickr';
                        $short_social_arr = explode(',',$short_social_arr);
                        $short_social_arr = array_map('trim',$short_social_arr);
                        ?>
                        <?php foreach ( $short_social_arr as $sc ): ?>
                            <?php if ( $url = get_the_author_meta($sc, $userdata->ID) ): ?>
                            <?php if ($sc == 'google-plus') $rel = 'publisher'; else $rel = 'alternate'; ?>
                            <li><a href="<?php echo esc_url($url);?>" rel="<?php echo esc_attr($rel);?>" target="_blank"><i class="fa fa-<?php echo esc_attr($sc);?>"></i></a></li>

                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                    <div class="clearfix"></div>
                </div><!-- .author-social -->
                
            </div>

            <?php endif; ?>
            
        </div><!-- .title-area -->
        
    </div><!-- .container -->
</div><!-- #headline -->