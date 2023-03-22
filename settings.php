<?php

class OpenaiAyarlar_Settings_Page {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wph_create_settings' ) );
		add_action( 'admin_init', array( $this, 'wph_setup_sections' ) );
		add_action( 'admin_init', array( $this, 'wph_setup_fields' ) );
	}

	public function wph_create_settings() {
		$page_title = 'Openai';
		$menu_title = 'Openai Ayarlar';
		$capability = 'manage_options';
		$slug = 'OpenaiAyarlar';
		$callback = array($this, 'wph_settings_content');
                $icon = 'dashicons-buddicons-pm';
		$position = 25;
		add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
		
	}
    
	public function wph_settings_content() { ?>
		<div class="wrap">
			<h1>Openai Ayarlar</h1>
			<?php settings_errors(); ?>
			<form method="POST" action="options.php">
				<?php
					settings_fields( 'OpenaiAyarlar' );
					do_settings_sections( 'OpenaiAyarlar' );
					submit_button();
				?>
			</form>
		</div> <?php
	}

	public function wph_setup_sections() {
		add_settings_section( 'OpenaiAyarlar_section', '', array(), 'OpenaiAyarlar' );
	}

	public function wph_setup_fields() {
		$fields = array(
                    array(
                        'section' => 'OpenaiAyarlar_section',
                        'label' => 'Openai Key',
                        'id' => 'openai_key',
                        'type' => 'text',
                    ),
        
                    array(
                        'section' => 'OpenaiAyarlar_section',
                        'label' => 'Openai Limit',
                        'id' => 'openai_limit',
                        'type' => 'number',
                    )
		);
		foreach( $fields as $field ){
			add_settings_field( $field['id'], $field['label'], array( $this, 'wph_field_callback' ), 'OpenaiAyarlar', $field['section'], $field );
			register_setting( 'OpenaiAyarlar', $field['id'] );
		}
	}
	public function wph_field_callback( $field ) {
		$value = get_option( $field['id'] );
		$placeholder = '';
		if ( isset($field['placeholder']) ) {
			$placeholder = $field['placeholder'];
		}
		switch ( $field['type'] ) {
            
            
			default:
				printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" style="width:450px;" />',
					$field['id'],
					$field['type'],
					$placeholder,
					$value
				);
		}
		if( isset($field['desc']) ) {
			if( $desc = $field['desc'] ) {
				printf( '<p class="description">%s </p>', $desc );
			}
		}
	}
    
}
new OpenaiAyarlar_Settings_Page();
                