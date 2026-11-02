<?php
/**
 * New Year landing page; only the /novyi-god/ page uses this template.
 * The stored page content, shared templates and CF7 settings are not modified.
 */
defined( 'ABSPATH' ) || exit;

add_filter( 'template_include', 'asny_page_template', 100 );
function asny_page_template( $template ) {
	if ( ! is_page( 'novyi-god' ) || is_feed() || is_embed() ) {
		return $template;
	}
	$landing = get_theme_file_path( 'templates/page-novyi-god.php' );
	return is_readable( $landing ) ? $landing : $template;
}

add_action( 'wp_enqueue_scripts', 'asny_enqueue_assets' );
function asny_enqueue_assets() {
	if ( ! is_page( 'novyi-god' ) ) {
		return;
	}
	foreach ( array( 'css', 'js' ) as $type ) {
		$file = 'assets/' . $type . '/new-year.' . $type;
		$version = (string) filemtime( get_theme_file_path( $file ) );
		if ( 'css' === $type ) {
			wp_enqueue_style( 'asny-landing', get_theme_file_uri( $file ), array( 'twentytwentyfive-style' ), $version );
		} else {
			$dependencies = wp_script_is( 'contact-form-7', 'registered' ) ? array( 'contact-form-7' ) : array();
			wp_enqueue_script( 'asny-landing', get_theme_file_uri( $file ), $dependencies, $version, true );
		}
	}
}

/** Single source of catalog copy and service identifiers used by the quiz. */
function asny_products() {
	return array(
		'mugs' => array(
			'name' => 'Кружки с вашим дизайном', 'short' => 'Кружки', 'image' => 'mug.jpg',
			'caption' => 'Любимые моменты — каждый день',
			'description' => 'С фотографией, именем, поздравлением или забавной надписью. Небольшой, но приятный подарок на каждый день.',
			'price_note' => 'Изделие, вид печати и количество',
		),
		'clothing' => array(
			'name' => 'Одежда с принтом', 'short' => 'Одежда с принтом', 'image' => 'sweatshirt-work.jpg',
			'caption' => 'Тёплый подарок с характером',
			'description' => 'Футболки, толстовки, свитшоты, поло, фартуки, панамы и другие изделия с индивидуальным дизайном.',
			'price_note' => 'Модель, размер и способ нанесения',
		),
		'calendars' => array(
			'name' => 'Календари с фотографиями на 2027 год', 'short' => 'Календари на 2027 год', 'image' => 'calendar.jpg',
			'caption' => 'Целый год ваших воспоминаний',
			'description' => 'Семейные воспоминания, любимые снимки или корпоративные фотографии — в календаре, созданном специально для вас.',
			'price_note' => 'Формат, число страниц и тираж',
		),
		'canvas' => array(
			'name' => 'Фотографии на холсте', 'short' => 'Фотографии на холсте', 'image' => 'canvas.jpg',
			'caption' => 'Воспоминание, которое всегда рядом',
			'description' => 'Превратим любимый снимок в украшение интерьера и памятный подарок для близких.',
			'price_note' => 'Размер холста и подготовка фотографии',
		),
		'portraits' => array(
			'name' => 'Портреты на холсте по фотографии', 'short' => 'Портреты на холсте', 'image' => 'portrait.jpg',
			'caption' => 'Личная история в красивом оформлении',
			'description' => 'Необычный персональный подарок, который приятно вручить и хочется сохранить.',
			'price_note' => 'Стиль портрета, размер и работа дизайнера',
		),
	);
}

function asny_image( $file, $alt, $eager = false ) {
	return sprintf(
		'<img src="%s" alt="%s" loading="%s" decoding="async"%s>',
		esc_url( get_theme_file_uri( 'assets/images/new-year/' . $file ) ),
		esc_attr( $alt ),
		$eager ? 'eager' : 'lazy',
		$eager ? ' fetchpriority="high"' : ''
	);
}

/** Use the site's existing CF7 delivery and spam protection, including its recipient. */
function asny_contact_form() {
	if ( ! shortcode_exists( 'contact-form-7' ) ) {
		return '';
	}
	$form_id = absint( apply_filters( 'asny_contact_form_id', 370 ) );
	return do_shortcode( '[contact-form-7 id="' . $form_id . '"]' );
}
