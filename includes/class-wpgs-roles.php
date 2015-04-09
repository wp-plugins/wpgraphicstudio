<?php
/**
 * Roles and Capabilities
 *
 * @package     WPGS
 * @subpackage  Classes/Roles
 * @copyright   Copyright (c) 2014, John Seroka
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.0
*/

/**
 * WPGS_Roles Class
 *
 * This class handles the role creation and assignment of capabilities for those roles.
 *
 * These roles let us have Site Managers, Site Workers, etc, each of whom can do
 * certain things within the WPGS Site
 *
 * @since 3.0
 */
class WPGS_Roles {
	/**
	 * Get things going
	 *
	 * @access public
	 * @since 3.0
	 * @see WPGS_Roles::add_roles()
	 * @see WPGS_Roles::add_caps()
	 * @return void
	 */
	public function __construct() {
		$this->add_roles();
		$this->add_caps();
	}

	/**
	 * Add new site roles with default WP caps
	 *
	 * @access public
	 * @since 3.0
	 * @return void
	 */
	public function add_roles() {
		add_role( 'site_manager', __( 'Site Manager', 'wpgs' ), array(
			'read'                   => true,
			'edit_posts'             => true,
			'delete_posts'           => true,
			'unfiltered_html'        => true,
			'upload_files'           => true,
			'export'                 => true,
			'import'                 => true,
			'delete_others_pages'    => true,
			'delete_others_posts'    => true,
			'delete_pages'           => true,
			'delete_private_pages'   => true,
			'delete_private_posts'   => true,
			'delete_published_pages' => true,
			'delete_published_posts' => true,
			'edit_others_pages'      => true,
			'edit_others_posts'      => true,
			'edit_pages'             => true,
			'edit_private_pages'     => true,
			'edit_private_posts'     => true,
			'edit_published_pages'   => true,
			'edit_published_posts'   => true,
			'manage_categories'      => true,
			'manage_links'           => true,
			'moderate_comments'      => true,
			'publish_pages'          => true,
			'publish_posts'          => true,
			'read_private_pages'     => true,
			'read_private_posts'     => true
		) );

		add_role( 'site_worker', __( 'Site Worker', 'wpgs' ), array(
			'read'                   => true,
			'edit_posts'             => false,
			'upload_files'           => true,
			'delete_posts'           => false
		) );

		add_role( 'site_vendor', __( 'Site Vendor', 'wpgs' ), array(
			'read'                   => true,
			'edit_posts'             => false,
			'upload_files'           => true,
			'delete_posts'           => false
		) );
	}

	/**
	 * Add new site-specific capabilities
	 *
	 * @access public
	 * @since  3.0
	 * @global obj $wp_roles
	 * @return void
	 */
	public function add_caps() {
		global $wp_roles;

		if ( class_exists('WP_Roles') )
			if ( ! isset( $wp_roles ) )
				$wp_roles = new WP_Roles();

		if ( is_object( $wp_roles ) ) {
			$wp_roles->add_cap( 'site_manager', 'view_site_reports' );
			$wp_roles->add_cap( 'site_manager', 'view_site_sensitive_data' );
			$wp_roles->add_cap( 'site_manager', 'export_site_reports' );
			$wp_roles->add_cap( 'site_manager', 'manage_site_settings' );

			$wp_roles->add_cap( 'administrator', 'view_site_reports' );
			$wp_roles->add_cap( 'administrator', 'view_site_sensitive_data' );
			$wp_roles->add_cap( 'administrator', 'export_site_reports' );
			$wp_roles->add_cap( 'administrator', 'manage_graphic_settings' );

			// Add the main post type capabilities
			$capabilities = $this->get_core_caps();
			foreach ( $capabilities as $cap_group ) {
				foreach ( $cap_group as $cap ) {
					$wp_roles->add_cap( 'site_manager', $cap );
					$wp_roles->add_cap( 'administrator', $cap );
					$wp_roles->add_cap( 'site_worker', $cap );
				}
			}

			$wp_roles->add_cap( 'site_vendor', 'edit_graphic' );
			$wp_roles->add_cap( 'site_vendor', 'edit_graphics' );
			$wp_roles->add_cap( 'site_vendor', 'delete_graphic' );
			$wp_roles->add_cap( 'site_vendor', 'delete_graphics' );
			$wp_roles->add_cap( 'site_vendor', 'publish_graphics' );
			$wp_roles->add_cap( 'site_vendor', 'edit_published_graphics' );
			$wp_roles->add_cap( 'site_vendor', 'upload_files' );
			$wp_roles->add_cap( 'site_vendor', 'assign_graphic_terms' );
		}
	}

	/**
	 * Remove core post type capabilities (called on uninstall)
	 *
	 * @access public
	 * @since 3.0
	 * @return void
	 */
	public function remove_caps() {
		if ( class_exists( 'WP_Roles' ) )
			if ( ! isset( $wp_roles ) )
				$wp_roles = new WP_Roles();

		if ( is_object( $wp_roles ) ) {
			/** Site Manager Capabilities */
			$wp_roles->remove_cap( 'site_manager', 'view_site_reports' );
			$wp_roles->remove_cap( 'site_manager', 'view_site_sensitive_data' );
			$wp_roles->remove_cap( 'site_manager', 'export_site_reports' );
			$wp_roles->remove_cap( 'site_manager', 'manage_site_settings' );

			/** Site Administrator Capabilities */
			$wp_roles->remove_cap( 'administrator', 'view_shop_reports' );
			$wp_roles->remove_cap( 'administrator', 'view_shop_sensitive_data' );
			$wp_roles->remove_cap( 'administrator', 'export_shop_reports' );
			$wp_roles->remove_cap( 'administrator', 'manage_graphic_settings' );

			/** Remove the Main Post Type Capabilities */
			$capabilities = $this->get_core_caps();

			foreach ( $capabilities as $cap_group ) {
				foreach ( $cap_group as $cap ) {
					$wp_roles->remove_cap( 'site_manager', $cap );
					$wp_roles->remove_cap( 'administrator', $cap );
					$wp_roles->remove_cap( 'site_worker', $cap );
				}
			}

			/** Shop Vendor Capabilities */
			$wp_roles->remove_cap( 'site_vendor', 'edit_graphic' );
			$wp_roles->remove_cap( 'site_vendor', 'edit_graphics' );
			$wp_roles->remove_cap( 'site_vendor', 'delete_product' );
			$wp_roles->remove_cap( 'site_vendor', 'delete_graphics' );
			$wp_roles->remove_cap( 'site_vendor', 'publish_graphics' );
			$wp_roles->remove_cap( 'site_vendor', 'edit_published_graphics' );
			$wp_roles->remove_cap( 'site_vendor', 'upload_files' );
		}
	}
}