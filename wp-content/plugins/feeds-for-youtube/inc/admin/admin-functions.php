<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function sby_admin_init() {
	global $sby_settings;

	$base_path = trailingslashit( SBY_PLUGIN_DIR ) . 'inc/admin/templates';
	$slug = SBY_SLUG;
	$plugin_name = SBY_PLUGIN_NAME;
	$capability = current_user_can( 'manage_options' ) ? 'manage_options' : 'manage_youtube_feed_options';
	$icon = 'dashicons-video-alt3';
	$position = 99;
	$tabs = array(
		array(
			'title' => __( 'Configure', SBY_TEXT_DOMAIN ),
			'slug' => 'configure',
			'capability' => $capability,
			'next_step_instructions' => __( 'Customize your feed', SBY_TEXT_DOMAIN )
		),
		array(
			'title' => __( 'Customize', SBY_TEXT_DOMAIN ),
			'slug' => 'customize',
			'capability' => $capability,
			'next_step_instructions' => __( 'Display your feed', SBY_TEXT_DOMAIN )
		),
		array(
			'title' => __( 'Display', SBY_TEXT_DOMAIN ),
			'slug' => 'display',
			'capability' => $capability
		),
		array(
			'title' => __( 'Support', SBY_TEXT_DOMAIN ),
			'slug' => 'support',
			'capability' => $capability,
			'numbered_tab' => false
		),
		array(
			'title' => __( 'All Feeds', SBY_TEXT_DOMAIN ),
			'slug' => 'allfeeds',
			'capability' => $capability,
			'numbered_tab' => false,
			'has_nav_tab' => false
		)
	);

	$active_tab = $tabs[0]['slug'];
	if ( isset( $_GET['tab'] ) ) {
		$active_tab = sanitize_text_field( $_GET['tab'] ); $tabs[0]['slug'];
	} elseif ( isset( $_GET['page'] ) ) {
		foreach ( $tabs as $tab ) {
			if ( $_GET['page'] === $slug . '_' . $tab['slug'] ) {
				$active_tab = $tab['slug'];
			}
		}
	}
	$vars = new SBY_Vars();
	$admin = new SBY_Admin( $vars, $base_path, $slug, $plugin_name, $capability, $icon, $position, $tabs, $sby_settings, $active_tab, 'sby_settings' );
	$admin->access_token_listener();

	$first_connected = sby_get_first_connected_account();
	$first_channel_id = isset( $first_connected['channel_id'] ) ? $first_connected['channel_id'] : '';

	$types = array(
		array(
			'slug' => 'channel',
			'label' => __( 'Channel', SBY_TEXT_DOMAIN ),
			'input_type' => 'text',
			'default' => $first_channel_id,
			'note' => __( 'Eg: Channel ID or User Name', SBY_TEXT_DOMAIN ),
			'example' => 'smashballoon',
			'description' => __( 'Display videos from a YouTube channel (channel)', SBY_TEXT_DOMAIN ),
			'tooltip' => '<p>' . __( 'Enter any channel ID or user name to display all of an accounts latest videos starting with the most recently published.', SBY_TEXT_DOMAIN ) . '</p><p><ul>
                                    <li><b>' . __( 'Channel ID or User Name', SBY_TEXT_DOMAIN ).'</b><br>
                                        ' . __( 'You can find the ID or User Name of your YouTube Channel from the URL. In each URL format, the text you need to use is highlighted below:', SBY_TEXT_DOMAIN ).'<br><br>
                                    ' . __( 'URL Format 1:', SBY_TEXT_DOMAIN ).' <code>https://www.youtube.com/channel/<span class="sbspf-highlight">UC1a2b3c4D5F6g7i8j9k</span></code>
                                    <br>
                                    ' . __( 'URL Format 2:', SBY_TEXT_DOMAIN ).' <code>https://www.youtube.com/user/<span class="sbspf-highlight">your_user_name</span></code>
                                                                        </li>
                                </ul></p>'
		),
		array(
			'slug' => 'playlist',
			'label' => __( 'Playlist', SBY_TEXT_DOMAIN ),
			'input_type' => 'text',
			'default' => '',
			'pro' => true,
			'note' => __( 'Eg: Playlist ID', SBY_TEXT_DOMAIN ),
			'example' => 'PLLLm1a2b3c4D6g7i8j9k_1a',
			'description' => __( 'Display videos from a specific playlist (playlist)', SBY_TEXT_DOMAIN ),
			'tooltip' => '<p>' . __( 'Enter any playlist ID to display videos from a playlist starting with the most recently published.', SBY_TEXT_DOMAIN ) . '</p><p><ul>
                                    <li><b>' . __( 'Playlist ID', SBY_TEXT_DOMAIN ).'</b><br>
                                        ' . __( 'You can find the ID of your YouTube playlist from the URL. The text you need to use is highlighted below:', SBY_TEXT_DOMAIN ).'<br><br>
                                    <code>https://www.youtube.com/playlist?list=<span class="sbspf-highlight">PLLLm1a2b3c4D6g7i8j9k_1a2b3c4D57i8j9k</span></code>
                                    </li>
                                </ul></p>'
		),
		array(
			'slug' => 'favorites',
			'label' => __( 'Favorites', SBY_TEXT_DOMAIN ),
			'input_type' => 'text',
			'default' => '',
			'pro' => true,
			'note' => __( 'Eg: Channel ID or User Name', SBY_TEXT_DOMAIN ),
			'example' => 'smashballoon',
			'description' => __( 'Display the "favorites" playlist for a channel (favorites)', SBY_TEXT_DOMAIN ),
			'tooltip' => '<p>' . __( 'Displays all videos marked as "favorites" by a YouTube account starting with the most recently published.', SBY_TEXT_DOMAIN ) . '</p><p><ul>
                                    <li><b>' . __( 'Channel ID or User Name', SBY_TEXT_DOMAIN ).'</b><br>
                                        ' . __( 'You can find the ID or User Name of your YouTube Channel from the URL. In each URL format, the text you need to use is highlighted below:', SBY_TEXT_DOMAIN ).'<br><br>
                                    ' . __( 'URL Format 1:', SBY_TEXT_DOMAIN ).' <code>https://www.youtube.com/channel/<span class="sbspf-highlight">UC1a2b3c4D5F6g7i8j9k</span></code>
                                    <br>
                                    ' . __( 'URL Format 2:', SBY_TEXT_DOMAIN ).' <code>https://www.youtube.com/user/<span class="sbspf-highlight">your_user_name</span></code>
                                                                        </li>
                                </ul></p>'
		),
		array(
			'slug' => 'search',
			'label' => __( 'Search', SBY_TEXT_DOMAIN ),
			'input_type' => 'text',
			'default' => '',
			'pro' => true,
			'note' => __( 'Eg: Search Term', SBY_TEXT_DOMAIN ),
			'example' => 'cats',
			'description' => __( 'Display a feed of matching search results (search)', SBY_TEXT_DOMAIN ),
			'tooltip' => '<p>' . __( 'Enter any search term or phrase. Separate multiple terms with commas. You can add your own additional query vars using the <a href="https://smashballoon.com/youtube-feed/custom-search-guide/" target="_blank" rel="noopener">guide on our website</a> and the input field above.', SBY_TEXT_DOMAIN ) . '</p>',
		),
		array(
			'slug' => 'live',
			'label' => __( 'Live Streams', SBY_TEXT_DOMAIN ),
			'input_type' => 'text',
			'default' => '',
			'pro' => true,
			'note' => __( 'Eg: Channel ID', SBY_TEXT_DOMAIN ),
			'example' => 'UC1a2b3c4D5F6g7i8j9k',
			'description' => __( 'Display upcoming and currently playing live streams (live)', SBY_TEXT_DOMAIN ),
			'tooltip' => '<p>' . __( 'Displays upcoming and currently playing live streaming videos sorted by soonest scheduled broadcast.', SBY_TEXT_DOMAIN ) . '</p><p><ul>
                                    <li><b>' . __( 'Channel ID', SBY_TEXT_DOMAIN ).'</b><br>
                                        ' . __( 'You can find the ID of your YouTube Channel from the URL. The text you need to use is highlighted below:', SBY_TEXT_DOMAIN ).'<br><br>
                                    <code>https://www.youtube.com/channel/<span class="sbspf-highlight">UC1a2b3c4D5F6g7i8j9k</span></code>
                                                                        </li>
                                </ul></p>'
		)
	);
	$admin->set_feed_types( $types );

	$text_domain = SBY_TEXT_DOMAIN;
	/* Layout */
	$layouts = array(
		array(
			'slug' => 'grid',
			'label' => __( 'Grid', $text_domain ),
			'image' => 'img/grid.png',
			'note' => __( 'Video thumbnails are displayed in columns and play in a lightbox when clicked.', $text_domain ),
			'options' => array(
				array(
					'name' => 'cols',
					'callback' => 'select',
					'label' => __( 'Columns', $text_domain ),
					'min' => 1,
					'max' => 7,
					'default' => 3,
					'shortcode' => array(
						'example' => '3',
						'description' => __( 'Videos in carousel when 480px screen width or less.', $text_domain ),
					)
				),
				array(
					'name' => 'colsmobile',
					'callback' => 'select',
					'label' => __( 'Mobile Columns', $text_domain ),
					'min' => 1,
					'max' => 2,
					'default' => 2,
					'shortcode' => array(
						'example' => '2',
						'description' => __( 'Columns when 480px screen width or less.', $text_domain ),
					)
				),
			)
		),
		array(
			'slug' => 'gallery',
			'label' => __( 'Gallery', $text_domain ),
			'image' => 'img/gallery.png',
			'note' => __( 'One large video that plays when clicked with thumbnails underneath to play more.', $text_domain ),
			'options' => array(
				array(
					'name' => 'cols',
					'callback' => 'select',
					'label' => __( 'Columns', $text_domain ),
					'min' => 1,
					'max' => 7,
					'default' => 3,
					'shortcode' => array(
						'example' => '3',
						'description' => __( 'Videos in carousel when 480px screen width or less.', $text_domain ),
					)
				),
				array(
					'name' => 'colsmobile',
					'callback' => 'select',
					'label' => __( 'Mobile Columns', $text_domain ),
					'min' => 1,
					'max' => 2,
					'default' => 2,
					'shortcode' => array(
						'example' => '2',
						'description' => __( 'Columns when 480px screen width or less.', $text_domain ),
					)
				),
			)
		),
		array(
			'slug' => 'list',
			'label' => __( 'List', $text_domain ),
			'image' => 'img/list.png',
			'note' => __( 'A single columns of videos that play when clicked.', $text_domain ),
		),
		array(
			'slug' => 'carousel',
			'label' => __( 'Carousel', $text_domain ),
			'image' => 'img/carousel.png',
			'note' => __( 'Posts are displayed in a slideshow carousel.', $text_domain ),
			'pro' => true,
			'options' => array(
				array(
					'name' => 'cols',
					'callback' => 'select',
					'label' => __( 'Columns', $text_domain ),
					'min' => 1,
					'max' => 7,
					'default' => 3,
					'shortcode' => array(
						'example' => '3',
						'description' => __( 'Videos in carousel when 480px screen width or less.', $text_domain ),
					)
				),
				array(
					'name' => 'colsmobile',
					'callback' => 'select',
					'label' => __( 'Mobile Columns', $text_domain ),
					'min' => 1,
					'max' => 2,
					'default' => 2,
					'shortcode' => array(
						'example' => '2',
						'description' => __( 'Columns when 480px screen width or less.', $text_domain ),
					)				),
				array(
					'name' => 'rows',
					'callback' => 'select',
					'label' => __( 'Number of Rows', $text_domain ),
					'min' => 1,
					'max' => 2,
					'default' => 1,
					'shortcode' => array(
						'example' => '2',
						'description' => __( 'Choose 2 rows to show two posts in a single slide.', $text_domain ),
					)
				),
				array(
					'name' => 'loop',
					'callback' => 'select',
					'label' => __( 'Loop Type', $text_domain ),
					'options' => array(
						array(
							'label' => __( 'Rewind', $text_domain ),
							'value' => 'rewind'
						),
						array(
							'label' => __( 'Infinity', $text_domain ),
							'value' => 'infinity'
						)
					),
					'default' => 'rewind',
					'shortcode' => array(
						'example' => 'infinity',
						'description' => __( 'What happens when the last slide is reached.', $text_domain ),
					)
				),
				array(
					'name' => 'arrows',
					'callback' => 'checkbox',
					'label' => __( 'Show Navigation Arrows', $text_domain ),
					'default' => true,
					'shortcode' => array(
						'example' => 'false',
						'description' => __( 'Show arrows on the sides to navigate posts.', $text_domain ),
					)
				),
				array(
					'name' => 'pag',
					'callback' => 'checkbox',
					'label' => __( 'Show Pagination', $text_domain ),
					'default' => true,
					'shortcode' => array(
						'example' => 'false',
						'description' => __( 'Show dots below carousel for an ordinal indication of which slide is being shown.', $text_domain ),
					)
				),
				array(
					'name' => 'autoplay',
					'callback' => 'checkbox',
					'label' => __( 'Enable Autoplay', $text_domain ),
					'default' => false,
					'shortcode' => array(
						'example' => 'true',
						'description' => __( 'Whether or not to change slides automatically on an interval.', $text_domain ),
					)
				),
				array(
					'name' => 'time',
					'callback' => 'text',
					'label' => __( 'Interval Time', $text_domain ),
					'default' => 5000,
					'shortcode' => array(
						'example' => '3000',
						'description' => __( 'Duration in milliseconds before the slide changes.', $text_domain ),
					)
				),
			)
		),

	);
	$admin->set_feed_layouts( $layouts );

	$display_your_feed_table_headings = array(
		array(
			'slug' => 'configure',
			'label' => __( 'Configure Options', SBY_TEXT_DOMAIN ),
		),
		array(
			'slug' => 'customize',
			'label' => __( 'Customize Options', SBY_TEXT_DOMAIN ),
		),
		array(
			'slug' => 'layout',
			'label' => __( 'Layout Options', SBY_TEXT_DOMAIN ),
		),
		array(
			'slug' => 'experience',
			'label' => __( 'Video Experience Options', SBY_TEXT_DOMAIN ),
		),
		array(
			'slug' => 'header',
			'label' => __( 'Header Options', SBY_TEXT_DOMAIN ),
		),
		array(
			'slug' => 'button',
			'label' => __( '"Load More" Button Options', SBY_TEXT_DOMAIN ),
		),
		array(
			'slug' => 'subscribe',
			'label' => __( '"Subscribe" Button Options', SBY_TEXT_DOMAIN ),
		)
	);
	$admin->set_display_table_sections( $display_your_feed_table_headings );

	$admin->init();
}

