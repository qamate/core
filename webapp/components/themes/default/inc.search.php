<?php
/*
 *      OpenSourceClassifieds – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2011 OpenSourceClassifieds
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
$sQuery = __('Enter your search terms here', 'modern');
?>

<script type="text/javascript">
var sQuery = '<?php echo $sQuery; ?>' ;
</script>
<script type="text/javascript" src="/static/scripts/search.js"></script>

<form action="<?php echo osc_base_url(true); ?>" method="get" class="search" onsubmit="javascript:return doSearch();">
    <input type="hidden" name="page" value="search" />
    <fieldset class="main">
        <input type="text" name="sPattern"  id="query" value="<?php echo (osc_search_pattern() != '') ? osc_search_pattern() : $sQuery; ?>" />
	<?php if (osc_count_categories()): ?>
            <?php osc_categories_select('sCategory', null, __('Select a category', 'modern')); ?>
	<?php endif; ?>
        <button type="submit"><?php _e('Search', 'modern'); ?></button>
    </fieldset>
</form>
