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
$view_type = elgg_extract('view_type', $vars);

echo "<td class='elgg-forum-narrow-column'>";
echo elgg_list_entities(array(
    'type'             => 'object',
    'subtype'          => 'forumreply',
    'container_guid'   => $item->getGUID(),
    'limit'            => 1,
    'order_by'         => 'time_created',
    'reverse_order_by' => true,
    'pagination'       => false,
    'view_type'        => 'compact',
    'class'            => 'elgg-forum-latest-reply'
));
echo '</td>';