function sby_admin_style() {
	wp_enqueue_style( SBY_SLUG . '_admin_notices_css', SBY_PLUGIN_URL . 'css/sby-notices.css', array(), SBYVER );
	if ( ! sby_is_admin_page() ) {
		return;
	}
	wp_enqueue_style( SBY_SLUG . '_admin_css', SBY_PLUGIN_URL . 'css/admin.css', array(), SBYVER );
	wp_enqueue_style( 'sb_font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
	wp_enqueue_style( 'wp-color-picker' );
}
add_action( 'admin_enqueue_scripts', 'sby_admin_style' );

function sby_admin_scripts() {
	if ( ! sby_is_admin_page() ) {
		return;
	}
	wp_enqueue_script( SBY_SLUG . '_admin_js', SBY_PLUGIN_URL . 'js/admin.js', array(), SBYVER );
	wp_localize_script( SBY_SLUG . '_admin_js', 'sbspf', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'sbspf_nonce' )
		)
	);
	wp_enqueue_script('wp-color-picker' );
}
add_action( 'admin_enqueue_scripts', 'sby_admin_scripts' );

function sby_is_admin_page() {
	if ( ! isset( $_GET['page'] ) ) {
		return false;
	} elseif ( strpos( sanitize_text_field( $_GET['page'] ), SBY_SLUG ) !== false ) {
		return true;
	}
	return false;
}

