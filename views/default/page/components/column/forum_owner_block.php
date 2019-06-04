<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
$entity = $vars['item'];
$owner = $entity->getOwnerEntity();
$owner_guid = $owner->getGuid();
$icon = elgg_view_entity_icon($owner, 'small');
$joined = elgg_view_friendly_time($owner->time_created);

$topics = elgg_get_entities(array(
    'type'       => 'object',
    'subtype'    => 'forumtopic',
    'owner_guid' => $owner_guid,
    'count'      => true
));
$replies = elgg_get_entities(array(
    'type'       => 'object',
    'subtype'    => 'forumreply',
    'owner_guid' => $owner_guid,
    'count'      => true
));

$subtitle = elgg_echo('forumtopic:topics') . '&nbsp;' . $topics . '<br/>' . elgg_echo('forumreply:replies') . '&nbsp;' . $replies . '<br/>' . elgg_echo('forum:joined') . '&nbsp;' . $joined;

$title = elgg_view('output/url', array(
    'text' => $owner->getDisplayName(),
    'href' => $owner->getURL()
));

$params = array(
    'entity'   => $owner,
    'title'    => $title,
    'subtitle' => $subtitle,
    'icon' => false
);
$params += $vars;

$body = elgg_view('object/elements/summary', $params);
echo "<td class='elgg-forum-narrow-column'>";
echo elgg_view_image_block($icon, $body, $vars);
echo '</td>';
