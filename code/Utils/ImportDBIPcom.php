<?php
class ImportDBIPcom extends BuildTask{
	
	protected $description = 'Import DBIPcom service to IPToLocation dataobjects from CSV';
	protected $connection;

	public function run($request) {

		$strCSVPath = CONTINENTAL_CONTENT_PATH . '/code/ThirdParty/dbip-city.csv';

		if(!file_exists($strCSVPath)){
			echo "<p>I cant find the dbip-city.csv file to import any data.<br>
				Please download any database from <a href='https://db-ip.com/db/'>https://db-ip.com/db/</a>.<br>
				NOTE: It's adviced to edit the DB to only include the countries you want to handle, it contains 2 million records!!!<br>
				Or make a CSV contain these columns<br>
				`IPFrom`,`IPTo`,`Country`,`Region`,`City`
				</p>";
			//"0.0.0.0","0.255.255.255","US","California","Los Angeles"
		}
		else{

			if(!isset($_REQUEST['confirm'])){
				$strLink = Director::baseURL() . 'dev/tasks/ImportDBIPcom?confirm=1';
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

				$conn = DB::get_conn();
				if($conn->supportsTransactions()){
					$conn->transactionStart();
				}

				$arrFields = array_keys(Config::inst()->get('IpToLocation', 'db'));
				$handle = fopen($strCSVPath, "r");
				if ($handle) {

					while (($line = fgets($handle)) !== false) {
						$line = str_replace('","', '___', $line);
						$line = str_replace('"', '', $line);
						$arrParts = Convert::raw2sql(explode("___", $line));
						$arrParts[0] = ContinentalContentUtils::IPAddressToIPNumber($arrParts[0]);
						$arrParts[1] = ContinentalContentUtils::IPAddressToIPNumber($arrParts[1]);
						DB::query('INSERT INTO `IpToLocation` (`' . implode('`,`', $arrFields) .'`) VALUES (\'' . implode('\',\'', $arrParts) . '\')');
					}

					fclose($handle);
				} else {
					echo 'Error opening file';
				}
				if($conn->supportsTransactions()){
					$conn->transactionEnd();
				}
			}

		}


	}
}
