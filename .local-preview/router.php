<?php
// Local-only rendering harness, outside the theme and upload package.
$root = dirname(__DIR__);
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
if ($uri !== '/' && is_file($root . $uri)) return false;
if ($uri === '/mobile') {
 $w = max(320, min(1440, (int)($_GET['width'] ?? 390)));
 echo '<!doctype html><html><head><title>Мобильная версия — ' . $w . ' px</title></head><body style="margin:0;background:#e0e2de;display:flex;justify-content:center"><iframe title="Мобильный просмотр" src="' . (2 === (int) ($_GET['variant'] ?? 1) ? '/variant-2/' : '/') . '" style="width:' . $w . 'px;height:900px;border:0;background:white"></iframe></body></html>';
 return;
}
if (str_starts_with($uri, '/_previewfonts/')) {
 $font = basename($uri);
 if (!in_array($font, array('sf-pro-display-regular.otf','sf-pro-display-medium.otf','bebasneuecyrillic.ttf'), true)) { http_response_code(404); return; }
 header('Content-Type: application/octet-stream');
 readfile(__DIR__ . '/fonts/' . $font);
 return;
}
define('ASNY_NEW_YEAR_VARIANT', trim($uri, '/') === 'variant-2' ? 2 : 1);
define('ABSPATH', $root . '/');
$live = file_get_contents(__DIR__ . '/site-snapshot.html');
foreach (array('sf-pro-display-regular.otf','sf-pro-display-medium.otf','bebasneuecyrillic.ttf') as $font) {
 $live = str_replace('https://xn--80aaghfmganxzjmbm2spa.xn--p1ai/wp-content/uploads/fonts/' . $font, '/_previewfonts/' . $font, $live);
}
function add_filter(...$args) {} function add_action(...$args) {}
function apply_filters($name, $value) { return $value; }
function absint($value) { return abs((int)$value); }
function shortcode_exists($tag) { return true; }
function get_theme_file_path($path) { return ABSPATH . $path; }
function get_theme_file_uri($path) { return '/' . $path; }
function esc_attr($value) { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
function esc_html($value) { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
function esc_url($value) { return esc_attr($value); }
function home_url($path) { return 'https://xn--80aaghfmganxzjmbm2spa.xn--p1ai' . $path; }
function get_privacy_policy_url() { return home_url('/privacy/'); }
function language_attributes() { echo 'lang="ru-RU"'; }
function bloginfo($what) { echo 'UTF-8'; }
function body_class($extra) { echo 'class="asny-page wp-singular page page-id-5055 wp-theme-twentytwentyfive gspbody gspb-bodyfront mega-menu-max-mega-menu-1 ' . esc_attr($extra) . '"'; }
function wp_body_open() {}
function block_template_part($name) {
 global $live;
 preg_match('~<' . $name . '\b[\s\S]*?</' . $name . '>~', $live, $match);
 $part = preg_replace('~<script\b[\s\S]*?</script>~', '', $match[0] ?? '');
 // Logo images on the live host are unavailable during an outage.
 $part = preg_replace('~<img\b[^>]*>~', '<span class="preview-brand">академия сюрпризов · студия печати</span>', $part);
 $part = preg_replace('~(<form\b[^>]*action=)["\'][^"\']*["\']~', '$1"#"', $part);
 echo $part;
}
function do_shortcode($code) {
 return '<div class="wpcf7" id="asny-preview-cf7"><form action="#" class="wpcf7-form" novalidate><input name="text-944"><textarea name="textarea-144"></textarea><input type="submit" value="Отправить"></form></div>';
}
function wp_head() {
 global $live;
 preg_match('~<head>([\s\S]*?)</head>~', $live, $match);
 preg_match_all('~<style\b[\s\S]*?</style>~', $match[1], $styles);
 preg_match_all('~@font-face\s*\{(?=[^}]*\/_previewfonts\/)[^}]*\}~', $live, $fonts); echo '<style>' . implode("\n", $fonts[0]) . '</style>'; echo '<title>Новый год — локальная проверка</title>' . implode("\n", $styles[0]);
 echo '<link rel="stylesheet" href="/style.css"><link rel="stylesheet" href="/assets/css/new-year.css">';
 echo '<link rel="stylesheet" href="/.local-preview/preview.css">';
 if (2 === asny_variant()) echo '<link rel="stylesheet" href="/assets/css/new-year-v2.css">';
}
function wp_footer() {
 echo <<<'HTML'
<div style="position:fixed;top:0;left:50%;transform:translateX(-50%);z-index:1000;background:#fff6da;color:#193f35;padding:3px 12px;border-radius:0 0 5px 5px;font:10px Arial">Локальный просмотр · отправка отключена</div>
<details style="margin:20px;padding:15px;background:#fff6da" id="preview-test"><summary>Локальная проверка формы — письма не отправляются</summary><label>Ответ тестовой формы <select id="preview-response"><option value="success">Успех</option><option value="failure">Ошибка отправки</option><option value="spam">Спам</option><option value="invalid">Неверные поля</option></select></label><pre id="preview-message" style="white-space:pre-wrap"></pre></details>
<script>
window.wpcf7 = {};
document.addEventListener('submit', function(event) {
 const form = event.target;
 if (form.closest('[data-asny-form]')) return;
 event.preventDefault();
 if (!form.closest('[data-asny-bridge]')) return;
 document.querySelector('#preview-message').textContent = form.elements.namedItem('textarea-144').value;
 const mode = document.querySelector('#preview-response').value;
 const bridge = document.querySelector('#asny-preview-cf7');
 setTimeout(() => bridge.dispatchEvent(new CustomEvent(({success:'wpcf7mailsent',failure:'wpcf7mailfailed',spam:'wpcf7spam',invalid:'wpcf7invalid'})[mode], {bubbles:true})), 150);
}, true);
</script>
HTML;
 echo '<script src="/assets/js/new-year' . (2 === asny_variant() ? '-v2' : '') . '.js"></script>';
}
require ABSPATH . 'inc/new-year.php';
require ABSPATH . (2 === asny_variant() ? 'templates/page-novyi-god-v2.php' : 'templates/page-novyi-god.php');
