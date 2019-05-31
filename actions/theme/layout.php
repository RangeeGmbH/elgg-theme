<?php

$params = get_input('params');

foreach ($params as $name => $value) {
	if (!is_scalar($value)) {
		$value = serialize($value);
	}
	elgg_set_plugin_setting($name, $value, 'rangee_theme');
}

return elgg_ok_response('', elgg_echo('admin:theme:settings_saved'));
