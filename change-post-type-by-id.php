<?php
/*
Plugin Name: Change Post Type by ID
Description: Changes the post type of a specific post ID.
Version: 1.1
Author: Emicha
*/

// Function to change the post type
function change_post_type_by_id( $post_id, $new_post_type ) {
	// Get the post object
	$post = get_post( $post_id );

	// If the post exists and the new post type is valid
	if ( $post && post_type_exists( $new_post_type ) ) {
		// Update the post type
		set_post_type( $post_id, $new_post_type );
	}
}

// Add settings page
function change_post_type_add_settings_page() {
	add_options_page(
		'Change Post Type by ID',
		'Change Post Type by ID',
		'manage_options',
		'change-post-type-by-id',
		'change_post_type_settings_page'
	);
}

add_action( 'admin_menu', 'change_post_type_add_settings_page' );

// Render settings page
function change_post_type_settings_page() {
	?>
    <div class="wrap">
        <h1>Change Post Type by ID</h1>
        <form method="post" action="options.php">
			<?php
			settings_fields( 'change_post_type_settings' );
			do_settings_sections( 'change-post-type-by-id' );
			submit_button();
			?>
        </form>
    </div>
	<?php
}

// Register settings
function change_post_type_register_settings() {
	register_setting( 'change_post_type_settings', 'change_post_type_post_id' );
	register_setting( 'change_post_type_settings',
		'change_post_type_new_post_type' );

	add_settings_section(
		'change_post_type_settings_section',
		'',
		null,
		'change-post-type-by-id'
	);

	add_settings_field(
		'change_post_type_post_id',
		'Post ID',
		'change_post_type_post_id_render',
		'change-post-type-by-id',
		'change_post_type_settings_section'
	);

	add_settings_field(
		'change_post_type_new_post_type',
		'New Post Type Slug',
		'change_post_type_new_post_type_render',
		'change-post-type-by-id',
		'change_post_type_settings_section'
	);
}

add_action( 'admin_init', 'change_post_type_register_settings' );

// Render Post ID field
function change_post_type_post_id_render() {
	$post_id = get_option( 'change_post_type_post_id' );
	?>
    <input type="text" name="change_post_type_post_id"
           value="<?php echo esc_attr( $post_id ); ?>">
	<?php
}

// Render New Post Type field
function change_post_type_new_post_type_render() {
	$new_post_type = get_option( 'change_post_type_new_post_type' );
	?>
    <input type="text" name="change_post_type_new_post_type"
           value="<?php echo esc_attr( $new_post_type ); ?>">
	<?php
}

// Hook into WordPress admin_init to change the post type when in the admin area
add_action( 'admin_init', 'init_change_post_type' );

function init_change_post_type() {
	// Get the post ID and the new post type from options
	$post_id       = get_option( 'change_post_type_post_id' );
	$new_post_type = get_option( 'change_post_type_new_post_type' );

	// Change the post type if both fields are filled
	if ( $post_id && $new_post_type ) {
		change_post_type_by_id( $post_id, $new_post_type );
	}
}
