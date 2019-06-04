<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
$item = elgg_extract('item', $vars);
$guid = $item->getGUID();

echo "<td class='elgg-forum-narrow-column'>";
echo elgg_list_entities(array(
    'type'           => 'object',
    'subtype'        => 'forumtopic',
    'container_guid' => $guid,
    'limit'          => 1,
    'pagination'     => false,
    'class'          => 'elgg-forum-latest-topic',
    'view_type'      => 'compact'
));
echo '</td>';
