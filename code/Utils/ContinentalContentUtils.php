<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 2/9/15
 * Time: 10:47 AM
 * To change this template use File | Settings | File Templates.
 */

class ContinentalContentUtils {


	public static function IPAddress(){
		if($ip = Session::get('FAKE_IP'))
			return $ip;
		elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']))
			return $_SERVER['HTTP_CLIENT_IP'];
		elseif (isset($_SERVER['REMOTE_ADDR']))
			return $_SERVER['REMOTE_ADDR'];
	}


	public static function IPAddressToIPNumber($strIP){
		if(self::GetProvider() == 'IPDBCOM'){
			return inet_pton($strIP);
		}

		$arrParts = explode('.', $strIP);
		return $arrParts[3]
			+ ($arrParts[2] * 256)
			+ ($arrParts[1] * 256 * 256)
			+ ($arrParts[0] * 256 * 256 * 256);

	}

	public static function GetLocation(){
		if($strIP = self::IPAddress()){
			Debug::log('IP Address : ' . $strIP);
			$iNumber = self::IPAddressToIPNumber($strIP);
			if(self::GetProvider() == 'IPDBCOM'){
				$conn = DB::get_conn();
				$addressType = IpToLocation::addr_type($strIP);
				$sql = "SELECT
						`ip_start` AS IPFrom,
						`ip_end` AS IPTo,
						`country` AS Country,
						`stateprov` AS Region,
						`city` AS City
				 	FROM
						`dbip_lookup`
					WHERE
						addr_type = '{$addressType}'
						AND ip_start <= '" . $conn->escapeString($iNumber) . "'
					ORDER BY
						ip_start DESC
					LIMIT 1";
				$res = DB::query($sql);
				while($row = $res->nextRecord()){
					$location = new IpToLocation($row);
					Debug::log("Location detect: '{$location->City}', '{$location->Region}', '{$location->Country}'");
					return $location;
				}

			}
			else
				return IpToLocation::get()->filter(array(
					'IPFrom:LessThanOrEqual'	=> $iNumber,
					'IPTo:GreaterThanOrEqual'	=> $iNumber
				))->first();
		}
		return null;
	}
	
	public static function GetProvider(){
		return Config::inst()->get('ContinentalContent', 'Provider');

	}
}