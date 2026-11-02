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
<body <?php body_class( 'asny-page' ); ?>>
<?php wp_body_open(); ?>
<div class="wp-site-blocks">
	<?php echo $asny_header; ?>
	<main id="wp--skip-link--target" class="asny" data-asny-root>
		<section class="asny-hero" aria-labelledby="asny-title">
			<div class="asny-container asny-hero-grid">
				<div class="asny-hero-copy">
					<h1 id="asny-title">Новогодние подарки с вашим дизайном</h1>
					<p class="asny-hero-term">Изготовление от 1 дня</p>
					<p class="asny-intro">Создадим персональные подарки для близких, друзей, коллег и партнёров. Нанесём любимую фотографию, имя, поздравление или логотип компании.</p>
					<ul class="asny-benefits">
						<li> Подарки от 1 штуки до корпоративных тиражей</li>
						<li> Помощь с индивидуальным дизайном</li>
						<li> Изготовление за 1 день</li>
					</ul>
					<a href="#asny-quiz" class="asny-button" data-asny-start data-asny-source="Первый экран">Подобрать подарок и узнать стоимость</a>
					<p class="asny-note">Поможем выбрать изделие и рассчитаем стоимость вашего заказа.</p>
				</div>
				<div class="asny-hero-photo"><?php echo asny_image( 'mugs-work.jpg', 'Примеры подарочных кружек с индивидуальным дизайном', true ); ?></div>
			</div>
		</section>

		<section id="asny-gifts" class="asny-section asny-container" aria-labelledby="asny-gifts-title">
			<div class="asny-section-heading"><div><h2 id="asny-gifts-title">Что подарить на Новый год?</h2></div><p>Подарок, который создаётся специально для получателя. Любимая фотография, памятная дата, тёплые слова или оригинальный рисунок — сделаем привычные вещи по-настоящему личными.</p></div>
			<div class="asny-catalog">
				<?php foreach ( $asny_products as $asny_key => $asny_product ) : ?>
				<article class="asny-product">
					<a class="asny-product-image" href="#asny-quiz" data-asny-start data-asny-service="<?php echo esc_attr( $asny_key ); ?>" data-asny-source="Каталог подарков" aria-label="Подобрать: <?php echo esc_attr( $asny_product['name'] ); ?>"><?php echo asny_image( $asny_product['image'], $asny_product['name'] ); ?></a>
					<h3><?php echo esc_html( $asny_product['name'] ); ?></h3>
					<p><?php echo esc_html( $asny_product['description'] ); ?></p>
					<?php if ( 'calendars' === $asny_key ) : ?><p class="asny-calendar-note">На фото — пример оформления. Календарную сетку подготовим на 2027 год.</p><?php endif; ?>
					<a class="asny-text-link" href="#asny-quiz" data-asny-start data-asny-service="<?php echo esc_attr( $asny_key ); ?>" data-asny-source="Каталог подарков">Рассчитать стоимость</a>
				</article>
				<?php endforeach; ?>
			</div>
			<p class="asny-catalog-action"><a href="#asny-quiz" class="asny-button" data-asny-start data-asny-source="Каталог подарков">Узнать стоимость подарка</a></p>
		</section>

		<section id="asny-works" class="asny-section asny-works" aria-labelledby="asny-works-title"><div class="asny-container">
			<div class="asny-section-heading"><div><h2 id="asny-works-title">Идеи становятся подарками</h2></div><p>Примеры печати из нашего портфолио. Ваш подарок оформим с вашим фото, надписью или рисунком.</p></div>
			<div class="asny-gallery">
				<?php foreach ( array( array( 'mugs-work.jpg', 'Печать на кружках' ), array( 'sweatshirt-work.jpg', 'Индивидуальный принт на одежде' ), array( 'canvas-work.jpg', 'Любимая фотография на холсте' ), array( 'portrait.jpg', 'Портрет по фотографии' ) ) as $asny_work ) : ?>
				<figure><a href="<?php echo esc_url( get_theme_file_uri( 'assets/images/new-year/' . $asny_work[0] ) ); ?>" data-asny-lightbox aria-label="Увеличить: <?php echo esc_attr( $asny_work[1] ); ?>"><?php echo asny_image( $asny_work[0], $asny_work[1] ); ?></a><figcaption><?php echo esc_html( $asny_work[1] ); ?></figcaption></figure>
				<?php endforeach; ?>
			</div>
		</div></section>

		<section id="asny-prices" class="asny-section asny-container" aria-labelledby="asny-prices-title">
			<div class="asny-section-heading"><div><h2 id="asny-prices-title">Подарок под вашу идею и бюджет</h2></div><p>Стоимость зависит от изделия, количества и подготовки дизайна. Выберите услугу — рассчитаем ваш заказ.</p></div>
			<div class="asny-price-list">
				<?php foreach ( $asny_products as $asny_key => $asny_product ) : ?>
				<div class="asny-price-row"><h3><?php echo esc_html( $asny_product['short'] ); ?></h3><p><?php echo esc_html( $asny_product['price_note'] ); ?></p><a class="asny-text-link" href="#asny-quiz" data-asny-start data-asny-service="<?php echo esc_attr( $asny_key ); ?>" data-asny-source="Стоимость">Узнать стоимость</a></div>
				<?php endforeach; ?>
			</div>
			<p class="asny-note">Срок изготовления зависит от изделия и тиража. В декабре рекомендуем согласовать заказ заранее.</p>
		</section>

		<section id="asny-business" class="asny-business asny-section" aria-labelledby="asny-business-title"><div class="asny-container">
			<div class="asny-business-top"><div><h2 id="asny-business-title">Корпоративные новогодние подарки</h2><p class="asny-business-subtitle">Для сотрудников, клиентов и партнёров — с логотипом вашей компании</p><p>Подготовим новогодние подарки в фирменном стиле вашего бизнеса. Поможем подобрать изделия, адаптировать дизайн и рассчитать стоимость тиража.</p><a class="asny-button asny-button-light" href="#asny-quiz" data-asny-start data-asny-recipient="business" data-asny-source="Корпоративный заказ">Рассчитать корпоративный заказ</a><p class="asny-note">Расскажите, сколько подарков вам нужно, — предложим подходящие варианты.</p></div><div class="asny-business-art"><figure><?php echo asny_image( 'mugs-work.jpg', 'Пример нанесения дизайна на кружки' ); ?><figcaption>Ваш фирменный стиль — на подарках</figcaption></figure></div></div>
			<div class="asny-business-benefits"><article><h3>Корпоративный стиль</h3><p>Нанесём логотип, фирменную графику или персональные поздравления.</p></article><article><h3>Тираж под вашу задачу</h3><p>Подберём подходящие изделия и обсудим количество подарков с учётом бюджета.</p></article><article><h3>Помощь с макетом</h3><p>Подготовим или адаптируем дизайн для печати и согласуем его перед изготовлением.</p></article></div>
			<div class="asny-business-gallery"><h3>Примеры нанесения на товары</h3><div class="asny-gallery asny-gallery-business">
				<?php foreach ( array( array( 'mugs-work.jpg', 'Печать на кружках' ), array( 'shirt-work.jpg', 'Печать на футболках' ), array( 'sweatshirt-work.jpg', 'Печать на свитшотах' ) ) as $asny_work ) : ?>
				<figure><a href="<?php echo esc_url( get_theme_file_uri( 'assets/images/new-year/' . $asny_work[0] ) ); ?>" data-asny-lightbox aria-label="Увеличить: <?php echo esc_attr( $asny_work[1] ); ?>"><?php echo asny_image( $asny_work[0], $asny_work[1] ); ?></a><figcaption><?php echo esc_html( $asny_work[1] ); ?></figcaption></figure>
				<?php endforeach; ?>
			</div></div>
		</div></section>

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

		<button class="asny-ideas-trigger" type="button" data-asny-open="ideas" hidden> Нужны идеи для подарка?</button>
		<dialog class="asny-dialog asny-ideas" data-asny-dialog="ideas" aria-labelledby="asny-ideas-title">
			<button class="asny-dialog-close" type="button" data-asny-close aria-label="Закрыть окно">×</button><h2 id="asny-ideas-title">А вы уже придумали, что подарить?</h2><p>У нас есть идея получше обычного сувенира. Создадим подарок с вашим дизайном — специально для того, кому он предназначен.</p>
			<form data-asny-form="ideas"><input type="hidden" name="source" value="Поп-ап: подборка идей"><fieldset><legend>Кому ищем подарок?</legend><div class="asny-options asny-options-compact"><label><input type="radio" name="recipient" value="personal" required><span>Для близкого человека</span></label><label><input type="radio" name="recipient" value="business" required><span>Для коллег или клиентов</span></label><label><input type="radio" name="recipient" value="team" required><span>Для целой команды</span></label></div></fieldset><label class="asny-field">Что вам больше нравится?<select name="gift" required><option value="">Выберите подарок</option><option value="mugs">Кружки</option><option value="clothing">Одежда</option><option value="calendars">Календари</option><option value="canvas">Холсты</option><option value="help">Помогите выбрать</option></select></label><label class="asny-field">Куда отправить идеи и расчёт стоимости?<input type="tel" name="phone" autocomplete="tel" inputmode="tel" placeholder="Ваш номер телефона" maxlength="24" required></label><label class="asny-consent"><input type="checkbox" name="consent" required><span>Соглашаюсь на обработку персональных данных в соответствии с <a href="<?php echo esc_url( $asny_privacy ); ?>" target="_blank" rel="noopener">политикой конфиденциальности</a>.</span></label><p class="asny-form-error" role="alert" data-asny-error hidden></p><button type="submit" class="asny-button" data-asny-submit>Получить подборку</button></form>
			<div class="asny-success" data-asny-success="ideas" hidden tabindex="-1"><h3>Спасибо! Ваша заявка отправлена.</h3><p>Мы свяжемся с вами для уточнения деталей.</p></div>
		</dialog>
		<dialog class="asny-dialog asny-lightbox" data-asny-dialog="lightbox" aria-label="Просмотр готовой работы"><button class="asny-dialog-close" type="button" data-asny-close aria-label="Закрыть фотографию">×</button><img src="" alt="" data-asny-large-image><p data-asny-large-caption></p></dialog>
		<div class="asny-cf7-bridge" data-asny-bridge hidden><?php echo $asny_contact; ?></div>
	</main>
	<?php echo $asny_footer; ?>
</div>
<?php wp_footer(); ?>
</body>
</html>
