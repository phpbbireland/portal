<?php
/**
*
* @package phpbbireland 1.0.2
* @copyright (c) 2013 phpbbireland
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbireland\portal\includes;

class modules_helper
{
	/**
	* Auth object
	* @var \phpbb\auth\auth
	*/
	protected $auth;

	/**
	* phpBB config
	* @var \phpbb\config\config
	*/
	protected $config;

	/**
	* phpBB request
	* @var \phpbb\request\request
	*/
	protected $request;

	/**
	* Constructor
	* NOTE: The parameters of this method must match in order and type with
	* the dependencies defined in the services.yml file for this service.
	* @param \phpbb\auth\auth $auth Auth object
	* @param \phpbb\config\config $config phpBB config
	* @param \phpbb\request\request $request phpBB request
	*/
	public function __construct($auth, $config, $request)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->request = $request;
	}

	/**
	* Get an array of disallowed forums
	*
	* @param bool $disallow_access Whether the array for disallowing access
	*			should be filled
	* @return array Array of forums the user is not allowed to access
	*/
	public function get_disallowed_forums($disallow_access)
	{
		if ($disallow_access == true)
		{
			$disallow_access = array_unique(array_keys($this->auth->acl_getf('!f_read', true)));
		}
		else
		{
			$disallow_access = [];
		}

		return $disallow_access;
	}

	/**
	* Generate select box
	*
	* @param string	$key			Key of select box
	* @param array	$select_ary		Array of select box options
	* @param array	$selected_options	Array of selected options
	*
	* @return string HTML code of select box
	* @access public
	*/
	public function generate_select_box($key, $select_ary, $selected_options)
	{
		// Build options
		$options = '<select id="' . $key . '" name="' . $key . '[]" multiple="multiple">';
		foreach ($select_ary as $id => $option)
		{
			$options .= '<option value="' . $option['value'] . '"' . ((in_array($option['value'], $selected_options)) ? ' selected="selected"' : '') . (!empty($option['disabled']) ? ' disabled="disabled" class="disabled-option"' : '') . '>' . $option['title'] . '</option>';
		}
		$options .= '</select>';

		return $options;
	}

	/**
	* Generate forum select box
	*
	* @param string $value	Value of select box
	* @param string $key	Key of select box
	*
	* @return string HTML code of select box
	* @access public
	*/
	public function generate_forum_select($value, $key)
	{
		$forum_list = make_forum_select(false, false, true, true, true, false, true);

		$selected_options = $select_ary = [];
		if (isset($this->config[$key]) && strlen($this->config[$key]) > 0)
		{
			$selected_options = explode(',', $this->config[$key]);
		}

		// Build forum options
		foreach ($forum_list as $f_id => $f_row)
		{
			$select_ary[] = [
				'value'		=> $f_id,
				'title'		=> $f_row['padding'] . $f_row['forum_name'],
				'disabled'	=> $f_row['disabled'],
			];
		}

		return $this->generate_select_box($key, $select_ary, $selected_options);
	}

	/**
	* Store selected forums
	*
	* @param string $key Key name
	*
	* @return null
	* @access public
	*/
	public function store_selected_forums($key)
	{
		// Get selected extensions
		$values = $this->request->variable($key, [0 => '']);
		$news = implode(',', $values);
		$this->config->set($key, $news);
	}
}
