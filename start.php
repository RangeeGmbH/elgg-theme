<?php

/**
 *
 *
 * Replaces some of the most corky elements of Elgg UI
 *
 * @author    Ismayil Khayredinov <info@hypejunction.com>
 * @copyright Copyright (c) 2017, Ismayil Khayredinov
 */
require_once __DIR__ . '/autoloader.php';

elgg_register_event_handler('init', 'system', function () {

	elgg_register_css('fonts.opensans', 'https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,700');
	elgg_load_css('fonts.opensans');

	elgg_unregister_plugin_hook_handler('prepare', 'menu:site', '_elgg_site_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:site', [Menus::class, 'setupSiteMenu']);
	elgg_register_plugin_hook_handler('prepare', 'menu:site', [Menus::class, 'prepareSiteMenu'], 999);

	elgg_register_plugin_hook_handler('register', 'menu:topbar', [Menus::class, 'setupTopbarMenu'], 999);
	elgg_register_plugin_hook_handler('register', 'menu:entity', [Menus::class, 'setupEntityMenu'], 999);
	elgg_register_plugin_hook_handler('register', 'menu:entity_social', [Menus::class, 'setupEntitySocialMenu'], 999);
	elgg_register_plugin_hook_handler('register', 'menu:entity', [Menus::class, 'setupWidgetMenu'], 999);
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', [Menus::class, 'setupOwnerBlockMenu'], 999);
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', [Menus::class, 'setupUserHoverMenu'], 999);
	elgg_register_plugin_hook_handler('register', 'menu:extras', [Menus::class, 'setupExtrasMenu'], 999);
	elgg_register_plugin_hook_handler('register', 'menu:title', [Menus::class, 'setupTitleMenu'], 999);
	elgg_register_plugin_hook_handler('register', 'menu:page', [Menus::class, 'setupPageMenu'], 999);
	elgg_register_plugin_hook_handler('register', 'menu:index', [Menus::class, 'setupIndexMenu'], 999);

	elgg_unregister_plugin_hook_handler('register', 'menu:login', '_elgg_login_menu_setup');

	elgg_extend_view('elgg.css', 'bulma.css', 1);
	elgg_extend_view('elgg.css', 'rangee.css', 1);
	elgg_extend_view('admin.css', 'bulma.css', 1);
	elgg_extend_view('elgg.js', 'page/elements/topbar.js');
  elgg_extend_view('js/elgg', 'event_manager.js');

	elgg_unextend_view('page/elements/header', 'search/header');

	// Lightbox
	elgg_unregister_css('lightbox');
	elgg_extend_view('elgg.css', 'lightbox/elgg-colorbox-theme/colorbox.css');
	elgg_extend_view('admin.css', 'lightbox/elgg-colorbox-theme/colorbox.css');
	elgg_register_plugin_hook_handler('elgg.data', 'site', [Lightbox::class, 'configure']);

	if (elgg_is_active_plugin('likes')) {
		elgg_unregister_plugin_hook_handler('register', 'menu:river', 'likes_river_menu_setup');
		elgg_unregister_plugin_hook_handler('register', 'menu:entity', 'likes_entity_menu_setup');
		if (!elgg_is_active_plugin('hypeInteractions')) {
			elgg_register_plugin_hook_handler('register', 'menu:entity_social', 'likes_entity_menu_setup', 400);
		}
	}

	if (elgg_is_active_plugin('blog')) {
		elgg_unregister_plugin_hook_handler('register', 'menu:entity', 'blog_entity_menu_setup');
	}

	elgg_register_ajax_view('object/elements/menu/contents');

	elgg_unregister_menu_item('footer', 'powered');

	elgg_register_action('theme/assets', __DIR__ . '/actions/theme/assets.php', 'admin');
	elgg_register_action('theme/layout', __DIR__ . '/actions/theme/layout.php', 'admin');

	elgg_register_plugin_hook_handler('register', 'menu:interactions', 'reply_menu_edit');
	elgg_register_plugin_hook_handler('register', 'menu:events_list', 'events_list_menu_edit');
	elgg_register_plugin_hook_handler('register', 'menu:filter', 'filter_menu_edit');

  elgg_unregister_action('profile/edit');
  elgg_register_action("profile/edit", elgg_get_plugins_path()."/rangee_theme/actions/profile/edit.php");
});

/**
 * Determine entity page handler
 *
 * @param ElggEntity $entity
 *
 * @return string
 */
function hypeapps_ui_get_entity_handler(ElggEntity $entity) {

	$map = [
		'object' => [
			'blog' => 'blog',
			'file' => 'file',
			'bookmarks' => 'bookmarks',
			'comment' => 'comment',
			'page' => 'pages',
			'page_top' => 'pages',
			'thewire' => 'thewire',
			'discussion' => 'discussion',
			'discussion_reply' => 'discussion_reply',
			'videolist_item' => 'videolist',
			'album' => 'photos',
			'image' => 'photos',
			'poll' => 'poll',
		],
		'user' => [
			'default' => 'profile',
		],
		'group' => [
			'default' => 'groups',
		],
	];

        if (elgg_is_active_plugin('event_calendar')) {
                $map['object']['event_calendar'] = 'event_calendar';
                elgg_load_library('elgg:event_calendar');
        }
        if (elgg_is_active_plugin('forum')) {
                $map['object']['forumcategory'] = 'forumcategory';
                $map['object']['forum'] = 'forum';
                $map['object']['forumtopic'] = 'forumtopic';
                $map['object']['forumreply'] = 'forumreply';
                elgg_load_library('forum_page_handlers');
        }

	$handler = elgg_extract($entity->getSubtype() ?: 'default', $map[$entity->type]);

	$params = ['entity' => $entity];

	return elgg_trigger_plugin_hook('entity:handler', $entity->type, $params, $handler);
}

function reply_menu_edit($hook, $type, $return, $params) {
    $return = array_filter($return, function ($item) {
        return $item->getName() !== 'comments';
    });
    return $return;
}

function events_list_menu_edit($hook, $type, $return, $params) {
    $return = array_filter($return, function ($item) {
        return $item->getName() !== 'attending';
    });
    return $return;
}

function filter_menu_edit($hook, $type, $return, $params) {
    if (!elgg_in_context('events')) {
        return;
    }

    $returnvalue = [];

    $returnvalue[] = \ElggMenuItem::factory([
        'name' => 'all',
        'text' => elgg_echo('all'),
        'href' => 'events',
    ]);
    $returnvalue[] = \ElggMenuItem::factory([
        'name' => 'mine',
        'text' => elgg_echo('event_manager:owner:created'),
        'href' => 'events/owner/' . (elgg_get_page_owner_entity() ? elgg_get_page_owner_entity() : elgg_get_logged_in_user_entity())->username,
    ]);

    $returnvalue[] = \ElggMenuItem::factory([
        'name' => 'attending',
        'text' => elgg_echo('event_manager:menu:attending'),
        'href' => 'events/attending/' . (elgg_get_page_owner_entity() ? elgg_get_page_owner_entity() : elgg_get_logged_in_user_entity())->username,
    ]);

    return $returnvalue;
}