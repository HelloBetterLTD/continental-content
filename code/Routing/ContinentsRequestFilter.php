<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 3/13/15
 * Time: 11:48 AM
 * To change this template use File | Settings | File Templates.
 */

class ContinentsRequestFilter implements RequestFilter {

	public function preRequest(SS_HTTPRequest $request, Session $session, DataModel $model) {

		if($request->getVar('FAKE_IP')){
			ContinentalContentUtils::set_fake_ip($request->getVar('FAKE_IP'));
		}
		if($request->getVar('CLEAR_FAKE_IP')){
			ContinentalContentUtils::clear_fake_ip();
		}

		ContinentsRequestFilter::UpdateContinentBasedOnURL($request);
		$routes = ContinentsRequestFilter::FilterURLRoutes();

		foreach(ContinentalContent::GetContinentSuffixes() as $strCode => $strContinent){
			$routes[$strContinent.'/$URLSegment!//$Action/$ID/$OtherID'] = array(
				'Controller' => 'ModelAsController',
				$strContinent
			);
			$routes[$strContinent] = array(
				'Controller' => 'ContinentsRootURLController',
				$strContinent
			);
		}

		$routes[''] = 'ContinentsRootURLController';
		Config::inst()->update('Director', 'rules', $routes);
	}

	public function postRequest(SS_HTTPRequest $request, SS_HTTPResponse $response, DataModel $model) {

		if(Director::isDev() && $request->requestVar('debug_location') && ContinentalContent::get_debug_messages()) {
			echo '<div style="padding: 10px; background: #000000; border: 2px solid #DDDDDD; color: white; position: absolute; top: 0; right: 0; width: 400px; font-size: 12px; z-index: 999999;">'.
				ContinentalContent::get_debug_messages()
				.'</div>';
		}

	}


	/**
	 * @return array
	 */
	public static function FilterURLRoutes(){
		$currentRouts = Config::inst()->get('Director', 'rules');
		$arrDefaultRoutes = array(
			'',
			'$Controller//$Action/$ID/$OtherID',
			'$URLSegment//$Action/$ID/$OtherID'
		);

		$routes = array();


		if($currentRouts) foreach($currentRouts as $route => $controller){
			if(!empty($route) && !in_array($route, $arrDefaultRoutes)){
				$routes[$route] = $controller;
			}
		}


		return $routes;
	}

	/**
	 * @param SS_HTTPRequest $request
	 */
	public static function UpdateContinentBasedOnURL(SS_HTTPRequest $request){
		if($strURL = $request->getURL(false)){
			$arrParts = explode('/', $strURL);
			foreach(ContinentalContent::GetContinentSuffixes() as $strContinent => $strCode){
				if($strCode === $arrParts[0])
					ContinentalContent::ForceUpdateContinent($strCode);
			}
		}
	}

}
