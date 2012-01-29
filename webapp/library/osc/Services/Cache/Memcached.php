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

require_once 'osc/Services/Cache/Interface.php';

class Services_Cache_Memcached implements CacheService
{
	private $service;
	public function __construct() 
	{
		$this->service = new Memcached;

		$config = ClassLoader::getInstance()->getClassInstance( 'Config' );
		if( $config->hasConfig( 'memcached' ) )
		{
			$memcachedConfig = $config->getConfig( 'memcached' );
			foreach( $memcachedConfig['servers'] as $server )
			{
				$this->service->addServer( $server['host'], $server['port'] );
			}
		}
	}
	public function read($key) 
	{
		return $this->service->get($key);
	}
	public function write($key, $content) 
	{
		$this->service->set($key, $content, 3600);
	}
}

