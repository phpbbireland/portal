<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

global $k_config, $k_blocks, $user, $phpEx;

foreach ($k_blocks as $blk)
{
	if ($blk['html_file_name'] == 'block_top_posters.html')
	{
		$block_cache_time = $blk['block_cache_time'];
		break;
	}
}
$block_cache_time = (isset($block_cache_time) ? $block_cache_time : $k_config['k_block_cache_time_default']);

include_once($phpbb_root_path . 'ext/phpbbireland/portal/includes/sgp_functions.' . $phpEx);

$k_top_posters_to_display = (!empty($k_config['k_top_posters_to_display'])) ? $k_config['k_top_posters_to_display'] : '5';

$sql = 'SELECT user_id, username, user_posts, user_colour, user_type, group_id, user_avatar, user_avatar_type, user_avatar_width , user_avatar_height
	FROM ' . USERS_TABLE . '
	WHERE user_posts <> 0
		AND user_type <> ' . USER_IGNORE . '
		AND user_type <> ' . USER_INACTIVE . '
	ORDER BY user_posts DESC';


$result = $db->sql_query_limit($sql, $k_top_posters_to_display, 0, $block_cache_time);

while ($row = $db->sql_fetchrow($result))
{
	if (!$row['username'])
	{
		continue;
	}

	//@$ava = phpbb_get_avatar($row, $user->lang['USER_AVATAR'], false);

	// a workaround //
	$arg['avatar'] = $row['user_avatar'];
	$arg['avatar_type'] = $row['user_avatar_type'];
	$arg['avatar_height'] = '16'; //$row[$i]['user_avatar_height'];
	$arg['avatar_width'] = '16'; //$row[$i]['user_avatar_width'];

	$this->template->assign_block_vars('top_posters', array(
		'S_SEARCH_ACTION'	=> append_sid("{$this->phpbb_root_path}search.$phpEx", 'author_id=' . $row['user_id'] . '&amp;sr=posts'),
		'USERNAME_FULL'		=> @get_username_string('full', $row['user_id'], sgp_checksize($row['username'],15), $row['user_colour']),
		'POSTER_POSTS'		=> $row['user_posts'],
		'USER_AVATAR_IMG'	=> phpbb_get_avatar($arg, $user->lang['USER_AVATAR'], false),
		//'URL'				=> $row['user_website'],
	));
}
$db->sql_freeresult($result);
