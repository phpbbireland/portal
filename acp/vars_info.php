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

class vars_info
{
	function module()
	{
		return [
			'filename' => '\phpbbireland\portal\acp\vars_module',
			'title'    => 'ACP_VARS_TITLE',
			'modes'    => [
				'manage'  => ['title' => 'ACP_VARS_CONFIG', 'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_PORTAL']],
			],
		];
	}
}