function sby_admin_icon( $icon, $class = '' ) {
	$class = ! empty( $class ) ? ' ' . $class : '';
	if ( $icon === 'question-circle' ) {
		return '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="question-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-question-circle fa-w-16'.$class.'"><path fill="currentColor" d="M504 256c0 136.997-111.043 248-248 248S8 392.997 8 256C8 119.083 119.043 8 256 8s248 111.083 248 248zM262.655 90c-54.497 0-89.255 22.957-116.549 63.758-3.536 5.286-2.353 12.415 2.715 16.258l34.699 26.31c5.205 3.947 12.621 3.008 16.665-2.122 17.864-22.658 30.113-35.797 57.303-35.797 20.429 0 45.698 13.148 45.698 32.958 0 14.976-12.363 22.667-32.534 33.976C247.128 238.528 216 254.941 216 296v4c0 6.627 5.373 12 12 12h56c6.627 0 12-5.373 12-12v-1.333c0-28.462 83.186-29.647 83.186-106.667 0-58.002-60.165-102-116.531-102zM256 338c-25.365 0-46 20.635-46 46 0 25.364 20.635 46 46 46s46-20.636 46-46c0-25.365-20.635-46-46-46z" class=""></path></svg>';
	} elseif ( $icon === 'info-circle' ) {
		return '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-info-circle fa-w-16'.$class.'"><path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z" class=""></path></svg>';
	} elseif ( $icon === 'life-ring' ) {
		return '<svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="life-ring" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-life-ring fa-w-16'.$class.'"><path fill="currentColor" d="M256 504c136.967 0 248-111.033 248-248S392.967 8 256 8 8 119.033 8 256s111.033 248 248 248zm-103.398-76.72l53.411-53.411c31.806 13.506 68.128 13.522 99.974 0l53.411 53.411c-63.217 38.319-143.579 38.319-206.796 0zM336 256c0 44.112-35.888 80-80 80s-80-35.888-80-80 35.888-80 80-80 80 35.888 80 80zm91.28 103.398l-53.411-53.411c13.505-31.806 13.522-68.128 0-99.974l53.411-53.411c38.319 63.217 38.319 143.579 0 206.796zM359.397 84.72l-53.411 53.411c-31.806-13.505-68.128-13.522-99.973 0L152.602 84.72c63.217-38.319 143.579-38.319 206.795 0zM84.72 152.602l53.411 53.411c-13.506 31.806-13.522 68.128 0 99.974L84.72 359.398c-38.319-63.217-38.319-143.579 0-206.796z" class=""></path></svg>';
	} elseif ( $icon === 'envelope' ) {
		return '<svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="envelope" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-envelope fa-w-16'.$class.'"><path fill="currentColor" d="M464 64H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V112c0-26.51-21.49-48-48-48zm0 48v40.805c-22.422 18.259-58.168 46.651-134.587 106.49-16.841 13.247-50.201 45.072-73.413 44.701-23.208.375-56.579-31.459-73.413-44.701C106.18 199.465 70.425 171.067 48 152.805V112h416zM48 400V214.398c22.914 18.251 55.409 43.862 104.938 82.646 21.857 17.205 60.134 55.186 103.062 54.955 42.717.231 80.509-37.199 103.053-54.947 49.528-38.783 82.032-64.401 104.947-82.653V400H48z" class=""></path></svg>';
	} elseif ( $icon === 'chevron-right' ) {
		return '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-circle-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-chevron-circle-right fa-w-16'.$class.'"><path fill="currentColor" d="M256 8c137 0 248 111 248 248S393 504 256 504 8 393 8 256 119 8 256 8zm113.9 231L234.4 103.5c-9.4-9.4-24.6-9.4-33.9 0l-17 17c-9.4 9.4-9.4 24.6 0 33.9L285.1 256 183.5 357.6c-9.4 9.4-9.4 24.6 0 33.9l17 17c9.4 9.4 24.6 9.4 33.9 0L369.9 273c9.4-9.4 9.4-24.6 0-34z" class=""></path></svg>';
	} elseif ( $icon === 'rocket' ) {
		return '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="rocket" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-rocket fa-w-16'.$class.'"><path fill="currentColor" d="M505.05 19.1a15.89 15.89 0 0 0-12.2-12.2C460.65 0 435.46 0 410.36 0c-103.2 0-165.1 55.2-211.29 128H94.87A48 48 0 0 0 52 154.49l-49.42 98.8A24 24 0 0 0 24.07 288h103.77l-22.47 22.47a32 32 0 0 0 0 45.25l50.9 50.91a32 32 0 0 0 45.26 0L224 384.16V488a24 24 0 0 0 34.7 21.49l98.7-49.39a47.91 47.91 0 0 0 26.5-42.9V312.79c72.59-46.3 128-108.4 128-211.09.1-25.2.1-50.4-6.85-82.6zM384 168a40 40 0 1 1 40-40 40 40 0 0 1-40 40z" class=""></path></svg>';
	} elseif ( $icon === 'minus-circle' ) {
		return '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="minus-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-minus-circle fa-w-16'.$class.'"><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zM124 296c-6.6 0-12-5.4-12-12v-56c0-6.6 5.4-12 12-12h264c6.6 0 12 5.4 12 12v56c0 6.6-5.4 12-12 12H124z" class=""></path></svg>';
	} elseif ( $icon === 'times' ) {
		return '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512" class="svg-inline--fa fa-times fa-w-11'.$class.'"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z" class=""></path></svg>';
	} elseif ( $icon === 'cog' ) {
		return '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="cog" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-cog fa-w-16'.$class.'"><path fill="currentColor" d="M487.4 315.7l-42.6-24.6c4.3-23.2 4.3-47 0-70.2l42.6-24.6c4.9-2.8 7.1-8.6 5.5-14-11.1-35.6-30-67.8-54.7-94.6-3.8-4.1-10-5.1-14.8-2.3L380.8 110c-17.9-15.4-38.5-27.3-60.8-35.1V25.8c0-5.6-3.9-10.5-9.4-11.7-36.7-8.2-74.3-7.8-109.2 0-5.5 1.2-9.4 6.1-9.4 11.7V75c-22.2 7.9-42.8 19.8-60.8 35.1L88.7 85.5c-4.9-2.8-11-1.9-14.8 2.3-24.7 26.7-43.6 58.9-54.7 94.6-1.7 5.4.6 11.2 5.5 14L67.3 221c-4.3 23.2-4.3 47 0 70.2l-42.6 24.6c-4.9 2.8-7.1 8.6-5.5 14 11.1 35.6 30 67.8 54.7 94.6 3.8 4.1 10 5.1 14.8 2.3l42.6-24.6c17.9 15.4 38.5 27.3 60.8 35.1v49.2c0 5.6 3.9 10.5 9.4 11.7 36.7 8.2 74.3 7.8 109.2 0 5.5-1.2 9.4-6.1 9.4-11.7v-49.2c22.2-7.9 42.8-19.8 60.8-35.1l42.6 24.6c4.9 2.8 11 1.9 14.8-2.3 24.7-26.7 43.6-58.9 54.7-94.6 1.5-5.5-.7-11.3-5.6-14.1zM256 336c-44.1 0-80-35.9-80-80s35.9-80 80-80 80 35.9 80 80-35.9 80-80 80z" class=""></path></svg>';
	} elseif ( $icon === 'ellipsis' ) {
		return '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ellipsis-h" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-ellipsis-h fa-w-16'.$class.'"><path fill="currentColor" d="M328 256c0 39.8-32.2 72-72 72s-72-32.2-72-72 32.2-72 72-72 72 32.2 72 72zm104-72c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72zm-352 0c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72z" class=""></path></svg>';
	} else {
		sby_icon( $icon );
	}
}

