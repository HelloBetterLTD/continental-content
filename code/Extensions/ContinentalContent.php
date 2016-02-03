<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 2/8/15
 * Time: 11:38 AM
 * To change this template use File | Settings | File Templates.
 *
 *
 * MELBOURNE ==
 * 		103.4.18.145
 * 		103.4.18.149
 *		220.244.123.208
 * 		220.246.2.112
 *
 * SYDNEY ==
 * 		223.252.22.128
 * 		221.133.191.80
 * 		220.245.102.48
 *
 */


class ContinentalContent extends DataExtension {

	private static $disable_continental_fields = false;

	private static $continents = array();

	private static $content_fields_types = array(
		'Text',
		'Varchar',
		'HTMLText',
		'HTMLVarchar'
	);

	private static $exclude_field_names = array(
		'URLSegment'
	);

	private static $country_codes = array(
		"BD" =>	"Bangladesh",
		"BE" =>	"Belgium",
		"BF" =>	"Burkina Faso",
		"BG" =>	"Bulgaria",
		"BA" =>	"Bosnia and Herzegovina",
		"BB" =>	"Barbados",
		"WF" =>	"Wallis and Futuna",
		"BL" =>	"Saint Barthelemy",
		"BM" =>	"Bermuda",
		"BN" =>	"Brunei",
		"BO" =>	"Bolivia",
		"BH" =>	"Bahrain",
		"BI" =>	"Burundi",
		"BJ" =>	"Benin",
		"BT" =>	"Bhutan",
		"JM" =>	"Jamaica",
		"BV" =>	"Bouvet Island",
		"BW" =>	"Botswana",
		"WS" =>	"Samoa",
		"BQ" =>	"Bonaire, Saint Eustatius and Saba	",
		"BR" =>	"Brazil",
		"BS" =>	"Bahamas",
		"JE" =>	"Jersey",
		"BY" =>	"Belarus",
		"BZ" =>	"Belize",
		"RU" =>	"Russia",
		"RW" =>	"Rwanda",
		"RS" =>	"Serbia",
		"TL" =>	"East Timor",
		"RE" =>	"Reunion",
		"TM" =>	"Turkmenistan",
		"TJ" =>	"Tajikistan",
		"RO" =>	"Romania",
		"TK" =>	"Tokelau",
		"GW" =>	"Guinea-Bissau",
		"GU" =>	"Guam",
		"GT" =>	"Guatemala",
		"GS" =>	"South Georgia and the South Sandwich Islands",
		"GR" =>	"Greece",
		"GQ" =>	"Equatorial Guinea",
		"GP" =>	"Guadeloupe",
		"JP" =>	"Japan",
		"GY" =>	"Guyana",
		"GG" =>	"Guernsey",
		"GF" =>	"French Guiana",
		"GE" =>	"Georgia",
		"GD" =>	"Grenada",
		"GB" =>	"United Kingdom",
		"GA" =>	"Gabon",
		"SV" =>	"El Salvador",
		"GN" =>	"Guinea",
		"GM" =>	"Gambia",
		"GL" =>	"Greenland",
		"GI" =>	"Gibraltar",
		"GH" =>	"Ghana",
		"OM" =>	"Oman",
		"TN" =>	"Tunisia",
		"JO" =>	"Jordan",
		"HR" =>	"Croatia",
		"HT" =>	"Haiti",
		"HU" =>	"Hungary",
		"HK" =>	"Hong Kong",
		"HN" =>	"Honduras",
		"HM" =>	"Heard Island and McDonald Islands",
		"VE" =>	"Venezuela",
		"PR" =>	"Puerto Rico",
		"PS" =>	"Palestinian Territory",
		"PW" =>	"Palau",
		"PT" =>	"Portugal",
		"SJ" =>	"Svalbard and Jan Mayen",
		"PY" =>	"Paraguay",
		"IQ" =>	"Iraq",
		"PA" =>	"Panama",
		"PF" =>	"French Polynesia",
		"PG" =>	"Papua New Guinea",
		"PE" =>	"Peru",
		"PK" =>	"Pakistan",
		"PH" =>	"Philippines",
		"PN" =>	"Pitcairn",
		"PL" =>	"Poland",
		"PM" =>	"Saint Pierre and Miquelon",
		"ZM" =>	"Zambia",
		"EH" =>	"Western Sahara",
		"EE" =>	"Estonia",
		"EG" =>	"Egypt",
		"ZA" =>	"South Africa",
		"EC" =>	"Ecuador",
		"IT" =>	"Italy",
		"VN" =>	"Vietnam",
		"SB" =>	"Solomon Islands",
		"ET" =>	"Ethiopia",
		"SO" =>	"Somalia",
		"ZW" =>	"Zimbabwe",
		"SA" =>	"Saudi Arabia",
		"ES" =>	"Spain",
		"ER" =>	"Eritrea",
		"ME" =>	"Montenegro",
		"MD" =>	"Moldova",
		"MG" =>	"Madagascar",
		"MF" =>	"Saint Martin",
		"MA" =>	"Morocco",
		"MC" =>	"Monaco",
		"UZ" =>	"Uzbekistan",
		"MM" =>	"Myanmar",
		"ML" =>	"Mali",
		"MO" =>	"Macao",
		"MN" =>	"Mongolia",
		"MH" =>	"Marshall Islands",
		"MK" =>	"Macedonia",
		"MU" =>	"Mauritius",
		"MT" =>	"Malta",
		"MW" =>	"Malawi",
		"MV" =>	"Maldives",
		"MQ" =>	"Martinique",
		"MP" =>	"Northern Mariana Islands",
		"MS" =>	"Montserrat",
		"MR" =>	"Mauritania",
		"IM" =>	"Isle of Man",
		"UG" =>	"Uganda",
		"TZ" =>	"Tanzania",
		"MY" =>	"Malaysia",
		"MX" =>	"Mexico",
		"IL" =>	"Israel",
		"FR" =>	"France",
		"IO" =>	"British Indian Ocean Territory",
		"SH" =>	"Saint Helena",
		"FI" =>	"Finland",
		"FJ" =>	"Fiji",
		"FK" =>	"Falkland Islands",
		"FM" =>	"Micronesia",
		"FO" =>	"Faroe Islands",
		"NI" =>	"Nicaragua",
		"NL" =>	"Netherlands",
		"NO" =>	"Norway",
		"NA" =>	"Namibia",
		"VU" =>	"Vanuatu",
		"NC" =>	"New Caledonia",
		"NE" =>	"Niger",
		"NF" =>	"Norfolk Island",
		"NG" =>	"Nigeria",
		"NZ" =>	"New Zealand",
		"NP" =>	"Nepal",
		"NR" =>	"Nauru",
		"NU" =>	"Niue",
		"CK" =>	"Cook Islands",
		"XK" =>	"Kosovo",
		"CI" =>	"Ivory Coast",
		"CH" =>	"Switzerland",
		"CO" =>	"Colombia",
		"CN" =>	"China",
		"CM" =>	"Cameroon",
		"CL" =>	"Chile",
		"CC" =>	"Cocos Islands",
		"CA" =>	"Canada",
		"CG" =>	"Republic of the Congo",
		"CF" =>	"Central African Republic",
		"CD" =>	"Democratic Republic of the Congo",
		"CZ" =>	"Czech Republic",
		"CY" =>	"Cyprus",
		"CX" =>	"Christmas Island",
		"CR" =>	"Costa Rica",
		"CW" =>	"Curacao",
		"CV" =>	"Cape Verde",
		"CU" =>	"Cuba",
		"SZ" =>	"Swaziland",
		"SY" =>	"Syria",
		"SX" =>	"Sint Maarten",
		"KG" =>	"Kyrgyzstan",
		"KE" =>	"Kenya",
		"SS" =>	"South Sudan",
		"SR" =>	"Suriname",
		"KI" =>	"Kiribati",
		"KH" =>	"Cambodia",
		"KN" =>	"Saint Kitts and Nevis",
		"KM" =>	"Comoros",
		"ST" =>	"Sao Tome and Principe",
		"SK" =>	"Slovakia",
		"KR" =>	"South Korea",
		"SI" =>	"Slovenia",
		"KP" =>	"North Korea",
		"KW" =>	"Kuwait",
		"SN" =>	"Senegal",
		"SM" =>	"San Marino",
		"SL" =>	"Sierra Leone",
		"SC" =>	"Seychelles",
		"KZ" =>	"Kazakhstan",
		"KY" =>	"Cayman Islands",
		"SG" =>	"Singapore",
		"SE" =>	"Sweden",
		"SD" =>	"Sudan",
		"DO" =>	"Dominican Republic",
		"DM" =>	"Dominica",
		"DJ" =>	"Djibouti",
		"DK" =>	"Denmark",
		"VG" =>	"British Virgin Islands",
		"DE" =>	"Germany",
		"YE" =>	"Yemen",
		"DZ" =>	"Algeria",
		"US" =>	"United States",
		"UY" =>	"Uruguay",
		"YT" =>	"Mayotte",
		"UM" =>	"United States Minor Outlying Islands",
		"LB" =>	"Lebanon",
		"LC" =>	"Saint Lucia",
		"LA" =>	"Laos",
		"TV" =>	"Tuvalu",
		"TW" =>	"Taiwan",
		"TT" =>	"Trinidad and Tobago",
		"TR" =>	"Turkey",
		"LK" =>	"Sri Lanka",
		"LI" =>	"Liechtenstein",
		"LV" =>	"Latvia",
		"TO" =>	"Tonga",
		"LT" =>	"Lithuania",
		"LU" =>	"Luxembourg",
		"LR" =>	"Liberia",
		"LS" =>	"Lesotho",
		"TH" =>	"Thailand",
		"TF" =>	"French Southern Territories",
		"TG" =>	"Togo",
		"TD" =>	"Chad",
		"TC" =>	"Turks and Caicos Islands",
		"LY" =>	"Libya",
		"VA" =>	"Vatican",
		"VC" =>	"Saint Vincent and the Grenadines",
		"AE" =>	"United Arab Emirates",
		"AD" =>	"Andorra",
		"AG" =>	"Antigua and Barbuda",
		"AF" =>	"Afghanistan",
		"AI" =>	"Anguilla",
		"VI" =>	"U.S. Virgin Islands",
		"IS" =>	"Iceland",
		"IR" =>	"Iran",
		"AM" =>	"Armenia",
		"AL" =>	"Albania",
		"AO" =>	"Angola",
		"AQ" =>	"Antarctica",
		"AS" =>	"American Samoa",
		"AR" =>	"Argentina",
		"AU" =>	"Australia",
		"AT" =>	"Austria",
		"AW" =>	"Aruba",
		"IN" =>	"India",
		"AX" =>	"Aland Islands",
		"AZ" =>	"Azerbaijan",
		"IE" =>	"Ireland",
		"ID" =>	"Indonesia",
		"UA" =>	"Ukraine",
		"QA" =>	"Qatar",
		"MZ" =>	"Mozambique"
	);

