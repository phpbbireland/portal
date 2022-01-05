<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2022 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbbireland\portal\blocks;

class block
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\cache\service */
	protected $cache;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\pagination */
	protected $pagination;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\user */
	protected $user;

	/** @var string php file extension */
	protected $php_ext;

	/** @var string phpbb root path */
	protected $phpbb_root_path;


	/**
	* Construct a block object
	*
	* @param \phpbb\auth\auth $auth phpBB auth service
	* @param \phpbb\cache\service $cache phpBB cache driver
	* @param \phpbb\config\config $config phpBB config
	* @param \phpbb\template\template $template phpBB template
	* @param \phpbb\db\driver\driver_interface $db Database driver
	* @param \phpbb\request\request $request phpBB request
	* @param \phpbb\user $user phpBB user object
	* @param string $phpEx php file extension
	* @param string $phpbb_root_path phpBB root path
	*/
	public function __construct($auth, $cache, $config, $template, $db, $request, $user, $phpEx, $phpbb_root_path)
	{
		$this->auth = $auth;
		$this->cache = $cache;
		$this->config = $config;
		$this->template = $template;
		$this->db = $db;
		$this->request = $request;
		$this->user = $user;
		$this->php_ext = $phpEx;
		$this->phpbb_root_path = $phpbb_root_path;

		$this->blocks_width		= $this->config['blocks_width'];
		$this->blocks_enabled	= $this->config['blocks_enabled'];

		$this->mod_root_path = $this->phpbb_root_path . 'ext/phpbbireland/portal/';
		$this->get_caches();
	}

	public function get_active_blocks()
	{
		include_once($this->phpbb_root_path . 'ext/phpbbireland/portal/config/constants.' . $this->phpEx);


		$block_cache_time  = $this->k_config['k_block_cache_time_default'];

		if (!$this->blocks_enabled)
		{
			$this->template->assign_vars([
				'PORTAL_MESSAGE' => $this->user->lang('BLOCKS_DISABLED'),
			]);
			return(NULL);
		}

		$sql = "SELECT *
			FROM " . K_BLOCKS_TABLE . "
			WHERE active = 1
				AND (view_pages <> '0')
				ORDER BY ndx ASC";

		$result = $db->sql_query($sql, $block_cache_time);

		while ($row = $db->sql_fetchrow($result))
		{
			$active_blocks[] = $row;
		}
		$db->sql_freeresult($result);

		return($active_blocks);
	}

	public function get_block_data($id)
	{
		$sql = "SELECT *
			FROM " . K_BLOCKS_TABLE . "
			WHERE active = 1
				AND id = " . (int)$id;

		$result = $db->sql_query($sql);

		$row = $db->sql_fetchrow($result);

		$db->sql_freeresult($result);

		return($row);
	}

	public function get_caches()
	{
		if (!isset($this->k_config))
		{
			$this->k_config    = $this->obtain_k_config();
			$this->k_blocks    = $this->obtain_block_data();
			$this->k_menus     = $this->obtain_k_menus();
			$this->k_pages     = $this->obtain_k_pages();
			$this->k_groups    = $this->obtain_k_groups();
			$this->k_resources = $this->obtain_k_resources();
		}
	}


	public function build_blocks($position = 'left')
	{
		$user_id = $this->user['user_id'];

		// set some variables //
		$all = '';
		$show_center = $show_left = $show_right = false;
		$LB = $CB = $RB = $active_blocks = [];

		// if styles use large block images change path to images //
		$block_image_path = $this->phpbb_root_path . 'ext/phpbbireland/portal/images/block_images/block/';
		$big_image_path   = $this->phpbb_root_path . 'ext/phpbbireland/portal/images/block_images/large/';

		$this_page = explode(".", $this->user->page['page']);
		$theme = rawurlencode($this->user->style['style_path']);

		$template->assign_vars([
			'EXT_TEMPLATE_PATH'		=> $this->phpbb_root_path . 'ext/phpbbireland/portal/styles/' . $theme,
			'EXT_IMAGE_PATH'		=> $this_>phpbb_root_path . 'ext/phpbbireland/portal/images/',
			'MOD_IMAGE_LANG_PATH'	=> $this_>phpbb_root_path . 'ext/phpbbireland/portal/styles/' . $theme . '/theme/' . $this->user->data['user_lang'] . '/',
		]);
	}


	private function obtain_k_config()
	{
		include_once($this->phpbb_root_path . 'ext/phpbbireland/portal/config/constants.' . $this->phpEx);

		if (($this->k_config = $this->cache->get('k_config')) === false)
		{
			$sql = 'SELECT config_name, config_value
				FROM ' . K_VARS_TABLE;

			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$this->k_config[$row['config_name']] = $row['config_value'];
			}
			$db->sql_freeresult($result);

			$this->cache->put('k_config', $this->k_config);
		}

		return $this->k_config;
	}

	private function obtain_k_menus()
	{
		include_once($this->phpbb_root_path . 'ext/phpbbireland/portal/config/constants.' . $this->phpEx);

		if (($this->k_menus = $this->cache->get('k_menus')) === false)
		{
			$sql = "SELECT * FROM ". K_MENUS_TABLE . "
				ORDER BY ndx ASC ";
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$this->k_menus[$row['m_id']]['m_id'] = $row['m_id'];
				$this->k_menus[$row['m_id']]['ndx'] = $row['ndx'];
				$this->k_menus[$row['m_id']]['menu_type'] = $row['menu_type'];
				$this->k_menus[$row['m_id']]['name'] = $row['name'];
				$this->k_menus[$row['m_id']]['link_to'] = $row['link_to'];
				$this->k_menus[$row['m_id']]['extern'] = $row['extern'];
				$this->k_menus[$row['m_id']]['menu_icon'] = $row['menu_icon'];
				$this->k_menus[$row['m_id']]['append_sid'] = $row['append_sid'];
				$this->k_menus[$row['m_id']]['append_uid'] = $row['append_uid'];
				$this->k_menus[$row['m_id']]['view_all'] = $row['view_all'];
				$this->k_menus[$row['m_id']]['view_groups'] = $row['view_groups'];
				$this->k_menus[$row['m_id']]['soft_hr'] = $row['soft_hr'];
				$this->k_menus[$row['m_id']]['sub_heading'] = $row['sub_heading'];
			}
			$db->sql_freeresult($result);

			$this->cache->put('k_menus', $this->k_menus);
		}
		return $this->k_menus;
	}

	function obtain_block_data()
	{
		include_once($this->phpbb_root_path . 'ext/phpbbireland/portal/config/constants.' . $this->phpEx);

		if (($this->k_blocks = $this->cache->get('k_blocks')) === false)
		{
			$sql = 'SELECT *
				FROM ' . K_BLOCKS_TABLE . '
				WHERE active = 1 AND is_static = 0 ORDER BY ndx ASC';
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				if (!$row['is_static'])
				{
					$this->k_blocks[$row['id']]['id'] = $row['id'];
					$this->k_blocks[$row['id']]['ndx'] = $row['ndx'];
					$this->k_blocks[$row['id']]['title'] = $row['title'];
					$this->k_blocks[$row['id']]['position'] = $row['position'];
					$this->k_blocks[$row['id']]['type'] = $row['type'];
					$this->k_blocks[$row['id']]['view_groups'] = $row['view_groups'];
					$this->k_blocks[$row['id']]['scroll'] = $row['scroll'];
					$this->k_blocks[$row['id']]['block_height']	= $row['block_height'];
					$this->k_blocks[$row['id']]['html_file_name'] = $row['html_file_name'];
					$this->k_blocks[$row['id']]['img_file_name'] = $row['img_file_name'];
					$this->k_blocks[$row['id']]['block_cache_time']	= $row['block_cache_time'];
				}
			}
			$db->sql_freeresult($result);

			$this->cache->put('k_blocks', $this->k_blocks);
		}
		return $this->k_blocks;
	}

	private function obtain_k_pages()
	{
		include_once($this->phpbb_root_path . 'ext/phpbbireland/portal/config/constants.' . $this->phpEx);

		if (($this->k_pages = $this->cache->get('k_pages')) === false)
		{
			$sql = 'SELECT page_id, page_name
				FROM ' . K_PAGES_TABLE;

			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$this->k_pages[$row['page_id']]['page_id'] = $row['page_id'];
				$this->k_pages[$row['page_id']]['page_name'] = $row['page_name'];
			}
			$db->sql_freeresult($result);

			$this->cache->put('k_pages', $this->k_pages);
		}
		return $this->k_pages;
	}

	function obtain_k_groups()
	{
		include_once($this->phpbb_root_path . 'ext/phpbbireland/portal/config/constants.' . $this->phpEx);

		if (($this->k_groups = $this->cache->get('k_groups')) === false)
		{
			// Get us all the groups
			$sql = 'SELECT group_id, group_name
				FROM ' . GROUPS_TABLE . '
				ORDER BY group_id ASC, group_name';
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$this->k_groups[$row['group_id']]['group_id'] = $row['group_id'];
				$this->k_groups[$row['group_id']]['group_name'] = $row['group_name'];
			}
			$db->sql_freeresult($result);

			$this->cache->put('k_groups', $this->k_groups);
		}
		return $this->k_groups;
	}

	function obtain_k_resources()
	{
		include_once($this->phpbb_root_path . 'ext/phpbbireland/portal/config/constants.' . $this->phpEx);

		if (($this->k_resources = $this->cache->get('k_resources')) === false)
		{
			$sql = 'SELECT *
				FROM ' . K_RESOURCES_TABLE  . '
				ORDER BY word ASC';
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$this->k_resources[] = $row['word'];

			}
			$db->sql_freeresult($result);

			$this->cache->put('k_resources', $this->k_resources);
		}
		return $this->k_resources;
	}

	public function sgp_get_rand_logo()
	{
		$rand_logo = $imglist = $imgs ="";

		// Random logos are disabled config, so return default logo //
		if ($this->k_config['k_allow_rotating_logos'] == 0)
		{
			return $this->user->img('site_logo');
		}

		mt_srand((double) microtime()*1000001);

		$logos_dir = $this->phpbb_root_path . 'ext/phpbbireland/portal/styles/' . rawurlencode($this->user->style['style_path']) . '/theme/images/logos';

		$handle = @opendir($logos_dir);

		if (!$handle) // no handle so we don't have logo directory or we are attempting to login to ACP so we need to return the default logo //
		{
			return($this->user->img('site_logo'));
		}

		while (false !== ($file = readdir($handle)))
		{
			if (stripos($file, ".gif") || stripos($file, ".jpg") || stripos($file, ".png") && stripos($file ,"ogo_") || stripos($file ,"logo"))
			{
				$imglist .= "$file ";
			}
		}
		closedir($handle);

		$imglist = explode(" ", $imglist);

		if (sizeof($imglist) < 2)
		{
			return $user->img('site_logo');
		}

		$random = mt_rand(0, (mt_rand(0, (sizeof($imglist)-2))));

		$image = $imglist[$random];

		$rand_logo .= '<img src="' . $logos_dir . '/' . $image . '" alt="" /><br />';

		return ($rand_logo);
	}

	public function k_progress_bar($percent)
	{
		// $percent = number between 0 and 100 //

		$ss = "";

		// define these in css
		$start = '<b class="green">';   // green
		$middl = '<b class="orange">';  // orange
		$endss = '<b class="red">';     // red

		$tens = $percent / 10; // how many tens //

		if ($percent % 10)
		{
			$i = 1;
		}
		else
		{
			$i = 0;
		}

		for ($i; $i < ($percent / 10); $i++)
		{
			$ss .= '|';
		}

		$start .= $ss . '</b>';

		if ($percent % 10)
		{
			$start .= $middl . '|' . '</b>' . $endss;
		}
		else
		{
			$start .= '' . $endss;
		}

		while ($i++ < 10)
		{
			$start .= '|';
		}

		$start .= '</b>';

		return ' [' . $start . ']';
	}

	public function sgp_checksize($txt, $len)
	{
		if (strlen($txt) > $len)
		{
			$txt = truncate_string($txt, $len);
			$txt .= '...';
		}
		return($txt);
	}

	public function smiley_sort($a, $b)
	{
		if (strlen($a['code']) == strlen($b['code']))
		{
			return 0;
		}

		return (strlen($a['code']) > strlen($b['code'])) ? -1 : 1;
	}

	public function search_block_func()
	{
		$template->assign_vars([
			"L_SEARCH_ADV"     => $this->user->lang['SEARCH_ADV'],
			"L_SEARCH_OPTION"  => (!empty($this->portal_config['search_option_text'])) ? $this->portal_config['search_option_text'] : $this->board_config['sitename'],
			'U_SEARCH'         => append_sid("{$this->phpbb_root_path}search.$phpEx", 'keywords=' . urlencode($keywords)),
		]);
	}

	public function which_group($id)
	{
		// Get group name for this user
		$sql = 'SELECT group_name
			FROM ' . GROUPS_TABLE . '
			WHERE group_id = ' . (int) $id;

		$result = $db->sql_query($sql,300);

		$name = $this->db->sql_fetchfield('group_name');

		$this->db->sql_freeresult($result);

		return ($name);
	}

	public function process_for_vars($data)
	{
		$a = ['{', '}'];
		$b = ['',''];

		$replace = [];

		foreach ($this->k_resources as $search)
		{
			$find = $search;

			// convert to normal text //
			$search = str_replace($a, $b, $search);
			$search = strtolower($search);

			if (isset($this->k_config[$search]))
			{
				$replace = (isset($this->k_config[$search])) ? $this->k_config[$search] : '';
				$data = str_replace($find, $replace, $data);
			}
			else if (isset($this->config[$search]))
			{
				$replace = (isset($this->config[$search])) ? $this->config[$search] : '';
				$data = str_replace($find, $replace, $data);
			}
		}
		return($data);
	}

	public function get_user_data($id, $what = '')
	{
		if (!$id)
		{
			return($this->user->lang['NO_ID_GIVEN']);
		}

		// Get user info
		$sql = 'SELECT user_id, username, user_colour
			FROM ' . USERS_TABLE . '
			WHERE user_id = ' . (int) $id;

		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		switch ($what)
		{
			case 'name':
				return($row['username']);

			case 'full':
				return(get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']));

			default:
				return;
		}
	}

	public function portal_block_template($block_file)
	{
		$this->template->set_filenames(['block' => 'blocks/' . $block_file]);
		return $this->template->assign_display('block', true);
	}

	public function get_page_id($this_page_name)
	{
		if (is_array($this->k_pages))
		{
			foreach ($this->k_pages as $page)
			{
				if ($page['page_name'] == $this_page_name)
				{
					return($page['page_id']);
				}
			}
		}
		else
		{
			throw new \phpbbireland\portal\exception('Not in array [' . $this_page_name . ']');
		}

	}

	public function get_menu_lang_name($input)
	{
		// Basic error checking //
		if ($input == '')
		{
			return('');
		}

		$block_title = $input;
		$name = strtoupper($input);
		$name = str_replace(" ","_", $name);
		$block_title = (!empty($this->user->lang[$name])) ? $this->user->lang[$name] : $block_title;

		return($block_title);
	}

	public function s_get_vars_array()
	{
		//define('K_RESOURCES_TABLE',	$this->table_prefix . 'k_resources');

		$resources = [];

		$sql = 'SELECT * FROM ' . K_RESOURCES_TABLE  . ' ORDER BY word ASC';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$resources[] = $row['word'];
		}

		$db->sql_freeresult($result);
		return($resources);
	}

	public function s_get_vars()
	{
		//define('K_RESOURCES_TABLE',	$table_prefix . 'k_resources');

		$sql = "SELECT * FROM " . K_RESOURCES_TABLE  . " WHERE type = 'V' ORDER BY word ASC";

		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$template->assign_block_vars('adm_vars', [
				'VAR' => $row['word'],
			]);
		}
		$db->sql_freeresult($result);
	}

	public function generate_menus()
	{
		static $process = 0;

		$menu_image_path = $this->phpbb_root_path . 'ext/phpbbireland/portal/images/block_images/menu/';

		// process all menus at once //
		if ($process)
		{
			return;
		}

		$this->user->add_lang_ext('phpbbireland/portal', 'kiss_block_variables');

		$p_count = count($k_menus);
		$hash = $request->variable('hash', '');

		if (!function_exists('group_memberships'))
		{
			include($this->phpbb_root_path . 'includes/functions_user.'. $this->phpEx);
		}
		$memberships = [];
		$memberships = group_memberships(false, $this->user->data['user_id'], false);

		for ($i = 1; $i < $p_count + 1; $i++)
		{
			if (isset($this->k_menus[$i]['menu_type']))
			{
				$u_id = '';
				$isamp = '';

				$menu_view_groups = $this->k_menus[$i]['view_groups'];
				$menu_item_view_all = $this->k_menus[$i]['view_all'];

				// skip process if everyone can view this menus //
				if ($menu_item_view_all == 1)
				{
					$process_menu_item = true;
				}
				else
				{
					$process_menu_item = false;
				}

				if (!$process_menu_item)
				{
					$grps = explode(",", $menu_view_groups);

					if ($memberships)
					{
						foreach ($memberships as $member)
						{
							for ($j = 0; $j < count($grps); $j++)
							{
								if ($grps[$j] == $member['group_id'])
								{
									$process_menu_item = true;
								}
							}
						}
					}
				}

				if ($k_menus[$i]['append_uid'] == 1)
				{
					$isamp = '&amp';
					$u_id = $user->data['user_id'];
				}
				else
				{
					$u_id = '';
					$isamp = '';
				}

				if ($process_menu_item)
				{
					$name = strtoupper($this->k_menus[$i]['name']);
					$tmp_name = str_replace(' ','_', $name);
					$name = (!empty($user->lang[$tmp_name])) ? $this->user->lang[$tmp_name] : $this->k_menus[$i]['name'];

					if (strstr($this->k_menus[$i]['link_to'], 'http'))
					{
						$link = ($this->k_menus[$i]['link_to']) ? $this->k_menus[$i]['link_to'] : '';
					}
					else
					{
						if ($this->k_menus[$i]['append_sid'])
						{
							if (strpos($this->k_menus[$i]['link_to'], 'hash')) // allow Mark forums read //
							{
								$link = ($this->user->data['is_registered'] || $this->config['load_anon_lastread']) ? append_sid("{$this->phpbb_root_path}index.$this->phpEx", 'hash=' . generate_link_hash('global') . '&amp;mark=forums') : '';
							}
							else
							{
								$link = ($this->auth->acl_get('a_') && !empty($this->user->data['is_registered'])) ? append_sid("{$this->phpbb_root_path}{$this->k_menus[$i]['link_to']}", false, true, $this->user->session_id) : '';
							}
						}
						else
						{
							$link = ($this->k_menus[$i]['link_to']) ? append_sid("{$this->phpbb_root_path}" . $this->k_menus[$i]['link_to'] . $u_id) : '';
						}
					}

					$is_sub_heading = ($this->k_menus[$i]['sub_heading']) ? true : false;

					// we use js to manage open ibn tab //
					switch ($k_menus[$i]['extern'])
					{
						case 1:
							$link_option = 'rel="external"';
						break;

						case 2:
							$link_option = ' onclick="window.open(this.href); return false;"';
						break;

						default:
							$link_option = '';
						break;
					}

					// can be reduce later...
					if ($this->k_menus[$i]['menu_type'] == NAV_MENUS)
					{
						$template->assign_block_vars('portal_nav_menus_row', [
							'PORTAL_LINK_OPTION'	=> $link_option,
							'PORTAL_MENU_HEAD_NAME'	=> ($is_sub_heading) ? $name : '',
							'PORTAL_MENU_NAME' 		=> $name,
							'PORTAL_MENU_ICON'		=> ($this->k_menus[$i]['menu_icon']) ? '<img src="' . $menu_image_path . $this->k_menus[$i]['menu_icon'] . '" height="16" width="16" alt="" />' : '<img src="' . $menu_image_path . 'spacer.gif" height="15px" width="15px" alt="" />',
							'U_PORTAL_MENU_LINK' 	=> ($this->k_menus[$i]['sub_heading']) ? '' : $link,
							'S_SOFT_HR'				=> $this->k_menus[$i]['soft_hr'],
							'S_SUB_HEADING' 		=> ($this->k_menus[$i]['sub_heading']) ? true : false,
						]);
					}
					else if ($this->k_menus[$i]['menu_type'] == SUB_MENUS)
					{
						$template->assign_block_vars('portal_sub_menus_row', [
							'PORTAL_LINK_OPTION'	=> $link_option,
							'PORTAL_MENU_HEAD_NAME'	=> ($is_sub_heading) ? $name : '',
							'PORTAL_MENU_NAME' 		=> $name,
							'PORTAL_MENU_ICON'		=> ($this->k_menus[$i]['menu_icon']) ? '<img src="' . $menu_image_path . $this->k_menus[$i]['menu_icon'] . '" height="16" width="16" alt="" />' : '<img src="' . $menu_image_path . 'spacer.gif" height="15px" width="15px" alt="" />',
							'U_PORTAL_MENU_LINK' 	=> ($this->k_menus[$i]['sub_heading']) ? '' : $link,
							'S_SOFT_HR'				=> $this->k_menus[$i]['soft_hr'],
							'S_SUB_HEADING' 		=> ($this->k_menus[$i]['sub_heading']) ? true : false,
						]);
					}
					else if ($this->k_menus[$i]['menu_type'] == LINKS_MENUS)
					{
						$template->assign_block_vars('portal_link_menus_row', [
							'LINK_OPTION'					=> $link_option,
							'PORTAL_LINK_MENU_HEAD_NAME'	=> ($is_sub_heading) ? $name : '',
							'PORTAL_LINK_MENU_NAME'			=> ($is_sub_heading) ? '' : $name,
							'U_PORTAL_LINK_MENU_LINK'		=> ($is_sub_heading) ? '' : $link,
							'PORTAL_LINK_MENU_ICON'			=> ($this->k_menus[$i]['menu_icon'] == 'NONE') ? '' : '<img src="' . $menu_image_path . $this->k_menus[$i]['menu_icon'] . '" alt="" />',
							'S_SOFT_HR'						=> $this->k_menus[$i]['soft_hr'],
							'S_SUB_HEADING'					=> ($this->k_menus[$i]['sub_heading']) ? true : false,
						]);
					}
				}
			}
		}
		$process = 1;

		$template->assign_vars([
			'S_USER_LOGGED_IN'	=> ($this->user->data['user_id'] != ANONYMOUS) ? true : false,
			'U_INDEX'			=> append_sid("{$phpbb_root_path}index.$this->phpEx"),
			'U_PORTAL'			=> append_sid("{$phpbb_root_path}portal.$this->phpEx"),
		]);
	}
}