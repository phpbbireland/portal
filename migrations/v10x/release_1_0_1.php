<?php
/**
*
* @package migration
* @copyright (c) 2022 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License v2
*
*/

namespace phpbbireland\portal\migrations\v10x;

class release_1_0_1 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return ['\phpbbireland\portal\migrations\v10x\release_1_0_0'];
	}

	public function update_data()
	{
		return [
			['config.update', ['portal_version', '1.0.1']],
			['config.update', ['portal_build', '310-002']],
			['custom', [[$this, 'seed_db']]],
		];
	}

	public function update_schema()
	{
		return [

			'add_tables' => [
				$this->table_prefix . 'k_link_images' => [
					'COLUMNS' => [
						'link'			=> ['VCHAR', ''],
						'url'			=> ['VCHAR', ''],
						'image'			=> ['VCHAR', ''],
						'active'		=> ['BOOL', '1'],
						'open_in_tab'	=> ['BOOL', '1'],
					],
					'PRIMARY_KEY'	=> 'link',
				],
			],
		];
	}


	public function revert_schema()
	{
		return [
			'drop_tables'	=> [
				$this->table_prefix . 'k_link_images',
			],
		];
	}

	public function seed_db()
	{
		$links_sql = [
			[
				'link'			=> 'Kiss Portal',
				'url'			=> 'www.phpbbireland.com',
				'image'			=> 'www.phpbbireland.gif',
				'active'		=> '1',
				'open_in_tab'	=> '1',
			],
			[
				'link'			=> 'phpBB',
				'url'			=> 'www.phpbb.com',
				'image'			=> 'www.phpbb.gif',
				'active'		=> '1',
				'open_in_tab'	=> '1',
			],
			[
				'link'			=> 'sourceforge',
				'url'			=> 'sourceforge.net',
				'image'			=> 'sourceforge.gif',
				'active'		=> '1',
				'open_in_tab'	=> '1',
			],
		];
		$this->db->sql_multi_insert($this->table_prefix . 'k_link_images', $links_sql);
	}
}
