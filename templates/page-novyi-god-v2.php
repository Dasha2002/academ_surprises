<?php
/** The page is selected automatically by slug; no changes in the page editor needed. */
defined( 'ABSPATH' ) || exit;
$asny_products = asny_products();
$asny_privacy = get_privacy_policy_url();
if ( ! $asny_privacy ) {
	$asny_privacy = home_url( '/privacy/' );
}
// Render before wp_head so Contact Form 7 can enqueue its own assets.
$asny_contact = asny_contact_form();
// Block rendering can enqueue styles: render shared parts before wp_head, as WP does.
ob_start();
block_template_part( 'header' );
$asny_header = ob_get_clean();
ob_start();
block_template_part( 'footer' );
$asny_footer = ob_get_clean();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'asny-page asny-page-v2' ); ?>>
<?php wp_body_open(); ?>
<div class="wp-site-blocks">
	<?php require get_theme_file_path( 'templates/new-year-header.php' ); ?>
	<main id="wp--skip-link--target" class="asny asny-v2" data-asny-root data-asny-variant="2">
		<section class="asny-hero" aria-labelledby="asny-title">
			<div class="asny-v2-backdrop" aria-hidden="true"></div>
			<div class="asny-v2-sparks" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
			<div class="asny-container asny-hero-grid">
				<div class="asny-hero-copy">
					<p class="asny-v2-kicker">Академия сюрпризов · Пермь</p><h1 id="asny-title">Новогодние подарки <span>с вашим дизайном</span></h1>
					<p class="asny-hero-term">Изготовление от 1 дня</p>
					<p class="asny-intro">Создадим персональные подарки для близких, друзей, коллег и партнёров. Нанесём любимую фотографию, имя, поздравление или логотип компании.</p>
					<!-- <ul class="asny-benefits">
						<li>От 1 подарка до тиража</li>
						<li>Поможем с дизайном</li>
						<li>Сделаем от 1 дня</li>
					</ul> -->
					<a href="#asny-quiz" class="asny-button" data-asny-start data-asny-source="Первый экран">Подобрать подарок и узнать стоимость</a>
				</div>
				<div class="asny-hero-products" role="group" aria-label="Примеры наших работ">
					<figure class="asny-hero-product asny-hero-product-shirt">
						<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/new-year/shirt-work.jpg' ) ); ?>" alt="Белые футболки с индивидуальным цветным принтом" width="600" height="451" loading="eager" decoding="async">
						<figcaption>Футболки с принтом</figcaption>
					</figure>
					<figure class="asny-hero-product asny-hero-product-mugs">
						<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/new-year/mugs-work.jpg' ) ); ?>" alt="Кружки с персональными иллюстрациями" width="600" height="450" loading="eager" decoding="async">
						<figcaption>Кружки с вашим дизайном</figcaption>
					</figure>
					<figure class="asny-hero-product asny-hero-product-canvas">
						<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/new-year/canvas-work.jpg' ) ); ?>" alt="Картины на холсте по фотографиям пары и семьи" width="600" height="750" loading="eager" decoding="async">
						<figcaption>Картины на холсте</figcaption>
					</figure>
				</div>
			</div>
		</section>

		<nav class="asny-gift-ribbon" aria-label="Быстрый выбор подарка"><div class="asny-container">
			<?php foreach ( $asny_products as $asny_key => $asny_product ) : ?><?php if ( 'portraits' === $asny_key ) { continue; } ?><a href="#asny-quiz" data-asny-start data-asny-service="<?php echo esc_attr( $asny_key ); ?>" data-asny-source="Новогодняя лента"><?php echo esc_html( 'calendars' === $asny_key ? 'Календари' : $asny_product['short'] ); ?></a><?php endforeach; ?>
		</div></nav>

		<div class="asny-v2-showcase" data-asny-parallax><div class="asny-v2-backdrop" aria-hidden="true"></div><div class="asny-v2-showcase-decor" aria-hidden="true"><?php asny_v2_ornaments( 'showcase' ); ?></div>
		<section id="asny-gifts" class="asny-section asny-v2-gifts" aria-labelledby="asny-gifts-title">
