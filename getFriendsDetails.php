<?php
include'includes/config.php';
include'includes/openDB.php'; 
	//$placeName=$_POST["place"];
	function getGeoCodeJSON($placeName){
	
			$placeName1 = str_replace(" ", "%20", $placeName);
			$jsonurl = "http://where.yahooapis.com/geocode?location=".$placeName1."&flags=J&appid=dj0yJmk9aFVuOEptNWhlR0VsJmQ9WVdrOVVrVjNhbloyTkdjbWNHbzlNakV6TkRBeE9UVTJNZy0tJnM9Y29uc3VtZXJzZWNyZXQmeD1lNg--";
			
			//echo '<br/>'.'yahoo geocoding used for '.$placeName.'<br/>';
			$json = file_get_contents($jsonurl);
			$json_output = json_decode($json);
			return $json_output;
	}

	function getLat($json_output){	
		$lat=$json_output->ResultSet->Results[0]->latitude;	
		return $lat;
	} 

	function getLon($json_output){
		$lng=$json_output->ResultSet->Results[0]->longitude;
		return $lng;
	}
	function insertIntoDB_geocode_table($placeName,$lat,$lng){
		$insertSql="Insert Into geocode (location,lat,lng) Values ('$placeName',$lat,$lng) ;";
	//	echo 'insertSql = '.$insertSql.'<br/>';		
		$isInserted = mysql_query($insertSql);
		return $isInserted;
	}
	
	function searchInDataBase($placeName){
		$searchQuery="Select lat,lng from geocode where location='$placeName';";
	//	echo 'seachQuery='.$searchQuery.'<br/>';
		$resultSet=mysql_query($searchQuery);		
		$row=mysql_fetch_row($resultSet);
		
		if(mysql_num_rows($resultSet)==0){
		//	echo 'No result Found for location='.$placeName.'<br/>';
		//	echo 'going to web to geocode anf then will store in db for future'.'<br/>';
			$json_output=getGeoCodeJSON($placeName);
			$lat=getLat($json_output);
			$lng=getLon($json_output);
			$isInserted=insertIntoDB_geocode_table($placeName,$lat,$lng);
		//	echo 'inserted = '.$isInserted;
		//	echo "placeName=".$placeName."=> lat=".$lat.",lng=".$lng.'<br/>';
		}
		else{
		//	echo 'found '.$placeName.' in DB lat='.$row[0].", lng=".$row[1];
			$lat=$row[0];
			$lng=$row[1];
		}
	//	echo '<br/>';		
		mysql_free_result($resultSet);
		$latlng=array($lat,$lng);
		return $latlng;
		
		
	}
	
	function getLocationFromDatabase($lat,$lng){
		$searchQuery="Select location from geocode where lat='$lat' AND lng='$lng';";
		//	echo 'seachQuery='.$searchQuery.'<br/>';
		$resultSet=mysql_query($searchQuery);		
		$row=mysql_fetch_row($resultSet);
		$location=$row[0];
		return $location;

	}
	
	function getGeoCode($placeName){
		$latlng=searchInDataBase($placeName);
		return $latlng;
	}
	
	function removeMultipleEntriesFromArray($d){
		$e=array();
		$found=0;	
		$n=0;
		for($n=0;$n<count($d);$n++){
			$found=0;
			for($j=0;$j<count($e);$j++){
				if($d[$n][0]!=''){
					if($d[$n]==$e[$j]){
						$found++;
					}
				}
			}
			if($found==0 && $d[$n][0]!=''){		
				$e[count($e)]=$d[$n];
			}
		}
		return $e;
	}

?>