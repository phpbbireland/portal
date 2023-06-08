<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2022 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbbireland\core\helpers;

class k_resources
{
	const OWNER_PUBLIC		= 0;
	const TYPE_RESOURCE		= 0;
	const STATUS_OPEN		= 0;
	const STATUS_LOCKED		= 1;

	public function get_owner($owner)
	{
		switch ($owner)
		{
			case 'public':
				return self::OWNER_PUBLIC;
		}
		throw new \phpbbireland\core\exception('k_resources [' . $owner . '] not found');
	}

	public function get_type($type)
	{
		switch ($type)
		{
			case 'k_config':
				return self::TYPE_CFG;
		}
		throw new \phpbbireland\core\exception('k_resources type [' . $type . '] not found');
	}

	public function get_status($status)
	{
		switch ($status)
		{
			case 'open':
			case 'unlocked':
				return self::STATUS_OPEN;

			case 'locked':
				return self::STATUS_LOCKED;
		}
		throw new \phpbbireland\core\exception('k_resources status [' . $status . '] not found');
	}
}
