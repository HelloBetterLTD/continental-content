<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 2/8/15
 * Time: 11:38 AM
 * To change this template use File | Settings | File Templates.
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

	protected static function without_continental_fields($callback) {
		$before = self::$disable_continental_fields;
		self::$disable_continental_fields = true;
		$result = $callback();
		self::$disable_continental_fields = $before;
		return $result;
	}


	public static function get_extra_config($class, $extension, $args) {
		if(self::$disable_continental_fields) return array();

		// Merge all config values for subclasses
		foreach (ClassInfo::subclassesFor($class) as $subClass) {
			$config = self::make_continental_fields($subClass);
			foreach($config as $name => $value) {
				Config::inst()->update($subClass, $name, $value);
			}
		}

		// Force all subclass DB caches to invalidate themselves since their db attribute is now expired
		DataObject::reset();

		return self::make_continental_fields($class);
	}


	/**
	 * @param $class
	 * @return mixed
	 */
	public static function make_continental_fields($class){

		$arrBaseFields = self::without_continental_fields(function() use ($class) {
			return Config::inst()->get($class, 'db', Config::UNINHERITED);
		});

		$arrBaseIndexes = self::without_continental_fields(function() use ($class) {
			return Config::inst()->get($class, 'indexes', Config::UNINHERITED);
		});

		$arrBaseManyManyExtra = self::without_continental_fields(function() use ($class) {
			return Config::inst()->get($class, 'many_many_ExtraFields', Config::UNINHERITED);
		});


		$arrMultipleFields = Config::inst()->get('ContinentalContent', 'content_fields_types');

		$arrNewFields = $arrBaseFields;
		$indexes = $arrBaseIndexes;
		$arrNewManyManyExtra = $arrBaseManyManyExtra;

		foreach(self::GetContinents() as $strName => $strSuffix){
			foreach($arrBaseFields as $strKey => $strType){
				$strFieldType = self::GetFieldType($strType);
				if(in_array($strFieldType, $arrMultipleFields)){
					$arrNewFields[$strKey . '_' . $strSuffix] = $strType;
					if($indexes && array_key_exists($strKey, $arrBaseIndexes)){
						$indexes[] = $strKey . '_' . $strSuffix;
					}
				}
			}

			if($arrBaseManyManyExtra) foreach($arrBaseManyManyExtra as $strRelation => $arrFields){
				foreach($arrFields as $strKey => $strType){
					$strFieldType = self::GetFieldType($strType);
					if(in_array($strFieldType, $arrMultipleFields)){
						$arrNewManyManyExtra[$strRelation][$strKey . '_' . $strSuffix] = $strType;
					}
				}
			}


		}

		return array(
			'db'						=> $arrNewFields,
			'indexes'					=> $arrBaseIndexes,
			'many_many_extraFields'		=> $arrNewManyManyExtra
		);
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
		$default_replacements = array(
			'/&amp;/u' 				=> '-and-',
			'/&/u' 					=> '-and-',
			'/\s|\+/u' 				=> '-', // remove whitespace/plus
			'/[_.-]+/u' 			=> '', // underscores and dots to dashes
			'/[^A-Za-z0-9\-]+/u' 	=> '' // remove non-ASCII chars, only allow alphanumeric and dashes
		);

		foreach(Config::inst()->get('ContinentalContent', 'continents') as $strContinent){
			$strExtension = strtolower($strContinent);
			foreach($default_replacements as $regex => $replace)
				$name = preg_replace($regex, $replace, $strExtension);
			$arrRet[$strContinent] = $name;
		}

		return $arrRet;

	}





} 