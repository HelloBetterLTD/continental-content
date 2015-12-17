<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 3/5/15
 * Time: 3:26 PM
 * To change this template use File | Settings | File Templates.
 */

class ImportMaxMindToDatabase extends BuildTask
{

    protected $description = 'Import IPToLocation dataobjects from CSV';
    protected $connection;

    public function run($request)
    {
        $strLocationCSV = CONTINENTAL_CONTENT_PATH . '/code/ThirdParty/GeoLiteCity-Location.csv';
        $strIPCSV = CONTINENTAL_CONTENT_PATH . '/code/ThirdParty/GeoLiteCity-Blocks.csv';

        if (!file_exists($strLocationCSV) || !file_exists($strIPCSV)) {
            echo "<p>I cant find the GeoLiteCity-Location.csv or GeoLiteCity-Blocks.csv file to import any data.<br>
				Please download the database from <a href='http://dev.maxmind.com/geoip/legacy/geolite/'>http://dev.maxmind.com/geoip/legacy/geolite/</a>.<br>
				NOTE: It's adviced to edit the DB to only include the countries you want to handle, it contains 2 million records!!!<br>
				</p>";
        } else {
            if (!isset($_REQUEST['confirm'])) {
                $strLink = Director::baseURL() . 'dev/tasks/ImportMaxMindToDatabase?confirm=1';
                echo "<p>CAUTION!!!<br>
					Please confirm your action<br>
					<a href='$strLink'>I confirm the action</a><br>
					<a href='{$strLink}&emptydb=1'>I confirm the action, please empty the DB before you import</a>
					</p>";
            } else {
                $arrFields = array_keys(Config::inst()->get('IpToLocation', 'db'));

                increase_time_limit_to();
                if (isset($_REQUEST['emptydb'])) {
                    DB::query('TRUNCATE `IpToLocation`;');
                }

                $arrLocations = array();

                $handle = fopen($strLocationCSV, "r");
                if ($handle) {
                    $i = 0;
                    while (($line = fgets($handle)) !== false) {
                        $i += 1;
                        if ($i > 3) {
                            $line = str_replace('","', '**', $line);
                            $line = str_replace(',', '**', $line);

                            $line = str_replace('"', '', $line);
                            $arrParts = Convert::raw2sql(explode("**", $line));

                            $arrLocations[$arrParts[0]] = array(
                                'Country'    => $arrParts[1],
                                'Region'    => $arrParts[2],
                                'City'        => $arrParts[3]
                            );
                        }
                    }

                    fclose($handle);
                } else {
                    echo 'Error opening file';
                }


                $ipHandle = fopen($strIPCSV, "r");
                if ($ipHandle) {
                    $i = 0;
                    while (($line = fgets($ipHandle)) !== false) {
                        $i += 1;
                        if ($i > 3) {
                            $line = str_replace("\n", "", $line);
                            $line = str_replace('","', '___', $line);
                            $line = str_replace('"', '', $line);
                            $arrParts = Convert::raw2sql(explode("___", $line));

                            if (count($arrParts) == 3 && isset($arrLocations[$arrParts[2]])) {
                                $strSQL = 'INSERT INTO `IpToLocation` (`' . implode('`,`', $arrFields) .'`) VALUES (\'' . implode('\',\'', array_merge(array(
                                    'IPFrom'    => $arrParts[0],
                                    'IPTo'        => $arrParts[1],
                                ), $arrLocations[$arrParts[2]])) . '\')';
                                DB::query($strSQL);
                            }
                        }
                    }
                } else {
                    echo 'Error opening file';
                }
            }
        }
    }
}
