<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class SBY_Admin extends SB_YOUTUBE_Admin {

	public function settings_init() {
		$text_domain = $this->vars->text_domain();

		$defaults = sby_settings_defaults();
		$this->add_false_field( 'disablecdn', 'customize' );

		/**
		 * Configure Tab
		 */
		$args = array(
			'id' => 'sbspf_types',
			'tab' => 'configure',
			'save_after' => 'true'
		);
		$this->add_settings_section( $args );

		$locator_html = '';
		if ( SBY_Feed_Locator::count_unique() > -1 ) {
			$locator_html .= '<div class="sby_locations_link">';
			$locator_html .= '<a href="?page=' . $this->slug .'&amp;tab=allfeeds"><svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-search fa-w-16 fa-2x"><path fill="currentColor" d="M508.5 468.9L387.1 347.5c-2.3-2.3-5.3-3.5-8.5-3.5h-13.2c31.5-36.5 50.6-84 50.6-136C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c52 0 99.5-19.1 136-50.6v13.2c0 3.2 1.3 6.2 3.5 8.5l121.4 121.4c4.7 4.7 12.3 4.7 17 0l22.6-22.6c4.7-4.7 4.7-12.3 0-17zM208 368c-88.4 0-160-71.6-160-160S119.6 48 208 48s160 71.6 160 160-71.6 160-160 160z" class=""></path></svg> ' . __( 'Feed Finder', $text_domain ) . '</a>';
			$locator_html .= '</div>';
		}
		/* Types */
		$args = array(
			'name' => 'type',
			'section' => 'sbspf_types',
			'callback' => 'types',
			'title' => '<label>' . __( 'Select a Feed Type', $text_domain ) .'</label>',
			'shortcode' => array(
				'key' => 'type',
				'example' => 'channel',
				'description' => __( 'Type of feed to display', $text_domain ) . ' e.g. channel, playlist, search, favorites, live',
				'after_description' => $locator_html,
				'display_section' => 'configure'
			),
			'types' => $this->types
		);
		$this->add_settings_field( $args );

		$this->pro_only[] = 'type';


		/* Cache */
		$args = array(
			'name' => 'cache',
			'section' => 'sbspf_types',
			'callback' => 'cache',
			'title' => __( 'Check for new posts', $text_domain )
		);
		$this->add_settings_field( $args );


		/**
		 * Customize Tab
		 */
		$args = array(
			'title' => __( 'General', $text_domain ),
			'id' => 'sbspf_general',
			'tab' => 'customize',
			'save_after' => 'true'
		);
		$this->add_settings_section( $args );

		/* Width and Height */
		$select_options = array(
			array(
				'label' => '%',
				'value' => '%'
			),
			array(
				'label' => 'px',
				'value' => 'px'
			)
		);

		$args = array(
			'name' => 'width',
			'default' => '100',
			'section' => 'sbspf_general',
			'callback' => 'text',
			'min' => 1,
			'size' => 4,
			'title' => __( 'Width of Feed', $text_domain ),
			'shortcode' => array(
				'key' => 'width',
				'example' => '300px',
				'description' => __( 'The width of your feed. Any number with a unit like "px" or "%".', $text_domain ),
				'display_section' => 'customize'
			),
			'select_name' => 'widthunit',
			'select_options' => $select_options,
			'hidden' => array(
				'callback' => 'checkbox',
				'name' => 'width_responsive',
				'label' => __( 'Set to be 100% width on mobile?', $text_domain ),
				'before' => '<div id="sbspf_width_options">',
				'after' => '</div>',
				'tooltip_info' =>  __( 'If you set a width on the feed then this will be used on mobile as well as desktop. Check this setting to set the feed width to be 100% on mobile so that it is responsive.', $text_domain )
			),
		);
		$this->add_settings_field( $args );

		$select_options = array(
			array(
				'label' => '%',
				'value' => '%'
			),
			array(
				'label' => 'px',
				'value' => 'px'
			)
		);
		$args = array(
			'name' => 'height',
			'default' => '',
			'section' => 'sbspf_general',
			'callback' => 'text',
			'min' => 1,
			'size' => 4,
			'title' => __( 'Height of Feed', $text_domain ),
			'shortcode' => array(
				'key' => 'height',
				'example' => '500px',
				'description' => __( 'The height of your feed. Any number with a unit like "px" or "em".', $text_domain ),
				'display_section' => 'customize'
			),
			'select_name' => 'heightunit',
			'select_options' => $select_options,
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'background',
			'default' => '',
			'section' => 'sbspf_general',
			'callback' => 'color',
			'title' => __( 'Background Color', $text_domain ),
			'shortcode' => array(
				'key' => 'background',
				'example' => '#f00',
				'description' => __( 'Background color for the feed. Any hex color code.', $text_domain ),
				'display_section' => 'customize'
			),
		);
		$this->add_settings_field( $args );

		$args = array(
			'title' => __( 'Layout', $text_domain ),
			'id' => 'sbspf_layout',
			'tab' => 'customize',
			'save_after' => 'true'
		);
		$this->add_settings_section( $args );

		$args = array(
			'name' => 'layout',
			'section' => 'sbspf_layout',
			'callback' => 'layout',
			'title' => __( 'Layout Type', $text_domain ),
			'layouts' => $this->layouts,
			'shortcode' => array(
				'key' => 'layout',
				'example' => 'list',
				'description' => __( 'How your posts are displayed visually.', $text_domain ) . ' e.g. list, grid, gallery',
				'display_section' => 'layout'
			)
		);
		$this->add_settings_field( $args );

		$this->pro_only[] = 'carouselcols';
		$this->pro_only[] = 'carouselcolsmobile';
		$this->pro_only[] = 'carouselrows';
		$this->pro_only[] = 'carouselloop';
		$this->pro_only[] = 'carouselarrows';
		$this->pro_only[] = 'carouselpag';
		$this->pro_only[] = 'carouselautoplay';
		$this->pro_only[] = 'carouseltime';

		$select_options = array(
			array(
				'label' => 'px',
				'value' => 'px'
			),
			array(
				'label' => '%',
				'value' => '%'
			)
		);

		$args = array(
			'name' => 'num',
			'default' => $defaults['num'],
			'section' => 'sbspf_layout',
			'callback' => 'text',
			'min' => 1,
			'max' => 50,
			'size' => 4,
			'title' => __( 'Number of Videos', $text_domain ),
			'additional' => '<span class="sby_note">' . __( 'Number of videos to show initially.', $text_domain ) . '</span>',
			'shortcode' => array(
				'key' => 'num',
				'example' => 5,
				'description' => __( 'The number of videos in the feed', $text_domain ),
				'display_section' => 'layout'
			)
		);
		$this->add_settings_field( $args );

		$include_options = array(
			array(
				'label' => __( 'Play Icon', $text_domain ),
				'value' => 'icon'
			),
			array(
				'label' => __( 'Title', $text_domain ),
				'value' => 'title',
				'pro' => true
			),
			array(
				'label' => __( 'User Name', $text_domain ),
				'value' => 'user',
				'pro' => true
			),
			array(
				'label' => __( 'Views', $text_domain ),
				'value' => 'views',
				'pro' => true
			),
			array(
				'label' => __( 'Date', $text_domain ),
				'value' => 'date',
				'pro' => true
			),
			array(
				'label' => __( 'Live Stream Countdown (when applies)', $text_domain ),
				'value' => 'countdown',
				'pro' => true
			),
			array(
				'label' => __( 'Stats (like and comment counts)', $text_domain ),
				'value' => 'stats',
				'pro' => true
			),
			array(
				'label' => __( 'Description', $text_domain ),
				'value' => 'description',
				'pro' => true
			),
		);
		$args = array(
			'name' => 'include',
			'default' => $defaults['include'],
			'section' => 'sbspf_layout',
			'callback' => 'multi_checkbox',
			'title' => __( 'Show/Hide', $text_domain ),
			'shortcode' => array(
				'key' => 'include',
				'example' => '"icon"',
				'description' => __( 'What video information will display in the feed. eg.', $text_domain ) . ' icon',
				'display_section' => 'customize'
			),
			'select_options' => $include_options,
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'itemspacing',
			'default' => 5,
			'section' => 'sbspf_layout',
			'callback' => 'text',
			'min' => 0,
			'size' => 4,
			'title' => __( 'Spacing between videos', $text_domain ),
			'shortcode' => array(
				'key' => 'itemspacing',
				'example' => '5px',
				'description' => __( 'The spacing/padding around the videos in the feed. Any number with a unit like "px" or "em".', $text_domain ),
				'display_section' => 'layout'
			),
			'select_name' => 'itemspacingunit',
			'select_options' => $select_options,
		);
		$this->add_settings_field( $args );

		$select_options = array(
			array(
				'label' => __( 'Below video thumbnail', $text_domain ),
				'value' => 'below'
			),
			array(
				'label' => __( 'Next to video thumbnail', $text_domain ),
				'value' => 'side'
			)
		);
		$args = array(
			'name' => 'infoposition',
			'default' => 'below',
			'section' => 'sbspf_layout',
			'pro' => true,
			'callback' => 'select',
			'title' => __( 'Position', $text_domain ),
			'shortcode' => array(
				'key' => 'infoposition',
				'example' => 'side',
				'description' => __( 'Where the information (title, description, stats) will display. eg.', $text_domain ) . ' below, side, none',
				'display_section' => 'customize'
			),
			'options' => $select_options,
		);
		$this->add_settings_field( $args );

		$args = array(
			'title' => __( 'Header', $text_domain ),
			'id' => 'sbspf_header',
			'tab' => 'customize',
		);
		$this->add_settings_section( $args );

		$args = array(
			'name' => 'showheader',
			'section' => 'sbspf_header',
			'callback' => 'checkbox',
			'title' => __( 'Show Header', $text_domain ),
			'default' => true,
			'shortcode' => array(
				'key' => 'showheader',
				'example' => 'false',
				'description' => __( 'Include a header for this feed.', $text_domain ),
				'display_section' => 'header'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'showdescription',
			'section' => 'sbspf_header',
			'callback' => 'checkbox',
			'title' => __( 'Show Channel Description', $text_domain ),
			'default' => true,
			'shortcode' => array(
				'key' => 'showdescription',
				'example' => 'false',
				'description' => __( 'Include the channel description in the header.', $text_domain ),
				'display_section' => 'header'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'showsubscribers',
			'section' => 'sbspf_header',
			'callback' => 'checkbox',
			'pro' => true,
			'title' => __( 'Show Subscribers', $text_domain ),
			'default' => true,
			'shortcode' => array(
				'key' => 'showsubscribers',
				'example' => 'false',
				'description' => __( 'Include the number of subscribers in the header.', $text_domain ),
				'display_section' => 'header'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'title' => __( '"Load More" Button', $text_domain ),
			'id' => 'sbspf_loadmore',
			'tab' => 'customize',
		);
		$this->add_settings_section( $args );

		$args = array(
			'name' => 'showbutton',
			'section' => 'sbspf_loadmore',
			'callback' => 'checkbox',
			'title' => __( 'Show "Load More" Button', $text_domain ),
			'default' => true,
			'shortcode' => array(
				'key' => 'showbutton',
				'example' => 'false',
				'description' => __( 'Include a "Load More" button at the bottom of the feed to load more videos.', $text_domain ),
				'display_section' => 'button'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'buttoncolor',
			'default' => '',
			'section' => 'sbspf_loadmore',
			'callback' => 'color',
			'title' => __( 'Button Background Color', $text_domain ),
			'shortcode' => array(
				'key' => 'buttoncolor',
				'example' => '#0f0',
				'description' => __( 'Background color for the "Load More" button. Any hex color code.', $text_domain ),
				'display_section' => 'button'
			),
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'buttontextcolor',
			'default' => '',
			'section' => 'sbspf_loadmore',
			'callback' => 'color',
			'title' => __( 'Button Text Color', $text_domain ),
			'shortcode' => array(
				'key' => 'buttontextcolor',
				'example' => '#00f',
				'description' => __( 'Text color for the "Load More" button. Any hex color code.', $text_domain ),
				'display_section' => 'button'
			),
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'buttontext',
			'default' => __( 'Load More...', $text_domain ),
			'section' => 'sbspf_loadmore',
			'callback' => 'text',
			'title' => __( 'Button Text', $text_domain ),
			'shortcode' => array(
				'key' => 'buttontext',
				'example' => '"More Videos"',
				'description' => __( 'The text that appers on the "Load More" button.', $text_domain ),
				'display_section' => 'button'
			)
		);
		$this->add_settings_field( $args );

		/* Subscribe button */
		$args = array(
			'title' => __( '"Subscribe" Button', $text_domain ),
			'id' => 'sbspf_subscribe',
			'tab' => 'customize',
			'save_after' => true
		);
		$this->add_settings_section( $args );

		$args = array(
			'name' => 'showsubscribe',
			'section' => 'sbspf_subscribe',
			'callback' => 'checkbox',
			'title' => __( 'Show "Subscribe" Button', $text_domain ),
			'default' => true,
			'shortcode' => array(
				'key' => 'showsubscribe',
				'example' => 'false',
				'description' => __( 'Include a "Subscribe" button at the bottom of the feed to load more videos.', $text_domain ),
				'display_section' => 'subscribe'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'subscribecolor',
			'default' => '',
			'section' => 'sbspf_subscribe',
			'callback' => 'color',
			'title' => __( 'Subscribe Background Color', $text_domain ),
			'shortcode' => array(
				'key' => 'subscribecolor',
				'example' => '#0f0',
				'description' => __( 'Background color for the "Subscribe" button. Any hex color code.', $text_domain ),
				'display_section' => 'subscribe'
			),
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'subscribetextcolor',
			'default' => '',
			'section' => 'sbspf_subscribe',
			'callback' => 'color',
			'title' => __( 'Subscribe Text Color', $text_domain ),
			'shortcode' => array(
				'key' => 'subscribetextcolor',
				'example' => '#00f',
				'description' => __( 'Text color for the "Subscribe" button. Any hex color code.', $text_domain ),
				'display_section' => 'subscribe'
			),
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'subscribetext',
			'default' => __( 'Subscribe', $text_domain ),
			'section' => 'sbspf_subscribe',
			'callback' => 'text',
			'title' => __( 'Subscribe Text', $text_domain ),
			'shortcode' => array(
				'key' => 'subscribetext',
				'example' => '"Subscribe to My Channel"',
				'description' => __( 'The text that appers on the "Subscribe" button.', $text_domain ),
				'display_section' => 'subscribe'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'title' => __( 'Video Experience', $text_domain ),
			'id' => 'sbspf_experience',
			'tab' => 'customize',
		);
		$this->add_settings_section( $args );

		$select_options = array(
			array(
				'label' => '9:16',
				'value' => '9:16'
			),
			array(
				'label' => '3:4',
				'value' => '3:4'
			),
		);
		$args = array(
			'name' => 'playerratio',
			'default' => '9:16',
			'section' => 'sbspf_experience',
			'callback' => 'select',
			'title' => __( 'Player Size Ratio', $text_domain ),
			'shortcode' => array(
				'key' => 'playerratio',
				'example' => '9:16',
				'description' => __( 'Player height relative to width e.g.', $text_domain ) . ' 9:16, 3:4',
				'display_section' => 'experience'
			),
			'options' => $select_options,
			'tooltip_info' => __( 'A 9:16 ratio does not leave room for video title and playback tools while a 3:4 ratio does.', $text_domain )
		);
		$this->add_settings_field( $args );

		$select_options = array(
			array(
				'label' => __( 'Play when clicked', $text_domain ),
				'value' => 'onclick'
			),
			array(
				'label' => 'Play automatically (desktop only)',
				'value' => 'automatically'
			)
		);
		$args = array(
			'name' => 'playvideo',
			'default' => 'onclick',
			'section' => 'sbspf_experience',
			'callback' => 'select',
			'title' => __( 'When does video play?', $text_domain ),
			'shortcode' => array(
				'key' => 'playvideo',
				'example' => 'onclick',
				'description' => __( 'What the user needs to do to play a video. eg.', $text_domain ) . ' onclick, automatically',
				'display_section' => 'customize'
			),
			'options' => $select_options,
			'tooltip_info' => __( 'List layout will not play automatically. Choose whether to play the video automatically in the player or wait until the user clicks the play button after the video is loaded.', $text_domain )
		);
		$this->add_settings_field( $args );

		$cta_options = array(
			array(
				'label' => __( 'Related Videos', SBY_TEXT_DOMAIN ),
				'slug' => 'related',
				'pro' => true,
				'note' => __( 'Display video thumbnails from the feed that play on your site when clicked.', SBY_TEXT_DOMAIN )
			),
			array(
				'label' => 'Custom Link',
				'slug' => 'link',
				'pro' => true,
				'note' => __( 'Display a button link to a custom URL.', SBY_TEXT_DOMAIN ),
				'options' => array(
					array(
						'name' => 'instructions',
						'callback' => 'instructions',
						'instructions' => __( 'To set a link for each video individually, add the link and button text in the video description on YouTube in this format:', SBY_TEXT_DOMAIN ) . '<br><br><code>{Link: Button Text https://my-site.com/buy-now/my-product/}</code>',
						'label' => __( 'Custom link for each video', SBY_TEXT_DOMAIN ),
					),
					array(
						'name' => 'url',
						'callback' => 'text',
						'label' => __( 'Default Link', SBY_TEXT_DOMAIN ),
						'class' => 'large-text',
						'default' => '',
						'shortcode' => array(
							'example' => 'https://my-site.com/buy-now/my-product/',
							'description' => __( 'URL for viewer to visit for the call to action.', $text_domain ),
						)
					),
					array(
						'name' => 'opentype',
						'callback' => 'select',
						'options' => array(
							array(
								'label' => __( 'Same window', SBY_TEXT_DOMAIN ),
								'value' => 'same'
							),
							array(
								'label' => __( 'New window', SBY_TEXT_DOMAIN ),
								'value' => 'newwindow'
							)
						),
						'label' => __( 'Link Open Type', SBY_TEXT_DOMAIN ),
						'default' => 'same',
						'shortcode' => array(
							'example' => 'newwindow',
							'description' => __( 'Whether to open the page in a new window or the same window.', $text_domain ),
						)
					),
					array(
						'name' => 'text',
						'callback' => 'text',
						'label' => __( 'Default Button Text', SBY_TEXT_DOMAIN ),
						'default' => __( 'Learn More', SBY_TEXT_DOMAIN ),
						'shortcode' => array(
							'example' => 'Buy Now',
							'description' => __( 'Text that appears on the call-to-action button.', $text_domain ),
						)
					),
					array(
						'name' => 'color',
						'default' => '',
						'callback' => 'color',
						'label' => __( 'Button Background Color', SBY_TEXT_DOMAIN ),
						'shortcode' => array(
							'example' => '#0f0',
							'description' => __( 'Button background. Turns opaque on hover.', $text_domain ),
						)
					),
					array(
						'name' => 'textcolor',
						'default' => '',
						'callback' => 'color',
						'label' => __( 'Button Text Color', SBY_TEXT_DOMAIN ),
						'shortcode' => array(
							'example' => '#0f0',
							'description' => __( 'Color of the text on the call-to-action-button', $text_domain ),
						)
					)
				)
			),
			array(
				'label' => __( 'YouTube Default', SBY_TEXT_DOMAIN ),
				'slug' => 'default',
				'pro' => true,
				'note' => __( 'YouTube suggested videos from your channel that play on YouTube when clicked.', SBY_TEXT_DOMAIN )
			),
		);
		$this->pro_only[] = 'linkurl';
		$this->pro_only[] = 'linkopentype';
		$this->pro_only[] = 'linktext';
		$this->pro_only[] = 'linkcolor';
		$this->pro_only[] = 'linktextcolor';

		$args = array(
			'name' => 'cta',
			'default' => 'related',
			'section' => 'sbspf_experience',
			'callback' => 'sub_option',
			'pro' => true,
			'sub_options' => $cta_options,
			'title' => __( 'Call to Action', $text_domain ),
			'before' => '<p style="margin-bottom: 10px">' . __( 'What the user sees when a video pauses or ends.', $text_domain ) . '</p>',
			'shortcode' => array(
				'key' => 'cta',
				'example' => 'link',
				'description' => __( 'What the user sees when a video pauses or ends. eg.', $text_domain ) . ' related, link',
				'display_section' => 'experience'
			),
			'tooltip_info' => __( 'Choose what will happen after a video is paused or completes.', $text_domain )
		);
		$this->add_settings_field( $args );

		$args = array(
			'title' => __( 'Moderation', $text_domain ),
			'id' => 'sbspf_moderation',
			'tab' => 'customize',
			'pro' => __( 'Upgrade to Pro to enable Moderation settings', $text_domain ),
		);
		$this->add_settings_section( $args );

		$args = array(
			'name' => 'includewords',
			'default' => '',
			'section' => 'sbspf_moderation',
			'callback' => 'text',
			'class' => 'large-text',
			'title' => __( 'Show videos containing these words or hashtags', $text_domain ),
			'shortcode' => array(
				'key' => 'includewords',
				'example' => '#filter',
				'description' => __( 'Show videos that have specific text in the title or description.', $text_domain ),
				'display_section' => 'customize'
			),
			'additional' => __( '"includewords" separate multiple words with commas, include "#" for hashtags', $text_domain )
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'excludewords',
			'default' => '',
			'section' => 'sbspf_moderation',
			'callback' => 'text',
			'class' => 'large-text',
			'title' => __( 'Remove videos containing these words or hashtags', $text_domain ),
			'shortcode' => array(
				'key' => 'excludewords',
				'example' => '#filter',
				'description' => __( 'Remove videos that have specific text in the title or description.', $text_domain ),
				'display_section' => 'customize'
			),
			'additional' => __( '"excludewords" separate multiple words with commas, include "#" for hashtags', $text_domain )
		);
		$this->add_settings_field( $args );
		$this->pro_only[] = 'includewords';
		$this->pro_only[] = 'excludewords';

		$args = array(
			'name' => 'hidevideos',
			'default' => '',
			'section' => 'sbspf_moderation',
			'callback' => 'textarea',
			'title' => __( 'Hide Specific Videos', $text_domain ),
			'options' => $select_options,
			'tooltip_info' => __( 'Separate IDs with commas.', $text_domain ) . '<a class="sbspf_tooltip_link" href="JavaScript:void(0);">'.$this->default_tooltip_text().'</a>
            <p class="sbspf_tooltip sbspf_more_info">' . __( 'These are the specific ID numbers associated with a video or with a post. You can find the ID of a video by viewing the video on YouTube and copy/pasting the ID number from the end of the URL. ex. <code>https://www.youtube.com/watch?v=<span class="sbspf-highlight">Ij1KvL8eN</span></code>', $text_domain ) . '</p>'
		);
		$this->add_settings_field( $args );

		$args = array(
			'title' => __( 'Custom Code Snippets', $text_domain ),
			'id' => 'sbspf_custom_snippets',
			'tab' => 'customize'
		);
		$this->add_settings_section( $args );

		$args = array(
			'name' => 'custom_css',
			'default' => '',
			'section' => 'sbspf_custom_snippets',
			'callback' => 'textarea',
			'title' => __( 'Custom CSS', $text_domain ),
			'options' => $select_options,
			'tooltip_info' => __( 'Enter your own custom CSS in the box below', $text_domain )
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'custom_js',
			'default' => '',
			'section' => 'sbspf_custom_snippets',
			'callback' => 'textarea',
			'title' => __( 'Custom JavaScript', $text_domain ),
			'options' => $select_options,
			'tooltip_info' => __( 'Enter your own custom JavaScript/jQuery in the box below', $text_domain ),
			'note' => __( 'Note: Custom JavaScript reruns every time more videos are loaded into the feed', $text_domain )
		);
		$this->add_settings_field( $args );

		$args = array(
			'title' => __( 'GDPR', $text_domain ),
			'id' => 'sbspf_gdpr',
			'tab' => 'customize',
			'save_after' => 'true'
		);
		$this->add_settings_section( $args );

		$this->add_settings_field( array(
			'name' => 'gdpr',
			'title' => __( 'Enable GDPR Settings', $text_domain ),
			'callback'  => 'gdpr', // name of the function that outputs the html
			'section' => 'sbspf_gdpr', // matches the section name
		));

		$args = array(
			'title' => __( 'Advanced', $text_domain ),
			'id' => 'sbspf_advanced',
			'tab' => 'customize',
			'save_after' => true
		);
		$this->add_settings_section( $args );

		$args = array(
			'name' => 'preserve_settings',
			'section' => 'sbspf_advanced',
			'callback' => 'checkbox',
			'title' => __( 'Preserve settings when plugin is removed', $text_domain ),
			'default' => false,
			'tooltip_info' => __( 'When removing the plugin your settings are automatically erased. Checking this box will prevent any settings from being deleted. This means that you can uninstall and reinstall the plugin without losing your settings.', $text_domain )
		);
		$this->add_settings_field( $args );

		$select_options = array(
			array(
				'label' => __( 'Background', $text_domain ),
				'value' => 'background'
			),
			array(
				'label' => __( 'Page', $text_domain ),
				'value' => 'page'
			),
			array(
				'label' => __( 'None', $text_domain ),
				'value' => 'none'
			)
		);
		$additional = '<input id="sby-clear-cache" class="button-secondary sbspf-button-action" data-sby-action="sby_delete_wp_posts" data-sby-confirm="'.esc_attr( 'This will permanently delete all YouTube posts from the wp_posts table and the related data in the postmeta table. Existing feeds will only have 15 or fewer videos available initially. Continue?', $text_domain ).'" style="margin-top: 1px;" type="submit" value="'.esc_attr( 'Clear YouTube Posts', $text_domain ).'">';
		$args = array(
			'name' => 'storage_process',
			'default' => '',
			'section' => 'sbspf_advanced',
			'callback' => 'select',
			'title' => __( 'Local storage process', $text_domain ),
			'options' => $select_options,
			'additional' => $additional,
			'tooltip_info' => __( 'To preserve your feeds and videos even if the YouTube API is unavailable, a record of each video is added to the wp_posts table in the WordPress database. Please note that changing this setting to "none" will limit the number of posts available in the feed to 15 or less.', $text_domain )
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'cronclear',
			'section' => 'sbspf_advanced',
			'callback' => 'checkbox',
			'title' => __( 'Cron Clear Cache', $text_domain ),
			'default' => false,
			'tooltip_info' => __( 'If your YouTube feed is not updating, your WordPress installation may have a rare issue with clearing temporary data in your database. Enable this setting to use WordPress cron to clear your feed caches.', $text_domain )
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'ajaxtheme',
			'section' => 'sbspf_advanced',
			'callback' => 'checkbox',
			'title' => __( 'Are you using an AJAX theme?', $text_domain ),
			'default' => false,
			'tooltip_info' => __( 'When navigating your site, if your theme uses Ajax to load content into your pages (meaning your page doesn\'t refresh) then check this setting. If you\'re not sure then it\'s best to leave this setting unchecked while checking with your theme author, otherwise checking it may cause a problem.', $text_domain )
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'customtemplates',
			'section' => 'sbspf_advanced',
			'callback' => 'checkbox',
			'title' => __( 'Enable Custom Templates', $text_domain ),
			'default' => false,
			'tooltip_info' => __( 'The default HTML for the feed can be replaced with custom templates added to your theme\'s folder. Enable this setting to use these templates. See <a href="https://smashballoon.com/youtube-custom-templates/" target="_blank">this guide</a>', $text_domain )
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'eagerload',
			'section' => 'sbspf_advanced',
			'callback' => 'checkbox',
			'title' => __( 'Load Iframes on Page Load', $text_domain ),
			'default' => false,
			'tooltip_info' => __( 'To optimize the performance of your site and feeds, the plugin loads iframes only after a visitor interacts with the feed. Enabling this setting will cause YouTube player iframes to load when the page loads. Some features may work differently when this is enabled.', $text_domain )
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'enqueue_js_in_head',
			'section' => 'sbspf_advanced',
			'callback' => 'checkbox',
			'title' => __( 'Enqueue JS file in head', $text_domain ),
			'default' => false,
			'tooltip_info' => __( 'Check this box if you\'d like to enqueue the JavaScript file for the plugin in the head instead of the footer.', $text_domain )
		);
		$this->add_settings_field( $args );

	}

	public function cache( $args ) {
		$social_network = $this->vars->social_network();
		$type_selected = isset( $this->settings['caching_type'] ) ? $this->settings['caching_type'] : 'page';
		$caching_time = isset( $this->settings['caching_time'] ) ? $this->settings['caching_time'] : 1;
		$cache_time_unit_selected = isset( $this->settings['cache_time_unit'] ) ? $this->settings['cache_time_unit'] : 'hours';

		$cache_cron_interval_selected = isset( $this->settings['cache_cron_interval'] ) ? $this->settings['cache_cron_interval'] : '';
		$cache_cron_time = isset( $this->settings['cache_cron_time'] ) ? $this->settings['cache_cron_time'] : '';
		$cache_cron_am_pm = isset( $this->settings['cache_cron_am_pm'] ) ? $this->settings['cache_cron_am_pm'] : '';

		?>
        <div class="sbspf_cache_settings_wrap">
            <div class="sbspf_row">
                <input type="radio" name="<?php echo $this->option_name.'[caching_type]'; ?>" class="sbspf_caching_type_input" id="sbspf_caching_type_page" value="page"<?php if ( $type_selected === 'page' ) echo ' checked'?>>
                <label class="sbspf_radio_label" for="sbspf_caching_type_page"><?php _e ( 'When the page loads', $this->vars->text_domain() ); ?></label>
                <a class="sbspf_tooltip_link" href="JavaScript:void(0);" style="position: relative; top: 2px;"><?php echo $this->default_tooltip_text() ?></a>
                <p class="sbspf_tooltip sbspf_more_info"><?php echo sprintf( __( "Your %s data is temporarily cached by the plugin in your WordPress database. There are two ways that you can set the plugin to check for new data:<br><br>
                <b>1. When the page loads</b><br>Selecting this option means that when the cache expires then the plugin will check %s for new posts the next time that the feed is loaded. You can choose how long this data should be cached for with a minimum time of 15 minutes. If you set the time to 60 minutes then the plugin will clear the cached data after that length of time, and the next time the page is viewed it will check for new data. <b>Tip:</b> If you're experiencing an issue with the plugin not updating automatically then try enabling the setting labeled <b>'Cron Clear Cache'</b> which is located on the 'Customize' tab.<br><br>
                <b>2. In the background</b><br>Selecting this option means that the plugin will check for new data in the background so that the feed is updated behind the scenes. You can select at what time and how often the plugin should check for new data using the settings below. <b>Please note</b> that the plugin will initially check for data from YouTube when the page first loads, but then after that will check in the background on the schedule selected - unless the cache is cleared.", $this->vars->text_domain() ), $social_network, $social_network ); ?>
                </p>
            </div>
            <div class="sbspf_row sbspf-caching-page-options" style="display: none;">
				<?php _e ( 'Every', $this->vars->text_domain() ); ?>:
                <input name="<?php echo $this->option_name.'[caching_time]'; ?>" type="text" value="<?php echo esc_attr( $caching_time ); ?>" size="4">
                <select name="<?php echo $this->option_name.'[cache_time_unit]'; ?>">
                    <option value="minutes"<?php if ( $cache_time_unit_selected === 'minutes' ) echo ' selected'?>><?php _e ( 'Minutes', $this->vars->text_domain() ); ?></option>
                    <option value="hours"<?php if ( $cache_time_unit_selected === 'hours' ) echo ' selected'?>><?php _e ( 'Hours', $this->vars->text_domain() ); ?></option>
                    <option value="days"<?php if ( $cache_time_unit_selected === 'days' ) echo ' selected'?>><?php _e ( 'Days', $this->vars->text_domain() ); ?></option>
                </select>
                <a class="sbspf_tooltip_link" href="JavaScript:void(0);"><?php _e ( 'What does this mean?', $this->vars->text_domain() ); ?></a>
                <p class="sbspf_tooltip sbspf_more_info"><?php echo sprintf( __("Your %s posts are temporarily cached by the plugin in your WordPress database. You can choose how long the posts should be cached for. If you set the time to 1 hour then the plugin will clear the cache after that length of time and check %s for posts again.", $this->vars->text_domain() ), $social_network, $social_network ); ?></p>
            </div>

            <div class="sbspf_row">
                <input type="radio" name="<?php echo $this->option_name.'[caching_type]'; ?>" id="sbspf_caching_type_cron" class="sbspf_caching_type_input" value="background" <?php if ( $type_selected === 'background' ) echo ' checked'?>>
                <label class="sbspf_radio_label" for="sbspf_caching_type_cron"><?php _e ( 'In the background', $this->vars->text_domain() ); ?></label>
            </div>
            <div class="sbspf_row sbspf-caching-cron-options" style="display: block;">

                <select name="<?php echo $this->option_name.'[cache_cron_interval]'; ?>" id="sbspf_cache_cron_interval">
                    <option value="30mins"<?php if ( $cache_cron_interval_selected === '30mins' ) echo ' selected'?>><?php _e ( 'Every 30 minutes', $this->vars->text_domain() ); ?></option>
                    <option value="1hour"<?php if ( $cache_cron_interval_selected === '1hour' ) echo ' selected'?>><?php _e ( 'Every hour', $this->vars->text_domain() ); ?></option>
                    <option value="12hours"<?php if ( $cache_cron_interval_selected === '12hours' ) echo ' selected'?>><?php _e ( 'Every 12 hours', $this->vars->text_domain() ); ?></option>
                    <option value="24hours"<?php if ( $cache_cron_interval_selected === '24hours' ) echo ' selected'?>><?php _e ( 'Every 24 hours', $this->vars->text_domain() ); ?></option>
                </select>

                <div id="sbspf-caching-time-settings" style="">
					<?php _e ( 'at', $this->vars->text_domain() ); ?>
                    <select name="<?php echo $this->option_name.'[cache_cron_time]'; ?>" style="width: 80px">
                        <option value="1"<?php if ( (int)$cache_cron_time === 1 ) echo ' selected'?>>1:00</option>
                        <option value="2"<?php if ( (int)$cache_cron_time === 2 ) echo ' selected'?>>2:00</option>
                        <option value="3"<?php if ( (int)$cache_cron_time === 3 ) echo ' selected'?>>3:00</option>
                        <option value="4"<?php if ( (int)$cache_cron_time === 4 ) echo ' selected'?>>4:00</option>
                        <option value="5"<?php if ( (int)$cache_cron_time === 5 ) echo ' selected'?>>5:00</option>
                        <option value="6"<?php if ( (int)$cache_cron_time === 6 ) echo ' selected'?>>6:00</option>
                        <option value="7"<?php if ( (int)$cache_cron_time === 7 ) echo ' selected'?>>7:00</option>
                        <option value="8"<?php if ( (int)$cache_cron_time === 8 ) echo ' selected'?>>8:00</option>
                        <option value="9"<?php if ( (int)$cache_cron_time === 9 ) echo ' selected'?>>9:00</option>
                        <option value="10"<?php if ( (int)$cache_cron_time === 10 ) echo ' selected'?>>10:00</option>
                        <option value="11"<?php if ( (int)$cache_cron_time === 11 ) echo ' selected'?>>11:00</option>
                        <option value="0"<?php if ( (int)$cache_cron_time === 0 ) echo ' selected'?>>12:00</option>
                    </select>

                    <select name="<?php echo $this->option_name.'[cache_cron_am_pm]'; ?>" style="width: 50px">
                        <option value="am"<?php if ( $cache_cron_am_pm === 'am' ) echo ' selected'?>><?php _e ( 'AM', $this->vars->text_domain() ); ?></option>
                        <option value="pm"<?php if ( $cache_cron_am_pm === 'pm' ) echo ' selected'?>><?php _e ( 'PM', $this->vars->text_domain() ); ?></option>
                    </select>
                </div>

				<?php
				if ( wp_next_scheduled( 'sbspf_feed_update' ) ) {
					$time_format = get_option( 'time_format' );
					if ( ! $time_format ) {
						$time_format = 'g:i a';
					}
					//
					$schedule = wp_get_schedule( 'sbspf_feed_update' );
					if ( $schedule == '30mins' ) $schedule = __( 'every 30 minutes', $this->vars->text_domain() );
					if ( $schedule == 'twicedaily' ) $schedule = __( 'every 12 hours', $this->vars->text_domain() );
					$sbspf_next_cron_event = wp_next_scheduled( 'sbspf_feed_update' );
					echo '<p class="sbspf-caching-sched-notice"><span><b>' . __( 'Next check', $this->vars->text_domain() ) . ': ' . date( $time_format, $sbspf_next_cron_event + sbspf_get_utc_offset() ) . ' (' . $schedule . ')</b> - ' . __( 'Note: Saving the settings on this page will clear the cache and reset this schedule', $this->vars->text_domain() ) . '</span></p>';
				} else {
					echo '<p style="font-size: 11px; color: #666;">' . __( 'Nothing currently scheduled', $this->vars->text_domain() ) . '</p>';
				}
				?>
            </div>
        </div>
		<?php
	}

	public function gdpr( $args ) {
		$gdpr = ( isset( $this->settings['gdpr'] ) ) ? $this->settings['gdpr'] : 'auto';
		$select_options = array(
			array(
				'label' => __( 'Automatic', 'youtube-feed' ),
				'value' => 'auto'
			),
			array(
				'label' => __( 'Yes', 'youtube-feed' ),
				'value' => 'yes'
			),
			array(
				'label' => __( 'No', 'youtube-feed' ),
				'value' => 'no'
			)
		)
		?>
		<?php
		$gdpr_list = "<ul class='sby-list'>
                            	<li>" . __('YouTube Player API will not be loaded.', 'youtube-feed') . "</li>
                            	<li>" . __('Thumbnail images for videos will display instead of actual video.', 'youtube-feed') . "</li>
                            	<li>" . __('To view videos, visitors will click on links to view the video on youtube.com.', 'youtube-feed') . "</li>
                            </ul>";
		?>
        <div>
            <select name="<?php echo $this->option_name.'[gdpr]'; ?>" id="sbspf_gdpr_setting">
				<?php foreach ( $select_options as $select_option ) :
					$selected = $select_option['value'] === $gdpr ? ' selected' : '';
					?>
                    <option value="<?php echo esc_attr( $select_option['value'] ); ?>"<?php echo $selected; ?> ><?php echo esc_html( $select_option['label'] ); ?></option>
				<?php endforeach; ?>
            </select>
            <a class="sbspf_tooltip_link" href="JavaScript:void(0);"><?php echo $this->default_tooltip_text(); ?></a>
            <div class="sbspf_tooltip sbspf_more_info gdpr_tooltip">

                <p><span><?php _e("Yes", 'youtube-feed' ); ?>:</span> <?php _e("Enabling this setting prevents all videos and external code from loading on your website. To accommodate this, some features of the plugin will be disabled or limited.", 'youtube-feed' ); ?> <a href="JavaScript:void(0);" class="sbspf_show_gdpr_list"><?php _e( 'What will be limited?', 'youtube-feed' ); ?></a></p>

				<?php echo "<div class='sbspf_gdpr_list'>" . $gdpr_list . '</div>'; ?>


                <p><span><?php _e("No", 'youtube-feed' ); ?>:</span> <?php _e("The plugin will still make some requests to display and play videos directly from YouTube.", 'youtube-feed' ); ?></p>


                <p><span><?php _e("Automatic", 'youtube-feed' ); ?>:</span> <?php echo sprintf( __( 'The plugin will only videos if consent has been given by one of these integrated %s', 'youtube-feed' ), '<a href="https://smashballoon.com/doc/gdpr-plugin-list/?youtube" target="_blank" rel="noopener">' . __( 'GDPR cookie plugins', 'youtube-feed' ) . '</a>' ); ?></p>

                <p><?php echo sprintf( __( '%s to learn more about GDPR compliance in the Feeds for YouTube plugin.', 'youtube-feed' ), '<a href="https://smashballoon.com/doc/feeds-for-youtube-gdpr-compliance/?youtube" target="_blank" rel="noopener">'. __( 'Click here', 'youtube-feed' ).'</a>' ); ?></p>
            </div>
        </div>

        <div id="sbspf_images_options" class="sbspf_box">
            <div class="sbspf_box_setting">
				<?php
				$checked = isset( $this->settings['disablecdn'] ) && $this->settings['disablecdn'] ? ' checked' : false;
				?>
                <input name="<?php echo $this->option_name.'[disablecdn]'; ?>" id="sbspf_disablecdn" class="sbspf_single_checkbox" type="checkbox"<?php echo $checked; ?>>
                <label for="sbspf_disablecdn"><?php _e("Block CDN Images", 'youtube-feed' ); ?></label>
                <a class="sbspf_tooltip_link" href="JavaScript:void(0);"><?php echo $this->default_tooltip_text(); ?></a>
                <div class="sbspf_tooltip sbspf_more_info">
					<?php _e("Images in the feed come from YouTube's CDN. Enabling this setting will show a placeholder image until consent is given.", 'youtube-feed' ); ?>
                </div>
            </div>
        </div>

		<?php if ( ! SBY_GDPR_Integrations::gdpr_tests_successful( isset( $_GET['retest'] ) ) ) :
			$errors = SBY_GDPR_Integrations::gdpr_tests_error_message();
			?>
            <div class="sbspf_box sbspf_gdpr_error">
                <div class="sbspf_box_setting">
                    <p>
                        <strong><?php _e( 'Error:', 'youtube-feed' ); ?></strong> <?php _e("Due to a configuration issue on your web server, the GDPR setting is unable to be enabled. Please see below for more information.", 'youtube-feed' ); ?></p>
                    <p>
						<?php echo $errors; ?>
                    </p>
                </div>
            </div>
		<?php else: ?>

            <div class="sbspf_gdpr_auto">
				<?php if ( SBY_GDPR_Integrations::gdpr_plugins_active() ) :
					$active_plugin = SBY_GDPR_Integrations::gdpr_plugins_active();
					?>
                    <div class="sbspf_gdpr_plugin_active">
                        <div class="sbspf_active">
                            <p>
                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-check-circle fa-w-16 fa-2x"><path fill="currentColor" d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z" class=""></path></svg>
                                <b><?php echo sprintf( __( '%s detected', 'youtube-feed' ), $active_plugin ); ?></b>
                                <br />
								<?php _e( 'Some Feeds for YouTube features will be limited for visitors to ensure GDPR compliance until they give consent.', 'youtube-feed' ); ?>
                                <a href="JavaScript:void(0);" class="sbspf_show_gdpr_list"><?php _e( 'What will be limited?', 'youtube-feed' ); ?></a>
                            </p>
							<?php echo "<div class='sbspf_gdpr_list'>" . $gdpr_list . '</div>'; ?>
                        </div>

                    </div>
				<?php else: ?>
                    <div class="sbspf_box">
                        <div class="sbspf_box_setting">
                            <p><?php _e( 'No GDPR consent plugin detected. Install a compatible <a href="https://smashballoon.com/doc/gdpr-plugin-list/?youtube">GDPR consent plugin</a>, or manually enable the setting above to display a GDPR compliant version of the feed to all visitors.', 'youtube-feed' ); ?></p>
                        </div>
                    </div>
				<?php endif; ?>
            </div>

            <div class="sbspf_box sbspf_gdpr_yes">
                <div class="sbspf_box_setting">
                    <p><?php _e( "No requests will be made to third-party websites. To accommodate this, some features of the plugin will be limited:", 'youtube-feed' ); ?></p>
					<?php echo $gdpr_list; ?>
                </div>
            </div>

            <div class="sbspf_box sbspf_gdpr_no">
                <div class="sbspf_box_setting">
                    <p><?php _e( "The plugin will function as normal and load images and videos directly from Twitter.", 'youtube-feed' ); ?></p>
                </div>
            </div>

		<?php endif;
	}

	public function get_connected_accounts() {
		global $sby_settings;

		if ( isset( $sby_settings['connected_accounts'] ) ) {
			return $sby_settings['connected_accounts'];
		}
		return array();
	}

	public function access_token_listener() {
		if ( isset( $_GET['page'] ) && $_GET['page'] === SBY_SLUG && isset( $_GET['sby_access_token'] ) ) {
			sby_attempt_connection();
		}
	}

	public static function connect_account( $args ) {
		sby_update_or_connect_account( $args );
	}
}
