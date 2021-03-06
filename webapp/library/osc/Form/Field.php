<?php
/*
 *      OpenSourceClassifieds – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2012 OpenSourceClassifieds
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
class Form_Field extends Form
{
	public function primary_input_hidden($field = null) 
	{
		if (isset($field['pk_i_id'])) 
		{
			parent::generic_input_hidden("id", $field["pk_i_id"]);
		}
	}
	public function name_input_text($field = null) 
	{
		parent::generic_input_text("s_name", (isset($field) && isset($field["s_name"])) ? $field["s_name"] : "", null, false);
	}
	public function options_input_text($field = null) 
	{
		parent::generic_input_text("s_options", (isset($field) && isset($field["s_options"])) ? $field["s_options"] : "", null, false);
	}
	public function type_select($field = null) 
	{
?>
            <select name="field_type" id="field_type">
                <option value="TEXT" <?php
		if ($field['e_type'] == "TEXT") 
		{
			echo 'selected="selected"';
		}; ?>>TEXT</option>
                <option value="TEXTAREA" <?php
		if ($field['e_type'] == "TEXTAREA") 
		{
			echo 'selected="selected"';
		}; ?>>TEXTAREA</option>
                <option value="DROPDOWN" <?php
		if ($field['e_type'] == "DROPDOWN") 
		{
			echo 'selected="selected"';
		}; ?>>DROPDOWN</option>
                <option value="RADIO" <?php
		if ($field['e_type'] == "RADIO") 
		{
			echo 'selected="selected"';
		}; ?>>RADIO</option>
            </select>
            <?php
	}
	public function meta($field = null) 
	{
		if ($field != null) 
		{
			if (ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('meta_' . $field['pk_i_id']) != "") 
			{
				$field['s_value'] = ClassLoader::getInstance()->getClassInstance( 'Session' )->_getForm('meta_' . $field['pk_i_id']);
			}
			echo '<label for="meta_' . $field['s_slug'] . '">' . $field['s_name'] . ': </label>';
			if ($field['e_type'] == "TEXTAREA") 
			{
				echo '<textarea id="meta_' . $field['s_slug'] . '" name="meta[' . $field['pk_i_id'] . ']" rows="10">' . ((isset($field) && isset($field["s_value"])) ? $field["s_value"] : "") . '</textarea>';
			}
			else if ($field['e_type'] == "DROPDOWN") 
			{
				if (isset($field) && isset($field['s_options'])) 
				{
					$options = explode(",", $field['s_options']);
					if (count($options) > 0) 
					{
						echo '<select name="meta[' . $field['pk_i_id'] . ']" id="meta_' . $field['s_slug'] . '">';
						foreach ($options as $option) 
						{
							echo '<option value="' . $option . '" ' . ($field['s_value'] == $option ? 'selected="selected"' : '') . '>' . $option . '</option>';
						}
						echo '</select>';
					}
				}
			}
			else if ($field['e_type'] == "RADIO") 
			{
				if (isset($field) && isset($field['s_options'])) 
				{
					$options = explode(",", $field['s_options']);
					if (count($options) > 0) 
					{
						echo '<ul style="float:left;" >';
						foreach ($options as $key => $option) 
						{
							echo '<li><input type="radio" name="meta[' . $field['pk_i_id'] . ']" id="meta_' . $field['s_slug'] . '_' . $key . '" value="' . $option . '" ' . ($field['s_value'] == $option ? 'checked' : '') . '/><label style="float:none;" for="meta_' . $field['s_slug'] . '_' . $key . '">' . $option . '</label></li>';
						}
						echo '</ul>';
					}
				}
			}
			else
			{
				echo '<input id="meta_' . $field['s_slug'] . '" type="text" name="meta[' . $field['pk_i_id'] . ']" value="' . htmlentities((isset($field) && isset($field["s_value"])) ? $field["s_value"] : "", ENT_COMPAT, "UTF-8") . '" ';
				echo '/>';
			}
		}
	}
	public function meta_fields_input($catId = null, $itemId = null) 
	{
		$fields = Field::newInstance()->findByCategoryItem($catId, $itemId);
		if (count($fields) > 0) 
		{
			echo '<div class="meta_list">';
			foreach ($fields as $field) 
			{
				echo '<div class="meta">';
				FieldForm::meta($field);
				echo '</div>';
			}
			echo '</div>';
		}
	}
}