	private static $array_generated_fields = array();

	private static $current_continent = '';

	private static $affected_tables = array();

	/**
	 * @param $callback
	 * @return mixed
	 */
	protected static function without_continental_fields($callback) {
		$before = self::$disable_continental_fields;
		self::$disable_continental_fields = true;
		$result = $callback();
		self::$disable_continental_fields = $before;
		return $result;
	}


	/**
	 * @param $class
	 * @param $extension
	 * @param $args
	 * @return array|mixed
	 */
	public static function get_extra_config($class, $extension, $args) {
		if(self::$disable_continental_fields) return array();

		foreach (ClassInfo::subclassesFor($class) as $subClass) {
			$config = self::make_continental_fields($subClass);
			foreach($config as $name => $value) {
				Config::inst()->update($subClass, $name, $value);
			}
		}

		DataObject::reset();

		return self::make_continental_fields($class);
	}


	/**
	 * @param $class
	 * @param $config
	 * @return mixed
	 */
	public static function get_configs_for_class($class, $config){
		return self::without_continental_fields(function() use ($class, $config) {
			return Config::inst()->get($class, $config, Config::UNINHERITED);
		});
	}


	/**
	 * @param $class
	 * @return mixed
	 *
	 * make fields for the dataobjects for the continents
	 */
	public static function make_continental_fields($class){

		if(isset(self::$array_generated_fields[$class])){
			return self::$array_generated_fields[$class];
		}
		else{
			$arrBaseFields = self::get_configs_for_class($class, 'db');
			$arrBaseIndexes = self::get_configs_for_class($class, 'indexes');
			$arrBaseManyManyExtra = self::get_configs_for_class($class, 'many_many_ExtraFields');
			$arrMultipleFields = Config::inst()->get('ContinentalContent', 'content_fields_types');
			$arrExcludeTypes = Config::inst()->get('ContinentalContent', 'exclude_field_names');

			$arrNewFields = $arrBaseFields;
			$indexes = $arrBaseIndexes;
			$arrNewManyManyExtra = $arrBaseManyManyExtra;

			foreach(self::GetContinentSuffixes() as $strName => $strSuffix){
				if($arrBaseFields) foreach($arrBaseFields as $strKey => $strType){
					if(!in_array($strKey, $arrExcludeTypes) && !in_array("{$class}.{$strKey}", $arrExcludeTypes)){
						if(in_array(self::GetFieldType($strType), $arrMultipleFields)){
							$arrNewFields[$strKey . '_' . $strSuffix] = $strType;
							if($indexes && array_key_exists($strKey, $arrBaseIndexes))
								$indexes[] = $strKey . '_' . $strSuffix;
						}
					}
				}

				if($arrBaseManyManyExtra) foreach($arrBaseManyManyExtra as $strRelation => $arrFields){
					foreach($arrFields as $strKey => $strType){
						if(in_array(self::GetFieldType($strType), $arrMultipleFields))
							$arrNewManyManyExtra[$strRelation][$strKey . '_' . $strSuffix] = $strType;

					}
				}
			}

			if(count($arrNewFields) != count($arrBaseFields))
				self::$affected_tables[] = $class;

			self::$array_generated_fields[$class] = array(
				'db'						=> $arrNewFields,
				'indexes'					=> $arrBaseIndexes,
				// 'many_many_extraFields'		=> $arrNewManyManyExtra
			);

			return self::$array_generated_fields[$class];
		}




	}


