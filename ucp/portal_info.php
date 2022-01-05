<?php
/**
*
* @package ucp (Kiss Portal Engine)
* @version $Id$
* @copyright (c) 2005-2022 phpbbireland
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace phpbbireland\portal\ucp;

class portal_info
{
	function module()
	{
		return [
			'filename'	=> '\phpbbireland\portal\ucp\portal_module',
			'title'     => 'UCP_PORTAL_TITLE',
			'modes'     => [
				'info'     => ['title' => 'UCP_K_BLOCKS_INFO',    'auth' => 'ext_phpbbireland/portal && acl_u_k_portal', 'cat' => ['UCP_K_BLOCKS']],
				'arrange'  => ['title' => 'UCP_K_BLOCKS_ARRANGE', 'auth' => 'ext_phpbbireland/portal && acl_u_k_portal', 'cat' => ['UCP_K_BLOCKS']],
				'edit'     => ['title' => 'UCP_K_BLOCKS_EDIT',    'auth' => 'ext_phpbbireland/portal && acl_u_k_portal', 'cat' => ['UCP_K_BLOCKS']],
				'delete'   => ['title' => 'UCP_K_BLOCKS_DELETE',  'auth' => 'ext_phpbbireland/portal && acl_u_k_portal', 'cat' => ['UCP_K_BLOCKS']],
				'width'    => ['title' => 'UCP_K_BLOCKS_WIDTH',   'auth' => 'ext_phpbbireland/portal && acl_u_k_portal', 'cat' => ['UCP_K_BLOCKS']],
			],
		];
	}

}
