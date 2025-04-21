<?php
/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */

/**
 * custom option and settings
 */
function antique_wp_settings_init() {
	// Register a new setting for "antique_wp" page.
	register_setting( 'antique_wp', 'antique_wp_options' );
	register_setting( 'antique_wp_checkout', 'antique_wp_checkout_options' );
	register_setting( 'antique_wp_shipstation', 'antique_wp_shipstation_options' );

    // Register the general section
	// add_settings_section(
	// 	'antique_wp_section_general',
	// 	__( 'General settings:', 'antique_wp' ), 'antique_wp_section_general_cb',
	// 	'antique_wp_general'
	// );

	// Register the arta section
	add_settings_section(
		'antique_wp_section_arta',
		__( 'Arta settings', 'antique_wp' ), 'antique_wp_section_arta_cb',
		'antique_wp'
	);

	// Register the shipstation section
	add_settings_section(
		'antique_wp_section_shipstation',
		__( 'Shipstation settings', 'antique_wp' ), 'antique_wp_section_shipstation_cb',
		'antique_wp_shipstation'
	);

	// Register the timers section
	add_settings_section(
		'antique_wp_section_timers',
		__( 'Timers settings', 'antique_wp' ), 'antique_wp_section_timers_cb',
		'antique_wp_timers'
	);
	// Register the checkout section
	add_settings_section(
		'antique_wp_section_checkout',
		__( 'Checkout page settings', 'antique_wp' ), 'antique_wp_section_checkout_cb',
		'antique_wp_checkout'
	);

	// arta key
	$artaOption = 'antique_wp_field_arta'; // Для удобства вынес в переменную
 
	register_setting( 'antique_wp', $artaOption );
 
	add_settings_field(
		$artaOption, 
		__( 'Api key', 'antique_wp' ),
		'antique_wp_text_field_cb',
		'antique_wp',
		'antique_wp_section_arta',
		array(
			'label_for' => $artaOption, 
			'name' => $artaOption
		)
	);
	// shipstation keys
	$shipstationOption = 'antique_wp_field_shipstation'; 
 	$shipstationOptionSecret = 'antique_wp_field_shipstation_secret'; 

	register_setting( 'antique_wp_shipstation', $shipstationOption );
	register_setting( 'antique_wp_shipstation', $shipstationOptionSecret );

	add_settings_field(
		$shipstationOption, 
		__( 'Api key', 'antique_wp' ),
		'antique_wp_text_field_cb',
		'antique_wp_shipstation',
		'antique_wp_section_shipstation',
		array(
			'label_for' => $shipstationOption,
			'name' => $shipstationOption
		)
	);

	add_settings_field(
		$shipstationOptionSecret, 
		__( 'Api secret', 'antique_wp' ),
		'antique_wp_text_field_cb',
		'antique_wp_shipstation',
		'antique_wp_section_shipstation',
		array(
			'label_for' => $shipstationOptionSecret,
			'name' => $shipstationOptionSecret
		)
	);

	// timers fields

	// timers Enabled?
 
	$timersEnabled = 'antique_wp_field_timers';
	register_setting( 'antique_wp_timers', $timersEnabled );
 
	add_settings_field(
		$timersEnabled, 
		__( 'Disable custom sale countdowns?', 'antique_wp' ),
		'antique_wp_checkbox_field_cb',
		'antique_wp_timers',
		'antique_wp_section_timers',
		array(
			'label_for' => $timersEnabled, 
			'name' => $timersEnabled
		)
	);
	$disableSale = 'antique_wp_field_disable_sale';
	register_setting( 'antique_wp_timers', $disableSale );
 
	add_settings_field(
		$disableSale, 
		__( 'Manually disable sale?', 'antique_wp' ),
		'antique_wp_checkbox_field_cb',
		'antique_wp_timers',
		'antique_wp_section_timers',
		array(
			'label_for' => $disableSale, 
			'name' => $disableSale
		)
	);

	$timersStart = 'antique_wp_field_timer_start'; 
	$timersEnd = 'antique_wp_field_timer_end';
	register_setting( 'antique_wp_timers', $timersStart );
	register_setting( 'antique_wp_timers', $timersEnd );

	add_settings_field(
		$timersStart,
		__( 'Sale start', 'antique_wp' ),
		'antique_wp_date_field_cb',
		'antique_wp_timers',
		'antique_wp_section_timers',
		array(
			'label_for' => $timersStart, 
			'name' => $timersStart
		)
	);
	add_settings_field(
		$timersEnd,
		__( 'Sale end', 'antique_wp' ),
		'antique_wp_date_field_cb',
		'antique_wp_timers',
		'antique_wp_section_timers',
		array(
			'label_for' => $timersEnd, 
			'name' => $timersEnd
		)
	);

	// checkout fields
	$disableShippingFields = 'antique_wp_field_disable_shipping_fields';
	register_setting( 'antique_wp_checkout', $disableShippingFields  );
 
	add_settings_field(
		$disableShippingFields , 
		__( 'Remove all shipping fields from checkout form?', 'antique_wp' ),
		'antique_wp_checkbox_field_cb',
		'antique_wp_checkout',
		'antique_wp_section_checkout',
		array(
			'label_for' => $disableShippingFields , 
			'name' => $disableShippingFields 
		)
	);
}

