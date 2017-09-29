<?php

/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/29/17
 * Time: 1:46 PM
 * To change this template use File | Settings | File Templates.
 */
use GeoIp2\Database\Reader;


class MaxMindParser extends IpParser
{

	public function getLocation($ip, $ipNumber)
	{
		try {
			$reader = new Reader(SiteConfig::current_site_config()->MaxMindDB()->getFullPath());
			if($record = $reader->city($ip)) {
				$data = $record->jsonSerialize();

				$subdivisions = array();
				if(!empty($data['subdivisions']) && is_array($data['subdivisions'])) foreach ($data['subdivisions'] as $subdivision) {
					$subdivisions[] = $subdivision['iso_code'];
				}
				$continentNames = array();
				if(!empty($data['continent']) && !empty($data['continent']['names']))
					$continentNames = $data['continent']['names'];
				$cityNames = array();
				if(!empty($data['city']) && !empty($data['city']['names']))
					$cityNames = $data['city']['names'];


				$location = array(
					'Country'			=> isset($data['country']) && isset($data['country']['iso_code']) ? $data['country']['iso_code'] : null,
					'SubDivisions'		=> $subdivisions,
					'Postal'			=> isset($data['postal']) && isset($data['postal']['code']) ? $data['postal']['code'] : null,
					'Lat'				=> isset($data['location']) && isset($data['location']['latitude']) ? $data['location']['latitude'] : null,
					'Long'				=> isset($data['location']) && isset($data['location']['longitude']) ? $data['location']['longitude'] : null,
					'ContinentNames'	=> $continentNames,
					'CityNames'			=> $cityNames
				);

				ContinentalContent::add_debug_message('<pre>' . print_r($location, 1) . '</pre>');

				return new ArrayData($location);
			}
		} catch (Exception $e) {
		}
		return null;
	}

}