<div class="asny-container">
			<div class="asny-section-heading"><div><h2 id="asny-gifts-title">Что подарить на Новый год?</h2></div><p>Выберите подарок, добавьте фото или надпись. Поможем с дизайном и рассчитаем стоимость под ваш тираж.</p></div>
			<div class="asny-catalog">
				<?php foreach ( $asny_products as $asny_key => $asny_product ) : ?>
				<article class="asny-product">
					<a class="asny-product-image" href="#asny-quiz" data-asny-start data-asny-service="<?php echo esc_attr( $asny_key ); ?>" data-asny-source="Каталог подарков" aria-label="Подобрать: <?php echo esc_attr( $asny_product['name'] ); ?>"><?php echo asny_image( $asny_product['image'], $asny_product['name'] ); ?></a>
					<h3><?php echo esc_html( $asny_product['name'] ); ?></h3>
					<p><?php echo esc_html( $asny_product['description'] ); ?></p>
					<?php if ( 'calendars' === $asny_key ) : ?><p class="asny-calendar-note">На фото — пример оформления. Календарную сетку подготовим на 2027 год.</p><?php endif; ?>
					<div class="asny-product-action"><a class="asny-button" href="#asny-quiz" data-asny-start data-asny-service="<?php echo esc_attr( $asny_key ); ?>" data-asny-source="Каталог подарков">Рассчитать стоимость</a></div>
				</article>
				<?php endforeach; ?>
			</div>
		</div></section>

		<section id="asny-works" class="asny-section asny-works" aria-labelledby="asny-works-title"><div class="asny-container">
			<div class="asny-section-heading"><div><h2 id="asny-works-title">Идеи становятся подарками</h2></div><p>Реальные примеры нашей печати. Выберите понравившуюся идею: для вашего подарка используем ваше фото, надпись или рисунок.</p></div>
			<div class="asny-gallery">
				<?php foreach ( array( array( 'mugs-work.jpg', 'Печать на кружках', 'mugs', 'Фото, имя или тёплые слова на подарке для близкого человека.' ), array( 'sweatshirt-work.jpg', 'Индивидуальный принт на одежде', 'clothing', 'Ваш рисунок или надпись на футболке, толстовке или свитшоте.' ), array( 'canvas-work.jpg', 'Любимая фотография на холсте', 'canvas', 'Семейный снимок или памятный момент в подарок для дома.' ), array( 'portrait.jpg', 'Портрет по фотографии', 'portraits', 'Персональный подарок с портретом по вашему снимку.' ) ) as $asny_work ) : ?>
				<figure><a href="<?php echo esc_url( get_theme_file_uri( 'assets/images/new-year/' . $asny_work[0] ) ); ?>" data-asny-lightbox aria-label="Увеличить: <?php echo esc_attr( $asny_work[1] ); ?>"><?php echo asny_image( $asny_work[0], $asny_work[1] ); ?></a><figcaption><h3><?php echo esc_html( $asny_work[1] ); ?></h3><p><?php echo esc_html( $asny_work[3] ); ?></p><a class="asny-button" href="#asny-quiz" data-asny-start data-asny-service="<?php echo esc_attr( $asny_work[2] ); ?>" data-asny-source="Примеры работ">Рассчитать стоимость</a></figcaption></figure>
				<?php endforeach; ?>
			</div>
		</div></section>

		</div>
		<div class="asny-v2-lower"><section id="asny-prices" class="asny-section asny-v2-prices" aria-labelledby="asny-prices-title">
			<div class="asny-prices-garlands" aria-hidden="true"></div>
			<div class="asny-prices-side-decor" aria-hidden="true"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/new-year/v2/prices-garland.png' ) ); ?>" alt="" loading="lazy" decoding="async"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/new-year/v2/prices-garland.png' ) ); ?>" alt="" loading="lazy" decoding="async"></div>
		<div class="asny-container">
			<div class="asny-section-heading"><div><h2 id="asny-prices-title">Подарок под вашу идею и бюджет</h2></div><p>Выберите изделие. Уточним детали, поможем с дизайном и рассчитаем стоимость вашего заказа.</p></div>
			<div class="asny-price-list">
				<?php foreach ( $asny_products as $asny_key => $asny_product ) : ?>
				<article class="asny-price-row"><div class="asny-price-thumb"><?php echo asny_image( $asny_product['image'], '' ); ?></div><div class="asny-price-copy"><h3><?php echo esc_html( $asny_product['short'] ); ?></h3><p><span>На стоимость влияют:</span><?php echo esc_html( $asny_product['price_note'] ); ?></p></div><a class="asny-button" href="#asny-quiz" data-asny-start data-asny-service="<?php echo esc_attr( $asny_key ); ?>" data-asny-source="Стоимость" aria-label="Рассчитать стоимость: <?php echo esc_attr( $asny_product['short'] ); ?>">Рассчитать стоимость</a></article>
				<?php endforeach; ?>
			</div>
			<p class="asny-note">Срок изготовления зависит от изделия и тиража. В декабре рекомендуем согласовать заказ заранее.</p>
		</div></section>

		<div class="asny-v2-last-two" data-asny-parallax>
			<div class="asny-v2-backdrop" aria-hidden="true"></div>
			<div class="asny-business-decor" aria-hidden="true"><?php asny_v2_ornaments( 'showcase' ); ?></div>
			<section id="asny-business" class="asny-business asny-section" aria-labelledby="asny-business-title"><div class="asny-container">
				<div class="asny-business-top">
					<div class="asny-business-copy">
						<h2 id="asny-business-title">Новогодние подарки для вашей команды</h2>
						<p class="asny-business-subtitle">Для сотрудников, клиентов и партнёров. С вашим логотипом и персональными поздравлениями.</p>
						<dl class="asny-business-points">
							<div><dt>Под ваш бюджет</dt><dd>Подберём изделия и рассчитаем нужный тираж.</dd></div>
							<div><dt>В фирменном стиле</dt><dd>Адаптируем логотип и согласуем макет до печати.</dd></div>
							<div><dt>С понятным сроком</dt><dd>Уточним дату готовности под ваш заказ.</dd></div>
						</dl>
						<a class="asny-button" href="#asny-quiz" data-asny-start data-asny-recipient="business" data-asny-source="Корпоративный заказ">Рассчитать стоимость</a>
					</div>
					<div class="asny-business-art"><figure><?php echo asny_image( 'mugs-work.jpg', 'Пример печати дизайна на подарочных кружках' ); ?><figcaption>Ваш дизайн на подарках для команды</figcaption></figure></div>
				</div>
				<div class="asny-business-gallery" role="group" aria-labelledby="asny-business-examples-title">
					<h3 id="asny-business-examples-title">Выберите подарок для вашего тиража</h3>
					<div class="asny-business-products">
						<?php foreach ( array( array( 'mugs-work.jpg', 'Кружки с логотипом', 'mugs', 'Практичный подарок для офиса и дома.' ), array( 'shirt-work.jpg', 'Футболки для команды', 'clothing', 'Фирменный принт на каждый день.' ), array( 'sweatshirt-work.jpg', 'Свитшоты с принтом', 'clothing', 'Тёплый подарок в вашем стиле.' ) ) as $asny_work ) : ?>
						<article class="asny-business-product">
							<a class="asny-business-product-photo" href="<?php echo esc_url( get_theme_file_uri( 'assets/images/new-year/' . $asny_work[0] ) ); ?>" data-asny-lightbox aria-label="Увеличить: <?php echo esc_attr( $asny_work[1] ); ?>"><?php echo asny_image( $asny_work[0], $asny_work[1] ); ?></a>
							<div><h4><?php echo esc_html( $asny_work[1] ); ?></h4><p><?php echo esc_html( $asny_work[3] ); ?></p><a class="asny-button" href="#asny-quiz" data-asny-start data-asny-service="<?php echo esc_attr( $asny_work[2] ); ?>" data-asny-recipient="business" data-asny-source="Корпоративный заказ">Рассчитать стоимость</a></div>
						</article>
						<?php endforeach; ?>
					</div>
				</div>
			</div></section>

			<section id="asny-cta" class="asny-v2-cta asny-section asny-container" aria-labelledby="asny-cta-title">
				<div class="asny-cta-card">
					<div class="asny-cta-garlands" aria-hidden="true"></div>
					<div class="asny-cta-copy"><h2 id="asny-cta-title">Ещё выбираете подарок?</h2><p>Расскажите, кому хотите сделать сюрприз. Поможем выбрать изделие, обсудим дизайн и рассчитаем стоимость.</p><a href="#asny-quiz" class="asny-button" data-asny-start data-asny-source="Подбор в конце страницы">Подобрать подарок</a><noscript><p>Позвоните нам: <a href="tel:+79519369490">8-951-936-94-90</a>.</p></noscript></div>
					<div class="asny-cta-art" aria-hidden="true"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/new-year/v2/gift.png' ) ); ?>" alt="" width="1280" height="1280" loading="lazy" decoding="async"></div>
					<img class="asny-cta-bauble" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/new-year/v2/bauble.png' ) ); ?>" alt="" aria-hidden="true" width="1280" height="1280" loading="lazy" decoding="async">
				</div>
			</section>
		</div>

		</div>
