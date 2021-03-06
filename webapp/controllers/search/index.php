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
class CWebSearch extends Controller_Default
{
	public function doGet( HttpRequest $req, HttpResponse $res ) 
	{
		$classLoader = $this->getClassLoader();

		$input = $this->getInput();
		$view = $this->getView();

		$this->mSearch = new \Osc\Model\Search;

		$classLoader->loadFile( 'helpers/premium' );
		osc_run_hook('before_search');
		$mCategories = new \Osc\Model\Category;

		$p_sCategory = Params::getParam('sCategory');
		if (!is_array($p_sCategory)) 
		{
			if ($p_sCategory == '') 
			{
				$p_sCategory = array();
			}
			else
			{
				$p_sCategory = explode(",", $p_sCategory);
			}
		}
		$p_sCityArea = Params::getParam('sCityArea');
		if (!is_array($p_sCityArea)) 
		{
			if ($p_sCityArea == '') 
			{
				$p_sCityArea = array();
			}
			else
			{
				$p_sCityArea = explode(",", $p_sCityArea);
			}
		}
		$p_sCity = Params::getParam('sCity');
		if (!is_array($p_sCity)) 
		{
			if ($p_sCity == '') 
			{
				$p_sCity = array();
			}
			else
			{
				$p_sCity = explode(",", $p_sCity);
			}
		}
		$p_sRegion = Params::getParam('sRegion');
		if (!is_array($p_sRegion)) 
		{
			if ($p_sRegion == '') 
			{
				$p_sRegion = array();
			}
			else
			{
				$p_sRegion = explode(",", $p_sRegion);
			}
		}
		$p_sCountry = Params::getParam('sCountry');
		if (!is_array($p_sCountry)) 
		{
			if ($p_sCountry == '') 
			{
				$p_sCountry = array();
			}
			else
			{
				$p_sCountry = explode(",", $p_sCountry);
			}
		}
		$p_sPattern = strip_tags( $input->getString( 'sPattern', '' ) );
		$p_sPattern = trim( $p_sPattern );
		$this->mSearch->setQueryString( $p_sPattern );
		if( osc_save_latest_searches() && !empty( $p_sPattern ) )
		{
			$latestModel = new \Osc\Model\SearchLatest;
			$latestModel->insert( $p_sPattern );
		}

		$page = $input->getInteger( 'iPage', 0 );
		$this->mSearch->setPage( $page );

		$p_sUser = strip_tags( $input->getString( 'sUser', '' ) );

		$p_bPic = Params::getParam('bPic');
		($p_bPic == 1) ? $p_bPic = 1 : $p_bPic = 0;
		$p_sPriceMin = $input->getFloat( 'sPriceMin' );
		$p_sPriceMax = $input->getFloat( 'sPriceMax');
		//WE CAN ONLY USE THE FIELDS RETURNED BY $this->mSearch->getAllowedColumnsForSorting()

		$this->setSearchOrder( $view, $input );

		$p_sFeed = $input->getString( 'sFeed' );
		if ($p_sFeed != '') 
		{
			$p_sPageSize = 1000;
		}
		$p_sShowAs = $input->getString( 'sShowAs' );
		$aValidShowAsValues = array('list', 'gallery');
		if (!in_array($p_sShowAs, $aValidShowAsValues)) 
		{
			$p_sShowAs = osc_default_show_as_at_search();
		}
		// search results: it's blocked with the maxResultsPerPage@search defined in t_preferences
		foreach ($p_sCategory as $category) 
		{
			$this->mSearch->addCategory($category);
		}
		//FILTERING CITY_AREA
		foreach ($p_sCityArea as $city_area) 
		{
			$this->mSearch->addCityArea($city_area);
		}
		$p_sCityArea = implode(", ", $p_sCityArea);
		//FILTERING CITY
		foreach ($p_sCity as $city) 
		{
			$this->mSearch->addCity($city);
		}
		$p_sCity = implode(", ", $p_sCity);
		//FILTERING REGION
		foreach ($p_sRegion as $region) 
		{
			$this->mSearch->addRegion($region);
		}
		$p_sRegion = implode(", ", $p_sRegion);
		//FILTERING COUNTRY
		foreach ($p_sCountry as $country) 
		{
			$this->mSearch->addCountry($country);
		}
		$p_sCountry = implode(", ", $p_sCountry);
		if ($p_sUser != '') 
		{
			$this->mSearch->fromUser(explode(",", $p_sUser));
		}
		if ($p_bPic) 
		{
			$this->mSearch->withPicture(true);
		}
		$this->mSearch->priceRange($p_sPriceMin, $p_sPriceMax);

		$defaultRowsPerPage = osc_default_results_per_page_at_search();
		$maxRowsPerPage = osc_max_results_per_page_at_search();
		$rowsPerPage = $input->getInteger( 'rowsPerPage', $defaultRowsPerPage );
		if( $rowsPerPage <= 0 )
		{
			$rowsPerPage = 10;
		}
		if( $rowsPerPage > $maxRowsPerPage )
			$rowsPerPage = $maxRowsPerPage;
		$this->mSearch->setRowsPerPage( $rowsPerPage );

		osc_run_hook('search_conditions', Params::getParamsAsArray());

		$premiums = array();//osc_get_premiums();
		$view->assign( 'premiums', $premiums );

		if (!Params::existParam('sFeed')) 
		{
			// RETRIEVE ITEMS AND TOTAL
			$aItems = $this->mSearch->doSearch();
			$iTotalItems = $this->mSearch->count();
			$iStart = $page * $rowsPerPage;
			$iEnd = min(($page + 1) * $rowsPerPage, $iTotalItems);
			$iNumPages = ceil($iTotalItems / $rowsPerPage );
			osc_run_hook('search', $this->mSearch);

			//$this->getView()->assign('non_empty_categories', $aCategories) ;
			$this->getView()->assign('search_start', $iStart);
			$this->getView()->assign('search_end', $iEnd);
			$this->getView()->assign('search_category', $p_sCategory);
			$this->getView()->assign('search_pattern', $p_sPattern);
			$this->getView()->assign('search_from_user', $p_sUser);
			$this->getView()->assign('search_total_pages', $iNumPages);
			$this->getView()->assign('search_page', $page );
			$this->getView()->assign('search_has_pic', $p_bPic);
			$this->getView()->assign('search_region', $p_sRegion);
			$this->getView()->assign('search_city', $p_sCity);
			$this->getView()->assign('search_price_min', $p_sPriceMin);
			$this->getView()->assign('search_price_max', $p_sPriceMax);
			$this->getView()->assign('search_total_items', $iTotalItems);
			$this->getView()->assign('items', $aItems);
			$this->getView()->assign('search_show_as', $p_sShowAs);
			$this->getView()->assign('search', $this->mSearch);
			$this->getView()->assign('search_alert', base64_encode(serialize($this->mSearch)));

			$view = $this->getView();
			if( 0 === $iTotalItems )
			{
				$view->setMetaRobots( array( 'noindex', 'nofollow' ) );
			}

			$this->setViewTitle( $view, $input );
			if( 0 < count( $aItems ) ) 
			{
				$item = $aItems[0];
				$itemCategory = $item['category_name'];
				$view->setMetaDescription( $itemCategory . ', ' . osc_highlight(strip_tags(osc_item_description( $item )), 140) . '..., ' . $itemCategory );
			}
	
			$pagination = new \Cuore\Web\Paginator;
			$searchUrls = $classLoader->getClassInstance( 'Url_Search' );
			$urlTemplate = $searchUrls->osc_update_search_url( array( 'iPage' => $pagination::PLACEHOLDER ) );
			$pagination->setSelectedPage( $page );
			$pagination->setUrlTemplate( $urlTemplate );
			$pagination->setItemsPerPage( $rowsPerPage );
			$pagination->setNumItems( $iTotalItems );
			$view->assign( 'pagination', $pagination );

			echo $view->render( 'search/results' );	
		}
		else
		{
			$this->mSearch->page(0, osc_num_rss_items());
			// RETRIEVE ITEMS AND TOTAL
			$iTotalItems = $this->mSearch->count();
			$aItems = $this->mSearch->doSearch();
			$this->getView()->assign('items', $aItems);
			if ($p_sFeed == '' || $p_sFeed == 'rss') 
			{
				$urls = $classLoader->getClassInstance( 'Url_Abstract' );
				$itemUrls = $classLoader->getClassInstance( 'Url_Item' );

				// FEED REQUESTED!
				$feed = new \Cuore\Feed\Rss\Writer;
				header('Content-type: text/xml; charset=utf-8');
				$feed->setTitle(__('Latest items added') . ' - ' . osc_page_title());
				$feed->setLink( $urls->getBaseUrl() );
				$feed->setDescription(__('Latest items added in') . ' ' . osc_page_title());
				if( count( $aItems ) > 0 ) 
				{
					foreach( $aItems as $item )
					{
						if (osc_count_item_resources( $item ) > 0) 
						{
							osc_has_item_resources();
							$feed->addItem(array('title' => osc_item_title( $item ), 'link' => htmlentities($itemUrls->osc_item_url( $item )), 'description' => osc_item_description( $item ), 'pub_date' => osc_item_pub_date( $item ), 'image' => array('url' => htmlentities(osc_resource_thumbnail_url( $item )), 'title' => osc_item_title( $item ), 'link' => htmlentities($itemUrls->osc_item_url( $item )))));
						}
						else
						{
							$feed->addItem(array('title' => osc_item_title( $item ), 'link' => htmlentities($itemUrls->osc_item_url( $item )), 'description' => osc_item_description( $item ), 'pub_date' => osc_item_pub_date( $item )));
						}
					}
				}
				osc_run_hook('feed', $feed);
				$feed->dumpXML();
			}
			else
			{
				osc_run_hook('feed_' . $p_sFeed, $aItems);
			}
		}
	}

