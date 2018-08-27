<?php

/*

Plugin Name: Meu Painel Personalizado
Plugin URL: https://caporalmktdigital.com.br/plataformas/plugin-meu-painel-personalizado/
Description: A plugin to modify the dashboard welcome message to your custom page.
Version: 1.6
Author: Alexandre Caporal
Author URI: https://caporalmktdigital.com.br/

*/

add_action( 'init', 'mpp_updater' );
function mpp_updater() {
	include_once 'updater.php';
	define( 'WP_GITHUB_FORCE_UPDATE', true );
if (is_admin()) { // note the use of is_admin() to double check that this is happening in the admin
	$config = array(
		'slug' => plugin_basename(__FILE__), // this is the slug of your plugin
		'proper_folder_name' => 'plugin-name', // this is the name of the folder your plugin lives in
		'api_url' => 'https://api.github.com/repos/caporalmktdigital/meu-painel-personalizado', // the GitHub API url of your GitHub repo
		'raw_url' => 'https://raw.github.com/caporalmktdigital/meu-painel-personalizado/master', // the GitHub raw url of your GitHub repo
		'github_url' => 'https://github.com/caporalmktdigital/meu-painel-personalizado', // the GitHub url of your GitHub repo
		'zip_url' => 'https://github.com/caporalmktdigital/meu-painel-personalizado/zipball/master', // the zip url of the GitHub repo
		'sslverify' => true, // whether WP should check the validity of the SSL cert when getting an update, see https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/2 and https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/4 for details
		'requires' => '3.8', // which version of WordPress does your plugin require?
		'tested' => '4.8.1', // which version of WordPress is your plugin tested up to?
		'readme' => 'README.md', // which file to use as the readme for the version number
		'access_token' => '', // Access private repositories by authorizing under Appearance > GitHub Updates when this example plugin is installed
	);
	new WP_GitHub_Updater($config);
}
}

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

function meu_painel_personalizado_admin() {
if( is_page( 'meu-painel-personalizado' ) ):
show_admin_bar(false);
   endif;
}
add_action('wp_head', 'meu_painel_personalizado_admin');

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
    unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );

remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel' );

add_action('wp_dashboard_setup', 'remove_rg_forms_dashboard' );
function remove_rg_forms_dashboard() {
  remove_meta_box( 'rg_forms_dashboard', 'dashboard', 'normal' );
}

add_action('wp_dashboard_setup', 'remove_wpseo_dashboard_overview' );
function remove_wpseo_dashboard_overview() {
  remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'normal' );
}

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
