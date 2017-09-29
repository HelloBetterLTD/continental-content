<?php

/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 7/18/16
 * Time: 8:43 AM
 * To change this template use File | Settings | File Templates.
 */

abstract class IpParser extends Object
{

	public static function get($type)
	{
		if(!ClassInfo::exists($type)) {
			user_error('The parser you specified does not exist', E_USER_ERROR);
			die();
		}
		$parser = new $type();
		return $parser;
	}

	abstract public function getLocation($ip, $ipNumber);

	
}