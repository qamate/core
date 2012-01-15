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
class CAdminStats extends AdminSecBaseModel
{
	//specific for this class
	function __construct() 
	{
		parent::__construct();
		//specific things for this class
		
	}
	//Business Layer...
	function doModel() 
	{
		parent::doModel();
		//specific things for this class
		switch ($this->action) 
		{
		case 'reports': // manage stats view
			$reports = array();
			if (Params::getParam('type_stat') == 'week') 
			{
				$stats_reports = Stats::newInstance()->new_reports_count(date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 70, date("Y"))), 'week');
				for ($k = 10; $k >= 0; $k--) 
				{
					$reports[date('W', mktime(0, 0, 0, date("m"), date("d"), date("Y"))) - $k]['views'] = 0;
					$reports[date('W', mktime(0, 0, 0, date("m"), date("d"), date("Y"))) - $k]['spam'] = 0;
					$reports[date('W', mktime(0, 0, 0, date("m"), date("d"), date("Y"))) - $k]['repeated'] = 0;
					$reports[date('W', mktime(0, 0, 0, date("m"), date("d"), date("Y"))) - $k]['bad_classified'] = 0;
					$reports[date('W', mktime(0, 0, 0, date("m"), date("d"), date("Y"))) - $k]['offensive'] = 0;
					$reports[date('W', mktime(0, 0, 0, date("m"), date("d"), date("Y"))) - $k]['expired'] = 0;
				}
			}
			else if (Params::getParam('type_stat') == 'month') 
			{
				$stats_reports = Stats::newInstance()->new_reports_count(date('Y-m-d', mktime(0, 0, 0, date("m") - 10, date("d"), date("Y"))), 'month');
				for ($k = 10; $k >= 0; $k--) 
				{
					$reports[date('F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y"))) ]['views'] = 0;
					$reports[date('F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y"))) ]['spam'] = 0;
					$reports[date('F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y"))) ]['repeated'] = 0;
					$reports[date('F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y"))) ]['bad_classified'] = 0;
					$reports[date('F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y"))) ]['offensive'] = 0;
					$reports[date('F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y"))) ]['expired'] = 0;
				}
			}
			else
			{
				$stats_reports = Stats::newInstance()->new_reports_count(date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 10, date("Y"))), 'day');
				for ($k = 10; $k >= 0; $k--) 
				{
					$reports[date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y"))) ]['views'] = 0;
					$reports[date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y"))) ]['spam'] = 0;
					$reports[date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y"))) ]['repeated'] = 0;
					$reports[date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y"))) ]['bad_classified'] = 0;
					$reports[date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y"))) ]['offensive'] = 0;
					$reports[date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y"))) ]['expired'] = 0;
				}
			}
			$max = array();
			$max['views'] = 0;
			$max['other'] = 0;
			foreach ($stats_reports as $report) 
			{
				$reports[$report['d_date']]['views'] = $report['views'];
				$reports[$report['d_date']]['spam'] = $report['spam'];
				$reports[$report['d_date']]['repeated'] = $report['repeated'];
				$reports[$report['d_date']]['bad_classified'] = $report['bad_classified'];
				$reports[$report['d_date']]['offensive'] = $report['offensive'];
				$reports[$report['d_date']]['expired'] = $report['expired'];
				if ($report['views'] > $max['views']) 
				{
					$max['views'] = $report['views'];
				}
				if ($report['spam'] > $max['other']) 
				{
					$max['other'] = $report['spam'];
				}
				if ($report['repeated'] > $max['other']) 
				{
					$max['other'] = $report['repeated'];
				}
				if ($report['bad_classified'] > $max['other']) 
				{
					$max['other'] = $report['bad_classified'];
				}
				if ($report['offensive'] > $max['other']) 
				{
					$max['other'] = $report['offensive'];
				}
				if ($report['expired'] > $max['other']) 
				{
					$max['other'] = $report['expired'];
				}
			}
			$this->_exportVariableToView("reports", $reports);
			$this->_exportVariableToView("max", $max);
			$this->doView("stats/reports.php");
			break;

		case 'comments': // manage stats view
			$comments = array();
			if (Params::getParam('type_stat') == 'week') 
			{
				$stats_comments = Stats::newInstance()->new_comments_count(date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") - 70, date("Y"))), 'week');
				for ($k = 10; $k >= 0; $k--) 
				{
					$comments[date('W', mktime(0, 0, 0, date("m"), date("d"), date("Y"))) - $k] = 0;
				}
			}
			else if (Params::getParam('type_stat') == 'month') 
			{
				$stats_comments = Stats::newInstance()->new_comments_count(date('Y-m-d H:i:s', mktime(0, 0, 0, date("m") - 10, date("d"), date("Y"))), 'month');
				for ($k = 10; $k >= 0; $k--) 
				{
					$comments[date('F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y"))) ] = 0;
				}
			}
			else
			{
				$stats_comments = Stats::newInstance()->new_comments_count(date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") - 10, date("Y"))), 'day');
				for ($k = 10; $k >= 0; $k--) 
				{
					$comments[date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - $k, date("Y"))) ] = 0;
				}
			}
			$max = 0;
			foreach ($stats_comments as $comment) 
			{
				$comments[$comment['d_date']] = $comment['num'];
				if ($comment['num'] > $max) 
				{
					$max = $comment['num'];
				}
			}
			$this->_exportVariableToView("comments", $comments);
			$this->_exportVariableToView("latest_comments", Stats::newInstance()->latest_comments());
			$this->_exportVariableToView("max", $max);
			$this->doView("stats/comments.php");
			break;

		default:
		case 'items': // manage stats view
			$items = array();
			$reports = array();
			if (Params::getParam('type_stat') == 'week') 
			{
				$stats_items = Stats::newInstance()->new_items_count(date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") - 70, date("Y"))), 'week');
				$stats_reports = Stats::newInstance()->new_reports_count(date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 70, date("Y"))), 'week');
				for ($k = 10; $k >= 0; $k--) 
				{
					$reports[date('W', mktime(0, 0, 0, date("m"), date("d"), date("Y"))) - $k]['views'] = 0;
					$items[date('W', mktime(0, 0, 0, date("m"), date("d"), date("Y"))) - $k] = 0;
				}
			}
			else if (Params::getParam('type_stat') == 'month') 
			{
				$stats_items = Stats::newInstance()->new_items_count(date('Y-m-d H:i:s', mktime(0, 0, 0, date("m") - 10, date("d"), date("Y"))), 'month');
				$stats_reports = Stats::newInstance()->new_reports_count(date('Y-m-d', mktime(0, 0, 0, date("m") - 10, date("d"), date("Y"))), 'month');
				for ($k = 10; $k >= 0; $k--) 
				{
					$reports[date('F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y"))) ]['views'] = 0;
					$items[date('F', mktime(0, 0, 0, date("m") - $k, date("d"), date("Y"))) ] = 0;
				}
			}
			else
			{
				$stats_items = Stats::newInstance()->new_items_count(date('Y-m-d H:i:s', mktime(0, 0, 0, date("m"), date("d") - 10, date("Y"))), 'day');
				$stats_reports = Stats::newInstance()->new_reports_count(date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 10, date("Y"))), 'day');
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
			$this->_exportVariableToView("reports", $reports);
			$this->_exportVariableToView("items", $items);
			$this->_exportVariableToView("latest_items", Stats::newInstance()->latest_items());
			$this->_exportVariableToView("max", $max);
			$this->_exportVariableToView("max_views", $max_views);
			$this->doView("stats/items.php");
			break;

		}
	}
	//hopefully generic...
	function doView($file) 
	{
		osc_current_admin_theme_path($file);
		Session::newInstance()->_clearVariables();
	}
}