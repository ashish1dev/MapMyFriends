<?php 
include 'facebook-php-sdk/src/facebook.php';
include'getFriendsDetails.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '120487071357795',
  'secret' => '848cf710d8a2dccabb7ed68911bede10',
  'cookie' => true,
));




// We may or may not have this data based on a $_GET or $_COOKIE based session.
//
// If we get a session here, it means we found a correctly signed session using
// the Application Secret only Facebook and the Application know. We dont know
// if it is still valid until we make an API call using the session. A session
// can become invalid if it has already expired (should not be getting the
// session back in this case) or if the user logged out of Facebook.
$session = $facebook->getSession();


$me = null;
// Session based API call.
if ($session) {
  try {
    $uid = $facebook->getUser();
	//echo $uid;
    $me = $facebook->api('/me');
	//echo $me;
	$friends=$facebook->api('/me/friends');
  } catch (FacebookApiException $err) {
    error_log($err);
   
  }
}

if ($me){
  if(count($friends)>0){
		$data=$friends['data'];
		$n=0;		
		$fid=array();
		$name=array();
		$hometown=array();
		$location=array();
		$link=array();
		$imgsrc=array();
		$uniqueLocationsForMapMarker=array();
		$locationsForMapMarker=array();
		$uniqueLatLngForMapMarker=array();
		
	for($n=0;$n<count($data)-1;$n++){
	//	for($n=0;$n<50;$n++){

			$fid[$n]=$data[$n]['id'];	

				
			$name[$n]=$data[$n]['name'];
			//echo "<br/>Processing ".$name[$n];
			
			
			$parameter='/'.$fid[$n];
			
			try{
				$detail[$n] = $facebook->api($parameter);
			
			}
			catch(Exception $e)
			  {
			// echo 'Exception Message: ' .$e->getMessage();
			  }
			$hometown[$n]=$detail[$n]['hometown']['name'];		
			//echo "hometown=".$hometown[$index];			
			$location[$n]=$detail[$n]['location']['name'];
			//if location  and hometown both are empty , then move to next record
			if(strlen($location[$n])==0 && strlen($hometown[$n])==0){
			//	$location[$n]="NULL";
			//	$hometown[$n]="NULL";
			//	continue;
			}
			
			$link[$n]="http://www.facebook.com/profile.php?id=".$fid[$n];
			$imgsrc[$n]="http://graph.facebook.com/".$fid[$n]."/picture";
	
	
	/*
	
			echo 'fid='.$fid[$n].'<br/>';
			echo 'name='.$name[$n].'<br/>';
			echo 'hometown='.$hometown[$n].'<br/>';
			echo 'location='.$location[$n].'<br/>';
			echo 'link='.$link[$n].'<br/>';
			echo 'image='."<img src='".$imgsrc[$n]."'/>".'<br/>';
			//echo 'details=>';var_dump($detail[$n]);
			echo '<br/>*************************************<br/>';
			echo '<br/>';
		*/	
			
			
			
			if($location[$n]!='' && $hometown[$n]==''){
				$latlng[$n]=getGeoCode($location[$n]);

				}
			else if($location[$n]=='' && $hometown[$n]!=''){
				$latlng[$n]=getGeoCode($hometown[$n]);

				}
			else if($location[$n]!='' && $hometown[$n]!=''){
				$latlng[$n]=getGeoCode($location[$n]);

				}
			else if($location[$n]=='' && $hometown[$n]==''){//user has kept current location and hometown field as blank onhis /her profile page
				$latlng[$n]=array('','');

				}

	/*	if(strlen($fid[$n])==0 || strlen($name[$n])==0 || strlen($hometown[$n])==0 || strlen($location[$n])==0 ||strlen($link[$n])==0 ||strlen($imgsrc[$n])==0 ||strlen($latlng[$n])==0 || strlen($uniqueLatLngForMapMarker[$n])==0||strlen($uniqueLocationForMapMarker[$n])==0){
			echo $fid[$n].",".$name[$n].",".$hometown[$n].",".$location[$n].",".$link[$n].",".$imgsrc[$n].",".$latlng[$n].",".$uniqueLatLngForMapMarker[$n].",".$uniqueLocationForMapMarker[$n] ;
			}*/
/*
		if(strlen($fid[$n])==0){
		echo $name[$n]." has fid blank has value->".$fid[$n]."<br/>";;
		} else
		if(strlen($name[$n])==0){
		echo "**name blank**".$name[$n]." has name blank has value->".$name[$n]."<br/>";;
		} else
		if(strlen($hometown[$n])==0){
		echo $name[$n]." has hometown blank has value->".$hometown[$n]."<br/>";;
		} else
		if(strlen($location[$n])==0){
		echo $name[$n]." has location blank has value->".$location[$n]."<br/>";;
		} else
		if(strlen($link[$n])==0){
		echo $name[$n]." has link blank has value->".$link[$n]."<br/>";;
		} else
		if(strlen($imgsrc[$n])==0){
		echo $name[$n]." has ingsrc blank has value->".$imgsrc[$n]."<br/>";;
		} else
		if(strlen($latlng[$n])==0){
		echo $name[$n]." has latlng blank has value->".$latlng[$n]."<br/>";;
		} else
		if(strlen($uniqueLatLngForMapMarker[$n])==0){
		echo $name[$n]." has uniqueLatLngForMapMarker blank has value->".$uniqueLatLngForMapMarker[$n]."<br/>";;
		} else
		if(strlen($uniqueLocationForMapMarker[$n])==0){
		echo $name[$n]." has uniqueLocationForMapMarker blank has value->".$uniqueLocationForMapMarker[$n]."<br/>";
		} 	
*/		
		
		//usleep(1000000);
	}
			 
			$uniqueLatLngForMapMarker=removeMultipleEntriesFromArray($latlng);

			for($n=0;$n<count($uniqueLatLngForMapMarker);$n++){
			$uniqueLocationForMapMarker[$n]=getLocationFromDatabase($uniqueLatLngForMapMarker[$n][0],$uniqueLatLngForMapMarker[$n][1]);
			}
			
			
			
			$friendsData=array($fid,$name,$hometown,$location,$link,$imgsrc,$latlng,$uniqueLatLngForMapMarker,$uniqueLocationForMapMarker);
			//echo $friendsData;
			echo json_encode($friendsData);			
	}	
}?>

	 
	