function sby_delete_connected_account() {
	if ( ! current_user_can( 'manage_youtube_feed_options' ) ) {
		return false;
	}

	if ( ! isset( $_POST['sbspf_nonce'] ) || ! isset( $_POST['account_id']) ) return;
	$nonce = $_POST['sbspf_nonce'];
	if ( ! wp_verify_nonce( $nonce, 'sbspf_nonce' ) ) {
		die ( 'You did not do this the right way!' );
	}

	global $sby_settings;

	$account_id = sanitize_text_field( $_POST['account_id'] );
	$to_save = array();

	foreach ( $sby_settings['connected_accounts'] as $connected_account ) {
		if ( (string)$connected_account['channel_id'] !== (string)$account_id ) {
			$to_save[ $connected_account['channel_id'] ] = $connected_account;
		}
	}

	$sby_settings['connected_accounts'] = $to_save;
	update_option( 'sby_settings', $sby_settings );

	echo wp_json_encode( array( 'success' => true ) );

	die();
}
add_action( 'wp_ajax_sby_ca_after_remove_clicked', 'sby_delete_connected_account' );

function sby_process_access_token() {
	if ( ! current_user_can( 'manage_youtube_feed_options' ) ) {
		return false;
	}

	if ( ! isset( $_POST['sbspf_nonce'] ) || ! isset( $_POST['sby_access_token'] ) ) return;
	$nonce = $_POST['sbspf_nonce'];
	if ( ! wp_verify_nonce( $nonce, 'sbspf_nonce' ) ) {
		die ( 'You did not do this the right way!' );
	}

	$account = sby_attempt_connection();

	if ( $account ) {
		global $sby_settings;

		$options = $sby_settings;
		$username = $account['username'] ? $account['username'] : $account['channel_id'];
		if ( isset( $account['local_avatar'] ) && $account['local_avatar'] && isset( $options['favorlocal'] ) && $options['favorlocal' ] === 'on' ) {
			$upload = wp_upload_dir();
			$resized_url = trailingslashit( $upload['baseurl'] ) . trailingslashit( SBY_UPLOADS_NAME );
			$profile_picture = '<img class="sbspf_ca_avatar" src="'.$resized_url . $account['username'].'.jpg" />'; //Could add placeholder avatar image
		} else {
			$profile_picture = $account['profile_picture'] ? '<img class="sbspf_ca_avatar" src="'.$account['profile_picture'].'" />' : ''; //Could add placeholder avatar image
		}

		$text_domain = SBY_TEXT_DOMAIN;
		$slug = SBY_SLUG;
		ob_start();
		include trailingslashit( SBY_PLUGIN_DIR ) . 'inc/admin/templates/single-connected-account.php';
		if ( sby_notice_not_dismissed() ) {
			include trailingslashit( SBY_PLUGIN_DIR ) . 'inc/admin/templates/modal.php';
			echo '<span class="sby_account_just_added"></span>';
		}

		$html = ob_get_contents();
		ob_get_clean();

		$return = array(
			'account_id' => $account['channel_id'],
			'html' => $html
		);
	} else {
		$return = array(
			'error' => __( 'Could not connect your account. Please check to make sure this is a valid access token for the Smash Balloon YouTube App.'),
			'html' => ''
		);
	}

	echo wp_json_encode( $return );

	die();
}
add_action( 'wp_ajax_sby_process_access_token', 'sby_process_access_token' );

