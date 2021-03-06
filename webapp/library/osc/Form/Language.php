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
class Form_Language extends Form
{
	public function primary_input_hidden($locale) 
	{
		parent::generic_input_hidden("pk_c_code", $locale["pk_c_code"]);
	}
	public function name_input_text($locale = null) 
	{
		parent::generic_input_text("s_name", (isset($locale)) ? $locale['s_name'] : "");
	}
	public function short_name_input_text($locale = null) 
	{
		parent::generic_input_text("s_short_name", (isset($locale)) ? $locale['s_short_name'] : "");
	}
	public function description_input_text($locale = null) 
	{
		parent::generic_input_text("s_description", (isset($locale)) ? $locale['s_description'] : "");
	}
	public function currency_format_input_text($locale = null) 
	{
		parent::generic_input_text("s_currency_format", (isset($locale)) ? $locale['s_currency_format'] : "");
	}
	public function dec_point_input_text($locale = null) 
	{
		parent::generic_input_text("s_dec_point", (isset($locale)) ? $locale['s_dec_point'] : "");
	}
	public function num_dec_input_text($locale = null) 
	{
		parent::generic_input_text("i_num_dec", (isset($locale)) ? $locale['i_num_dec'] : "");
	}
	public function thousands_sep_input_text($locale = null) 
	{
		parent::generic_input_text("s_thousands_sep", (isset($locale)) ? $locale['s_thousands_sep'] : "");
	}
	public function date_format_input_text($locale = null) 
	{
		parent::generic_input_text("s_date_format", (isset($locale)) ? $locale['s_date_format'] : "");
	}
	public function description_textarea($locale = null) 
	{
		parent::generic_textarea("s_stop_words", $locale['s_stop_words']);
	}
	public function enabled_input_checkbox($locale = null) 
	{
		parent::generic_input_checkbox("b_enabled", "1", ($locale["b_enabled"] == 1) ? true : false);
	}
	public function enabled_bo_input_checkbox($locale = null) 
	{
		parent::generic_input_checkbox("b_enabled_bo", "1", ($locale["b_enabled_bo"] == 1) ? true : false);
	}
}
