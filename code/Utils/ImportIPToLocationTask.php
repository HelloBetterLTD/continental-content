<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 2/8/15
 * Time: 11:10 AM
 * To change this template use File | Settings | File Templates.
 */

class ImportIPToLocationTask extends BuildTask {

	protected $description = 'Import IPToLocation dataobjects from CSV';
	protected $connection;

	public function run($request) {

		$strCSVPath = CONTINENTAL_CONTENT_PATH . '/code/ThirdParty/IP2LOCATION-DB.CSV';

		if(!file_exists($strCSVPath)){
			echo "<p>I cant find the IP2LOCATION-DB.CSV file to import any data.<br>
				Please download DB3.LITE database from <a href='http://lite.ip2location.com/'>http://lite.ip2location.com/</a>.<br>
				NOTE: It's adviced to edit the DB to only include the countries you want to handle, it contains 2 million records!!!<br>
				Or make a CSV contain these columns<br>
				`IPFrom`,`IPTo`,`Country`,`CountryName`,`Region`,`City`
				</p>";
		}
		else{

			if(!isset($_REQUEST['confirm'])){
				$strLink = Director::baseURL() . 'dev/tasks/ImportIPToLocationTask?confirm=1';
				echo "<p>CAUTION!!!<br>
					Please confirm your action<br>
					<a href='$strLink'>I confirm the action</a><br>
					<a href='{$strLink}&emptydb=1'>I confirm the action, please empty the DB before you import</a>
					</p>";
			}

			else{

				increase_time_limit_to();
				if(isset($_REQUEST['emptydb'])){
					DB::query('TRUNCATE `IpToLocation`;');
				}

				$arrFields = array_keys(Config::inst()->get('IpToLocation', 'db'));
				$handle = fopen($strCSVPath, "r");
				if ($handle) {

					while (($line = fgets($handle)) !== false) {
						$line = str_replace('","', '___', $line);
						$line = str_replace('"', '', $line);
						$arrParts = Convert::raw2sql(explode("___", $line));
						unset($arrParts[3]);
						DB::query('INSERT INTO `IpToLocation` (`' . implode('`,`', $arrFields) .'`) VALUES (\'' . implode('\',\'', $arrParts) . '\')');
					}

					fclose($handle);
				} else {
					echo 'Error opening file';
				}

			}

		}


	}


} 