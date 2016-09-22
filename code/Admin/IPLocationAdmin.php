<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 3/5/15
 * Time: 3:17 PM
 * To change this template use File | Settings | File Templates.
 */

class IPLocationAdmin extends ModelAdmin {

	private static $menu_title = 'Ip To Location';
	private static $url_segment = 'iptolocation';

	private static $managed_models = array(
		'ForwardedIP',
		'IpToLocation'
	);

} 