<dialog class="asny-dialog asny-quiz-dialog" data-asny-dialog="quiz" aria-labelledby="asny-quiz-title">
<button class="asny-dialog-close" type="button" data-asny-close aria-label="Закрыть подбор подарка">×</button>
		<section id="asny-quiz" class="asny-section asny-container asny-quiz-section" aria-labelledby="asny-quiz-title">
			<div class="asny-quiz-copy"><h2 id="asny-quiz-title">Подберём новогодний подарок под вашу идею и бюджет</h2><p>Ответьте на несколько вопросов. Поможем выбрать подходящее изделие, обсудим дизайн и рассчитаем стоимость заказа.</p></div>
			<div class="asny-quiz-card">
				<form class="asny-quiz-form" data-asny-form="quiz" hidden>
					<input type="hidden" name="source" value="Квиз на странице Новый год"><input type="hidden" name="service_detail" value="">
					<div class="asny-progress-header"><span data-asny-step-label aria-live="polite">Шаг 1 из 4</span><span>Ваш особенный подарок</span></div><progress class="asny-progress" max="4" value="1" aria-label="Прогресс подбора подарка"></progress>
					<fieldset data-asny-step="0"><legend tabindex="-1">Для кого выбираете подарки?</legend><div class="asny-options">
						<label><input type="radio" name="recipient" value="personal" required><span>Для себя или близких</span></label><label><input type="radio" name="recipient" value="business" required><span>Для сотрудников и клиентов</span></label><label><input type="radio" name="recipient" value="event" required><span>Для мероприятия</span></label>
					</div></fieldset>
					<fieldset data-asny-step="1" hidden><legend tabindex="-1">Сколько подарков вам нужно?</legend><div class="asny-options asny-options-grid">
						<?php foreach ( array( 'one' => '1 шт.', 'few' => '2–10 шт.', 'medium' => '11–50 шт.', 'many' => 'Более 50 шт.' ) as $asny_value => $asny_label ) : ?><label><input type="radio" name="quantity" value="<?php echo esc_attr( $asny_value ); ?>" required><span><?php echo esc_html( $asny_label ); ?></span></label><?php endforeach; ?>
					</div></fieldset>
					<fieldset data-asny-step="2" hidden><legend tabindex="-1">Что хотите подарить?</legend><div class="asny-options">
						<?php foreach ( array( 'mugs' => 'Кружки', 'clothing' => 'Одежду с принтом', 'calendars' => 'Календарь', 'canvas' => 'Фото или портрет на холсте', 'help' => 'Пока не определился — нужна помощь с выбором' ) as $asny_value => $asny_label ) : ?><label><input type="radio" name="gift" value="<?php echo esc_attr( $asny_value ); ?>" required><span><?php echo esc_html( $asny_label ); ?></span></label><?php endforeach; ?>
					</div></fieldset>
					<fieldset data-asny-step="3" hidden><legend tabindex="-1">Готово! Осталось узнать, куда отправить варианты</legend><p>Оставьте номер телефона — свяжемся с вами, уточним детали и поможем подобрать подарок.</p><p class="asny-selection" data-asny-summary></p><label class="asny-field">Ваш номер телефона<input type="tel" name="phone" autocomplete="tel" inputmode="tel" placeholder="+7 (___) ___-__-__" maxlength="24" required></label><label class="asny-consent"><input type="checkbox" name="consent" required><span>Соглашаюсь на обработку персональных данных в соответствии с <a href="<?php echo esc_url( $asny_privacy ); ?>" target="_blank" rel="noopener">политикой конфиденциальности</a>.</span></label></fieldset>
					<p class="asny-form-error" role="alert" data-asny-error hidden></p><div class="asny-quiz-actions"><button type="button" class="asny-back" data-asny-back hidden>← Назад</button><button type="button" class="asny-button" data-asny-next>Далее</button><button type="submit" class="asny-button" data-asny-submit hidden>Получить варианты и стоимость</button></div>
				</form>
				<div class="asny-success" data-asny-success="quiz" hidden tabindex="-1"><h3>Спасибо! Ваша заявка отправлена.</h3><p>Мы свяжемся с вами для уточнения деталей.</p><button type="button" class="asny-text-link" data-asny-restart>Подобрать ещё один подарок ↗</button></div>
				<div class="asny-form-fallback" data-asny-fallback><h3>Обсудим ваш подарок?</h3><p>Позвоните нам: <a href="tel:+79519369490">8-951-936-94-90</a>. Поможем с выбором и рассчитаем стоимость.</p><p>Также можно воспользоваться формой обратной связи внизу сайта.</p></div>
			</div>
		</section>
