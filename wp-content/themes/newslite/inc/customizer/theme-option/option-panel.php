<?php
global $newslite_panels;
/*creating panel for theme settings*/
$newslite_panels['newslite-theme-options'] =
    array(
        'title'          => __( 'Theme Options', 'newslite' ),
        'priority'       => 235
    );

/*footer options */
require get_template_directory().'/inc/customizer/theme-option/footer.php';

/*layout options */
require get_template_directory().'/inc/customizer/theme-option/layout-options.php';

/*breadcrumb options */
require get_template_directory().'/inc/customizer/theme-option/breadcrumb.php';

/*back to top options */
require get_template_directory().'/inc/customizer/theme-option/back-to-top.php';