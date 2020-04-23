<?php
/* ========================================================== */
/* TITLE CAPYBARA ------------------------------------------- */
/* ========================================================== */
function wp_capybara() {
    add_menu_page( 'Custom Theme', 'Capybara', 'manage_options', 'wp_capybara', 'wp_capybara_options', 'dashicons-image-filter' );
}
add_action( 'admin_menu', 'wp_capybara' );
/* ========================================================== */
/* CORE CAPYBARA -------------------------------------------- */
/* ========================================================== */
function wp_capybara_options() {

    if (!current_user_can('manage_options')){
        wp_die( 'Pequeño padawan... debes utilizar la fuerza para entrar aquí.' );
    }
    $hidden_field_name = 'wp_capybara_data_hidden';
    $data_field_name = 'wp_capybara_name';

    $opt_val = get_option( $data_field_name );

        if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'ruta_hidden') {
            $opt_val = $_POST[ $data_field_name ]; update_option( $data_field_name, $opt_val );
            echo '<div class="updated"><p><strong>Cambios guardados correctamente.</strong></p></div>';
        } ?>

    <div class="wrap">
        <h2>Theme - Configuración</h2>
        <style type="text/css">#wpfooter{position:relative !important;}.tooltip-giftwoo{position: relative;display: inline-block;}.tooltip-giftwoo .tooltiptext{visibility: hidden;width: 200px;background-color: black;color: #fff;text-align: center;border-radius: 6px;padding: 5px 0;position: absolute;z-index: 1;}.tooltip-giftwoo:hover .tooltiptext {visibility: visible;}.tooltip-giftwoo:after{content: "\f223";font-family: dashicons !important;}</style>
        <form name="form1" method="post" action="">
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="ruta_hidden">

            <div class="widget-liquid-left">
            <p>Descripción envío:<br>
                <textarea name="<?php echo $data_field_name; ?>" rows="10" cols="50"><?php echo $opt_val; ?></textarea>
            </div>

            <div style="width:100%;float:left;">
                <hr />
                <p class="submit"><input type="submit" name="Submit" class="button-primary" value="Guardar Datos" /></p>
            </div>
        </form>
    </div>