function sby_delete_wp_posts() {
	if ( ! current_user_can( 'manage_youtube_feed_options' ) ) {
		return false;
	}

	if ( ! isset( $_POST['sbspf_nonce'] ) ) return;
	$nonce = $_POST['sbspf_nonce'];
	if ( ! wp_verify_nonce( $nonce, 'sbspf_nonce' ) ) {
		die ( 'You did not do this the right way!' );
	}

	sby_clear_wp_posts();

	echo '{}';

	die();
}
add_action( 'wp_ajax_sby_delete_wp_posts', 'sby_delete_wp_posts' );

function sby_attempt_connection() {
	if ( ! current_user_can( 'manage_youtube_feed_options' ) ) {
		return false;
	}
	if ( isset( $_GET['sby_access_token'] ) ) {
		$access_token = sanitize_text_field( urldecode( $_GET['sby_access_token'] ) );
		$refresh_token = '';
	} else {
		$access_token = sanitize_text_field( $_POST['sby_access_token'] );
		$refresh_token = '';
	}

	$account_info = array(
		'access_token' => $access_token,
		'refresh_token' => $refresh_token
	);
	$sby_api_connect = new SBY_API_Connect( $account_info, 'tokeninfo' );
	$sby_api_connect->connect();

	$data = $sby_api_connect->get_data();

	if ( isset( $data['audience'] ) ) {
		$expires = $data['expires_in'] + time();
		$sby_api_connect = new SBY_API_Connect( $account_info, 'channels' );
		$sby_api_connect->connect();
		$data = $sby_api_connect->get_data();

		if ( isset( $data['items'] ) ) {
			$account_info['username'] = $data['items'][0]['snippet']['title'];
			$account_info['channel_id'] = $data['items'][0]['id'];
			$account_info['profile_picture'] = $data['items'][0]['snippet']['thumbnails']['default']['url'];
			$account_info['privacy'] = '';
			$account_info['expires'] = $expires;
			//privacyStatus
			SBY_Admin::connect_account( $account_info );

			return $account_info;
		} else {
			$account_info['username'] = '(No Channel)';
			$account_info['channel_id'] = '';
			$account_info['profile_picture'] = '';
			$account_info['privacy'] = '';
			$account_info['expires'] = $expires;
			//privacyStatus
			SBY_Admin::connect_account( $account_info );

			return $account_info;
		}
	}
	return false;
}

