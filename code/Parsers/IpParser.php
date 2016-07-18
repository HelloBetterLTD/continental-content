<?php

/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 7/18/16
 * Time: 8:43 AM
 * To change this template use File | Settings | File Templates.
 */
class IpParser extends Object
{

	public static function get($type)
	{
		if($type == 'DBIP_Api') {
			return new DBIP_IpParser();
		}
		else if ($type == 'IPDBCOM') {
			return new IPDBCOM_IpParser();
		}
		else {
			return new IpParser();
		}
	}

	public function getLocation($ip, $ipNumber)
	{
		$location = IpToLocation::get()->filter(array(
			'IPFrom:LessThanOrEqual' 	=> $ipNumber,
			'IPTo:GreaterThanOrEqual' 	=> $ipNumber,
			'Type' 						=> ContinentalContentUtils::IPType($ip) == 'ipv4' ? 'IpV4' : 'IpV6'
		))->first();
		$this->debugLocation($location);
		return $location;
	}

	public function debugLocation($location)
	{
		if($location && isset($_REQUEST['debug_location'])) {
			echo "<p style='display: block; padding: 10px 40px; background: white; color: black; position: absolute; top: 0; left: 0; z-index: 999999;'>$location->City $location->Region $location->Country</p>";
		}
	}

}