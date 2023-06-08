<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2022 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

//$auth->acl($user->data);

global $user, $forum_id, $phpbb_root_path, $phpEx, $SID, $config, $template, $k_config, $k_blocks, $db, $web_path, $phpbb_container;

$phpbb_content_visibility = $phpbb_container->get('content.visibility');

foreach ($k_blocks as $blk)
{
	if ($blk['html_file_name'] == 'block_recent_topics_wide.html')
	{
		$block_cache_time = $blk['block_cache_time'];
		break;
	}
}

$block_cache_time = (isset($block_cache_time) ? $block_cache_time : $k_config['k_block_cache_time_default']);


/*
if (!defined('POST_TOPIC_URL'))
{
	define('POST_TOPIC_URL' , 't');
}
if (!defined('POST_CAT_URL'))
{
	define('POST_CAT_URL', 'c');
}

if (!defined('POST_USERS_URL'))
{
	define('POST_USERS_URL', 'u');
}
if (!defined('POST_POST_URL'))
{
	define('POST_POST_URL', 'p');
}
if (!defined('POST_GROUPS_URL'))
{
	define('POST_GROUPS_URL', 'g');
}
*/


if (!defined('POST_FORUM_URL'))
{
	define('POST_FORUM_URL', 'f');
}

/***
	Could add option to show a simplified listing (without categories or forum grouping)
	Basically just show most recent topics unsorted... (requested)
***/


// set up variables used //
$forum_count = $row_count = 0;
$valid_forum_ids = [];

$display_this_many = $k_config['k_recent_topics_to_display'];
$except_forum_id = $k_config['k_recent_topics_search_exclude'];
$k_recent_search_days = (isset($k_config['k_recent_search_days'])) ? $k_config['k_recent_search_days'] : 7;
$k_post_types = $k_config['k_post_types'];
$k_recent_topics_per_forum = $k_config['k_recent_topics_per_forum'];

static $last_forum = 0;

$forum_data = [];

$sql = "SELECT html_file_name, scroll, position
	FROM " . K_BLOCKS_TABLE . "
	WHERE html_file_name = 'block_recent_topics_wide.html'";

//$result = $db->sql_query($sql);


if ($result = $db->sql_query($sql, $block_cache_time))
{
	$row = $db->sql_fetchrow($result);
	$scroll = $row['scroll'];
	$display_center = $row['position'];
}
else
{
	trigger_error('ERROR_PORTAL_BLOCKS' . '102');
}
$db->sql_freeresult($result);

$style_row = ($scroll) ? 'scrollwide_' : 'staticwide_';

$sql = "SELECT * FROM ". FORUMS_TABLE . " ORDER BY forum_id";

$result = $db->sql_query($sql);

if (!$result = $db->sql_query($sql, $block_cache_time))
{
	trigger_error($user->lang['ERROR_PORTAL_FORUMS'] . '111');
}

/* don't show these (set in ACP) */
$except_forum_ids = explode(",", $except_forum_id);

while ($row = $db->sql_fetchrow($result))
{
	if (!in_array($row['forum_id'], $except_forum_ids))
	{
		$forum_data[] = $row;
		$forum_count++;
	}
}
$db->sql_freeresult($result);

for ($i = 0; $i < $forum_count; $i++)
{
	if ($auth->acl_gets('f_list', 'f_read', $forum_data[$i]['forum_id']))
	{
		$valid_forum_ids[] = (int) $forum_data[$i]['forum_id'];
	}
}

// do we at least one valid forum for this user, if not, don't continue //
if (count($valid_forum_ids) < 1)
{
	return;
}

$where_sql = $db->sql_in_set('t.forum_id', $valid_forum_ids);

if ($k_post_types)
{
	$types_sql = '';
}
else
{
	$types_sql = "AND t.topic_type < " . POST_ANNOUNCE;
}

$post_time_days = time() - 86400 * $k_recent_search_days;

// New code //user_avatar, user_avatar_type, user_avatar_width , user_avatar_height
$sql_array = [
	'SELECT'		=> 'p.post_id, t.*, p.post_edit_time, p.post_subject, p.post_text, p.post_time, p.bbcode_bitfield, p.bbcode_uid, f.forum_desc, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height, f.forum_name',

	'FROM'			=> [FORUMS_TABLE => 'f'],

	'LEFT_JOIN'		=> [
		[
			'FROM'	=> [TOPICS_TABLE => 't'],
			'ON'	=> "f.forum_id = t.forum_id",
		],
		[
			'FROM'	=> [POSTS_TABLE => 'p'],
			'ON'	=> "t.topic_id = p.topic_id",
		],
		[
			'FROM'	=> [USERS_TABLE => 'u'],
			'ON'	=> "t.topic_last_poster_id = u.user_id",
		],
	],

	'WHERE'	=> $where_sql . '
		' . $types_sql . '
		AND p.post_id = t.topic_last_post_id
		AND (p.post_time >= ' . $post_time_days . ' OR p.post_edit_time >= ' . $post_time_days . ')
			ORDER BY t.forum_id, p.post_time DESC'
];