/**
 * Register our antique_wp_settings_init to the admin_init action hook.
 */
add_action( 'admin_init', 'antique_wp_settings_init' );


/**
 * Custom option and settings:
 *  - callback functions
 */


/**
 * Developers section callback function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */

 function antique_wp_section_general_cb( $args ) {
	?>
	<!-- <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Global settings.', 'antique_wp' ); ?></p> -->
	<?php
}

 function antique_wp_section_checkout_cb( $args ) {
	?>
	<!-- <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Global settings.', 'antique_wp' ); ?></p> -->
	<?php
}

function antique_wp_section_arta_cb( $args ) {
	?>
	<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Used for arta shipping.', 'antique_wp' ); ?></p>
	<?php
}


function antique_wp_section_shipstation_cb( $args ) {
	?>
	<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Used for shipStation shipping.', 'antique_wp' ); ?></p>
	<?php
}

function antique_wp_section_timers_cb( $args ) {
	?>
	<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Custom sale timers functionality.', 'antique_wp' ); ?></p>
		<p class="description">
			<?php _e('Main countdown shortcode: [antique_timer_main]') ?> <br>
			<?php _e('Before sale countdown shortcode: [antique_timer_before]') ?> <br>
			<?php _e('After sale countdown shortcode: [antique_timer_after]') ?>
		</p>
		<p class="description">
			<small>
				<?php _e('Note: You can insert shortcode anywhere, but remember that one of parents must be a section html tag. And all shortcodes have to be located in the same page') ?>
			</small>
		</p>
	<?php
}
/**
 * Pill field callbakc function.
 *
 * WordPress has magic interaction with the following keys: label_for, class.
 * - the "label_for" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function antique_wp_text_field_cb( $args ) {
	printf(
		'<input class="curate-input" type="text" id="%s" name="%s" placeholder="Enter your key..." value="%s" />',
		$args[ 'label_for' ],
		$args[ 'name' ],
		esc_attr( get_option( $args[ 'name' ] ) )
	);
}

function antique_wp_checkbox_field_cb( $args ) {
	$html = '<label class="curate-switch" for="' . $args[ 'label_for' ] . '">';
	$html .= '<input type="checkbox" id="' . $args[ 'label_for' ] .'" name="' . $args[ 'name' ] .'" value="1"' . checked( 1, get_option( $args[ 'name' ] ), false ) . '/>';
	$html .= '<div class="curate-slider round"></div></label>';
    echo $html;
	// printf(
	// 	'<input type="checkbox" id="%s" name="%s" value="%s" />',
	// 	$args[ 'label_for' ],
	// 	$args[ 'name' ],
	// 	esc_attr( get_option( $args[ 'name' ] ) )
	// );
}

function antique_wp_date_field_cb( $args ) {
	printf(
		'<input type="datetime-local" id="%s" name="%s" value="%s" />',
		$args[ 'label_for' ],
		$args[ 'name' ],
		esc_attr( get_option( $args[ 'name' ] ) )
	);
}

/**
 * Add the top level menu page.
 */