	/**
	 * @param $strType
	 * @return string
	 */
	public static function GetFieldType($strType){
		return strpos($strType, '(') === false ? $strType : substr($strType, 0, strpos($strType, '('));;
	}


	/**
	 * @return array
	 */
	public static function GetContinents(){
		$arrRet = array();
		foreach(Config::inst()->get('ContinentalContent', 'continents') as $key => $continent){
			if(is_array($continent)){
				$arrRet[self::UpdateContinentExtensionName($key)] = $continent;
			}
			else{
				$arrRet[self::UpdateContinentExtensionName($continent)] = array(
					$continent
				);
			}
		}
		return $arrRet;
	}


	public static function GetContinentSuffixes(){
		$arrRet = array();
		foreach(Config::inst()->get('ContinentalContent', 'continents') as $key => $continent){
			if(is_array($continent)){
				$arrRet[Convert::raw2url($key)] = self::UpdateContinentExtensionName($key);
			}
			else{
				$arrRet[Convert::raw2url($continent)] = self::UpdateContinentExtensionName($continent);
			}
		}
		return $arrRet;
	}

	public static function UpdateContinentExtensionName($strName){
		$default_replacements = array(
			'/&amp;/u' 				=> '-and-',
			'/&/u' 					=> '-and-',
			'/\s|\+/u' 				=> '-', // remove whitespace/plus
			'/[_.-]+/u' 			=> '', // underscores and dots to dashes
			'/[^A-Za-z0-9\-]+/u' 	=> '' // remove non-ASCII chars, only allow alphanumeric and dashes
		);
		$strName = strtolower($strName);
		foreach($default_replacements as $regex => $replace){
			$strName = preg_replace($regex, $replace, $strName);
		}
		return $strName;
	}


