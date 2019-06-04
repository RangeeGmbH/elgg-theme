<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
$description = null;
$entity = elgg_extract('entity', $vars);
$view_type = elgg_extract('view_type', $vars);
$offset = get_input('offset', 0);
$limit = 4;
$footer = null;
switch ($view_type) {
    default:
    case 'compact':
        $title_text .= elgg_get_excerpt($entity->getDisplayName(), 100);
        $pagination = false;
        $count = elgg_get_entities(array(
            'type'           => 'object',
            'subtype'        => 'forum',
            'container_guid' => $entity->getGUID(),
            'count'          => true
        ));

        if ($count > 4) {
            $footer = elgg_view('output/url', array(
                'text'  => elgg_echo('more'),
                'class' => 'elgg-button elgg-button-action float-alt mbm mrm',
                'href'  => $entity->getURL()
            ));
        }
        break;
    case 'full':
        $title_text .= $entity->getDisplayName();
        $pagination = true;
        break;
}


$title_url = $entity->getURL();
$title = elgg_view('output/url', array(
    'text' => $title_text,
    'href' => $title_url
));

if (elgg_is_admin_logged_in()) {
    $title .= elgg_view_menu('entity', array(
        'entity'  => $entity,
        'handler' => 'forumcategory',
        'sort_by' => 'priority',
        'class'   => 'elgg-menu-hz mbm'
    ));
}

$description = elgg_get_excerpt($entity->description);

$content = elgg_format_element('div', array(
    'class' => 'mbm'
), $description);

$content .= elgg_list_entities(array(
    'type'           => 'object',
    'subtype'        => 'forum',
    'container_guid' => $entity->getGUID(),
    'list_type'      => 'table',
    'order_by'       => 'time_created',
    'limit'          => $limit,
    'offset'         => $offset,
    'pagination'     => $pagination,
    'columns'        => array(
        elgg()->table_columns->item(elgg_echo('forum:forums')),
        elgg()->table_columns->fromView('forum_last_topic', elgg_echo('forum:last:topic'))
    )
));


echo elgg_view_module('featured', $title, $content, array(
    'footer' => $footer
));
