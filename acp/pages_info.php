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

class pages_info
{
	function module()
	{
		return [
			'filename' => '\phpbbireland\portal\acp\pages_module',
			'title'    => 'ACP_PAGES_TITLE',
			'modes'    => [
				'add'    => ['title' => 'ACP_K_PAGES_ADD',	  'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_PAGES'], 'display' => false],
				'delete' => ['title' => 'ACP_K_PAGES_DELETE', 'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_PAGES'], 'display' => false],
				'land'   => ['title' => 'ACP_K_PAGES_LAND',   'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_PAGES'], 'display' => false],
				'manage' => ['title' => 'ACP_K_PAGES_MANAGE', 'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_PAGES']]
			],
		];
	}
}