	/**
	 * @param FieldList $fields
	 */
	public function updateCMSFields(FieldList $fields) {
		if(Config::inst()->get('ContinentalContent', 'AutoAddCMSFields')){
			$arrBaseDB = Config::inst()->get(get_class($this->owner), 'db');
			foreach(self::GetContinentSuffixes() as $strContinent => $strSuffix){
				if($arrBaseDB) foreach($arrBaseDB as $strName => $strType){
					if(array_key_exists($strName . '_' . $strSuffix, $arrBaseDB)){
						if($dataField = $fields->dataFieldByName($strName)){
							$newField = clone $dataField;
							$newField->setName($strName . '_' . $strSuffix);
							$newField->setTitle($dataField->Title() . ' (' . $strContinent . ')');
							$fields->insertAfter($newField, $strName);
						}
					}
				}
			}
		}
	}

	/**
	 * @return bool
	 */
	public static function IsViewingThroughProxy(){
		$bRet = false;
		$strProxyIP = Config::inst()->get('ContinentalContent', 'proxy_ip');
		if($strProxyIP != '0.0.0.0'){
			$bRet = $strProxyIP == ContinentalContentUtils::IPAddress();
		}
		return $bRet;

	}

	/**
	 * @param $strContinent
	 */
	public static function ForceUpdateContinent($strContinent){
		self::$current_continent = $strContinent;
	}


