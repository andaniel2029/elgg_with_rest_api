<?php
function site_search($q, $offset = 0,$search_type = 'all', $limit = 2, $entity_type = ELGG_ENTITIES_ANY_VALUE, $owner_guid = ELGG_ENTITIES_ANY_VALUE, $container_guid = ELGG_ENTITIES_ANY_VALUE, $friends = ELGG_ENTITIES_ANY_VALUE, $entity_subtype = ELGG_ENTITIES_ANY_VALUE, $sort = 'relevance', $order = 'desc')	{

$query = stripslashes($q);

$display_query = _elgg_get_display_query($query);

// check that we have an actual query
if (empty($query) && $query != "0") {
	$return['status'] = false;
	$return['output'] = elgg_echo('search:no_query');
	return $return;
}

// get limit and offset.  override if on search dashboard, where only 2
// of each most recent entity types will be shown.
$limit = ($search_type == 'all') ? 2 : get_input('limit', elgg_get_config('default_limit'));
$offset = ($search_type == 'all') ? 0 : get_input('offset', 0);

switch ($sort) {
	case 'relevance':
	case 'created':
	case 'updated':
	case 'action_on':
	case 'alpha':
		break;

	default:
		$sort = 'relevance';
		break;
}

// set up search params
$params = [
	'query' => $query,
	'offset' => $offset,
	'limit' => $limit,
	'sort' => $sort,
	'order' => $order,
	'search_type' => $search_type,
	'type' => $entity_type,
	'subtype' => $entity_subtype,
	'owner_guid' => $owner_guid,
	'container_guid' => $container_guid,
	'pagination' => ($search_type == 'all') ? FALSE : TRUE
];

$types = get_registered_entity_types();
$types = elgg_trigger_plugin_hook('search_types', 'get_queries', $params, $types);

$custom_types = elgg_trigger_plugin_hook('search_types', 'get_types', $params, []);

// start the actual search
$results_html = [];

if ($search_type == 'all' || $search_type == 'entities') {
	// to pass the correct current search type to the views
	$current_params = $params;
	$current_params['search_type'] = 'entities';

	// foreach through types.
	// if a plugin returns FALSE for subtype ignore it.
	// if a plugin returns NULL or '' for subtype, pass to generic type search function.
	// if still NULL or '' or empty(array()) no results found. (== don't show??)
	foreach ($types as $type => $subtypes) {
		if ($search_type != 'all' && $entity_type != $type) {
			continue;
		}

		if (is_array($subtypes) && count($subtypes)) {
			foreach ($subtypes as $subtype) {
				// no need to search if we're not interested in these results
				// @todo when using index table, allow search to get full count.
				if ($search_type != 'all' && $entity_subtype != $subtype) {
					continue;
				}
				$current_params['subtype'] = $subtype;
				$current_params['type'] = $type;

				$results = elgg_trigger_plugin_hook('search', "$type:$subtype", $current_params, NULL);
				
				if ($results === FALSE) {
					// someone is saying not to display these types in searches.
					continue;
				} elseif (is_array($results) && !count($results)) {
					// no results, but results searched in hook.
				} elseif (!$results) {
					// no results and not hooked.  use default type search.
					// don't change the params here, since it's really a different subtype.
					// Will be passed to elgg_get_entities().
					$results = elgg_trigger_plugin_hook('search', $type, $current_params, []);
				}

				if (is_array($results['entities']) && $results['count']) {
					foreach($results['entities'] as $result){
						array_push($results_html, (array)$result->toObject());
					}				
				}
			}
		}

		// pull in default type entities with no subtypes
		$current_params['type'] = $type;
		$current_params['subtype'] = ELGG_ENTITIES_NO_VALUE;

		$results = elgg_trigger_plugin_hook('search', $type, $current_params, []);
		if ($results === FALSE) {
			// someone is saying not to display these types in searches.
			continue;
		}

		if (is_array($results['entities']) && $results['count']) {
			foreach($results['entities'] as $result){
				array_push($results_html, (array)$result->toObject());
			}
		}
	}
}

// call custom searches
if ($search_type != 'entities' || $search_type == 'all') {
	if (is_array($custom_types)) {
		foreach ($custom_types as $type) {
			if ($search_type != 'all' && $search_type != $type) {
				continue;
			}

			$current_params = $params;
			$current_params['search_type'] = $type;

			$results = elgg_trigger_plugin_hook('search', $type, $current_params, []);

			if ($results === FALSE) {
				// someone is saying not to display these types in searches.
				continue;
			}

			if (is_array($results['entities']) && $results['count']) {
					foreach($results['entities'] as $result){
						array_push($results_html, (array)$result->toObject());
					}
			}
		}
	}
}

if (!$results_html) {
	$return['status'] = false;
	$return['output'] = elgg_echo('No Results');
} else {
	$return['status'] = true;
	$return['output'] = $results_html;
}

return $return;

}

elgg_ws_expose_function('search.site',
	"site_search",
	[
		'q' 		=> ['type' => 'string','required' => true],
		'offset' 	=> ['type' => 'int','required' => false],
		'search_type' 	=> ['type' => 'string','required' => false],
		'limit'			=> ['type' => 'int','required' => false],
		'entity_type'	=> ['type' => 'string','required' => false],
		'entity_subtype' 	=> ['type' => 'string','required' => false],
		'sort' 	=> ['type' => 'string','required' => false],
		'order' => ['type' => 'string','required' => false],
	],
	"Search the Site",
	'GET',
    false,
	false);

?>