function antique_wp_options_page() {
	add_menu_page(
		'antique Curate',
		'antique Curate',
		'manage_options',
		'antique_wp',
		'antique_wp_options_page_html'
	);
	 add_submenu_page( 
		'antique_wp', 
		'Arta', 
		'Arta', 
		'manage_options', 
		'arta_settings', 
		'antique_wp_arta_page_html'  
	);
	add_submenu_page( 
		'antique_wp', 
		'Shipstation', 
		'Shipstation', 
		'manage_options', 
		'shipstation_settings', 
		'antique_wp_shipstation_page_html'  
	);
	add_submenu_page( 
		'antique_wp', 
		'Countdowns', 
		'Countdowns', 
		'manage_options', 
		'timers_settings', 
		'antique_wp_timers_page_html'  
	);
	add_submenu_page( 
		'antique_wp', 
		'Checkout', 
		'Checkout', 
		'manage_options', 
		'checkout_settings', 
		'antique_wp_checkout_page_html'  
	);
}


/**
 * Register our antique_wp_options_page to the admin_menu action hook.
 */
add_action( 'admin_menu', 'antique_wp_options_page' );


/**
 * Top level menu callback function
 */
function antique_wp_options_page_html() {
	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// add error/update messages

	// check if the user have submitted the settings
	// WordPress will add the "settings-updated" $_GET parameter to the url
	if ( isset( $_GET['settings-updated'] ) ) {
		// add settings saved message with the class of "updated"
		add_settings_error( 'antique_wp_messages', 'antique_wp_message', __( 'Settings Saved', 'antique_wp' ), 'updated' );
	}

	// show error/update messages
	settings_errors( 'antique_wp_messages' );
	?>
	<div class="wrap curate-wrap">
		<div class="antique-header" style="display: flex; padding-top: 20px; align-items: flex-start">
		<div class="">
			<!-- <h1><?php echo esc_html( get_admin_page_title() ); ?></h1> -->
			<img src="<?php echo plugins_url('assets/img/antique-logo.png',__FILE__ )?>" alt="antique logo" width="200">
			<p><?php _e('The antique marketplace plugin was created to extend the delivery functionality')?></p>
			<p><?php _e('There are available cost calculations by Arta, ShipStation. And you can set custom price too') ?></p>
			<p><?php _e('The plugin contains the functionality of dynamic timers that change sections. Timers based on shortcodes, to start go -> Timers') ?></p>
			<p class="description">
				<small>
					<?php _e('Note: Plugin is compatible with any woocommerce theme') ?>
				</small>
			</p>

			<br>
			<!-- <form action="options.php" method="post">
			<?php
			
			// output security fields for the registered setting "antique_wp"
			settings_fields( 'antique_wp_shipstation' );
			// output setting sections and their fields
			// (sections are registered for "antique_wp", each field is registered to a specific section)
			do_settings_sections( 'antique_wp_shipstation' );
			// output save settings button
			submit_button( 'Save Settings' );
			?>
		</form> -->
		</div>
			<div class="" style="padding-left:45px; position:sticky; top: 20px;">
				<h3>Plugin Navigation menu:</h3>
				<nav>
					<ul>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=antique_wp">
								Home
							</a>
						</li>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=arta_settings">
								Arta options
							</a>
						</li>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=shipstation_settings">
								Shipstation options
							</a>
						</li>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=timers_settings">
								Countdowns options
							</a>
						</li>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=checkout_settings">
								Checkout options
							</a>
						</li>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=wc-settings&tab=shipping&section=antique_wp">
								Shipping method options
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
	<?php
}

