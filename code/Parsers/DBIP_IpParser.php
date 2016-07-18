<?php

/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 7/18/16
 * Time: 8:45 AM
 * To change this template use File | Settings | File Templates.
 */
class DBIP_IpParser extends IpParser
{

	private static $api = '';

	public function getLocation($ip, $ipNumber)
	{
		$api = Config::inst()->get('DBIP_IpParser', 'api');
		$url = "http://api.db-ip.com/v2/{$api}/{$ip}";
		try {
			$json = file_get_contents($url);
			$data = Convert::json2array($json);
			$location = new ArrayData(array(
				'Country'		=> $data['countryCode'],
				'Region'		=> $data['stateProv'],
				'City'			=> $data['city'],
				'Type'			=> ContinentalContentUtils::IPType($ip)
			));

			$this->debugLocation($location);
			return $location;

		} catch (Exception $e) {}

	}

}