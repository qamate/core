<?php
/**
 * OpenSourceClassifieds – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2012 OpenSourceClassifieds
 *
 * This program is free software: you can redistribute it and/or modify it under the terms
 * of the GNU Affero General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
?>

                <div id="content_header" class="content_header">
                    <div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url('images/tools-icon.png'); ?>" title="" alt=""/>
                    </div>
                    <div id="content_header_arrow">&raquo; <?php _e('Import data'); ?></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <div id="settings_form" style="border: 1px solid #ccc; background: #eee; ">
                    <div style="padding: 20px;">
                        <p>
                            <?php _e("You can modify your database to add, remove or modify its data here. It's usually used to import countries, regions and cities"); ?>.
                        </p>
                        <form action="<?php echo osc_admin_base_url(true); ?>" enctype="multipart/form-data" method="post">
                            <input type="hidden" name="action" value="import_post" />
                            <input type="hidden" name="page" value="tools" />
                            <p>
                                <label for="sql"><?php _e('Data'); ?> (.sql)</label>
                                <input type="file" name="sql" id="sql" />
                            </p>
                            <input id="button_save" type="submit" value="<?php _e('Import data'); ?>" />
                        </form>
                    </div>
                </div>

