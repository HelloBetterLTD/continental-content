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
		else if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']))
			return $_SERVER['HTTP_CLIENT_IP'];
		elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
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
			$iNumber = self::IPAddressToIPNumber($strIP);
			if(self::GetProvider() == 'IPDBCOM')
				return IpToLocation::get()->filter(array(
				'IPFrom:LessThanOrEqual'	=> $iNumber,
				))->sort('IPFrom DESC')->first();
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