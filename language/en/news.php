<?php
/**
*
* Kiss Portal extension for the phpBB Forum Software package.
*
* @copyright (c) 2022 Michael O’Toole <http://www.phpbbireland.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

//
// If the portal is disabled we load this file to handle left over lang vars
//

$lang = array_merge($lang, [
	'ANNOUNCEMENTS_AND_NEWS'  => 'News and Announcements',
	'BLOCKS_DISABLED'         => 'Portal blocks are currently disabled!',
	'POST_NEWS'				  => 'News',
	'POST_NEWS_GLOBAL'		  => 'Global News',
	'POST_NEW_IMG'			  => 'Post New',
	'POST_NEW_HOT_IMG'		  => 'Post New Hot',
	'VIEW_TOPIC_NEWS'		  => 'Post News: ',
]);
