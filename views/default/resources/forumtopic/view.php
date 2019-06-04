<?php

/**
 * Elgg Forum Plugin
 *
 * Provides forum functionality for Elgg
 *
 * @author  Shane Barron <clifton@sbarron.com>
 *
 */
forum_push_breadcrumbs($vars);
$guid = elgg_extract(1, $vars);

$forumtopic = get_entity($guid);
if (!elgg_instanceof($forumtopic, 'object', 'forumtopic')) {
    return true;
}

$title = $forumtopic->getDisplayName();

$content = elgg_view_entity($forumtopic, array(
    'view_type' => 'full'
));

$content .= elgg_list_entities(array(
    'type'           => 'object',
    'subtype'        => 'forumreply',
    'container_guid' => $guid,
    'list_type'      => 'table',
    'order_by'       => 'time_created',
    'offset'         => get_input('offset', 0),
    'columns'        => array(
        elgg()->table_columns->fromView('forum_owner_block', elgg_echo('forum:author'),
            array('class' => 'elgg-forum-author-box')),
        elgg()->table_columns->item(elgg_echo('forum:replies'))
    )
));
if (elgg_is_logged_in()) {
    $content .= elgg_view_form('forumreply/save', array(), $vars);
} else {
    $content .= elgg_echo('forum:reply:login');
}

$forum = get_entity($forumtopic->container_guid);

$sidebar = elgg_view('sidebar/forumtopic/view', array(
    'guid' => $forum->getGUID()
));
$params = array(
    'title'   => $title,
    'content' => $content,
    'sidebar' => $sidebar
);
$body = elgg_view_layout('one_sidebar', $params);
echo elgg_view_page($params['title'], $body);