function sbspf_account_search() {
	if ( ! current_user_can( 'manage_youtube_feed_options' ) ) {
		return false;
	}

	if ( ! isset( $_POST['sbspf_nonce'] ) || ! isset( $_POST['term']) ) return;
	$nonce = $_POST['sbspf_nonce'];
	if ( ! wp_verify_nonce( $nonce, 'sbspf_nonce' ) ) {
		die ( 'You did not do this the right way!' );
	}

	global $sby_settings;

	$term = sanitize_text_field( $_POST['term'] );
	$params = array(
		'q' => $term,
		'type' => 'channel'
	);

	$connected_account_for_term = array();
	foreach ( $sby_settings['connected_accounts'] as $connected_account ) {
		$connected_account_for_term = $connected_account;
	}
	if ( $connected_account_for_term['expires'] < time() + 5 ) {
		$new_token_data = SBY_API_Connect::refresh_token( sby_get_account_bottom(), $connected_account_for_term['refresh_token'], sby_get_account_top() );

		if ( isset( $new_token_data['access_token'] ) ) {
			$connected_account_for_term['access_token'] = $new_token_data['access_token'];
			$connected_accounts_for_feed[ $term ]['access_token'] = $new_token_data['access_token'];
			$connected_account_for_term['expires'] = $new_token_data['expires_in'] + time();
			$connected_accounts_for_feed[ $term ]['expires'] = $new_token_data['expires_in'] + time();

			sby_update_or_connect_account( $connected_account_for_term );

		}
	}

	$search = new SBY_API_Connect( $connected_account_for_term, 'search', $params );

	$search->connect();


	echo wp_json_encode( $search->get_data() );

	die();
}
add_action( 'wp_ajax_sbspf_account_search', 'sbspf_account_search' );

