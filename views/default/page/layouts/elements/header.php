<?php
/**
 * Layout header
 */

$header = elgg_extract('header', $vars);
if ($header === false) {
	return;
}

$attrs = [
	'class' => ['elgg-layout-header', 'hero'],
];

$cover = '';
$has_cover_class = '';
$cover_url = elgg_extract('cover_url', $vars);

if (!$cover_url) {
	$owner = elgg_get_page_owner_entity();
	if ($owner && $owner->hasIcon('large', 'cover')) {
		$cover_url = $owner->getIconURL([
			'size' => 'large',
			'type' => 'cover',
		]);
	}
}

if ($cover_url) {
	$cover = elgg_format_element('div', [
		'class' => 'elgg-layout-cover',
		'style' => "background-image:url($cover_url);"
	]);
	$attrs['class'][] = 'is-dark';
	$attrs['class'][] = 'has-cover';
} else {
	$style = elgg_get_plugin_setting('style:header', 'rangee_theme', 'light');
	$attrs['class'][] = "is-$style";
}

$attrs = elgg_format_attributes($attrs);
$hero_menu = elgg_extract('hero_menu', $vars, false);
?>
<div <?= $attrs ?>>
	<?= $cover ?>
    <div class="hero-body">
        <div class="elgg-inner container">
			<?php
			echo elgg_view('page/layouts/elements/title', $vars);
			?>
        </div>
    </div>
    <div class="hero-foot">
        <?php if($hero_menu) { ?>
            <div class="elgg-inner container">
                <div class="nav-left">
                    <?= $hero_menu ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
