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

				//
				// this needs varbinary fields so create the table here
				//
				$arr = DB::table_list();
				if(!in_array('dbip_lookup', $arr)){
					$strSQL = "CREATE TABLE `dbip_lookup` (
					  `addr_type` enum('ipv4','ipv6') NOT NULL,
					  `ip_start` varbinary(16) NOT NULL,
					  `ip_end` varbinary(16) NOT NULL,
					  `country` char(2) NOT NULL,
					  `stateprov` varchar(80) NOT NULL,
					  `city` varchar(80) NOT NULL,
					  PRIMARY KEY (`ip_start`)
					);";
					DB::query($strSQL);
				}


				if(isset($_REQUEST['emptydb'])){
					DB::query('TRUNCATE `dbip_lookup`;');
				}

				$conn = DB::get_conn();
				if($conn->supportsTransactions()){
					$conn->transactionStart();
				}

				$handle = fopen($strCSVPath, "r");
				if ($handle) {

					while (($line = fgets($handle)) !== false) {
						$line = str_replace('","', '___', $line);
						$line = str_replace('"', '', $line);
						$arrParts = Convert::raw2sql(explode("___", $line));
						$vals = array(
							'addr_type'		=> "'" . IpToLocation::addr_type($arrParts[0]) . "'",
							'ip_start'		=> "'" . $conn->escapeString(ContinentalContentUtils::IPAddressToIPNumber($arrParts[0])) . "'",
							'ip_end'		=> "'" . $conn->escapeString(ContinentalContentUtils::IPAddressToIPNumber($arrParts[1])) . "'",
							'country'		=> "'" . $arrParts[2] . "'",
							'stateprov'		=> "'" . $arrParts[3] . "'",
							'city'			=> "'" . $arrParts[4] . "'"
						);

						$fields = array_keys($vals);
						DB::query('INSERT INTO `dbip_lookup` (`' . implode('`,`', $fields) .'`) VALUES (' . implode(',', $vals) . ')');

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