function sby_reset_cron( $settings ) {
	$sbi_caching_type = isset( $settings['caching_type'] ) ? $settings['caching_type'] : '';
	$sbi_cache_cron_interval = isset( $settings['cache_cron_interval'] ) ? $settings['cache_cron_interval'] : '';
	$sbi_cache_cron_time = isset( $settings['cache_cron_time'] ) ? $settings['cache_cron_time'] : '';
	$sbi_cache_cron_am_pm = isset( $settings['cache_cron_am_pm'] ) ? $settings['cache_cron_am_pm'] : '';

	if ( $sbi_caching_type === 'background' ) {
		delete_option( 'sby_cron_report' );
		SBY_Cron_Updater::start_cron_job( $sbi_cache_cron_interval, $sbi_cache_cron_time, $sbi_cache_cron_am_pm );
	}
}
add_action( 'sby_settings_after_configure_save', 'sby_reset_cron', 10, 1 );

function sby_maybe_start_cron_clear_cache( $settings ) {
	$sby_doing_cron_clear = isset( $settings['cronclear'] ) ? $settings['cronclear'] : false;

	if ( $sby_doing_cron_clear ) {
		wp_clear_scheduled_hook( 'sby_cron_job' );

		wp_schedule_event( time(), 'hourly', 'sby_cron_job' );
	}
}
add_action( 'sby_settings_after_customize_save', 'sby_maybe_start_cron_clear_cache', 10, 1 );

function sby_clear_wp_posts() {

	global $wpdb;

	$youtube_ids = $wpdb->get_col( "SELECT ID FROM $wpdb->posts WHERE post_type = '".SBY_CPT."';" );

	$id_string = implode( ', ', $youtube_ids );
	if ( ! empty( $id_string ) ) {
		$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE post_id IN ($id_string);" );
		$wpdb->query( "DELETE FROM $wpdb->posts WHERE post_type = '".SBY_CPT."';" );
	}
}

/** Notices */

