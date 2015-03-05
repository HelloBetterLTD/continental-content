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
		'IPFrom'		=> 'Float',
		'IPTo'			=> 'Float',
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


} 