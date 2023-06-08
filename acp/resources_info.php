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

class resources_info
{
	function module()
	{
		return [
			'filename' => '\phpbbireland\portal\acp\resources_module',
			'title'    => 'ACP_RESOURCES_TITLE',
			'modes'    => [
				'select' => ['title' => 'ACP_K_RESOURCES', 'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_TOOLS']],
			],
		];
	}
}
