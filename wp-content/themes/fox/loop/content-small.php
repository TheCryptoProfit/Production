<article id="post-<?php the_ID(); ?>" <?php post_class('post-small small-item'); ?>><div class="small-inner">
    
    <?php wi_display_thumbnail('thumbnail-medium','small-thumbnail',true,true); ?>
        
    <section class="small-body">
            
        <header class="small-header">

            <div class="small-meta">

                <span class="small-date">
                    <time datetime="<?php echo get_the_date('c');?>" title="<?php echo esc_attr(get_the_date(get_option('date_format')));?>"><?php echo get_the_date(get_option('date_format'));?></time>
                </span>

            </div><!-- .small-meta -->

            <h3 class="small-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>

        </header><!-- .small-header -->

        <div class="small-excerpt">
            <?php echo wi_subword(get_the_excerpt(), 0, 12);?>
        </div>

        <div class="clearfix"></div>
     <?php echo do_shortcode('[cm_ad_changer campaign_id="1" class="CM Ad" debug="1"]'); ?>
         <?php echo do_shortcode('[cm_ad_changer campaign_id="2" class="CM Ad" debug="1"]'); ?>
          <?php echo do_shortcode('[cm_ad_changer campaign_id="3" class="CM Ad" debug="1"]'); ?>
           <?php echo do_shortcode('[cm_ad_changer campaign_id="4" class="CM Ad" debug="1"]'); ?>
            <?php echo do_shortcode('[cm_ad_changer campaign_id="5" class="CM Ad" debug="1"]'); ?>
             <?php echo do_shortcode('[cm_ad_changer campaign_id="6" class="CM Ad" debug="1"]'); ?>
              <?php echo do_shortcode('[cm_ad_changer campaign_id="7" class="CM Ad" debug="1"]'); ?>
               <?php echo do_shortcode('[cm_ad_changer campaign_id="8" class="CM Ad" debug="1"]'); ?>
                <?php echo do_shortcode('[cm_ad_changer campaign_id="9" class="CM Ad" debug="1"]'); ?>
                 <?php echo do_shortcode('[cm_ad_changer campaign_id="10" class="CM Ad" debug="1"]'); ?>
                  <?php echo do_shortcode('[cm_ad_changer campaign_id="11" class="CM Ad" debug="1"]'); ?>
                   <?php echo do_shortcode('[cm_ad_changer campaign_id="12" class="CM Ad" debug="1"]'); ?>
                    <?php echo do_shortcode('[cm_ad_changer campaign_id="13" class="CM Ad" debug="1"]'); ?>
                     <?php echo do_shortcode('[cm_ad_changer campaign_id="14" class="CM Ad" debug="1"]'); ?>
                      <?php echo do_shortcode('[cm_ad_changer campaign_id="15" class="CM Ad" debug="1"]'); ?>
                       <?php echo do_shortcode('[cm_ad_changer campaign_id="16" class="CM Ad" debug="1"]'); ?>
                        <?php echo do_shortcode('[cm_ad_changer campaign_id="17" class="CM Ad" debug="1"]'); ?>
                         <?php echo do_shortcode('[cm_ad_changer campaign_id="18" class="CM Ad" debug="1"]'); ?>
                          <?php echo do_shortcode('[cm_ad_changer campaign_id="19" class="CM Ad" debug="1"]'); ?>
                           <?php echo do_shortcode('[cm_ad_changer campaign_id="20" class="CM Ad" debug="1"]'); ?>
                            <?php echo do_shortcode('[cm_ad_changer campaign_id="21" class="CM Ad" debug="1"]'); ?>
                             <?php echo do_shortcode('[cm_ad_changer campaign_id="22" class="CM Ad" debug="1"]'); ?> <?php echo do_shortcode('[cm_ad_changer campaign_id="23" class="CM Ad" debug="1"]'); ?>
                              <?php echo do_shortcode('[cm_ad_changer campaign_id="1" class="CM Ad" debug="24"]'); ?>
                               <?php echo do_shortcode('[cm_ad_changer campaign_id="1" class="CM Ad" debug="25"]'); ?>
                                <?php echo do_shortcode('[cm_ad_changer campaign_id="1" class="CM Ad" debug="26"]'); ?>
                                 <?php echo do_shortcode('[cm_ad_changer campaign_id="1" class="CM Ad" debug="27"]'); ?>
                                  <?php echo do_shortcode('[cm_ad_changer campaign_id="1" class="CM Ad" debug="28"]'); ?>
                                   <?php echo do_shortcode('[cm_ad_changer campaign_id="1" class="CM Ad" debug="29"]'); ?>
                                    <?php echo do_shortcode('[cm_ad_changer campaign_id="1" class="CM Ad" debug="30"]'); ?>
                                     <?php echo do_shortcode('[cm_ad_changer campaign_id="31" class="CM Ad" debug="1"]'); ?>
                                        
    </section><!-- .small-body -->
    
    <div class="clearfix"></div>
    
    </div></article><!-- .post-small -->
