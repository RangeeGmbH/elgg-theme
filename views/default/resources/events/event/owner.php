<?php

$user = elgg_get_page_owner_entity();
if (!($user instanceof \ElggUser)) {
	forward('', '404');
}

$title = elgg_echo('event_manager:owner:title', [$user->name]);
if ($user->guid === elgg_get_logged_in_user_guid()) {

}
$events = event_manager_search_events(array(
    'user_guid' => $user->guid,
    'owning' => true,
));

$content = elgg_view('event_manager/list', [
    'entities' => $events['entities'],
    'count' => $events['count'],
]);

$form = elgg_view_form('event_manager/event/search', [
    'id' => 'event_manager_search_form',
    'name' => 'event_manager_search_form',
    'class' => 'mbl',
]);
$menu = elgg_view_menu('events_list', ['class' => 'elgg-tabs', 'sort_by' => 'register']);
$filter = elgg_view_menu('filter', ['class' => 'elgg-tabs', 'sort_by' => 'register']);

$body = elgg_view_layout('content', [
    'content' => $form .'<br>' . $content,
    'title' => $title,
    'filter' => $menu,
    'hero_menu' => $filter,
]);

echo elgg_view_page($title, $body);
