elgg.event_manager.activate_menu_tab = function(selected) {
    if(selected.startsWith("events_"))
        selected = selected.substr(7);

    if(selected === "map")
        selected = "onthemap";
    var selected_element = $(`.elgg-layout-filter .container .nav-left a[rel=${selected}]`);

    if (selected_element.hasClass('is-active'))
        return;


    $('.elgg-layout-filter .nav-left a').removeClass('is-active');
    selected_element.addClass('is-active');


    $('.event-manager-results, #event_manager_search_form').hide();
    if (selected !== 'events_calendar')
        $('#event_manager_search_form').show();

    $('#search_type').val(selected);

    if (selected == 'onthemap') {
        $('#event_manager_event_map').show();
        if (typeof elgg.event_manager.map === 'undefined') {
            require(['elgg/spinner'], function(spinner) {
                spinner.start();

                require(['event_manager/maps'], function (EventMap) {
                    elgg.event_manager.map = EventMap.setup('#event_manager_onthemap_canvas');
                    elgg.event_manager.map.gmap.addListener('idle', elgg.event_manager.execute_search_map);
                });
            });
        } else {
            elgg.event_manager.execute_search_map();
        }
    } else if (selected == 'list') {
        $('#event_manager_event_listing').show();
        elgg.event_manager.execute_search_list();
    } else if (selected == 'calendar') {
        $('#event-manager-event-calendar').show();

        require(['elgg/spinner'], function(spinner) {
            spinner.start();

            require(['event_manager/calendar'], function () {
                spinner.stop();
            });
        });
    }

};