</dialog>
		<button class="asny-ideas-trigger" type="button" data-asny-open="ideas" hidden> Нужны идеи для подарка?</button>
		<dialog class="asny-dialog asny-ideas" data-asny-dialog="ideas" aria-labelledby="asny-ideas-title">
			<button class="asny-dialog-close" type="button" data-asny-close aria-label="Закрыть окно">×</button><h2 id="asny-ideas-title">А вы уже придумали, что подарить?</h2><p>У нас есть идея получше обычного сувенира. Создадим подарок с вашим дизайном — специально для того, кому он предназначен.</p>
			<form data-asny-form="ideas"><input type="hidden" name="source" value="Поп-ап: подборка идей"><fieldset><legend>Кому ищем подарок?</legend><div class="asny-options asny-options-compact"><label><input type="radio" name="recipient" value="personal" required><span>Для близкого человека</span></label><label><input type="radio" name="recipient" value="business" required><span>Для коллег или клиентов</span></label><label><input type="radio" name="recipient" value="team" required><span>Для целой команды</span></label></div></fieldset><label class="asny-field">Что вам больше нравится?<select name="gift" required><option value="">Выберите подарок</option><option value="mugs">Кружки</option><option value="clothing">Одежда</option><option value="calendars">Календари</option><option value="canvas">Холсты</option><option value="help">Помогите выбрать</option></select></label><label class="asny-field">Куда отправить идеи и расчёт стоимости?<input type="tel" name="phone" autocomplete="tel" inputmode="tel" placeholder="Ваш номер телефона" maxlength="24" required></label><label class="asny-consent"><input type="checkbox" name="consent" required><span>Соглашаюсь на обработку персональных данных в соответствии с <a href="<?php echo esc_url( $asny_privacy ); ?>" target="_blank" rel="noopener">политикой конфиденциальности</a>.</span></label><p class="asny-form-error" role="alert" data-asny-error hidden></p><button type="submit" class="asny-button" data-asny-submit>Получить подборку</button></form>
			<div class="asny-success" data-asny-success="ideas" hidden tabindex="-1"><h3>Спасибо! Ваша заявка отправлена.</h3><p>Мы свяжемся с вами для уточнения деталей.</p></div>
		</dialog>
		<dialog class="asny-dialog asny-lightbox" data-asny-dialog="lightbox" aria-label="Просмотр готовой работы"><button class="asny-dialog-close" type="button" data-asny-close aria-label="Закрыть фотографию">×</button><img src="" alt="" data-asny-large-image><p data-asny-large-caption></p></dialog>
		<div class="asny-cf7-bridge" data-asny-bridge hidden><?php echo $asny_contact; ?></div>
	</main>
	<?php require get_theme_file_path( 'templates/new-year-footer.php' ); ?>
</div>
<?php wp_footer(); ?>
</body>
</html>
