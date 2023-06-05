<div id="sbspf_modal_overlay">
    <div class="sbspf_modal">
        <div class="sbspf_modal_message">
            <div class="sby_after_connection">
                <p class="heading"><?php _e ( 'You have successfully connected your account' ); ?></p>
                <p><?php _e ( 'You may receive an email from Google notifying you that our plugin has been granted read-access to your account.', $text_domain ); ?></p>
                <p class="sbspf_submit">
                    <a href="JavaScript:void(0);" class="button button-secondary sbspf_dismiss_at_warning_button" data-action="sby_dismiss_at_warning_notice"><?php esc_html_e( 'Dismiss', $text_domain); ?></a>
                </p>
                <a href="JavaScript:void(0);" class="sbspf_modal_close sbspf_dismiss_at_warning_button" data-action="sby_dismiss_at_warning_notice"><i class="fa fa-times"></i></a>

            </div>
        </div>

    </div>
</div>