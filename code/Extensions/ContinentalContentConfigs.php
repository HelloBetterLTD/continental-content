<?php

/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/29/17
 * Time: 1:52 PM
 * To change this template use File | Settings | File Templates.
 */

class ContinentalContentConfigs extends DataExtension
{

	private static $has_one = array(
		'MaxMindDB'		=> 'File'
	);

	public function updateCMSFields(FieldList $fields)
	{
		$fields->addFieldToTab('Root.GeoContent',
			UploadField::create('MaxMindDB', 'Max mind database'));
	}

}