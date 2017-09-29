<?php

/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 7/18/16
 * Time: 8:44 AM
 * To change this template use File | Settings | File Templates.
 */
class IPDBCOM_IpParser extends IpParser
{

	public function getLocation($ip, $ipNumber)
	{
		$conn = DB::get_conn();
		$addressType = IpToLocation::addr_type($ip);
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
						AND ip_start <= '" . $conn->escapeString($ipNumber) . "'
					ORDER BY
						ip_start DESC
					LIMIT 1";
		$res = DB::query($sql);
		while($row = $res->nextRecord()){
			$location = new IpToLocation($row);
			$this->debugLocation($location);
			return $location;
		}
	}

}