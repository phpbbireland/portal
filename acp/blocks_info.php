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

class blocks_info
{
	function module()
	{
		return [
			'filename'	=> '\phpbbireland\portal\acp\blocks_module',
			'title'		=> 'ACP_BLOCKS_TITLE',
			'modes'		=> [
				'add'		=> ['title' => 'ACP_K_BLOCKS_ADD',         'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_BLOCKS']],
				'edit'		=> ['title' => 'ACP_K_BLOCKS_EDIT',        'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_BLOCKS'], 'display' => false],
				'delete'	=> ['title' => 'ACP_K_BLOCKS_DELETE',      'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_BLOCKS'], 'display' => false],
				'up'		=> ['title' => 'ACP_K_UP',                 'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_BLOCKS'], 'display' => false],
				'down'		=> ['title' => 'ACP_K_DOWN',               'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_BLOCKS'], 'display' => false],
				'reindex'	=> ['title' => 'ACP_K_BLOCKS_REINDEX',     'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_BLOCKS'], 'display' => false],
				'L'			=> ['title' => 'ACP_K_PAGE_LEFT_BLOCKS',   'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_BLOCKS']],
				'C'			=> ['title' => 'ACP_K_PAGE_CERTRE_BLOCKS', 'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_BLOCKS']],
				'R'			=> ['title' => 'ACP_K_PAGE_RIGHT_BLOCKS',  'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_BLOCKS']],
				'manage'	=> ['title' => 'ACP_K_BLOCKS_MANAGE',      'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_BLOCKS']],
				'reset'		=> ['title' => 'ACP_K_BLOCKS_RESET',       'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_BLOCKS']],
				'config'	=> ['title' => 'ACP_BLOCK_CONFIG',         'auth' => 'ext_phpbbireland/portal && acl_a_k_portal', 'cat' => ['ACP_K_BLOCKS']]
			],
		];
	}
}
