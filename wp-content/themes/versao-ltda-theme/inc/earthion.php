<?php
/**
 * Earthion controller landing page.
 *
 * @package Versao_Ltda_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Register the public /earthion route without creating a WordPress page. */
function versao_ltda_register_earthion_route() {
	add_rewrite_rule( '^earthion/?$', 'index.php?versao_earthion=1', 'top' );

	if ( '2' !== get_option( 'versao_ltda_earthion_rewrite_version' ) ) {
		flush_rewrite_rules( false );
		update_option( 'versao_ltda_earthion_rewrite_version', '2' );
	}
}
add_action( 'init', 'versao_ltda_register_earthion_route' );

function versao_ltda_add_earthion_query_var( $vars ) {
	$vars[] = 'versao_earthion';
	return $vars;
}
add_filter( 'query_vars', 'versao_ltda_add_earthion_query_var' );

/** Keep WordPress canonical handling from resolving /earthion to the product slug. */
function versao_ltda_disable_earthion_canonical_redirect( $redirect_url ) {
	return '1' === get_query_var( 'versao_earthion' ) ? false : $redirect_url;
}
add_filter( 'redirect_canonical', 'versao_ltda_disable_earthion_canonical_redirect' );

function versao_ltda_use_earthion_template( $template ) {
	if ( '1' !== get_query_var( 'versao_earthion' ) ) {
		return $template;
	}

	status_header( 200 );
	return get_template_directory() . '/page-earthion.php';
}
add_filter( 'template_include', 'versao_ltda_use_earthion_template', 99 );

/** Find the existing WooCommerce Earthion product when available. */
function versao_ltda_get_earthion_product_id() {
	$product = get_page_by_path( 'earthion', OBJECT, 'product' );
	return $product ? (int) $product->ID : 0;
}

/** Handle waitlist registrations from the Earthion landing page. */
function versao_ltda_handle_earthion_waitlist() {
	$redirect_url = home_url( '/earthion/' );

	if (
		! isset( $_POST['versao_ltda_earthion_nonce'] ) ||
		! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['versao_ltda_earthion_nonce'] ) ), 'versao_ltda_earthion_waitlist' )
	) {
		wp_safe_redirect( add_query_arg( 'waitlist_status', 'security-error', $redirect_url ) . '#earthion-waitlist' );
		exit;
	}

	// A hidden field keeps automated submissions out without requiring a third-party CAPTCHA key.
	if ( ! empty( $_POST['website'] ) ) {
		wp_safe_redirect( add_query_arg( 'waitlist_status', 'success', $redirect_url ) . '#earthion-waitlist' );
		exit;
	}

	$name    = isset( $_POST['customer_name'] ) ? sanitize_text_field( wp_unslash( $_POST['customer_name'] ) ) : '';
	$country = isset( $_POST['customer_country'] ) ? sanitize_text_field( wp_unslash( $_POST['customer_country'] ) ) : '';
	$email   = isset( $_POST['customer_email'] ) ? sanitize_email( wp_unslash( $_POST['customer_email'] ) ) : '';

	if ( '' === $name || '' === $country || ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( 'waitlist_status', 'validation-error', $redirect_url ) . '#earthion-waitlist' );
		exit;
	}

	$product_id  = versao_ltda_get_earthion_product_id();
	$product_url = $product_id ? get_permalink( $product_id ) : home_url( '/product/earthion/' );
	$stored      = versao_ltda_insert_product_notify_entry(
		$product_id,
		'Controle Earthion',
		$product_url,
		$email,
		'earthion',
		$name,
		$country,
		'controle earthion'
	);

	wp_safe_redirect( add_query_arg( 'waitlist_status', $stored ? 'success' : 'send-error', $redirect_url ) . '#earthion-waitlist' );
	exit;
}
add_action( 'admin_post_nopriv_versao_ltda_earthion_waitlist', 'versao_ltda_handle_earthion_waitlist' );
add_action( 'admin_post_versao_ltda_earthion_waitlist', 'versao_ltda_handle_earthion_waitlist' );