	/**
	 * @return int|string
	 */
	public static function CurrentContinent(){
		if(self::$current_continent)
			return self::$current_continent;


		self::$current_continent = CONTINENTAL_DEFAULT;
		if(!self::IsViewingThroughProxy() && Session::get('SESSION_MAP_LOCATION')){
			self::$current_continent = strtolower(trim(Session::get('SESSION_MAP_LOCATION')));
		}
		else if($location = ContinentalContentUtils::GetLocation()){

			$countryToCode = array_flip(self::$country_codes);
			foreach(self::GetContinents() as $strCode => $arrContinents){
				foreach($arrContinents as $strContinent){

					$strCountryCode = isset($countryToCode[$strContinent]) ? $countryToCode[$strContinent] : null;
					if(strtolower(trim($location->Country)) == strtolower(trim($strContinent))
						|| strtolower(trim($location->Region)) == strtolower(trim($strContinent))
						|| strtolower(trim($location->City)) == strtolower(trim($strContinent))
						|| strtolower(trim($location->Country)) == strtolower(trim($strCode))
						|| strtolower(trim($location->Region)) == strtolower(trim($strCode))
						|| strtolower(trim($location->City)) == strtolower(trim($strCode))
						|| (!is_null($strCountryCode) && strtolower(trim($location->Country)) == strtolower(trim($strCountryCode)))
					){
						self::$current_continent = $strCode;
						break;
					}
				}

			}
		}

		return self::$current_continent;
	}


	/**
	 * @return array
	 */
	public function getAffectedTables(){
		return self::$affected_tables;
	}


	/**
	 * @param $class
	 * @param $select
	 * @param $fallback
	 * @return string
	 */
	protected function localiseSelect($class, $select, $fallback) {

		return "CASE
			WHEN (\"{$class}\".\"{$select}\" IS NOT NULL AND \"{$class}\".\"{$select}\" != '')
			THEN \"{$class}\".\"{$select}\"
			ELSE \"{$class}\".\"{$fallback}\" END";

	}


	/**
	 * @param SQLQuery $query
	 * @param DataQuery $dataQuery
	 */
	public function augmentSQL(SQLQuery &$query, DataQuery &$dataQuery = null) {
		
		$controller = Controller::curr();
		if(!is_subclass_of($controller ,'LeftAndMain')){
			$includedTables = ContinentalContent::getAffectedTables();
			$strContinent = ContinentalContent::CurrentContinent();
			if($strContinent != CONTINENTAL_DEFAULT){

				foreach($query->getSelect() as $alias => $select) {

					if(!preg_match('/^"(?<class>\w+)"\."(?<field>\w+)"$/i', $select, $matches)) continue;
					$class = $matches['class'];
					$field = $matches['field'];


					if(!in_array($class, $includedTables)) continue;

					$strNewField = $field . '_' . $strContinent;
					$arrFields = ContinentalContent::make_continental_fields($class);

					if(isset($arrFields['db']) && isset($arrFields['db'][$strNewField])){
						$expression = $this->localiseSelect($class, $strNewField, $field);
						$query->selectField($expression, $alias);
					}

				}

				// TODO: update where clues too

			}
		}
	}


	/**
	 * @param $base
	 * @param $action
	 */
	public function updateRelativeLink(&$base, &$action) {
		if(Config::inst()->get('ContinentalContent', 'custom_urls') == 'Y'){
			if($strContinent = ContinentalContent::CurrentContinent()){
				$bAddContinent = $strContinent != 100;
				if($bAddContinent && strpos($base, $strContinent) === false)
					$base = Controller::join_links($strContinent, $base);
			}
		}
	}


	public static function ForceLocationFromSession($strLocation){
		Session::set('SESSION_MAP_LOCATION', $strLocation);
		Session::save();
	}

	public static function ClearForceLocation(){
		Session::clear('SESSION_MAP_LOCATION');
		Session::save();
	}



} 