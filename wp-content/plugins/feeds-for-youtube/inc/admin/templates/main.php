<?php
$vars = $this->get_vars();
$text_domain = $vars->text_domain();
$setup_url = $vars->setup_url();
$oauth_processor_url = $vars->oauth_processor_url();
$social_network = $vars->social_network();
$sn_with_a_an = $vars->social_network( true );
$plugin_version = $vars->version();
$demo_url = $vars->demo_url();
$pro_logo = $vars->pro_logo();

if ( isset( $_POST[ $this->get_option_name() . '_validate' ] ) && $this->verify_post( $_POST ) ) {
    $tab = isset( $_POST[ $this->get_option_name() . '_tab_marker' ] ) ? sanitize_text_field( $_POST[ $this->get_option_name() . '_tab_marker' ] ) : 'main';
    $new_settings = $this->validate_options( $_POST[ $this->get_option_name() ], $tab );
    $this->update_options( $new_settings );
    ?>
    <div class="updated"><p><strong><?php _e('Settings saved.', $text_domain ); ?></strong></p></div>

	<?php
}
$plugin_name = $this->get_plugin_name();
$active_tab = $this->get_active_tab();
$slug = $this->get_slug();
$tabs = $this->get_tabs();
?>

<div id="sbspf_admin" class="wrap sbspf-admin sby_admin" data-sb-plugin="sbspf">
	<?php
	$lite_notice_dismissed = get_transient( 'youtube_feed_dismiss_lite' );

	if ( ! $lite_notice_dismissed ) :
		?>
        <div id="sbspf-notice-bar" style="display:none">
            <span class="sbspf-notice-bar-message"><?php _e( 'You\'re using Feeds for YouTube Lite. To unlock more features consider <a href="https://smashballoon.com/youtube-feed/?utm_source=WordPress&utm_campaign=youtubeliteplugin&utm_medium=notice-bar" target="_blank" rel="noopener noreferrer">upgrading to Pro</a>.', $text_domain ); ?></span>
            <button type="button" class="dismiss" title="<?php _e( 'Dismiss this message.', $text_domain ); ?>" data-page="overview">
            </button>
        </div>
	<?php endif; ?>

	<?php do_action( 'sby_admin_overview_before_title' ); ?>

	<h1><?php echo esc_html( $plugin_name ); ?></h1>

	<!-- Display the tabs along with styling for the 'active' tab -->
	<h2 class="nav-tab-wrapper">
		<?php
		$i = 1;
		foreach ( $tabs as $tab ) :
            if ( ! isset( $tab['has_nav_tab'] ) ) :
			$title = isset( $tab['numbered_tab'] ) && ! $tab['numbered_tab'] ? __( $tab['title'], 'feeds-for-youtube' ) : $i . '. ' . __( $tab['title'], 'feeds-for-youtube' );
			?>
			<a href="admin.php?page=<?php echo esc_attr( $slug ); ?>&tab=<?php echo esc_attr( $tab['slug'] ); ?>" class="nav-tab <?php if ( $active_tab === $tab['slug'] ){ echo 'nav-tab-active'; } ?>"><?php echo $title; ?></a>
		<?php
            $i ++;
            endif;
		endforeach; ?>
	</h2>
	<?php
	settings_errors();

	include $this->get_path( $active_tab );

	$next_step = $this->next_step();
	if ( ! empty( $next_step ) ) : ?>
    <p class="sbspf_footer_help">
        <?php echo sby_admin_icon( 'chevron-right', 'sbspf_small_svg' ) ; ?>&nbsp; <?php _e('Next Step', $text_domain ); ?>: <a href="?page=<?php echo esc_attr( $slug ); ?>&tab=<?php echo esc_attr( $next_step['next_tab'] ); ?>"><?php echo esc_html( __( $next_step['instructions'], 'feeds-for-youtube' ) ); ?></a>
    </p>
	<?php endif; ?>

	<p class="sbspf_footer_help"><?php echo sby_admin_icon( 'life-ring', 'sbspf_small_svg' ); ?>&nbsp; <?php _e('Need help setting up the plugin? Check out our <a href="' . esc_url( $setup_url ) . '" target="_blank">setup directions</a>', $text_domain); ?></p>

	<div class="sbspf-quick-start">
		<h3><?php echo sby_admin_icon( 'rocket', 'sbspf_small_svg' ); ?>&nbsp; <?php _e( 'Display your feed', $text_domain); ?></h3>
		<p><?php _e( "Copy and paste this shortcode directly into the page, post or widget where you'd like to display the feed:", $text_domain ); ?>
			<input type="text" value="[<?php echo $slug; ?>]" size="18" readonly="readonly" style="text-align: center;" onclick="this.focus();this.select()" title="<?php _e( 'To copy, click the field then press Ctrl + C (PC) or Cmd + C (Mac).', $text_domain ); ?>" /></p>
		<p><?php _e( "Find out how to display <a href='?page=".$slug."&tab=display'>multiple feeds</a>.", $text_domain ); ?></p>
	</div>

    <a href="https://smashballoon.com/youtube-feed/?utm_source=plugin-free&utm_campaign=sby" target="_blank" class="sbspf-pro-notice"><img src="<?php echo SBY_PLUGIN_URL . 'img/pro.png' ?>" alt="Pro" /></a>


</div>
<div class="wp-clearfix"></div>