$sql = $db->sql_build_query('SELECT', $sql_array);

$result = $db->sql_query_limit($sql, $display_this_many, 0, $block_cache_time);

$row = $db->sql_fetchrowset($result);

$db->sql_freeresult($result);

$row_count = count($row);

// display_this_many do we have them?
if ($row_count < $display_this_many)
{
	$display_this_many = $row_count;
}

/*
We need a way to disable scrolling (of any block) if the information retrieved
is less that can be properly displayed in the block. The minimum height of all
blocks that support scrolling is currently set to 160px.
Note as this affects scrolling it is read by the portal.html page...
*/

// Don't scroll recent-topics(RT) if less that 5 posts returned. //
if ($scroll)
{
	if ($row_count > 5)
	{
		$template->assign_var('DISABLE_RT_SCROLL', false);
	}
	else
	{
		$template->assign_var('DISABLE_RT_SCROLL', true);
	}
}


//$next_img = '<img src="' . $phpbb_root_path . 'images/next_line.gif" height="9" width="11" alt="" />';


$tn = time();
$od = 86400;
$td = 172800;

for ($i = 0; $i < $display_this_many; $i++)
{
	$unique = ($row[$i]['forum_id'] == $last_forum) ? false : true;

	if ($i >= $k_recent_topics_per_forum && $row[$i]['forum_id'] == $row[$i - $k_recent_topics_per_forum]['forum_id'])
	{
		continue;
	}

	$pd = $row[$i]['post_time'];

	if (($tn - $pd) < $od)
	{
		$thisd = 1;
	}
	else if ($tn - $pd < $td)
	{
		$thisd = 2;
	}
	else
	{
		$thisd = 0;
	}

	$my_title = $row[$i]['topic_title'];

	if (strlen($my_title) > 25)
	{
		sgp_checksize($my_title, 25);
	}

	$forum_name = $row[$i]['forum_name'];

	if (strlen($forum_name) > 25)
	{
		$forum_name = sgp_checksize($forum_name, 25);
	}

	$view_topic_url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . $row[$i]['forum_id']);

	if ($row[$i]['post_edit_time'] > $row[$i]['post_time'])
	{
		$this_post_time = '*<span style="font-style:italic">' . $user->format_date($row[$i]['post_edit_time']) . '</span>';
	}
	else
	{
		$this_post_time = $user->format_date($row[$i]['post_time']);
	}

	$template->assign_block_vars($style_row . 'recent_topic_row', [
		'AVATAR_SMALL_IMG'	=> phpbb_get_user_avatar($row[$i], $user->lang['USER_AVATAR'], false),
		'FORUM_W'			=> $forum_name,
		'LAST_POST_IMG_W'	=> $user->img('icon_topic_newest', 'VIEW_LATEST_POST'),
		//'LAST_POST_IMG_W'	=> $next_img,
		'POSTER_FULL_W'		=> get_username_string('full', $row[$i]['topic_last_poster_id'], $row[$i]['topic_last_poster_name'], $row[$i]['topic_last_poster_colour']),
		'POSTTIME_W'		=> $this_post_time,

		'REPLIES'			=> $phpbb_content_visibility->get_count('topic_posts', $row[$i], $row[$i]['forum_id']) - 1,

		'U_FORUM_W'			=> append_sid("{$phpbb_root_path}viewforum.$phpEx?" . POST_FORUM_URL . '=' . $row[$i]['forum_id']),
		'U_TITLE_W'			=> $view_topic_url . '&amp;p=' . $row[$i]['topic_last_post_id'] . '#p' . $row[$i]['topic_last_post_id'],
		'S_ROW_COUNT_W'		=> $i,
		'S_UNIQUE_W'		=> $unique,
		'S_TYPE_W'			=> $row[$i]['topic_type'],
		'TITLE_W'			=> censor_text($my_title),
		//'TOOLTIP_W'			=> bbcode_strip($row[$i]['post_text']),
		//'TOOLTIP2_W'		=> bbcode_strip($row[$i]['forum_desc']),
		'S_PC'              => $thisd,
		'SS' => $tn - $pd,
	]);

	$last_forum = $row[$i]['forum_id'];
}

if ($i > 1)
{
	$post_or_posts = strtolower($user->lang['TOPICS']);
}
else
{
	$post_or_posts = strtolower($user->lang['TOPIC']);
}

$template->assign_vars([
	'S_COUNT_RECENT'		=> ($i > 0) ? true : false,
	'RECENT_SEARCH_TYPE'	=> sprintf($user->lang['K_RECENT_SEARCH_DAYS'], $k_recent_search_days),
	'S_FULL_LEGEND'			=> ($k_post_types) ? true : false,
]);
