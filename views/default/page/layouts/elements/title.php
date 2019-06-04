<?php
/**
 * Layout title
 */
$header = elgg_extract('header', $vars);

if (isset($header)) {
	echo $header;
}


	$title = elgg_extract('title', $vars, '');
	if ($title) {
		$title = elgg_view_title($title, [
			'class' => 'elgg-heading-main title is-2',
		]);
	}
	$subtitle = elgg_extract('subtitle', $vars);
	if ($subtitle) {
		$subtitle = elgg_format_element('div', [
			'class' => 'subtitle is-4',
		], $subtitle);
	}

	$title = elgg_format_element('div', [], $title . $subtitle);

?>
<div class="elgg-layout-title">
    <div class="elgg-inner container">
		<?= $title ?>
    </div>
</div>