function sby_dismiss_at_warning_notice() {
	if ( ! current_user_can( 'manage_youtube_feed_options' ) ) {
		return false;
	}
	update_user_meta( get_current_user_id(), 'sby_at_warning_notice', time() );

	die();
}
add_action( 'wp_ajax_sby_dismiss_at_warning_notice', 'sby_dismiss_at_warning_notice' );

function sby_dismiss_connect_warning_notice() {
	if ( ! current_user_can( 'manage_youtube_feed_options' ) ) {
		return false;
	}
	update_user_meta( get_current_user_id(), 'sby_connect_warning_notice', time() );

	die();
}
add_action( 'wp_ajax_sby_dismiss_connect_warning_button', 'sby_dismiss_connect_warning_notice' );

function sby_notice_not_dismissed( $key = 'sby_at_warning_notice' ) {
	$meta = get_user_meta( get_current_user_id(), $key, true );
	return (int)$meta + DAY_IN_SECONDS < time();
}

function sby_access_token_warning_modal() {
	if ( isset( $_GET['page'] ) && $_GET['page'] === SBY_SLUG && isset( $_GET['sby_access_token'] ) && sby_notice_not_dismissed() ) {
		$text_domain = SBY_TEXT_DOMAIN;
		include trailingslashit( SBY_PLUGIN_DIR ) . 'inc/admin/templates/modal.php';
		echo '<span class="sby_account_just_added"></span>';
	}

}
add_action( 'admin_footer', 'sby_access_token_warning_modal', 1 );

function sby_get_current_time() {
	$current_time = time();

	// where to do tests
	//$current_time = strtotime( 'November 25, 2020' ) + 1;

	return $current_time;
}

// generates the html for the admin notices
function sby_notices_html() {
}



function sby_lite_dismiss() {
	if ( ! current_user_can( 'manage_youtube_feed_options' ) ) {
		return false;
	}

	if ( ! isset( $_POST['sbspf_nonce'] ) ) return;
	$nonce = $_POST['sbspf_nonce'];
	if ( ! wp_verify_nonce( $nonce, 'sbspf_nonce' ) ) {
		die ( 'You did not do this the right way!' );
	}

	set_transient( 'youtube_feed_dismiss_lite', 'dismiss', 1 * WEEK_IN_SECONDS );

	die();
}
add_action( 'wp_ajax_sby_lite_dismiss', 'sby_lite_dismiss' );

function sby_get_future_date( $month, $year, $week, $day, $direction ) {
	if ( $direction > 0 ) {
		$startday = 1;
	} else {
		$startday = date( 't', mktime(0, 0, 0, $month, 1, $year ) );
	}

	$start = mktime( 0, 0, 0, $month, $startday, $year );
	$weekday = date( 'N', $start );

	$offset = 0;
	if ( $direction * $day >= $direction * $weekday ) {
		$offset = -$direction * 7;
	}

	$offset += $direction * ($week * 7) + ($day - $weekday);
	return mktime( 0, 0, 0, $month, $startday + $offset, $year );
}

function sby_admin_hide_unrelated_notices() {

	// Bail if we're not on a Sby screen or page.
	if ( ! sby_is_admin_page() ) {
		return;
	}

	// Extra banned classes and callbacks from third-party plugins.
	$blacklist = array(
		'classes'   => array(),
		'callbacks' => array(
			'sbydb_admin_notice', // 'Database for Sby' plugin.
		),
	);

	global $wp_filter;

	foreach ( array( 'user_admin_notices', 'admin_notices', 'all_admin_notices' ) as $notices_type ) {
		if ( empty( $wp_filter[ $notices_type ]->callbacks ) || ! is_array( $wp_filter[ $notices_type ]->callbacks ) ) {
			continue;
		}
		foreach ( $wp_filter[ $notices_type ]->callbacks as $priority => $hooks ) {
			foreach ( $hooks as $name => $arr ) {
				if ( is_object( $arr['function'] ) && $arr['function'] instanceof Closure ) {
					unset( $wp_filter[ $notices_type ]->callbacks[ $priority ][ $name ] );
					continue;
				}
				$class = ! empty( $arr['function'][0] ) && is_object( $arr['function'][0] ) ? strtolower( get_class( $arr['function'][0] ) ) : '';
				if (
					! empty( $class ) &&
					strpos( $class, 'sby' ) !== false &&
					! in_array( $class, $blacklist['classes'], true )
				) {
					continue;
				}
				if (
					! empty( $name ) && (
						strpos( $name, 'sby' ) === false ||
						in_array( $class, $blacklist['classes'], true ) ||
						in_array( $name, $blacklist['callbacks'], true )
					)
				) {
					unset( $wp_filter[ $notices_type ]->callbacks[ $priority ][ $name ] );
				}
			}
		}
	}
}
add_action( 'admin_print_scripts', 'sby_admin_hide_unrelated_notices' );
