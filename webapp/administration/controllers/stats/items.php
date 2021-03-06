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
class CAdminStats extends Controller_Administration
{
	public function doGet( HttpRequest $req, HttpResponse $res )
	{
		$items = array();
		$reports = array();
		if (Params::getParam('type_stat') == 'week') 
		{
			$stats_items = ClassLoader::getInstance()->getClassInstance( 'Model_Stats' )->new_items_count(date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") - 70, date("Y"))), 'week');
			$stats_reports = ClassLoader::getInstance()->getClassInstance( 'Model_Stats' )->new_reports_count(date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 70, date("Y"))), 'week');
			for ($k = 10; $k >= 0; $k--) 
			{
				$reports[date('W', mktime(0, 0, 0, date("m"), date("d"), date("Y"))) - $k]['views'] = 0;
				$items[date('W', mktime(0, 0, 0, date("m"), date("d"), date("Y"))) - $k] = 0;
			}
		}
		else if (Params::getParam('type_stat') == 'month') 
		{
			$stats_items = ClassLoader::getInstance()->getClassInstance( 'Model_Stats' )->new_items_count(date('Y-m-d H:i:s', mktime(0, 0, 0, date("m") - 10, date("d"), date("Y"))), 'month');
			$stats_reports = ClassLoader::getInstance()->getClassInstance( 'Model_Stats' )->new_reports_count(date('Y-m-d', mktime(0, 0, 0, date("m") - 10, date("d"), date("Y"))), 'month');
			for ($k = 10; $k >= 0; $k--) 
			{
				$reports[date('F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y"))) ]['views'] = 0;
				$items[date('F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y"))) ] = 0;
			}
		}
		else
		{
			$stats_items = ClassLoader::getInstance()->getClassInstance( 'Model_Stats' )->new_items_count(date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") - 10, date("Y"))), 'day');
			$stats_reports = ClassLoader::getInstance()->getClassInstance( 'Model_Stats' )->new_reports_count(date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 10, date("Y"))), 'day');
			for ($k = 10; $k >= 0; $k--) 
			{
				$reports[date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y"))) ]['views'] = 0;
				$items[date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y"))) ] = 0;
			}
		}
		$max = 0;
		foreach ($stats_items as $item) 
		{
			$items[$item['d_date']] = $item['num'];
			if ($item['num'] > $max) 
			{
				$max = $item['num'];
			}
		}
		$max_views = 0;
		foreach ($stats_reports as $report) 
		{
			$reports[$report['d_date']]['views'] = $report['views'];
			if ($report['views'] > $max_views) 
			{
				$max_views = $report['views'];
			}
		}
		$this->getView()->assign("reports", $reports);
		$this->getView()->assign("items", $items);
		$this->getView()->assign("latest_items", ClassLoader::getInstance()->getClassInstance( 'Model_Stats' )->latest_items());
		$this->getView()->assign("max", $max);
		$this->getView()->assign("max_views", $max_views);
		$this->doView("stats/items.php");
	}
}

