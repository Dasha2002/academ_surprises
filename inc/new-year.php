<?php
/**
 * New Year landing page; only the /novyi-god/ page uses this template.
 * The stored page content, shared templates and CF7 settings are not modified.
 */
defined( 'ABSPATH' ) || exit;

/** Variant 1 stays the default until variant 2 is explicitly enabled. */
function asny_variant() {
	return defined( 'ASNY_NEW_YEAR_VARIANT' ) && 2 === (int) ASNY_NEW_YEAR_VARIANT ? 2 : 1;
}

/** Separate transparent holiday objects, each with its own parallax speed. */
function asny_v2_ornaments( $scene ) {
	$scenes = array(
		'showcase' => array(
			array( 'wreath', -3, 5, 145, -12, .12 ),
			array( 'gift', 90, 10, 145, 16, -.12 ),
			array( 'candy', -4, 29, 130, -19, .18 ),
			array( 'bauble', 92, 36, 140, 15, -.20 ),
			array( 'gift', -3, 49, 150, -15, .10 ),
			array( 'wreath', 90, 60, 145, 12, -.15 ),
			array( 'bauble', -2, 73, 130, -15, .16 ),
			array( 'candy', 91, 83, 130, 18, -.19 ),
		),
		'hero' => array(
			array( 'gift', 4, 12, 160, -14, .10 ),
			array( 'bauble', 84, 9, 145, 12, -.16 ),
			array( 'candy', 9, 63, 135, -20, .20 ),
			array( 'wreath', 83, 65, 160, 12, -.11 ),
			array( 'bauble', 24, 82, 75, -18, .25 ),
			array( 'gift', 72, 10, 80, 15, -.20 ),
		),
		'catalog' => array(
			array( 'wreath', 3, 8, 145, -12, .12 ),
			array( 'gift', 86, 4, 130, 16, -.12 ),
			array( 'candy', 1, 43, 120, -19, .18 ),
			array( 'bauble', 89, 49, 120, 15, -.20 ),
			array( 'gift', 4, 81, 135, -15, .10 ),
			array( 'wreath', 84, 85, 140, 12, -.15 ),
		),
		'gallery' => array(
			array( 'bauble', 3, 9, 115, -15, .16 ),
			array( 'candy', 87, 5, 110, 18, -.19 ),
			array( 'gift', 2, 72, 135, -12, .11 ),
			array( 'wreath', 87, 73, 125, 16, -.13 ),
		),
	);
	foreach ( isset( $scenes[ $scene ] ) ? $scenes[ $scene ] : array() as $item ) {
		printf(
			'<span class="asny-v2-ornament" data-asny-depth="%s" style="--ornament-x:%s%%;--ornament-y:%s%%;--ornament-size:%spx;--ornament-turn:%sdeg" aria-hidden="true"><img src="%s" alt="" loading="%s" decoding="async"></span>',
			esc_attr( $item[5] ), esc_attr( $item[1] ), esc_attr( $item[2] ), esc_attr( $item[3] ), esc_attr( $item[4] ),
			esc_url( get_theme_file_uri( 'assets/images/new-year/v2/' . $item[0] . '.png' ) ),
			'hero' === $scene ? 'eager' : 'lazy'
		);
	}
}

add_filter( 'template_include', 'asny_page_template', 100 );
function asny_page_template( $template ) {
	if ( ! is_page( 'novyi-god' ) || is_feed() || is_embed() ) {
		return $template;
	}
	$filename = 2 === asny_variant() ? 'templates/page-novyi-god-v2.php' : 'templates/page-novyi-god.php';
	$landing = get_theme_file_path( $filename );
	return is_readable( $landing ) ? $landing : $template;
}

add_action( 'wp_enqueue_scripts', 'asny_enqueue_assets' );
function asny_enqueue_assets() {
	if ( ! is_page( 'novyi-god' ) ) {
		return;
	}
	foreach ( array( 'css', 'js' ) as $type ) {
		$suffix = 'js' === $type && 2 === asny_variant() ? '-v2' : '';
		$file = 'assets/' . $type . '/new-year' . $suffix . '.' . $type;
		$version = (string) filemtime( get_theme_file_path( $file ) );
		if ( 'css' === $type ) {
			wp_enqueue_style( 'asny-landing', get_theme_file_uri( $file ), array( 'twentytwentyfive-style' ), $version );
		} else {
			$dependencies = wp_script_is( 'contact-form-7', 'registered' ) ? array( 'contact-form-7' ) : array();
			wp_enqueue_script( 'asny-landing', get_theme_file_uri( $file ), $dependencies, $version, true );
		}
	}
	if ( 2 === asny_variant() ) {
		$file = 'assets/css/new-year-v2.css';
		wp_enqueue_style( 'asny-landing-v2', get_theme_file_uri( $file ), array( 'asny-landing' ), (string) filemtime( get_theme_file_path( $file ) ) );
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