function antique_wp_arta_page_html() {
	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// add error/update messages

	// check if the user have submitted the settings
	// WordPress will add the "settings-updated" $_GET parameter to the url
	if ( isset( $_GET['settings-updated'] ) ) {
		// add settings saved message with the class of "updated"
		add_settings_error( 'antique_wp_messages', 'antique_wp_message', __( 'Settings Saved', 'antique_wp' ), 'updated' );
	}

	// show error/update messages
	settings_errors( 'antique_wp_messages' );
	?>
<div class="wrap curate-wrap">
		<div class="antique-header" style="display: flex; padding-top: 20px; align-items: flex-start">
		<div class="">
			<!-- <h1><?php echo esc_html( get_admin_page_title() ); ?></h1> -->
			<img src="<?php echo plugins_url('assets/img/antique-logo.png',__FILE__ )?>" alt="antique logo" width="200">
			<p><?php _e('The antique marketplace plugin was created to extend the delivery functionality')?></p>
			<p><?php _e('There are available cost calculations by Arta, ShipStation. And you can set custom price too') ?></p>
			<p><?php _e('The plugin contains the functionality of dynamic timers that change sections. Timers based on shortcodes, to start go -> Timers') ?></p>
			<p class="description">
				<small>
					<?php _e('Note: Plugin is compatible with any woocommerce theme') ?>
				</small>
			</p>

			<br>
			<form action="options.php" method="post">
			<?php
			
			// output security fields for the registered setting "antique_wp"
			settings_fields( 'antique_wp' );
			// output setting sections and their fields
			// (sections are registered for "antique_wp", each field is registered to a specific section)
			do_settings_sections( 'antique_wp' );
			// output save settings button
			submit_button( 'Save Settings' );
			?>
		</form>
		</div>
			<div class="" style="padding-left:45px; position:sticky; top: 20px;">
				<h3>Plugin Navigation menu:</h3>
				<nav>
						<ul>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=antique_wp">
								Home
							</a>
						</li>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=arta_settings">
								Arta options
							</a>
						</li>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=shipstation_settings">
								Shipstation options
							</a>
						</li>
							<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=timers_settings">
								Countdowns options
							</a>
						</li>
								<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=checkout_settings">
								Checkout options
							</a>
						</li>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=wc-settings&tab=shipping&section=antique_wp">
								Shipping method options
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
	<?php
}

function antique_wp_shipstation_page_html() {
	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// add error/update messages

	// check if the user have submitted the settings
	// WordPress will add the "settings-updated" $_GET parameter to the url
	if ( isset( $_GET['settings-updated'] ) ) {
		// add settings saved message with the class of "updated"
		add_settings_error( 'antique_wp_shipstation_messages', 'antique_wp_shipstation_message', __( 'Settings Saved', 'antique_wp' ), 'updated' );
	}

	// show error/update messages
	settings_errors( 'antique_wp_shipstation_messages' );
	?>
	<div class="wrap curate-wrap">
		<div class="antique-header" style="display: flex; padding-top: 20px; align-items: flex-start">
		<div class="">
			<!-- <h1><?php echo esc_html( get_admin_page_title() ); ?></h1> -->
			<img src="<?php echo plugins_url('assets/img/antique-logo.png',__FILE__ )?>" alt="antique logo" width="200">
			<p><?php _e('The antique marketplace plugin was created to extend the delivery functionality')?></p>
			<p><?php _e('There are available cost calculations by Arta, ShipStation. And you can set custom price too') ?></p>
			<p><?php _e('The plugin contains the functionality of dynamic timers that change sections. Timers based on shortcodes, to start go -> Timers') ?></p>
			<p class="description">
				<small>
					<?php _e('Note: Plugin is compatible with any woocommerce theme') ?>
				</small>
			</p>

			<br>
			<form action="options.php" method="post">
			<?php
			
			// output security fields for the registered setting "antique_wp"
			settings_fields( 'antique_wp_shipstation' );
			// output setting sections and their fields
			// (sections are registered for "antique_wp", each field is registered to a specific section)
			do_settings_sections( 'antique_wp_shipstation' );
			// output save settings button
			submit_button( 'Save Settings' );
			?>
		</form>
		</div>
			<div class="" style="padding-left:45px; position:sticky; top: 20px;">
				<h3>Plugin Navigation menu:</h3>
				<nav>
						<ul>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=antique_wp">
								Home
							</a>
						</li>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=arta_settings">
								Arta options
							</a>
						</li>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=shipstation_settings">
								Shipstation options
							</a>
						</li>
							<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=timers_settings">
								Countdowns options
							</a>
						</li>
								<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=checkout_settings">
								Checkout options
							</a>
						</li>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=wc-settings&tab=shipping&section=antique_wp">
								Shipping method options
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
	<?php
}
function antique_wp_timers_page_html() {
	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// add error/update messages

	// check if the user have submitted the settings
	// WordPress will add the "settings-updated" $_GET parameter to the url
	if ( isset( $_GET['settings-updated'] ) ) {
		// add settings saved message with the class of "updated"
		add_settings_error( 'antique_wp_shipstation_messages', 'antique_wp_shipstation_message', __( 'Settings Saved', 'antique_wp' ), 'updated' );
	}

	// show error/update messages
	settings_errors( 'antique_wp_shipstation_messages' );
	?>
	<div class="wrap curate-wrap">
		<div class="antique-header" style="display: flex; padding-top: 20px; align-items: flex-start">
		<div class="">
			<!-- <h1><?php echo esc_html( get_admin_page_title() ); ?></h1> -->
			<img src="<?php echo plugins_url('assets/img/antique-logo.png',__FILE__ )?>" alt="antique logo" width="200">
			<p><?php _e('The antique marketplace plugin was created to extend the delivery functionality')?></p>
			<p><?php _e('There are available cost calculations by Arta, ShipStation. And you can set custom price too') ?></p>
			<p><?php _e('The plugin contains the functionality of dynamic timers that change sections. Timers based on shortcodes, to start go -> Timers') ?></p>
			<p class="description">
				<small>
					<?php _e('Note: Plugin is compatible with any woocommerce theme') ?>
				</small>
			</p>

			<br>
			<form action="options.php" method="post">
			<?php
			
			// output security fields for the registered setting "antique_wp"
			settings_fields( 'antique_wp_timers' );
			// output setting sections and their fields
			// (sections are registered for "antique_wp", each field is registered to a specific section)
			do_settings_sections( 'antique_wp_timers' );
			// output save settings button
			submit_button( 'Save Settings' );
			?>
		</form>
		</div>
			<div class="" style="padding-left:45px; position:sticky; top: 20px;">
				<h3>Plugin Navigation menu:</h3>
				<nav>
						<ul>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=antique_wp">
								Home
							</a>
						</li>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=arta_settings">
								Arta options
							</a>
						</li>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=shipstation_settings">
								Shipstation options
							</a>
						</li>
							<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=timers_settings">
								Countdowns options
							</a>
						</li>
								<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=checkout_settings">
								Checkout options
							</a>
						</li>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=wc-settings&tab=shipping&section=antique_wp">
								Shipping method options
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
	<?php
}

