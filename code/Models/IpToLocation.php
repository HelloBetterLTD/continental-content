<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 2/8/15
 * Time: 11:10 AM
 * To change this template use File | Settings | File Templates.
 */

class IpToLocation extends DataObject {

	private static $db = array(
		'IPFrom'		=> 'Varchar(100)',
		'IPTo'			=> 'Varchar(100)',
		'Country'		=> 'Varchar(2)',
		'Region'		=> 'Varchar(128)',
		'City'			=> 'Varchar(128)',
	);

	private static $indexes = array(
		'IPFrom'		=> true,
		'IPTo'			=> true
	);

	private static $summary_fields = array(
		'IPFrom',
		'IPTo',
		'Country',
		'Region',
		'City'
	);

	public static function addr_type($addr) {

		if (ip2long($addr) !== false) {
			return "ipv4";
		} else if (preg_match('/^[0-9a-fA-F:]+$/', $addr) && @inet_pton($addr)) {
			return "ipv6";
		}

	}


} 