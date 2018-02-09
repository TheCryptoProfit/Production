<?php
/**
 * newslite Custom Metabox
 *
 * @package newslite
 */
$newslite_post_types = array(
    'post',
    'page'
);

add_action('add_meta_boxes', 'newslite_add_layout_metabox');
function newslite_add_layout_metabox() {
    global $post;
    $frontpage_id = get_option('page_on_front');
    if( $post->ID == $frontpage_id ){
        return;
    }

    global $newslite_post_types;
    foreach ( $newslite_post_types as $post_type ) {
        add_meta_box(
            'newslite_layout_options', // $id
            __( 'Layout options', 'newslite' ), // $title
            'newslite_layout_options_callback', // $callback
            $post_type, // $page
            'normal', // $context
            'high'// $priority
        );
    }

}
/* newslite sidebar layout */
$newslite_default_layout_options = array(
    'left-sidebar' => array(
        'value'     => 'left-sidebar',
        'thumbnail' => get_template_directory_uri() . '/inc/images/left-sidebar.png'
    ),
    'right-sidebar' => array(
        'value' => 'right-sidebar',
        'thumbnail' => get_template_directory_uri() . '/inc/images/right-sidebar.png'
    ),
    'no-sidebar' => array(
        'value'     => 'no-sidebar',
        'thumbnail' => get_template_directory_uri() . '/inc/images/no-sidebar.png'
    )
);
/* newslite featured layout */
$newslite_single_post_image_align_options = array(
    'full' => array(
        'value' => 'full',
        'label' => __( 'Full', 'newslite' )
    ),
    'right' => array(
        'value' => 'right',
        'label' => __( 'Right ', 'newslite' ),
    ),
    'left' => array(
        'value'     => 'left',
        'label' => __( 'Left', 'newslite' ),
    ),
    'no-image' => array(
        'value'     => 'no-image',
        'label' => __( 'No Image', 'newslite' )
    )
);

function newslite_layout_options_callback() {

    global $post , $newslite_default_layout_options, $newslite_single_post_image_align_options;
    $newslite_customizer_saved_values = newslite_get_all_options(1);

    /*newslite-single-post-image-align*/
    $newslite_single_post_image_align = $newslite_customizer_saved_values['newslite-single-post-image-align'];

    /*newslite default layout*/
    $newslite_single_sidebar_layout = $newslite_customizer_saved_values['newslite-default-layout'];

    wp_nonce_field( basename( __FILE__ ), 'newslite_layout_options_nonce' );
    ?>
    <table class="form-table page-meta-box">
        <!--Image alignment-->
        <tr>
            <td colspan="4"><em class="f13"><?php _e( 'Choose Sidebar Template', 'newslite' ); ?></em></td>
        </tr>
        <tr>
            <td>
                <?php
                $newslite_single_sidebar_layout_meta = get_post_meta( $post->ID, 'newslite-default-layout', true );
                if( false != $newslite_single_sidebar_layout_meta ){
                   $newslite_single_sidebar_layout = $newslite_single_sidebar_layout_meta;
                }
                foreach ($newslite_default_layout_options as $field) {
                    ?>
                    <div class="hide-radio radio-image-wrapper" style="float:left; margin-right:30px;">
                        <input id="<?php echo esc_attr( $field['value'] ); ?>" type="radio" name="newslite-default-layout"
                               value="<?php echo esc_attr( $field['value'] ); ?>"
                            <?php checked( $field['value'], $newslite_single_sidebar_layout ); ?>/>
                        <label class="description" for="<?php echo esc_attr( $field['value'] ); ?>">
                            <img src="<?php echo esc_url( $field['thumbnail'] ); ?>" />
                        </label>
                    </div>
                <?php } // end foreach
                ?>
                <div class="clear"></div>
            </td>
        </tr>
        <tr>
            <td><em class="f13"><?php _e( 'You can set up the sidebar content', 'newslite' ); ?> <a href="<?php echo esc_url( admin_url('/widgets.php') ); ?>"><?php _e( 'here', 'newslite' ); ?></a></em></td>
        </tr>
        <!--Image alignment-->
        <tr>
            <td colspan="4"><?php _e( 'Featured Image Alignment', 'newslite' ); ?></td>
        </tr>
        <tr>
            <td>
                <?php
                $newslite_single_post_image_align_meta = get_post_meta( $post->ID, 'newslite-single-post-image-align', true );
                if( false != $newslite_single_post_image_align_meta ){
                    $newslite_single_post_image_align = $newslite_single_post_image_align_meta;
                }
                foreach ($newslite_single_post_image_align_options as $field) {
                    ?>
                    <input id="<?php echo esc_attr( $field['value'] ); ?>" type="radio" name="newslite-single-post-image-align" value="<?php echo esc_attr( $field['value'] ); ?>" <?php checked( $field['value'], $newslite_single_post_image_align ); ?>/>
                    <label class="description" for="<?php echo esc_attr( $field['value'] ); ?>">
                        <?php echo esc_html( $field['label'] ); ?>
                    </label>
                <?php } // end foreach
                ?>
                <div class="clear"></div>
            </td>
        </tr>
    </table>

<?php }

/**
 * save the custom metabox data
 * @hooked to save_post hook
 */
function newslite_save_sidebar_layout( $post_id ) {
    global $post;
    // Verify the nonce before proceeding.
    if ( !isset( $_POST[ 'newslite_layout_options_nonce' ] ) || !wp_verify_nonce( $_POST[ 'newslite_layout_options_nonce' ], basename( __FILE__ ) ) ) {
        return;
    }

    // Stop WP from clearing custom fields on autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( !current_user_can( 'edit_page', $post_id ) ) {
        return $post_id;
    }
    
    if(isset($_POST['newslite-default-layout'])){
        $old = get_post_meta( $post_id, 'newslite-default-layout', true);
        $new = sanitize_text_field($_POST['newslite-default-layout']);
        if ($new && $new != $old) {
            update_post_meta($post_id, 'newslite-default-layout', $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id,'newslite-default-layout', $old);
        }
    }

    /*image align*/
    if(isset($_POST['newslite-single-post-image-align'])){
        $old = get_post_meta( $post_id, 'newslite-single-post-image-align', true);
        $new = sanitize_text_field($_POST['newslite-single-post-image-align']);
        if ($new && $new != $old) {
            update_post_meta($post_id, 'newslite-single-post-image-align', $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id,'newslite-single-post-image-align', $old);
        }
    }
}
add_action('save_post', 'newslite_save_sidebar_layout');
