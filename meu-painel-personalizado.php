<?php

/*

Plugin Name: Meu Painel Personalizado
Plugin URL: https://caporalmktdigital.com.br/
Description: A plugin to modify the dashboard welcome message to your custom page.
Version: 1.0
Author: Caporal Mkt Digital by Alexandre Caporal
Author URI: https://caporalmktdigital.com.br/

*/

function meu_painel_personalizado() {

	?>

<script type="text/javascript">

/* Hide default welcome message */

jQuery(document).ready( function($) 

{

	$('div.welcome-panel-content').hide();

});

</script>
<iframe id="meu-painel-personalizado-iframe" style="margin-top: -24px; margin-bottom: -9px; margin-left: -1%;" marginheight="0" frameborder="0" src="<?php echo get_home_url(); ?>/meu-painel-personalizado" width="102%" height="800px"></iframe>

<?php

}

add_action( 'welcome_panel', 'meu_painel_personalizado' );

function meu_painel_personalizado_admin($bool) {
if ( is_page( 'meu-painel-personalizado' ) ) :
return false;
else :
return $bool;
endif;
}
add_filter('show_admin_bar', 'meu_painel_personalizado_hide_admin');

add_action('wp_trash_post', 'restrict_post_deletion', 10, 1);
add_action('before_delete_post', 'restrict_post_deletion', 10, 1);
function restrict_post_deletion($post_id) {
    if( $post_id == 31185 ) {
      exit('Oops! Essa página não pode ser deletada, pois vai afetar seu painel administrativo personalizado.');
    }
  }
    
add_action('admin_head', 'meu_painel_personalizado_styles');

function meu_painel_personalizado_styles() {
  echo '<style>
    .welcome-panel {
    overflow: hidden;
}
  </style>';
}

function remove_dashboard_widgets() {
    global $wp_meta_boxes;
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['wpseo-dashboard-overview']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );

add_action( 'admin_bar_menu', 'remove_wp_logo', 999 );
function remove_wp_logo( $wp_admin_bar ) {
  $wp_admin_bar->remove_node( 'wp-logo' );
}

function remove_footer_admin () {
echo 'Desenvolvido por <a href="https://caporalmktdigital.com.br/" target="_blank" title="Caporal Mkt Digital">Agência de planejamento estratégico digital</a>';
}
add_filter('admin_footer_text', 'remove_footer_admin');

 add_filter('user_has_cap', 'se_119694_user_has_cap');
 function se_119694_user_has_cap($capabilities){
 global $pagenow;
      if ($pagenow == 'index.php')
           $capabilities['edit_theme_options'] = 1;
      return $capabilities;
 }
add_action( 'load-index.php', 'show_welcome_panel' );
function show_welcome_panel() {
    $user_id = get_current_user_id();
    if ( 1 != get_user_meta( $user_id, 'show_welcome_panel', true ) )
        update_user_meta( $user_id, 'show_welcome_panel', 1 );
}
$PrivateRole = get_role('author');
$PrivateRole -> add_cap('read_private_pages');
$PrivateRole = get_role('editor');
$PrivateRole -> add_cap('read_private_pages');
$PrivateRole = get_role('subscriber');
$PrivateRole -> add_cap('read_private_pages');