<?php }
/* ========================================================== */
/* OBTENEMOS DATOS EN FUNCION ------------------------------- */
/* ========================================================== */
function get_data_capybara($attr) {
    if(empty($attr)){$attr = 'wp_capybara_name';}
    $dbh = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
    global $table_prefix;
    $table = $table_prefix.'options';
    $query_link = "SELECT `option_value` FROM $table WHERE `option_name` = '". $attr ."'";
    $res_link = $dbh->get_results( $query_link );
    return $res_link[0]->option_value;
}
/******************************************************************************/
/****** CUSTOM ELEMENTS - Marcel CL *******************************************/
/******************************************************************************/
// Eliminar metabox Welcome del Dashboard
function remove_welcome_panel(){
    remove_action('welcome_panel', 'wp_welcome_panel');
}
add_action( 'load-index.php', 'remove_welcome_panel' );
// Widget para el Dashboard
function custom_dashboard_widget() {
$custom_logo_id = get_theme_mod( 'custom_logo' );
$logo_add = wp_get_attachment_image_src( $custom_logo_id , 'full' ); ?>
    <img src="<?php echo $logo_add[0]; ?>" width="250" />
<?php }
function add_custom_dashboard_widget() {
    wp_add_dashboard_widget('custom_dashboard_widget', 'Panel de Administración - YOURZED', 'custom_dashboard_widget');
}
add_action('wp_dashboard_setup', 'add_custom_dashboard_widget');
// Añadir LogOut AdminBar Panel
function custom_logout_link() {
    global $wp_admin_bar;
    $wp_admin_bar->add_menu( array(
        'id'    => 'custom-logout',
        'title' => 'Cerrar Sesión',
        'parent'=> 'top-secondary',
        'href'  => wp_logout_url()
    ) );
    $wp_admin_bar->remove_menu('my-account');
}
add_action( 'wp_before_admin_bar_render', 'custom_logout_link' );
// Añadir Icono nuevo enlace AdminBar Panel
function icon_link_simple_toolbar() {
    echo '<style>#wp-toolbar .rolinecontact .contact.ab-icon:before { content: "\f466"; top: 2px; } #wp-toolbar .inicio .inicio.ab-icon:before { content: "\f102"; top: 2px; } .wp-admin #wpadminbar #wp-admin-bar-site-name>.ab-item:before{content: "\f319";}.vc_cta_mcl{background-image:url("/wp-content/themes/X/assets/icon_cta.png") !important;}</style>';
}
add_action( 'admin_head', 'icon_link_simple_toolbar' );
// Ocultar Versión WP Generator
remove_action('wp_head', 'wp_generator');
// Eliminar Elementos AdminBar Panel
function eliminar_nodos_admin_bar() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');          // Elimina el logo de WordPress (desaparece también todo el submenú)
    $wp_admin_bar->remove_menu('updates');          // Elimina el icono de notificación de actualizaciones
    $wp_admin_bar->remove_menu('comments');         // Elimina el acceso directo a los comentarios
}
add_action('wp_before_admin_bar_render', 'eliminar_nodos_admin_bar');
function my_login_logo() {
$custom_logo_id = get_theme_mod( 'custom_logo' );
$logo_add = wp_get_attachment_image_src( $custom_logo_id , 'full' ); ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
        background-image: url('<?php echo $logo_add[0]; ?>');
        width:100%;
        background-repeat: no-repeat;
        background-size: contain;
        background-position: center;
        }
        .wp-core-ui .button-primary, .wp-core-ui .button-primary.active, .wp-core-ui .button-primary.active:focus, .wp-core-ui .button-primary.active:hover, .wp-core-ui .button-primary:active{
            text-shadow: none !important;
            box-shadow: none !important;
        }
        body{
            background: #fff !important;
        }
        .wp-core-ui .button-primary{
            background: #000 !important;
            border-color: #000 !important; 
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

/******************************************************************************/
/****** DESACTIVAR COMENTARIOS - Marcel CL ************************************/
/******************************************************************************/
// Disable support for comments and trackbacks in post types
function df_disable_comments_post_types_support() {
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if(post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}
add_action('admin_init', 'df_disable_comments_post_types_support');

// Close comments on the front-end
function df_disable_comments_status() {
    return false;
}
add_filter('comments_open', 'df_disable_comments_status', 20, 2);
add_filter('pings_open', 'df_disable_comments_status', 20, 2);

// Hide existing comments
function df_disable_comments_hide_existing_comments($comments) {
    $comments = array();
    return $comments;
}
add_filter('comments_array', 'df_disable_comments_hide_existing_comments', 10, 2);

// Remove comments page in menu
function df_disable_comments_admin_menu() {
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'df_disable_comments_admin_menu');

// Redirect any user trying to access comments page
function df_disable_comments_admin_menu_redirect() {
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url()); exit;
    }
}
add_action('admin_init', 'df_disable_comments_admin_menu_redirect');

// Remove comments metabox from dashboard
function df_disable_comments_dashboard() {
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'df_disable_comments_dashboard');

// Remove comments links from admin bar
function df_disable_comments_admin_bar() {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
}
add_action('init', 'df_disable_comments_admin_bar');

/******************************************************************************/
/********* + ADD SLUG BODY CLASS **********************************************/
/******************************************************************************/
function add_slug_body_class( $classes ) {
    global $post;
    if ( isset( $post ) ) {
        $classes[] = $post->post_type . '-' . $post->post_name;
    }
    return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );
