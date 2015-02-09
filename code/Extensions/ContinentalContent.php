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

	private static $array_generated_fields = array();

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

			$arrNewFields = $arrBaseFields;
			$indexes = $arrBaseIndexes;
			$arrNewManyManyExtra = $arrBaseManyManyExtra;

			foreach(self::GetContinents() as $strName => $strSuffix){
				foreach($arrBaseFields as $strKey => $strType){
					if(in_array(self::GetFieldType($strType), $arrMultipleFields)){
						$arrNewFields[$strKey . '_' . $strSuffix] = $strType;
						if($indexes && array_key_exists($strKey, $arrBaseIndexes))
							$indexes[] = $strKey . '_' . $strSuffix;
					}
				}

				if($arrBaseManyManyExtra) foreach($arrBaseManyManyExtra as $strRelation => $arrFields){
					foreach($arrFields as $strKey => $strType){
						if(in_array(self::GetFieldType($strType), $arrMultipleFields))
							$arrNewManyManyExtra[$strRelation][$strKey . '_' . $strSuffix] = $strType;

					}
				}

			}

			self::$array_generated_fields[$class] = array(
				'db'						=> $arrNewFields,
				'indexes'					=> $arrBaseIndexes,
				'many_many_extraFields'		=> $arrNewManyManyExtra
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


	/**
	 * @param FieldList $fields
	 */
	public function updateCMSFields(FieldList $fields) {
		$arrBaseDB = Config::inst()->get(get_class($this->owner), 'db');
		foreach(self::GetContinents() as $strContinent => $strSuffix){
			foreach($arrBaseDB as $strName => $strType){
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