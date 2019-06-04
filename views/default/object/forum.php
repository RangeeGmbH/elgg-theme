<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
$entity = elgg_extract('entity', $vars);
$view_type = elgg_extract('view_type', $vars);
$owner_link = '';
$owner = $entity->getOwnerEntity();
if ($owner) {
    $owner_link = elgg_view('output/url', array(
        'href'       => $owner->getURL(),
        'text'       => $owner->getDisplayName(),
        'is_trusted' => true
    ));
}

$date = elgg_view_friendly_time($entity->getTimeCreated());

$subtitle = "$owner_link $date";
switch ($view_type) {
    default:
        $title_text = elgg_get_excerpt($entity->getDisplayName(), 300);
        $icon_size = 'small';
        if (!elgg_in_context('widgets')) {
            $metadata = elgg_view_menu('entity', array(
                'entity'  => $entity,
                'handler' => 'forum',
                'sort_by' => 'priority',
                'class'   => 'elgg-menu-hz'
            ));
        }
        $content = elgg_get_excerpt($entity->description, 100);
        break;
    case 'compact':
        $title_text = elgg_get_excerpt($entity->getDisplayName());
        $icon_size = 'tiny';
        $metadata = null;
        $content = null;
        break;
    case 'full':
        forum_update_views($entity);
        $title_text = $entity->getDisplayName();
        $title = elgg_view('output/url', array(
            'text' => $title_text,
            'href' => $entity->getURL()
        ));
        $body = elgg_view('output/longtext', array(
            'value' => $entity->description,
            'class' => 'forumtopic-post'
        ));
        if (!elgg_in_context('widgets')) {
            $metadata = elgg_view_menu('entity', array(
                'entity'  => $entity,
                'handler' => 'forum',
                'sort_by' => 'priority',
                'class'   => 'elgg-menu-hz'
            ));
        }
        $params = array(
            'entity'   => $entity,
            'title'    => $title,
            'metadata' => $metadata,
            'subtitle' => $subtitle
        );
        $params += $vars;
        $summary = elgg_view('object/elements/summary', $params);

        echo elgg_view('object/elements/full', array(
            'entity'  => $entity,
            'summary' => $summary,
            'icon'    => $owner_icon,
            'body'    => $body
        ));
        break;
}

if ($view_type !== 'full') {

    $icon = elgg_view_entity_icon($entity, $icon_size);
    $title = elgg_view('output/url', array(
        'text' => $title_text,
        'href' => $entity->getURL()
    ));


    $params = array(
        'entity'   => $entity,
        'title'    => $title,
        'metadata' => $metadata,
        'subtitle' => $subtitle,
        'content'  => $content
    );
    $params += $vars;
    $body = elgg_view('object/elements/summary', $params);

    echo elgg_view_image_block($icon, $body, $vars);
}