	protected function setSearchOrder( View_Default $view, \Cuore\Input\Http $input )
	{
		$p_sOrder = $input->getString( 'sOrder' );
		if( !in_array( $p_sOrder, $this->mSearch->getAllowedColumnsForSorting() ) )
		{
			$p_sOrder = osc_default_order_field_at_search();
		}
		$this->mSearch->setOrderColumn( $p_sOrder );
		$view->assign('search_order', $p_sOrder);

		$p_iOrderType = $input->getString( 'sOrderType' );
		if( !in_array( $p_iOrderType, $this->mSearch->getAllowedTypesForSorting() ) )
		{
			$p_iOrderType = osc_default_order_type_at_search();
		}
		$this->mSearch->setOrderDirection( $p_iOrderType );
		$view->assign('search_order_type', $p_iOrderType);
	}

	protected function setViewTitle( View_Html $view, \Cuore\Input\Http $input )
	{
		$region = Params::getParam('sRegion');
		$city = Params::getParam('sCity');
		$pattern = Params::getParam('sPattern');
		$category = osc_search_category_id();
		$category = ((count($category) == 1) ? $category[0] : '');
		$s_page = '';
		$i_page = $input->getInteger( 'iPage', 0 );
		if ($i_page != '' && $i_page > 0) 
		{
			$s_page = __('page', 'modern') . ' ' . ($i_page + 1) . ' - ';
		}
		$b_show_all = ($region == '' && $city == '' & $pattern == '' && $category == '');
		$b_category = ($category != '');
		$b_pattern = ($pattern != '');
		$b_city = ($city != '');
		$b_region = ($region != '');
		if ($b_show_all) 
		{
			$text = __('Show all items', 'modern') . ' - ' . $s_page . osc_page_title();
		}
		$result = '';
		if ($b_pattern) 
		{
			$result.= $pattern . ' &raquo; ';
		}
		if ($b_category) 
		{
			$list = array();
			$categoryModel = new \Osc\Model\Category;
			$aCategories = $categoryModel->toRootTree($category);
			if (count($aCategories) > 0) 
			{
				foreach ($aCategories as $single) 
				{
					$list[] = $single['s_name'];
				}
				$result.= implode(' &raquo; ', $list) . ' &raquo; ';
			}
		}
		if ($b_city) 
		{
			$result.= $city . ' &raquo; ';
		}
		if ($b_region) 
		{
			$result.= $region . ' &raquo; ';
		}
		$result = preg_replace('|\s?&raquo;\s$|', '', $result);
		if ($result == '') 
		{
			$result = __('Search', 'modern');
		}
		$text = $result . ' - ' . $s_page . osc_page_title();
		$view->setTitle( $text );
	}
}


