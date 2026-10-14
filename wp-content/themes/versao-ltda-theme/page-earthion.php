<?php
/** Earthion arcade controller waitlist landing page. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$status = isset( $_GET['waitlist_status'] ) ? sanitize_key( wp_unslash( $_GET['waitlist_status'] ) ) : '';
$assets = get_template_directory_uri() . '/assets/images/earthion/';
$countries = array( 'Argentina', 'Bolivia', 'Brazil', 'Canada', 'Chile', 'Colombia', 'Ecuador', 'Mexico', 'Paraguay', 'Peru', 'Portugal', 'United Kingdom', 'United States', 'Uruguay', 'Other' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo esc_html( 'Earthion YK-IIA Arcade Controller | Versão LTDA' ); ?></title>
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'earthion-landing-page' ); ?>>
<?php wp_body_open(); ?>
<main class="earthion-landing">
	<section class="earthion-hero" aria-labelledby="earthion-title">
		<div class="earthion-brand">
			<img class="earthion-brand__logo" src="<?php echo esc_url( $assets . 'earthion-logo.webp' ); ?>" alt="Earthion">
			<span class="earthion-brand__divider" aria-hidden="true"></span>
			<h1 id="earthion-title"><img src="<?php echo esc_url( $assets . 'title.webp' ); ?>" alt="YK-IIA Arcade Controller"></h1>
		</div>
		<img class="earthion-controller" src="<?php echo esc_url( $assets . 'controller.webp' ); ?>" alt="Earthion YK-IIA arcade controller">

		<div class="earthion-waitlist" id="earthion-waitlist">
			<h2>Join the Waitlist</h2>
			<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<?php wp_nonce_field( 'versao_ltda_earthion_waitlist', 'versao_ltda_earthion_nonce' ); ?>
				<input type="hidden" name="action" value="versao_ltda_earthion_waitlist">
				<label class="screen-reader-text" for="earthion-name">Your Name</label>
				<input id="earthion-name" name="customer_name" type="text" autocomplete="name" placeholder="Your Name" required>
				<label class="screen-reader-text" for="earthion-country">Country</label>
				<select id="earthion-country" name="customer_country" autocomplete="country-name" required>
					<option value="" selected disabled>Select Country</option>
					<?php foreach ( $countries as $country ) : ?>
						<option value="<?php echo esc_attr( $country ); ?>"><?php echo esc_html( $country ); ?></option>
					<?php endforeach; ?>
				</select>
				<label class="screen-reader-text" for="earthion-email">Your Email</label>
				<input id="earthion-email" name="customer_email" type="email" autocomplete="email" placeholder="Your Email" required>
				<label class="earthion-honeypot" aria-hidden="true">Website <input name="website" type="text" tabindex="-1" autocomplete="off"></label>
				<button type="submit">Subscribe</button>
			</form>
			<?php if ( 'success' === $status ) : ?>
				<p class="earthion-waitlist__message is-success" role="status">You're on the list! We'll keep you posted.</p>
			<?php elseif ( 'validation-error' === $status ) : ?>
				<p class="earthion-waitlist__message is-error" role="alert">Please enter your name, country, and a valid email address.</p>
			<?php elseif ( $status ) : ?>
				<p class="earthion-waitlist__message is-error" role="alert">We couldn't complete your registration. Please try again.</p>
			<?php endif; ?>
		</div>

		<div class="earthion-partners" aria-label="Parceiros">
			<img src="<?php echo esc_url( $assets . 'games-care.webp' ); ?>" alt="Games Care">
			<img src="<?php echo esc_url( $assets . 'versao-ltda.webp' ); ?>" alt="Versão LTDA">
			<img class="earthion-partners__ancient" src="<?php echo esc_url( $assets . 'ancient.webp' ); ?>" alt="Ancient">
		</div>
	</section>
</main>
<?php wp_footer(); ?>
</body>
</html>
