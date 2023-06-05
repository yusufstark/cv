<p class="sbspf-contents-links" id="general">
    <span>Quick links: </span>
    <a href="#layout"><?php _e( 'Layout', $text_domain ); ?></a>
    <a href="#header"><?php _e( 'Header', $text_domain ); ?></a>
    <a href="#loadmore"><?php _e( 'Buttons', $text_domain ); ?></a>
    <a href="#gdpr"><?php _e( 'GDPR', $text_domain ); ?></a>
    <a href="#advanced"><?php _e( 'Advanced', $text_domain ); ?></a>

</p>
<form method="post" action="">
	<?php $this->hidden_fields_for_tab( 'customize' ); ?>

	<?php foreach ( $this->get_sections( 'customize' ) as $section ) :
        if ( $section['pro'] ) : ?>
            <div class="sbspf_pro_section">
	        <p style="padding-bottom: 18px;" class="sbspf_pro_reveal">
				<a href="https://smashballoon.com/youtube-feed/?utm_source=plugin-free&amp;utm_campaign=sby" target="_blank"><?php echo esc_html( $section['pro'] ); ?></a><br>
				<a href="javascript:void(0);" class="button button-secondary sbspf-show-pro"><b>+</b> <?php _e( 'Show Pro Options', $text_domain ); ?></a>
			</p>
        <?php endif; ?>
                <span id="<?php echo str_replace( 'sbspf_', '', $section['id'] ); ?>"></span>

                <?php
        do_settings_sections( $section['id'] ); // matches the section name
		if ( $section['pro'] ) {
			echo '</div>';
		}
		if ( $section['save_after'] ) : ?>
            <p class="submit"><input class="button-primary" type="submit" name="save" value="<?php esc_attr_e( 'Save Changes' ); ?>" /></p>
        <?php endif; ?>
        <hr>
	<?php endforeach; ?>
</form>
