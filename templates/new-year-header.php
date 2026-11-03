<?php
/** Seasonal header. Reuse the stored header's logo and catalog destinations. */
defined( 'ABSPATH' ) || exit;
$asny_logo = '';
if ( preg_match( '~<a\b[^>]*class=["\'][^"\']*\bcustom-logo-link\b[^"\']*["\'][^>]*>[\s\S]*?</a>~i', $asny_header, $asny_logo_match ) ) {
	$asny_logo = $asny_logo_match[0];
}
$asny_catalog_links = array();
if ( preg_match_all( '~<a\b[^>]*class=["\'][^"\']*\bmega-menu-link\b[^"\']*["\'][^>]*>[\s\S]*?</a>~i', $asny_header, $asny_menu_matches ) ) {
	foreach ( $asny_menu_matches[0] as $asny_menu_link ) {
		if ( ! preg_match( '~\bhref=["\']([^"\']*)["\']~i', $asny_menu_link, $asny_href ) ) {
			continue;
		}
		$asny_url = html_entity_decode( $asny_href[1], ENT_QUOTES, 'UTF-8' );
		if ( preg_match( '~/((dostavka|o-nas)/?)$~', $asny_url ) ) {
			continue;
		}
		$asny_catalog_links[ $asny_url ] = trim( html_entity_decode( strip_tags( $asny_menu_link ), ENT_QUOTES, 'UTF-8' ) );
	}
}
if ( ! $asny_catalog_links ) {
	$asny_catalog_links = array(
		home_url( '/pechat-na-holste/' ) => 'Печать на холсте',
		home_url( '/catalog/pechat-portreta-na-holste/' ) => 'Портрет на холсте',
		home_url( '/catalog/pechat-na-suvenirah/' ) => 'Печать на сувенирах',
		home_url( '/catalog/pechat-na-odezhde/' ) => 'Печать на одежде',
		home_url( '/poligrafiya/' ) => 'Полиграфия',
		home_url( '/catalog/reklamnaya-produkciya/' ) => 'Рекламные товары',
		home_url( '/shop/' ) => 'Все товары',
	);
}
?>
<header class="asny-header" data-asny-header data-theme="beige">
	<div class="asny-header-inner">
		<div class="asny-header-brand">
			<?php if ( $asny_logo ) : ?>
				<?php echo $asny_logo; ?>
			<?php else : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="asny-header-wordmark">академия сюрпризов<span>студия печати</span></a>
			<?php endif; ?>
		</div>
		<button type="button" class="asny-menu-toggle" data-asny-menu-toggle aria-expanded="false" aria-controls="asny-header-navigation" hidden><span data-asny-menu-label>Меню</span><span class="asny-menu-lines" aria-hidden="true"></span></button>
		<nav class="asny-header-nav" id="asny-header-navigation" aria-label="Основная навигация">
			<details class="asny-header-catalog" data-asny-header-disclosure>
				<summary>Каталог</summary>
				<div class="asny-header-catalog-panel"><p>Печатаем ваши идеи</p><ul>
					<?php foreach ( $asny_catalog_links as $asny_url => $asny_label ) : ?>
					<li><a href="<?php echo esc_url( $asny_url ); ?>"><?php echo esc_html( $asny_label ); ?></a></li>
					<?php endforeach; ?>
				</ul></div>
			</details>
			<a class="asny-header-season" href="<?php echo esc_url( home_url( '/novyi-god/' ) ); ?>" aria-current="page">Новый год</a>
			<a href="<?php echo esc_url( home_url( '/dostavka/' ) ); ?>">Доставка</a>
			<a href="<?php echo esc_url( home_url( '/o-nas/' ) ); ?>">О нас</a>
			<a class="asny-header-mobile-phone" href="tel:+79519369490">8-951-936-94-90</a>
		</nav>
		<div class="asny-header-contact"><a href="tel:+79519369490">8-951-936-94-90</a><span>Пермь, ул. Ленина, 76</span></div>
	</div>
</header>
