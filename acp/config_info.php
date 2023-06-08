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

class config_info
{
	function module()
	{
		return [
			'filename'	=> '\phpbbireland\portal\acp\config_module',
			'title'		=> 'ACP_PORTAL_TITLE',
			'modes'		=> [
				'config_portal'	=> ['title' => 'ACP_PORTAL_CONFIG', 'auth' => 'ext_phpbbireland/portal && acl_a_k_portal',	'cat' => ['ACP_CONFIG']],
			],
		];
	}
}
