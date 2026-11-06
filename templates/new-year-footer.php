<?php
/** Variant 2 footer: rearrange the stored content, preserving the native CF7 form. */
defined( 'ABSPATH' ) || exit;

// Remove the studio credit from this variant, including the no-DOM fallback.
$asny_footer = preg_replace( '~(?:<br\s*/?>\s*)?<a\b[^>]*>\s*(?:<mark\b[^>]*>\s*)?Сделано\s+в\s+атма\s*(?:</mark>\s*)?</a>~iu', '', $asny_footer );

// Keep the stored footer available if the host lacks the DOM extension.
if ( ! class_exists( 'DOMDocument' ) ) {
	echo $asny_footer;
	return;
}
$asny_footer_dom = new DOMDocument();
$asny_footer_errors = libxml_use_internal_errors( true );
$asny_footer_dom->loadHTML( '<?xml encoding="UTF-8"><html><body>' . $asny_footer . '</body></html>' );
libxml_clear_errors();
libxml_use_internal_errors( $asny_footer_errors );
$asny_footer_xpath = new DOMXPath( $asny_footer_dom );
$asny_footer_has_class = static function ( $class ) {
	return 'contains(concat(" ", normalize-space(@class), " "), " ' . $class . ' ")';
};
$asny_footer_inner = static function ( $node ) use ( $asny_footer_dom ) {
	$html = '';
	foreach ( $node->childNodes as $child ) {
		$html .= $asny_footer_dom->saveHTML( $child );
	}
	return $html;
};
$asny_footer_rows = array();
foreach ( $asny_footer_xpath->query( '//*[' . $asny_footer_has_class( 'wp-block-columns' ) . ']' ) as $row ) {
	$values = $asny_footer_xpath->query( './/*[' . $asny_footer_has_class( 'gspb_text' ) . ']', $row );
	if ( $values->length >= 2 && trim( $values->item( 0 )->textContent ) ) {
		$asny_footer_rows[] = array( trim( $values->item( 0 )->textContent ), $asny_footer_inner( $values->item( 1 ) ) );
	}
}
$asny_footer_form = $asny_footer_xpath->query( '//*[' . $asny_footer_has_class( 'wpcf7' ) . ']' )->item( 0 );
$asny_footer_legal = $asny_footer_xpath->query( '//*[' . $asny_footer_has_class( 'gspb_text' ) . '][.//a[contains(@href,"/privacy")]]' )->item( 0 );
$asny_footer_credit = $asny_footer_xpath->query( '//*[' . $asny_footer_has_class( 'gspb_text' ) . '][contains(.,"Академия сюрпризов 2013")]' )->item( 0 );
$asny_footer_logo = $asny_footer_xpath->query( '//footer//a[.//img or .//*[' . $asny_footer_has_class( 'preview-brand' ) . ']]' )->item( 0 );
$asny_footer_consent = '';
if ( $asny_footer_form ) {
	// Pull the consent copy out of the accordion alongside the existing form.
	$consent = $asny_footer_xpath->query( 'ancestor::*[' . $asny_footer_has_class( 'gs-accordion-item__text' ) . ']//*[' . $asny_footer_has_class( 'gspb_text' ) . ']', $asny_footer_form )->item( 0 );
	if ( $consent ) {
		$asny_footer_consent = $asny_footer_inner( $consent );
	}
	// Add persistent labels; retain field names, order, validation and CF7 metadata.
	foreach ( array( 'text-944' => 'Почта или телефон', 'textarea-144' => 'Ваше сообщение', 'file-289' => 'Файл с макетом' ) as $field_name => $field_label ) {
		$field = $asny_footer_xpath->query( './/*[@name="' . $field_name . '"]', $asny_footer_form )->item( 0 );
		if ( ! $field ) {
			continue;
		}
		if ( ! $field->hasAttribute( 'id' ) ) {
			$field->setAttribute( 'id', 'asny-footer-' . $field_name );
		}
		$label = $asny_footer_dom->createElement( 'label' );
		$label->appendChild( $asny_footer_dom->createTextNode( $field_label ) );
		$label->setAttribute( 'for', $field->getAttribute( 'id' ) );
		$label->setAttribute( 'class', 'asny-footer-field-label' );
		$wrap = $field->parentNode;
		$wrap->parentNode->insertBefore( $label, $wrap->parentNode->firstChild );
	}
}
?>
<footer id="asny-footer" class="asny-seasonal-footer" aria-labelledby="asny-footer-title">
	<div class="asny-footer-inner">
		<div class="asny-footer-contact">
			<h2 id="asny-footer-title">Обсудим ваш подарок</h2>
			<p class="asny-footer-intro">Напишите, позвоните или загляните в студию. Поможем с выбором и подготовкой дизайна.</p>
			<dl class="asny-footer-details">
				<?php foreach ( $asny_footer_rows as $asny_footer_row ) : ?>
				<div><dt><?php echo esc_html( rtrim( $asny_footer_row[0], ':' ) ); ?></dt><dd><?php echo $asny_footer_row[1]; ?></dd></div>
				<?php endforeach; ?>
			</dl>
		</div>
		<section class="asny-footer-form" aria-labelledby="asny-footer-form-title">
			<h3 id="asny-footer-form-title">Форма обратной связи</h3>
			<p class="asny-footer-form-intro">Оставьте контакт и расскажите, что хотите напечатать.</p>
			<?php if ( $asny_footer_form ) : ?>
				<?php echo $asny_footer_dom->saveHTML( $asny_footer_form ); ?>
				<?php if ( $asny_footer_consent ) : ?><div class="asny-footer-consent"><?php echo $asny_footer_consent; ?></div><?php endif; ?>
			<?php else : ?>
				<p>Свяжитесь с нами по телефону или в мессенджерах.</p>
			<?php endif; ?>
		</section>
		<div class="asny-footer-bottom">
			<?php if ( $asny_footer_logo ) : ?><div class="asny-footer-logo"><?php echo $asny_footer_dom->saveHTML( $asny_footer_logo ); ?></div><?php else : ?><a class="asny-footer-name" href="<?php echo esc_url( home_url( '/' ) ); ?>">Академия сюрпризов</a><?php endif; ?>
			<?php if ( $asny_footer_legal ) : ?><div><?php echo $asny_footer_inner( $asny_footer_legal ); ?></div><?php endif; ?>
			<?php if ( $asny_footer_credit ) : ?><div class="asny-footer-credit"><?php echo $asny_footer_inner( $asny_footer_credit ); ?></div><?php endif; ?>
		</div>
	</div>
</footer>
