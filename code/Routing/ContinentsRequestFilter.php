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



		ContinentsRequestFilter::UpdateContinentBasedOnURL($request);
		$routes = ContinentsRequestFilter::FilterURLRoutes();

		foreach(ContinentalContent::GetContinents() as $strCode => $strContinent){
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
			foreach(ContinentalContent::GetContinents() as $strContinent => $strCode){
				if($strCode === $arrParts[0])
					ContinentalContent::ForceUpdateContinent($strCode);
			}
		}
	}

} 