function antique_wp_checkout_page_html() {
	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// add error/update messages

	// check if the user have submitted the settings
	// WordPress will add the "settings-updated" $_GET parameter to the url
	if ( isset( $_GET['settings-updated'] ) ) {
		// add settings saved message with the class of "updated"
		add_settings_error( 'antique_wp_checkout_messages', 'antique_wp_checkout_message', __( 'Settings Saved', 'antique_wp' ), 'updated' );
	}

	// show error/update messages
	settings_errors( 'antique_wp_checkout_messages' );
	?>
	<div class="wrap curate-wrap">
		<div class="antique-header" style="display: flex; padding-top: 20px; align-items: flex-start">
		<div class="">
			<!-- <h1><?php echo esc_html( get_admin_page_title() ); ?></h1> -->
			<img src="<?php echo plugins_url('assets/img/antique-logo.png',__FILE__ )?>" alt="antique logo" width="200">
			<p><?php _e('The antique marketplace plugin was created to extend the delivery functionality')?></p>
			<p><?php _e('There are available cost calculations by Arta, ShipStation. And you can set custom price too') ?></p>
			<p><?php _e('The plugin contains the functionality of dynamic timers that change sections. Timers based on shortcodes, to start go -> Timers') ?></p>
			<p class="description">
				<small>
					<?php _e('Note: Plugin is compatible with any woocommerce theme') ?>
				</small>
			</p>

			<br>
			<form action="options.php" method="post">
			<?php
			
			// output security fields for the registered setting "antique_wp"
			settings_fields( 'antique_wp_checkout' );
			// output setting sections and their fields
			// (sections are registered for "antique_wp", each field is registered to a specific section)
			do_settings_sections( 'antique_wp_checkout' );
			// output save settings button
			submit_button( 'Save Settings' );
			?>
		</form>
		</div>
			<div class="" style="padding-left:45px; position:sticky; top: 20px;">
				<h3>Plugin Navigation menu:</h3>
				<nav>
						<ul>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=antique_wp">
								Home
							</a>
						</li>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=arta_settings">
								Arta options
							</a>
						</li>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=shipstation_settings">
								Shipstation options
							</a>
						</li>
							<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=timers_settings">
								Countdowns options
							</a>
						</li>
								<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=checkout_settings">
								Checkout options
							</a>
						</li>
						<li>
							<a href="<?php echo get_home_url()?>/wp-admin/admin.php?page=wc-settings&tab=shipping&section=antique_wp">
								Shipping method options
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
	<?php
}