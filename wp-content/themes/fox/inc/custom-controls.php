<?php
if ( !class_exists( 'Wi_Heading_Control' ) ) :
/**
 * Custom Heading Control
 *
 * @since 2.3
 */
class Wi_Heading_Control extends WP_Customize_Control
{
    
    public $type = 'heading';
    
    public function render_content()
    {
        ?>
        <div class="wi-customize-heading">
            <h2><?php echo $this->label; ?></h2>
        </div>
<?php
    }
    
}

endif;

if ( !class_exists( 'Wi_Message_Control' ) ) :
/**
 * Custom Message Control
 *
 * Prints an instruction for ease of customization
 *
 * @since 2.3
 */
class Wi_Message_Control extends WP_Customize_Control
{
    
    public $type = 'message';
    
    public function render_content()
    {
        ?>
        <div class="wi-message">
            <?php echo wpautop( $this->value() ); ?>
        </div>
<?php
    }
    
}

endif;

/**
 * Custom HTML
 *
 * Prints html
 *
 * @since 2.3
 */
if ( !class_exists( 'Wi_HTML_Control' ) ) :

class Wi_HTML_Control extends WP_Customize_Control
{
    
    public $type = 'html';
    
    public function render_content()
    {
        echo $this->value();
        
    }
}

endif;

if ( !class_exists( 'Wi_Multicheckbox_Control' ) ) :
/**
 * Multicheckbox Control
 *
 * @since 2.3
 */
$wp_customize->register_control_type( 'Wi_Multicheckbox_Control' );

class Wi_Multicheckbox_Control extends WP_Customize_Control
{
    
    public $type = 'multicheckbox';
    
    /**
     * Compress to reduce size
     */
    protected function render() {
        $id    = 'customize-control-' . str_replace( array( '[', ']' ), array( '-', '' ), $this->id );
        $class = 'customize-control customize-control-' . $this->type;

        ?><li id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>"><?php $this->render_content(); ?></li><?php
    }
    
    /*
     * Don't render the control content from PHP, as it's rendered via JS on load.
     */
    public function render_content() {}
    
    /**
     * Refresh the parameters passed to the JavaScript via JSON.
     *
     * @uses WP_Customize_Control::to_json()
     */
    public function to_json() {
        parent::to_json();
        $this->json['choices'] = $this->choices;
    }
    
    /*
     * Render the content on the theme customizer page
     */
    public function content_template()
    {
        ?>
        <label>
            <# if ( data.label ) { #>
                <span class="customize-control-title">{{{ data.label }}}</span>
            <# } #>
            <# if ( data.description ) { #>
                <span class="description customize-control-description">{{{ data.description }}}</span>
            <# } #>
            <div class="customize-control-content">
                
                <?php $this->js_content(); ?>
                
            </div>
        </label>
        <?php
    }
    
    public function js_content() {
        ?>
        <ul>
            
            <# _.each( data.choices, function( value, key, obj ) { #>
                
            <li>
                <label>
                    <input type="checkbox" value="{{ key }}" />
                    {{{ value }}}
                </label>
            </li>

            <# }) #>
                
        </ul>
        <input type="hidden" class="checkbox-result" data-customize-setting-link="{{ data.settings.default }}" />
    <?php
    }
}

endif;