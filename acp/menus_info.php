<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2022 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbbireland\portal\acp;

class menus_info
{
	function module()
	{
		return [
			'filename'	=> '\phpbbireland\portal\acp\menus_module',
			'title'		=> 'ACP_MENUS_TITLE',
			'modes'		=> [
				'add'       => ['title' => 'ACP_K_MENU_ADD',         'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_MENUS']],
				'nav'       => ['title' => 'ACP_K_MENU_MAIN',        'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_MENUS']],
				'sub'       => ['title' => 'ACP_K_MENU_SUB',         'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_MENUS']],
				'link'      => ['title' => 'ACP_K_MENU_LINKS',       'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_MENUS']],
				'edit'      => ['title' => 'ACP_K_MENU_EDIT',        'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_MENUS'], 'display' => false],
				'delete'    => ['title' => 'ACP_K_MENU_DELETE',      'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_MENUS'], 'display' => false],
				'up'        => ['title' => 'ACP_K_UP',               'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_MENUS'], 'display' => false],
				'down'      => ['title' => 'ACP_K_DOWN',             'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_MENUS'], 'display' => false],
				'all'       => ['title' => 'ACP_K_MENU_ALL',         'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_MENUS']],
				'unalloc'   => ['title' => 'ACP_K_MENU_UNALLOCATED', 'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_MENUS']]
			],
		];
	}
}
