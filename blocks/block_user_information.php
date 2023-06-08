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

global $k_config, $k_blocks, $config, $template, $user, $phpEx;

$this->config = $config;
$this->template = $template;
$this->user = $user;
$this->php_ext = $phpEx;

$total_queries = $queries = $cached_queries = 0;
$rank_title = $rank_img = $rank_img_src = '';

if (!function_exists('get_user_rank'))
{
	include($this->phpbb_root_path . 'includes/functions_display.' . $this->php_ext);
}

foreach ($k_blocks as $blk)
{
	if ($blk['html_file_name'] == 'block_user_information.html')
	{
		$block_cache_time = $blk['block_cache_time'];
		break;
	}
}
$block_cache_time = (isset($block_cache_time) ? $block_cache_time : $k_config['k_block_cache_time_default']);

get_user_rank($this->user->data['user_rank'], (($this->user->data['user_id'] == ANONYMOUS) ? false : $this->user->data['user_posts']), $rank_title, $rank_img, $rank_img_src);

// Last visit date/time
$s_last_visit = ($user->data['user_id'] != ANONYMOUS) ? $user->format_date($user->data['session_last_visit']) : '';

// Generate logged in/logged out status
if ($this->user->data['user_id'] != ANONYMOUS)
{
	$u_login_logout = append_sid("{$this->phpbb_root_path}ucp.{$this->php_ext}", 'mode=logout', true, $this->user->session_id);
	$l_login_logout = sprintf($this->user->lang['LOGOUT_USER'], $this->user->data['username']);
}
else
{
	$u_login_logout = append_sid("{$this->phpbb_root_path}ucp.{$this->php_ext}", 'mode=login');
	$l_login_logout = $this->user->lang['LOGIN'];
}

$s_login_redirect = build_hidden_fields(['redirect' => append_sid("{$this->phpbb_root_path}portal.{$this->php_ext}")]);

// Add form token for login box, in case page is presenting a login form.
add_form_key('login', '_LOGIN');

$this->template->assign_vars([
	'AVATAR'		=> phpbb_get_user_avatar($this->user->data, $user->lang['USER_AVATAR'], false),
	'WELCOME_SITE'		=> sprintf($this->user->lang['WELCOME_SITE'], $this->config['sitename']),
	'LAST_VISIT_DATE'	=> sprintf($user->lang['YOU_LAST_VISIT'], $s_last_visit),
	'CURRENT_TIME'		=> sprintf($user->lang['CURRENT_TIME'], $user->format_date(time(), false, true)),
	'USR_RANK_TITLE'	=> $rank_title,
	'USR_RANK_IMG'		=> $rank_img,
	'U_LOGIN_LOGOUT'	=> $u_login_logout,
	'L_LOGIN_LOGOUT'	=> $l_login_logout,
	'S_LOGIN_ACTION'	=> append_sid("{$this->phpbb_root_path}ucp.{$this->php_ext}", 'mode=login'),
	'S_LOGIN_REDIRECT'	=> $s_login_redirect,
	'U_SEARCH_NEW'		=> append_sid("{$this->phpbb_root_path}search.{$this->php_ext}", 'search_id=newposts'),
	'U_SEARCH_SELF'		=> append_sid("{$this->phpbb_root_path}search.{$this->php_ext}", 'search_id=egosearch'),
	'U_SEARCH_UNREAD'	=> append_sid("{$this->phpbb_root_path}search.{$this->php_ext}", 'search_id=unreadposts'),
]);
