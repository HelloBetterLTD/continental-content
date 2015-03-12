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

			$arrNewFields = $arrBaseFields;
			$indexes = $arrBaseIndexes;
			$arrNewManyManyExtra = $arrBaseManyManyExtra;

			foreach(self::GetContinents() as $strName => $strSuffix){
				if($arrBaseFields) foreach($arrBaseFields as $strKey => $strType){
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


	/**
	 * @return int|string
	 */
	public static function CurrentContinent(){
		if(self::$current_continent)
			return self::$current_continent;

		self::$current_continent = CONTINENTAL_DEFAULT;
		if($location = ContinentalContentUtils::GetLocation()){
			foreach(self::GetContinents() as $strContinent => $strCode){
				if(strtolower(trim($location->Country)) == strtolower(trim($strContinent))
					|| strtolower(trim($location->Region)) == strtolower(trim($strContinent))
					|| strtolower(trim($location->City)) == strtolower(trim($strContinent))
					|| strtolower(trim($location->Country)) == strtolower(trim($strCode))
					|| strtolower(trim($location->Region)) == strtolower(trim($strCode))
					|| strtolower(trim($location->City)) == strtolower(trim($strCode))
				){
					self::$current_continent = $strCode;
					break;
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



} 