/******************************************************************************/
/****** +  ADD NEW NAV MENU ***************************************************/
/******************************************************************************/
function wpb_custom_new_menu() {
  register_nav_menu('secondary',__( 'Secondary Menu' ));
}
add_action( 'init', 'wpb_custom_new_menu' );
/******************************************************************************/
/****** +  EDIT PRIV EDITOR ROLE **********************************************/
/******************************************************************************/
$role_object = get_role( 'editor' );
$role_object->add_cap( 'edit_theme_options' );
function hide_menu() {
    if (current_user_can('editor')) {
        remove_submenu_page( 'themes.php', 'themes.php' ); // hide the theme selection submenu
        remove_submenu_page( 'themes.php', 'widgets.php' ); // hide the widgets submenu
        remove_submenu_page( 'themes.php', 'customize.php?return=%2Fwp-admin%2Findex.php' ); // hide the customizer submenu
        remove_submenu_page( 'themes.php', 'customize.php?return=%2Fwp-admin%2Fnav-menus.php' ); // hide the customizer submenu
    }
}
add_action('admin_head', 'hide_menu');
/******************************************************************************/
/****** + Create Custom Sidebars - Marcel CL **********************************/
/******************************************************************************/

function MCLCustomSidebar() {
    register_sidebar(
        array (
            'name' => 'Bottom 1',
            'id' => 'bottom-1',
            'description' => 'Aparece en la parte inferior del contenido, en entradas y páginas.',
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '<h4 class="widget__title">',
            'after_title' => '</h4>',
        )
    );
    register_sidebar(
        array (
            'name' => 'Bottom 2',
            'id' => 'bottom-2',
            'description' => 'Aparece en la parte inferior del contenido, en entradas y páginas.',
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '<h4 class="widget__title">',
            'after_title' => '</h4>',
        )
    );
    register_sidebar(
        array (
            'name' => 'Bottom 3',
            'id' => 'bottom-3',
            'description' => 'Aparece en la parte inferior del contenido, en entradas y páginas.',
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '<h4 class="widget__title">',
            'after_title' => '</h4>',
        )
    );
    register_sidebar(
        array (
            'name' => 'Bottom 4',
            'id' => 'bottom-4',
            'description' => 'Aparece en la parte inferior del contenido, en entradas y páginas.',
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '<h4 class="widget__title">',
            'after_title' => '</h4>',
        )
    );
    register_sidebar(
        array (
            'name' => 'Bottom 5',
            'id' => 'bottom-5',
            'description' => 'Aparece en la parte inferior del contenido, en entradas y páginas.',
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '<h4 class="widget__title">',
            'after_title' => '</h4>',
        )
    );
    register_sidebar(
        array (
            'name' => 'Sub Footer 1',
            'id' => 'subfooter-1',
            'description' => 'Aparece en la parte inferior del contenido, en entradas y páginas.',
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '<h4 class="widget__title">',
            'after_title' => '</h4>',
        )
    );
    register_sidebar(
        array (
            'name' => 'Sub Footer 2',
            'id' => 'subfooter-2',
            'description' => 'Aparece en la parte inferior del contenido, en entradas y páginas.',
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '<h4 class="widget__title">',
            'after_title' => '</h4>',
        )
    );
}
add_action( 'widgets_init', 'MCLCustomSidebar' );

/******************************************************************************/
/****** CUSTOM VISUAL COMPOSER CTA - Marcel CL ********************************/
/******************************************************************************/

// Before VC Init
add_action( 'vc_before_init', 'vc_before_init_actions' );

function vc_before_init_actions() {
    //require_once( get_stylesheet_directory().'/vc_templates/vc_arrow_marcelcl.php' );
    //require_once( get_stylesheet_directory().'/vc_templates/vc_block_marcelcl.php' );
    //require_once( get_stylesheet_directory().'/vc_templates/vc_iparallax_marcelcl.php' );
    require_once( get_stylesheet_directory().'/vc_templates/vc_cta_marcelcl.php' );
}

/******************************************************************************/
/****** ADD JUSTIFY AND UNDERLINE BTN TINY MCE - Marcel CL ********************/
/******************************************************************************/
/* UNDERLINE */
function ratb_tiny_mce_buttons_underline( $buttons_array ){   
    if ( !in_array( 'underline', $buttons_array ) ){
        $inserted = array( 'underline' );
        array_splice( $buttons_array, 0, 0, $inserted );
    }
    return $buttons_array;   
}
/* JUSTIFY */
function ratb_tiny_mce_buttons_justify( $buttons_array ){   
    if ( !in_array( 'alignjustify', $buttons_array ) && in_array( 'alignright', $buttons_array ) ){
        $key = array_search( 'alignright', $buttons_array );
        $inserted = array( 'alignjustify' );
        array_splice( $buttons_array, $key + 1, 0, $inserted );
    }
    return $buttons_array;   
}
function ratb_buttons_lines_tiny_mce(){
    add_filter( 'mce_buttons', 'ratb_tiny_mce_buttons_justify', 5 );
    add_filter( 'mce_buttons_2', 'ratb_tiny_mce_buttons_underline', 5 );    
}    
add_action( 'admin_init', 'ratb_buttons_lines_tiny_mce' );

