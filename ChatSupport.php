<?php

/**
 * Plugin Name:       ChatSupport
 * Plugin URI:        https://graficarulez.forumfree.it/
 * Description:       ChatSupport let you add a new and Fluent WhatsApp Support Button to your WordPress Website.
 * Version:           1.0.5
 * Requires at least: 6.1
 * Requires PHP:      7.2
 * Author:            Giuseppe Antonino Cotroneo | Cotrox
 * Author URI:        https://www.behance.net/Cotrox
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       Chat Support
 * Domain Path:       /languages
 */

 /* WhatsApp Support Plugin Settings Page */

 class WhatsAppSupport {
	private $chat_support_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'chat_support_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'chat_support_page_init' ) );
	}

	public function chat_support_add_plugin_page() {
		add_menu_page(
			'WhatsApp Support', // page_title
			'WA Support', // menu_title
			'manage_options', // capability
			'whatsapp-support', // menu_slug
			array( $this, 'chat_support_create_admin_page' ), // function
			'dashicons-format-status', // icon_url
			2 // position
		);
	}

	public function chat_support_create_admin_page() {
		$this->chat_support_options = get_option( 'chat_support_option_name' ); ?>

		<div class="wrap">
			<h2>WhatsApp Support</h2>
			<p>Settings page for WhatsApp Support Plugin.<br><a href="https://graficarulez.forumfree.it/?f=10805258">Contact Us</a> for <b>answer</b> and <b>support</b>.</p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'chat_support_option_group' );
					do_settings_sections( 'whatsapp-support-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function chat_support_page_init() {
		register_setting(
			'chat_support_option_group', // option_group
			'chat_support_option_name', // option_name
			array( $this, 'chat_support_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'chat_support_setting_section', // id
			'Settings', // title
			array( $this, 'chat_support_section_info' ), // callback
			'whatsapp-support-admin' // page
		);

		add_settings_field(
			'phone_number_0', // id
			'Phone Number', // title
			array( $this, 'phone_number_0_callback' ), // callback
			'whatsapp-support-admin', // page
			'chat_support_setting_section' // section
		);

		add_settings_field(
			'icon_color_0', // id
			'Icon Color', // title
			array( $this, 'icon_color_0_callback' ), // callback
			'whatsapp-support-admin', // page
			'chat_support_setting_section' // section
		);
	}

	public function chat_support_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['phone_number_0'] ) ) {
			$sanitary_values['phone_number_0'] = sanitize_text_field( $input['phone_number_0'] );
		}

		if ( isset( $input['icon_color_0'] ) ) {
			$sanitary_values['icon_color_0'] = sanitize_text_field( $input['icon_color_0'] );
		}

		return $sanitary_values;
	}

	public function chat_support_section_info() {
		
	}

	public function phone_number_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="chat_support_option_name[phone_number_0]" id="phone_number_0" value="%s">',
			isset( $this->chat_support_options['phone_number_0'] ) ? esc_attr( $this->chat_support_options['phone_number_0']) : ''
		);
	}

	public function icon_color_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="chat_support_option_name[icon_color_0]" id="icon_color_0" value="%s">',
			isset( $this->chat_support_options['icon_color_0'] ) ? esc_attr( $this->chat_support_options['icon_color_0']) : ''
		);
	}

}
if ( is_admin() )
	$chat_support = new WhatsAppSupport();

/*
 * Retrieve this value with:
 * $chat_support_options = get_option( 'chat_support_option_name' ); // Array of All Options
 * $phone_number_0 = $chat_support_options['phone_number_0']; // Phone Number
 * $icon_color_0 = $chat_support_options['icon_color_0']; // Icon Color
 */

 add_action( 'wp_footer', 'chat_support_add' );

function chat_support_add(){
  ?>

<div id="whatsapp-business">
	<a href="https://wa.me/<?php 
		echo esc_html(get_option( 'chat_support_option_name' )['phone_number_0']);
	?>" class="whatsapp-business-icon"></a>
</div>

<style>
  #whatsapp-business {
	display: flex;
    justify-content: center;
    position: fixed;
    background: <?php 
		echo get_option( 'chat_support_option_name' )['icon_color_0'] == '' ? '#25d366' : esc_html(get_option( 'chat_support_option_name' )['icon_color_0']);
	?>;
    width: 50px;
    height: 50px;
    bottom: 30px;
    right: 30px;
    border-radius: 100%;
    box-shadow: 0 0 5px #00000040;
    opacity: 0;
    transition: .2s ease-out;
    animation: fadeIn .2s linear.2s normal forwards;
    cursor: pointer;
    z-index: 100;
}

#whatsapp-business:hover {
    transform: scale(1.2);
}

.whatsapp-business-icon {
	align-self: center;
}

.whatsapp-business-icon:after {
    content: '\f232';
    font-family: 'FontAwesome';
    font-weight: bold;
	display: block;
    color: #fff;
    font-size: 24pt;
    cursor: pointer;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}
</style>
  
  <?php
}