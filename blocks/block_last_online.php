<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2022 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if (!defined('IN_PHPBB'))
{
   exit;
}

global $k_config, $k_blocks, $template;

$this->template = $template;

foreach ($k_blocks as $blk)
{
	if ($blk['html_file_name'] == 'block_last_online.html')
	{
		$block_cache_time = $blk['block_cache_time'];
		break;
	}
}
$block_cache_time = (isset($block_cache_time) ? $block_cache_time : $k_config['k_block_cache_time_default']);
$k_last_online_max = $k_config['k_last_online_max']; //Numbers of users to show in the last online is configurable via ACP
$queries = $cached_queries = 0;

// Can this user view profiles/memberlist/onlinelist?
if ($auth->acl_gets('u_viewprofile'))
{
	$this->template->assign_vars([
		'VIEWONLINE' => true,
	]);

	//Fetch all the block data
	$sql = 'SELECT u.user_id, u.username, u.user_colour, u.user_type, u.user_avatar, u.user_avatar_width, u.user_avatar_height, u.user_avatar_type, u.user_lastvisit, s.session_user_id, MAX(s.session_time) AS session_time
		FROM ' . USERS_TABLE . ' u
		LEFT JOIN ' . SESSIONS_TABLE . ' s ON (u.user_id = s.session_user_id AND session_time >= ' . (time() - $config['session_length']) . ')
		WHERE u.user_type <> ' . USER_IGNORE . '
			AND u.user_lastvisit <> 0
		GROUP BY s.session_user_id, u.user_id
		ORDER BY session_time DESC, u.user_lastvisit DESC' ;

	$result = $db->sql_query_limit($sql, $k_last_online_max, 0, $block_cache_time);

	$session_times = [];
	while ($row = $db->sql_fetchrow($result))
	{
		if (!$row['username'])
		{
			continue;
		}

		$session_times[$row['session_user_id']] = $row['session_time'];
		$row['session_time'] = (!empty($session_times[$row['user_id']])) ? $session_times[$row['user_id']] : 0;
		$row['last_visit'] = (!empty($row['session_time'])) ? $row['session_time'] : $row['user_lastvisit'];
		$last_visit = (!empty($row['session_time'])) ? $row['session_time'] : $row['user_lastvisit'];

		$this->template->assign_block_vars('last_online', [
			'USERNAME_FULL'		=> get_username_string('full', $row['user_id'], sgp_checksize($row['username'],15), $row['user_colour']),
			'ONLINE_TIME'		=> (empty($last_visit)) ? ' - ' : $user->format_date($last_visit, '|d M Y|, H:i'),
			'USER_AVATAR_IMG'	=> phpbb_get_user_avatar($row, $user->lang['USER_AVATAR'], false),
			'U_REGISTER'		=> 'append_sid("{$phpbb_root_path}ucp.$phpEx", mode=register)',
		]);
	}
	$db->sql_freeresult($result);
}

//Is user logged in and have no auth  to view profiles/memberlist/onlinelist?
if ($user->data['user_type'] <> USER_IGNORE && !$auth->acl_gets('u_viewprofile'))
{
	$template->assign_vars([
		'NO_VIEWONLINE_R' => true,
	]);
}

//Is user not logged in and have no auth to view profiles/memberlist/onlinelist?
if ($user->data['user_id'] == ANONYMOUS && !$auth->acl_gets('u_viewprofile'))
{
	$template->assign_vars([
		'NO_VIEWONLINE_A' => true,
	]);
}
