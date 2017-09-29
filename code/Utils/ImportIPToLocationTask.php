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

		$csvPath = CONTINENTAL_CONTENT_PATH . '/code/ThirdParty/IP2LOCATION-DB.CSV';
		$csvPathIPv6 = CONTINENTAL_CONTENT_PATH . '/code/ThirdParty/IP2LOCATION-DB-IPV6.CSV';

		if(!file_exists($csvPath)){
			echo "<p>I cant find the IP2LOCATION-DB.CSV file to import any data.<br>
				Please download DB3.LITE database from <a href='http://lite.ip2location.com/'>http://lite.ip2location.com/</a> and place it at {$csvPath},<br>
				if you wish to use ipv6 copy the file and paste as {$csvPathIPv6}.<br>
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
				$start = microtime(true);
				increase_time_limit_to();
				if(isset($_REQUEST['emptydb'])){
					DB::query('TRUNCATE `IpToLocation`;');
				}

				$conn = DB::get_conn();
				if($conn->supportsTransactions()){
					$conn->transactionStart();
				}

				$dbFields = array_keys(Config::inst()->get('IpToLocation', 'db'));

				$handle = fopen($csvPath, "r");
				if ($handle) {
					while($line = fgetcsv($handle)) {
						for($i = 0; $i < count($line); $i++){
							$line[$i] = $conn->escapeString($line[$i]);
						}
						unset($line[3]);
						$line[] = 'IpV4';
						$sql = 'INSERT INTO `IpToLocation` (`' . implode('`,`', $dbFields) .'`) VALUES (\'' . implode('\',\'', $line) . '\')';
						DB::query($sql);
					}
					fclose($handle);
					echo 'IPV4 is imported<br>';
				} else {
					echo 'Error opening IPV4 file<br>';
				}


				if(file_exists($csvPathIPv6)) {
					$handle = fopen($csvPathIPv6, 'r');
					if($handle) {
						while($line = fgetcsv($handle)) {
							for($i = 0; $i < count($line); $i++){
								$line[$i] = $conn->escapeString($line[$i]);
							}
							unset($line[3]);
							$line[] = 'IpV6';
							$sql = 'INSERT INTO `IpToLocation` (`' . implode('`,`', $dbFields) .'`) VALUES (\'' . implode('\',\'', $line) . '\')';
							DB::query($sql);
						}

						echo 'IPV6 is imported<br>';
					}
					else {
						echo 'Error opening IPV6 file<br>';
					}
				}


				if($conn->supportsTransactions()){
					$conn->transactionEnd();
				}


				$end = microtime(true);

				$timeSpent = $start - $end;
				echo "Total time spent is: $timeSpent";

			}

		}


	}


} 