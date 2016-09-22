<?php

/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/22/16
 * Time: 9:19 AM
 * To change this template use File | Settings | File Templates.
 */
class ForwardedIP extends DataObject
{

	private static $db = array(
		'IP'			=> 'Varchar(50)',
		'Continent'		=> 'Varchar(100)'
	);

	private static $summary_fields = array(
		'IP',
		'Continent'
	);

	public function getCMSFields()
	{
		$fields = parent::getCMSFields();
		$fields->removeByName('Continent');
		$sources = Config::inst()->get('ContinentalContent', 'country_codes');
		$fields->addFieldToTab('Root.Main', DropdownField::create('Continent', 'Continent')->setSource($sources));
		return $fields;
	}



}