/******************************************************************************/
/****** ADD CUSTOM JS - Marcel CL *********************************************/
/******************************************************************************/
function mcl_custom_js() {
    wp_register_script('sidebarEffects', get_stylesheet_directory_uri() . '/assets/js/sidebarEffects.js', array(),'1.0.0', true);
    wp_enqueue_script('sidebarEffects');
    wp_register_script('classie', get_stylesheet_directory_uri() . '/assets/js/classie.js', array(),'1.0.0', true);
    wp_enqueue_script('classie');
    //wp_register_script('mcl_parallax', get_stylesheet_directory_uri() . '/assets/js/iparallax.js', array(),'3.1.1', true);
    //wp_enqueue_script('mcl_parallax');
    wp_register_script('mcl_custom', get_stylesheet_directory_uri() . '/assets/js/custom.js', array(),'1.0.0', true);
    wp_enqueue_script('mcl_custom');

    wp_register_script('boots_js', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.8/slick.min.js', array(),'1.5.8', true);
    wp_enqueue_script('boots_js');
} 

add_action( 'wp_enqueue_scripts', 'mcl_custom_js', 999 ); 

/******************************************************************************/
/****** + Loop Custom POST (Mode 1 & 2) - Marcel CL ***************************/
/******************************************************************************/

function LoopMode($atts, $content = null) {
    extract(shortcode_atts(array(
        'pagination' => 'false',
        'posts_per_page' => '',
        'query' => '',
        'category' => '',
        'taxonomy' => '',
        'style' => '',
        'type' => '',
        'orderby' => ''
    ), $atts));
    global $wp_query,$paged,$post;
    $temp = $wp_query;
    $wp_query= null;
    $wp_query = new WP_Query();
    if ($pagination == 'true') { $query .= '&paged='.$paged; }
    if (!empty($category)) { $query .= '&category_name='.$category; }
    if (!empty($taxonomy)) { $query .= '&taxonomy_name='.$taxonomy; }
    if(!empty($style)&&($style == 'carousel-gastronomia')){
        if (!empty($taxonomy)) { $query .= '&gastronomia_category='.$taxonomy; }
    }
    if (!empty($type)) { $query .= '&post_type='.$type; }
    if (!empty($posts_per_page)) { $query .= '&posts_per_page='.$posts_per_page; }
    if (!empty($orderby)) { $query .= '&orderby=rand'; }
    if (!empty($query)) { $query .= $query; }
    $wp_query->query($query);
    ob_start();
    ?>
        <?php if ($style == 'row') { ?>
            <div class="site-content">
                <div class="vc_row wpb_row vc_row-fluid">
                    <?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
                    <div class="wpb_column vc_column_container vc_col-sm-6">
                        <div class="vc_column-inner ">
                            <div class="wpb_wrapper">
                                <div class="wpb_text_column wpb_content_element ">
                                    <div class="wpb_wrapper blog-home">
                                        <a href="<?php the_permalink() ?>" class="read-on">
                                        <div class="img-blog" style="background-image:url(<?php the_post_thumbnail_url(); ?>);background-position:center center;background-size:cover;background-repeat:no-repeat;width:100%;height:350px;"></div>
                                        <div class="link-blog">
                                            <div class="links-data"><?php echo get_the_date('d-m-Y'); ?> | <?php echo get_the_category($post->ID)[0]->name; ?></div>
                                            <span><?php the_title(); ?></span>
                                        </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php } elseif($style == 'testimonio') { ?>
            <div class="site-content">
                <div class="vc_row wpb_row vc_row-fluid">
                    <?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
                    <div class="wpb_column vc_column_container vc_col-sm-4">
                        <div class="vc_column-inner ">
                            <div class="wpb_wrapper">
                                <div class="wpb_text_column wpb_content_element ">
                                    <div class="wpb_wrapper">
                                        <?php the_title(); ?>
                                        <br>
                                        <?php the_content(); ?>
                                        <br>
                                        – <?php the_excerpt(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php } elseif($style == 'carousel') { ?>
            <div class="carousel">
            <?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
                <div class="blog-home">
                    <a href="<?php the_permalink() ?>" class="read-on">
                        <div class="img-blog" style="width:100%;">
                            <img src="<?php the_post_thumbnail_url(array(400, 300)); ?>" />
                        </div>
                        <div class="link-blog">
                            <div class="links-data"><?php echo get_the_date('d-m-Y'); ?> | <?php echo get_the_category($post->ID)[0]->name; ?></div>
                            <div class="animated-arrow">
                              <span class="the-arrow -left">
                                <span class="shaft"></span>
                              </span>
                              <span class="main">
                                <span class="text">
                                  <?php the_title(); ?>
                                </span>
                                <span class="the-arrow -right">
                                  <span class="shaft"></span>
                                </span>
                              </span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
            </div>
        <?php } elseif($style == 'carousel-espacios') { ?>
            <div class="carousel carousel-espacios">
            <?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
                <div class="blog-home">
                    <a href="<?php the_permalink() ?>" class="read-on">
                        <div class="img-blog" style="width:100%;">
                            <img src="<?php the_post_thumbnail_url(array(400, 300)); ?>" />
                        </div>
                        <div class="link-blog">
                            <div class="animated-arrow">
                              <span class="the-arrow -left">
                                <span class="shaft"></span>
                              </span>
                              <span class="main">
                                <span class="text">
                                  <?php the_title(); ?>
                                </span>
                                <span class="the-arrow -right">
                                  <span class="shaft"></span>
                                </span>
                              </span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
            </div>
        <?php } elseif($style == 'carousel-gastronomia') { ?>
            <div class="carousel carousel-gastronomia">
            <?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
                <div class="blog-home">
                    <div class="img-blog" style="width:100%;">
                        <img src="<?php the_post_thumbnail_url(array(400, 300)); ?>" />
                    </div>
                    <div class="link-blog">
                        <span><?php the_title(); ?></span>
                    </div>
                </div>
            <?php endwhile; ?>
            </div>
        <?php } else { ?>
            Ups! :( Miss Design...
        <?php } ?>
        
        <?php if ($pagination == 'true') { ?>
            <div class="navigation">
                <div class="arrow-alignleft"><div class="tp-leftarrow tparrows persephone"></div></div>
                <div class="arrow-alignright"><div class="tp-rightarrow tparrows persephone"></div></div>
            </div>
        <?php } ?>
    <?php
    $wp_query = null; $wp_query = $temp;
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode('loop-mode', 'LoopMode');

/******************************************************************************/
/******* CUSTOM TESTIMONIAL - Marcel CL ***************************************/
/******************************************************************************/
function custom_post_testimonial() {
  $labels = array(
    'name'               => 'Testimonios',
    'singular_name'      => 'Testimonio',
    'add_new'            => 'Añadir testimonio', 'book',
    'add_new_item'       => __( 'Añadir Testimonio' ),
    'edit_item'          => __( 'Editar Testimonio' ),
    'new_item'           => __( 'Nuevo Testimonio' ),
    'all_items'          => __( 'Testimonios' ),
    'view_item'          => __( 'Ver Testimonio' ),
    'search_items'       => __( 'Buscar Testimonios' ),
    'not_found'          => __( 'No testimonial found' ),
    'not_found_in_trash' => __( 'No testimonial found in the Trash' ), 
    'parent_item_colon'  => '',
    'menu_name'          => '21 Testimonios'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => ' Datos Testomonios',
    'public'        => true,
    'menu_icon'     => 'dashicons-format-status',
    'menu_position' => 5,
    'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
    'has_archive'   => true,
  );
  register_post_type( 'testimonio', $args ); 
}
add_action( 'init', 'custom_post_testimonial' );

/******************************************************************************/
/******* CUSTOM ESPACIOS - Marcel CL ******************************************/
/******************************************************************************/
function custom_post_espacios() {
  $labels = array(
    'name'               => 'Espacios',
    'singular_name'      => 'Espacio',
    'add_new'            => 'Añadir espacio', 'book',
    'add_new_item'       => __( 'Añadir Espacio' ),
    'edit_item'          => __( 'Editar Espacio' ),
    'new_item'           => __( 'Nuevo Espacio' ),
    'all_items'          => __( 'Espacios' ),
    'view_item'          => __( 'Ver Espacio' ),
    'search_items'       => __( 'Buscar Espacios' ),
    'not_found'          => __( 'No se encontraron espacios aún' ),
    'not_found_in_trash' => __( 'No hay espacios en la papelera' ), 
    'parent_item_colon'  => '',
    'menu_name'          => '21 Espacios'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => ' Datos Espacios',
    'public'        => true,
    'menu_icon'     => 'dashicons-location-alt',
    'menu_position' => 5,
    'supports'      => array( 'title', 'editor', 'thumbnail' ),
    'has_archive'   => true,
  );
  register_post_type( 'espacios', $args ); 
}
add_action( 'init', 'custom_post_espacios' );

function taxonomies_espacios() {
  $labels = array(
    'name'              => 'Espacio Categoría',
    'singular_name'     => 'Espacio Categoría',
    'search_items'      => __( 'Buscar categoría' ),
    'all_items'         => __( 'Todas las categorías' ),
    'parent_item'       => __( 'Categoría padre' ),
    'parent_item_colon' => __( 'Categoría padre:' ),
    'edit_item'         => __( 'Editar categoría' ), 
    'update_item'       => __( 'Actualizar categoría' ),
    'add_new_item'      => __( 'Añadir categoría' ),
    'new_item_name'     => __( 'Nombre de la categoría' ),
    'menu_name'         => __( 'Categorías Espacios' ),
  );
  $args = array(
    'labels' => $labels,
    'hierarchical' => true,
  );
  register_taxonomy( 'espacios_category', 'espacios', $args );
}
add_action( 'init', 'taxonomies_espacios', 0 );

/******************************************************************************/
/******* CUSTOM EQUIPO - Marcel CL ********************************************/
/******************************************************************************/
function custom_post_equipo() {
  $labels = array(
    'name'               => 'Equipo',
    'singular_name'      => 'Integrante',
    'add_new'            => 'Añadir personal', 'book',
    'add_new_item'       => __( 'Añadir Integrante' ),
    'edit_item'          => __( 'Editar Integrante' ),
    'new_item'           => __( 'Nuevo Integrante' ),
    'all_items'          => __( 'Equipo' ),
    'view_item'          => __( 'Ver Integrante' ),
    'search_items'       => __( 'Buscar Integrante' ),
    'not_found'          => __( 'No hay integrantes aún' ),
    'not_found_in_trash' => __( 'No hay nada en la papelera' ), 
    'parent_item_colon'  => '',
    'menu_name'          => '21 Equipo'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => ' Datos Equipo',
    'public'        => true,
    'menu_icon'     => 'dashicons-groups',
    'menu_position' => 5,
    'supports'      => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
    'has_archive'   => true,
  );
  register_post_type( 'equipo', $args ); 
}
add_action( 'init', 'custom_post_equipo' );

/******************************************************************************/
/******* CUSTOM GESTRONOMÍA - Marcel CL ***************************************/
/******************************************************************************/
function custom_post_gastronomia() {
  $labels = array(
    'name'               => 'Gastronomia',
    'singular_name'      => 'Gastronomia',
    'add_new'            => 'Añadir Gastronomia', 'book',
    'add_new_item'       => __( 'Añadir Gastronomia' ),
    'edit_item'          => __( 'Editar Gastronomia' ),
    'new_item'           => __( 'Nuevo Gastronomia' ),
    'all_items'          => __( 'Gastronomia' ),
    'view_item'          => __( 'Ver Gastronomia' ),
    'search_items'       => __( 'Buscar Gastronomia' ),
    'not_found'          => __( 'No se encontraron resultados' ),
    'not_found_in_trash' => __( 'No hay nada en la papelera' ), 
    'parent_item_colon'  => '',
    'menu_name'          => '21 Gastronomía'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => ' Datos Gastronomía',
    'public'        => true,
    'menu_icon'     => 'dashicons-carrot',
    'menu_position' => 5,
    'supports'      => array( 'title', 'thumbnail' ),
    'has_archive'   => true,
  );
  register_post_type( 'gastronomia', $args ); 
}
add_action( 'init', 'custom_post_gastronomia' );

function taxonomies_gastronomia() {
  $labels = array(
    'name'              => 'Gastro categoría',
    'singular_name'     => 'Gastrocategoría',
    'search_items'      => __( 'Buscar categoría' ),
    'all_items'         => __( 'Todas las categorías' ),
    'parent_item'       => __( 'Categoría padre' ),
    'parent_item_colon' => __( 'Categoría padre:' ),
    'edit_item'         => __( 'Editar categoría' ), 
    'update_item'       => __( 'Actualizar categoría' ),
    'add_new_item'      => __( 'Añadir categoría' ),
    'new_item_name'     => __( 'Nombre de la categoría' ),
    'menu_name'         => __( 'Gastrocategoría' ),
  );
  $args = array(
    'labels' => $labels,
    'hierarchical' => true,
  );
  register_taxonomy( 'gastronomia_category', 'gastronomia', $args );
}
add_action( 'init', 'taxonomies_gastronomia', 0 );
/******************************************************************************/
/****** CUSTOM ADD BLOCK's - Marcel CL ****************************************/
/******************************************************************************/
function custom_post_block() {
  $labels = array(
    'name'               => 'Blocks',
    'singular_name'      => 'Block',
    'add_new'            => 'Añadir', 'block',
    'add_new_item'       => __( 'Añadir Block' ),
    'edit_item'          => __( 'Editar Block' ),
    'new_item'           => __( 'Nuevo Block' ),
    'all_items'          => __( 'Todos los Blocks' ),
    'view_item'          => __( 'Ver Block' ),
    'search_items'       => __( 'Buscar Block' ),
    'not_found'          => __( 'Ningún Block creado aún.' ),
    'not_found_in_trash' => __( 'Ningún Block en la papelera.' ), 
    'menu_name'          => '21 Blocks'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => ' Block data',
    'public'        => true,
    'menu_icon'     => 'dashicons-image-filter',
    'menu_position' => 5,
    'supports'      => array( 'title', 'editor' ),
    'has_archive'   => true,
  );
  register_post_type( 'blockmarzo', $args ); 
}
add_action( 'init', 'custom_post_block' );


add_filter('manage_edit-blockmarzo_columns', 'posts_columns_id', 5);
add_action('manage_blockmarzo_posts_custom_column', 'posts_custom_id_columns', 5, 2);
 
function posts_columns_id($defaults){
    $defaults['wps_post_id'] = __('ID');
    return $defaults;
}
function posts_custom_id_columns($column_name, $id){
    if($column_name === 'wps_post_id'){
            echo '<span style="background:yellow;padding:5px;">'.$id.'</span>';
    }
}

/******************************************************************************/
/******* REGISTER NAV MOBILE - Marcel CL **************************************/
/******************************************************************************/

register_nav_menus( array(
    'mobile' => __( 'Móvil', 'mobile')
 ) );

function cta_add_menu_item($items, $args) {
    if($args->theme_location == 'secondary'){
        //$cta_item = '<li class="btn-cta menu-item" data-effect="st-effect-1">Presupuesto</li>';
        $cta_item = '<li class="btn-custom-cta menu-item"><a href="/21goodfood/">21 GoodFood</a></li>';
        $items = $items . $cta_item;
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'cta_add_menu_item', 10, 2);

/* ========================================================== */
/* ELIMINO CAMPOS EXTRA CHECKOUT WOOCOMMERCE ---------------- */
/* ========================================================== */
function giftwoo_unset_checkout_fields( $fields ) {
    $billing_keys = array(
        'billing_company',
        'billing_address_2',
    );
    foreach( $billing_keys as $key ) { unset( $fields['billing'][$key] ); }
    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'giftwoo_unset_checkout_fields' );

/******************************************************************************/
/******* EXCLUDE TABLETS FROM ISMOBILE FUNCTION - Marcel CL *******************/
/******************************************************************************/
function rtg_wp_is_mobile() {
    static $is_mobile;

    if ( isset($is_mobile) )
        return $is_mobile;

    if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
        $is_mobile = false;
    } elseif (
        strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false ) {
            $is_mobile = true;
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') == false) {
            $is_mobile = true;
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') !== false) {
        $is_mobile = false;
    } else {
        $is_mobile = false;
    }

    return $is_mobile;
}

/******************************************************************************/
/** Add a 'Add rel="nofollow" to link' checkbox to the WordPress link editor **/
/** @see https://danielbachhuber.com/tip/rel-nofollow-link-modal **************/
/******************************************************************************/
add_action( 'after_wp_tiny_mce', function(){
    ?>
    <script>
        var originalWpLink;
        // Ensure both TinyMCE, underscores and wpLink are initialized
        if ( typeof tinymce !== 'undefined' && typeof _ !== 'undefined' && typeof wpLink !== 'undefined' ) {
            // Ensure the #link-options div is present, because it's where we're appending our checkbox.
            if ( tinymce.$('#link-options').length ) {
                // Append our checkbox HTML to the #link-options div, which is already present in the DOM.
                tinymce.$('#link-options').append(<?php echo json_encode( '<div class="link-nofollow"><label><span></span><input type="checkbox" id="wp-link-nofollow" /> Add rel="nofollow" to link</label></div>' ); ?>);
                // Clone the original wpLink object so we retain access to some functions.
                originalWpLink = _.clone( wpLink );
                wpLink.addRelNofollow = tinymce.$('#wp-link-nofollow');
                // Override the original wpLink object to include our custom functions.
                wpLink = _.extend( wpLink, {
                    /**
                     * Fetch attributes for the generated link based on
                     * the link editor form properties.
                     *
                     * In this case, we're calling the original getAttrs()
                     * function, and then including our own behavior.
                     */
                    getAttrs: function() {
                        var attrs = originalWpLink.getAttrs();
                        attrs.rel = wpLink.addRelNofollow.prop( 'checked' ) ? 'nofollow' : false;
                        return attrs;
                    },
                    /**
                     * Build the link's HTML based on attrs when inserting
                     * into the text editor.
                     *
                     * In this case, we're completely overriding the existing
                     * function.
                     */
                    buildHtml: function( attrs ) {
                        var html = '<a href="' + attrs.href + '"';
                        if ( attrs.target ) {
                            html += ' target="' + attrs.target + '"';
                        }
                        if ( attrs.rel ) {
                            html += ' rel="' + attrs.rel + '"';
                        }
                        return html + '>';
                    },
                    /**
                     * Set the value of our checkbox based on the presence
                     * of the rel='nofollow' link attribute.
                     *
                     * In this case, we're calling the original mceRefresh()
                     * function, then including our own behavior
                     */
                    mceRefresh: function( searchStr, text ) {
                        originalWpLink.mceRefresh( searchStr, text );
                        var editor = window.tinymce.get( window.wpActiveEditor )
                        if ( typeof editor !== 'undefined' && ! editor.isHidden() ) {
                            var linkNode = editor.dom.getParent( editor.selection.getNode(), 'a[href]' );
                            if ( linkNode ) {
                                wpLink.addRelNofollow.prop( 'checked', 'nofollow' === editor.dom.getAttrib( linkNode, 'rel' ) );
                            }
                        }
                    }
                });
            }
        }
    </script>
    <style>
    #wp-link #link-options .link-nofollow {padding: 3px 0 0;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;}
    #wp-link #link-options .link-nofollow label span {width: 83px;}
    .has-text-field #wp-link .query-results {top: 223px;}
    </style>
    <?php
});
