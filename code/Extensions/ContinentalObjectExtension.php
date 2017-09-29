<?php

/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/29/17
 * Time: 4:41 PM
 * To change this template use File | Settings | File Templates.
 */
class ContinentalObjectExtension extends DataExtension
{

	public function CurrentLocation()
	{
		return ContinentalContent::CurrentContinent();
	}

}