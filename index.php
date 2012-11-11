<?php

//require 'getFBaccess.php';
?>

<?php

	include 'facebook-php-sdk/src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '120487071357795',
  'secret' => '848cf710d8a2dccabb7ed68911bede10',
  'cookie' => true,
));

//echo '<br/>'.'facebook=';
//var_dump($facebook);


// We may or may not have this data based on a $_GET or $_COOKIE based session.
//
// If we get a session here, it means we found a correctly signed session using
// the Application Secret only Facebook and the Application know. We dont know
// if it is still valid until we make an API call using the session. A session
// can become invalid if it has already expired (should not be getting the
// session back in this case) or if the user logged out of Facebook.
$session = $facebook->getSession();

//echo '<br/>';
//var_dump($session);





$me = null;
// Session based API call.
if ($session) {
  try {
    $uid = $facebook->getUser();
    $me = $facebook->api('/me');
	$friends=$facebook->api('/me/friends');
  } catch (FacebookApiException $e) {
    error_log($e);
  }
}



// login or logout url will be needed depending on current user state.
if ($me) {
  $logoutUrl = $facebook->getLogoutUrl();
  
} else {
  $loginUrl = $facebook->getLoginUrl();
}

?>   
  



<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<link href="http://code.google.com/apis/maps/documentation/javascript/examples/standard.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">


	var map;
	 var bounds = new google.maps.LatLngBounds();
	 
	 
	function initialize() {
	  var myLatLng = new google.maps.LatLng(13,77.5);
	  var myOptions = {
		zoom: 2,
		center: myLatLng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	  };
	  map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
	  
	}

	var session;
	var facebook='';

	
	function getFBData(){
		//alert('1');
		xmlhttpPost2("getFBaccess.php");
		//alert('2');
	}


	var uniqueLatLngForMapMarker=new Array();
	var uniqueLocationForMapMarker=new Array();

	function xmlhttpPost2(strURL) {
		var xmlHttpReq = false;
		var self = this;
		// Mozilla/Safari
		if (window.XMLHttpRequest) {
			self.xmlHttpReq = new XMLHttpRequest();
		}
		// IE
		else if (window.ActiveXObject) {	
			self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
		}
		self.xmlHttpReq.open('POST', strURL, true);
		self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		self.xmlHttpReq.onreadystatechange = function() {	
			if (self.xmlHttpReq.readyState == 4) {
			//	alert('response received, now ploting !!!');
				//alert(self.xmlHttpReq.responseText);
				//document.getElementById("FBresult").innerHTML=self.xmlHttpReq.responseText;
				updatepage(self.xmlHttpReq.responseText);		
				document.getElementById("FBresult").innerHTML="";
				
			}
		}	
		self.xmlHttpReq.send("facebook="+facebook);
	}
 
	function ltrim(str) { 
		for(var k = 0; k < str.length && isWhitespace(str.charAt(k)); k++);
		return str.substring(k, str.length);
	}
	
	function rtrim(str) {
		for(var j=str.length-1; j>=0 && isWhitespace(str.charAt(j)) ; j--) ;
		return str.substring(0,j+1);
	}
	
	function trim(str) {
		return ltrim(rtrim(str));
	}
	
	function isWhitespace(charToCheck) {
		var whitespaceChars = " \t\n\r\f";
		return (whitespaceChars.indexOf(charToCheck) != -1);
	}
	
	function updatepage(str){			
		//	alert('str length without trim='+str.length);
			str=trim(str);
		//	alert('str length after trim='+str.length);
			
			
			
			
			//document.getElementById("FBresult").innerHTML =str+"</br>";
			var response=new Array();
			response=eval(str);
		//	alert("response ="+response );
			
			//if(typeof response=="undefined"){
			//	alert("response is undefined !!!");
			//}
			/*alert("0"+response[0]);
			alert("1"+response[1]);
			alert("2"+response[2]);
			alert("3"+response[3]);
			alert("4"+response[4]);
			alert("5"+response[5]);
			alert("6"+response[6]);
			alert("7"+response[7]);
			alert("8"+response[8]);
			
alert("fid = response[0]=>"+response[0]+"</br>**************************<br/>"+"name = response[1]=>"+response[1]+"</br>**************************<br/>"+"hometown = response[2]=>"+response[2]+"</br>*********** ***************<br/>"+"location = response[3]=>"+response[3]+"</br>**************************<br/>"+"link = response[4]=>"+response[4]+"</br>**************************<br/>"+"imgsrc = response[5]=>"+response[5]+"</br>**** **********************<br/>"+"latlng = response[6]=>"+response[6]+"</br>**************************<br/>"+"uniqueLatLngForMapMarker = response[7]=>"+response[7]+"</br>**************************<br/>"+"uniqueLocationForMapMarker =response[8]="+response[8]+"</br>****** ******************** <br/>"+"response[7] length ="+response[7].length+"; response[8] length ="+response[8].length);
			*/
			
//document.getElementById("FBresult").innerHTML ="fid = response[0]=>"+response[0]+"</br>**************************<br/>"+"name = response[1]=>"+response[1]+"</br>**************************<br/>"+"hometown = response[2]=>"+response[2]+"</br>**************************<br/>"+"location = response[3]=>"+response[3]+"</br>**************************<br/>"+"link = response[4]=>"+response[4]+"</br>**************************<br/>"+"imgsrc = response[5]=>"+response[5]+"</br>**************************<br/>"+"latlng = response[6]=>"+response[6]+"</br>**************************<br/>"+"uniqueLatLngForMapMarker = response[7]=>"+response[7]+"</br>**************************<br/>"+"uniqueLocationForMapMarker =response[8]="+response[8]+"</br>**************************<br/>"+"response[7] length ="+response[7].length+"; response[8] length ="+response[8].length;

  // alert("Marking your friends over map ...");
			//alert('array a = '+response[0]);
			//alert('array b = '+response[1]);
			
			//making markers on map
			var index=0;			
			
			// Create the markers ad infowindows.
		/*	for (index in response[7]) {
				uniqueLatLngForMapMarker[index]=new google.maps.LatLng(response[7][index][0],response[7][index][1]);
				uniqueLocationForMapMarker[index]=response[8][index];
				
				//alert(index+"=>"+response[7][index][0]+","+response[7][index][1]);
				var info =uniqueLocationForMapMarker[index];
				//addMarker(uniqueLatLngForMapMarker[index],info);			
			}			 
		*/	
	//	alert("response[7]= "+response[7]);
	//		alert("response[7].length = "+response[7].length);

		if(typeof response!="undefined"){
			for (index in response[7]){
			
			//// Create the markers ad infowindows. city's location marker & infowindow
			uniqueLatLngForMapMarker[index]=new google.maps.LatLng(response[7][index][0],response[7][index][1]);
				uniqueLocationForMapMarker[index]=response[8][index];				
			//	alert(index+"=>"+response[7][index][0]+","+response[7][index][1]);
				
			// friends location marker & infowindow
				var i=0;
				var usersFromSameLocation=new Array();
				var imgsrc=new Array();
				var link=new Array();
				var index_usersFromSameLocation=0;
				var location=new Array();
				var hometown=new Array();
				var usersLocation=new Array();
				
				for(i in response[6]){
					if(response[7][index][0]==response[6][i][0] && response[7][index][1]==response[6][i][1]){
					usersFromSameLocation[index_usersFromSameLocation]=response[1][i];
					imgsrc[index_usersFromSameLocation]=response[5][i];
					link[index_usersFromSameLocation]=response[4][i];
					location[index_usersFromSameLocation]=response[3][i];
					hometown[index_usersFromSameLocation]=response[2][i];
					if(location[index_usersFromSameLocation]!=''){
						usersLocation[index_usersFromSameLocation]=location[index_usersFromSameLocation];
					}
					else if(hometown[index_usersFromSameLocation]!=''){
						usersLocation[index_usersFromSameLocation]=hometown[index_usersFromSameLocation];
					}
					else {
						usersLocation[index_usersFromSameLocation]='';
					}
					
					index_usersFromSameLocation++;
					}					
				}

				if(index_usersFromSameLocation>=2){
					drawPolygon(index_usersFromSameLocation,response[7][index][0],response[7][index][1],0.3910,usersFromSameLocation,imgsrc,link,usersLocation);			
				}
				else if(index_usersFromSameLocation==1){
					info=usersFromSameLocation[0];
					var singleUserLatLng=new google.maps.LatLng(response[7][index][0],response[7][index][1]);
					addMarker(singleUserLatLng,info,imgsrc[0],link[0],usersLocation[0]);	
				}
				
				var location_info ="<b>"+uniqueLocationForMapMarker[index]+ " has "+index_usersFromSameLocation+" of your friends : </b>"+usersFromSameLocation;
				addMarker(uniqueLatLngForMapMarker[index],location_info,"");	
			}
			document.getElementById("FBresult").innerHTML="";
	}
	else{
		alert("Could not load data from Facebook... trying again later... ");
	document.getElementById("FBresult").innerHTML="<h3>Oops ! something went wrong while loading your data from Facebook. <br/> Do try again later, we guarantee you will like this app. !</h3>";
	}
  }

	function getAngledCoordinates_x(center_x,radius,theta){
		
		var newX=(center_x*1)+((radius*(Math.cos(theta)))*1);
		//alert("cos("+theta+")"+(Math.cos(theta))+"newX="+newX);
		return newX;
	}

	function getAngledCoordinates_y(center_y,radius,theta){
		var newY=(center_y*1)+((radius*(Math.sin(theta)))*1);
		//alert("newY="+newY);
		return newY;
	}


	
	
	var infowindow;
	
	
	function drawPolygon(vertices,center_x,center_y,radius,usersFromSameLocation,imgsrc,link,user_Location){
		var latlng=new Array();
		var polygonCoords=new Array();
		var x=new Array();
		var y=new Array();
		//alert("vertices = "+vertices+", cen_x = "+center_x+",cen_y = "+center_y+", radi= "+radius);
		var deltaTheta=(360)/vertices;
		var theta=0;
		for(var i=0;i<vertices;i++){
			theta=i*(deltaTheta)*(Math.PI/180);
			//alert('theta='+theta);
			x[i]=getAngledCoordinates_x(center_x,radius,theta);
			y[i]=getAngledCoordinates_y(center_y,radius,theta);
				
			latlng[i]=new google.maps.LatLng(x[i],y[i]);
			//alert('inside drawPolygon() => '+latlng[i]);
			polygonCoords[i]=latlng[i]; 
			}
		
		var index_polygonCoords=0;
		// Create the markers ad infowindows.
		
		for (index_polygonCoords in polygonCoords) {
			var info =usersFromSameLocation[index_polygonCoords];
			//alert('1');
	addMarker(polygonCoords[index_polygonCoords],info,imgsrc[index_polygonCoords],link[index_polygonCoords],user_Location[index_polygonCoords]);		
		}
		
		addPolygon(polygonCoords,usersFromSameLocation);
		
		
	/*	var polygon;
		polygon = new google.maps.Polygon({
			paths: polygonCoords,
			strokeColor: "#FF0000",
			strokeOpacity: 0.2,
			strokeWeight: 3,
			fillColor: "#FF0000",
			fillOpacity: 0.20
		});

		polygon.setMap(map);
		// Add a listener for the click event
		google.maps.event.addListener(polygon, 'click', showArrays);
		infowindow = new google.maps.InfoWindow();
		*/
	//	alert('polygon drawing completed!');
	}

	
	function addPolygon(polyCoordinates,info_location){
		var polygon;
		polygon = new google.maps.Polygon({
			paths: polyCoordinates,
			strokeColor: "#FF0000",
			strokeOpacity: 0.2,
			strokeWeight: 3,
			fillColor: "#FF0000",
			fillOpacity: 0.20
		});

		polygon.setMap(map);
		var content="hi";
		//var infowindow = new google.maps.InfoWindow();
		  var infowindow = new google.maps.InfoWindow({
			content: content
		  });
		// Add a listener for the click event
		google.maps.event.addListener(polygon, "click", function(){
		
	//	  var vertices = this.getPath();
/*
	  var contentString = "<b> Polygon</b><br />";
	  contentString += "Clicked Location: <br />" + event.latLng.lat() + "," + event.latLng.lng() + "<br />";

	  // Iterate over the vertices.
	  for (var i =0; i < vertices.length; i++) {
		var xy = vertices.getAt(i);
		contentString += "<br />" + "Coordinate: " + i + "<br />" + xy.lat() +"," + xy.lng();
	  }
*/
	  // Replace our Info Window's content and position
	  //infowindow.setContent("hi");
	  //infowindow.setPosition(event.latLng);

	  infowindow.open(map,polygon);
	  
		});
		
	}
	
/*	function showArrays(event) {
	  // Since this Polygon only has one path, we can call getPath()
	  // to return the MVCArray of LatLngs
	  var vertices = this.getPath();

	  var contentString = "<b> Polygon</b><br />";
	  contentString += "Clicked Location: <br />" + event.latLng.lat() + "," + event.latLng.lng() + "<br />";

	  // Iterate over the vertices.
	  for (var i =0; i < vertices.length; i++) {
		var xy = vertices.getAt(i);
		contentString += "<br />" + "Coordinate: " + i + "<br />" + xy.lat() +"," + xy.lng();
	  }

	  // Replace our Info Window's content and position
	  infowindow.setContent(contentString);
	  infowindow.setPosition(event.latLng);

	  infowindow.open(map);
	}
*/
	
	 function addMarker(coordinates,info,image,hyperlink,locationName) {
	  
	   
		  // Create the marker
		  var marker = new google.maps.Marker({
			position: coordinates,
			map: map,
			title: info,
			icon: image
		  });    
	   
	   
	 
			bounds.extend(coordinates);
			map.fitBounds(bounds);
	
	 
		  // Create the infowindow with two DIV placeholders
		  // One for a text string, the other for the StreetView panorama.
		  var content = document.createElement("DIV");
		  var title = document.createElement("DIV");
		  

		  if(hyperlink!='' && image!=''){
				if(locationName=="null" || locationName==null){
					locationName="";
				}
				title.innerHTML = "<img src="+image+" border='0'/><br/>"+info+",<br/>"+locationName+"<br/><a href="+hyperlink+" target='_blank'>FaceBook Profile</a>";//data.name;
		  }else{
				title.innerHTML = info;
		  } 
		  content.appendChild(title);
		
		  var infowindow_marker = new google.maps.InfoWindow({
			content: content
		  });
		
		  // Open the infowindow on marker click
		  google.maps.event.addListener(marker, "click", function() {
			infowindow_marker.open(map, marker);
		  });
		  
		
    }
</script>
</head>
<body onload="initialize();"  >

 <div id="fb-root"></div>
 <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId   : '<?php echo $facebook->getAppId(); ?>',
          session : <?php echo json_encode($session); ?>, // don't refetch the session when PHP already has it
          status  : true, // check login status
          cookie  : true, // enable cookies to allow the server to access the session
          xfbml   : true // parse XFBML
        });

        // whenever the user logs in, we refresh the page
        FB.Event.subscribe('auth.login', function() {
          window.location.reload();
		 // alert("login success");
			  
		//  
        });
		
		 FB.Event.subscribe('auth.logout', function (response) {
                // do something with response
               // alert("logout success");
				 window.location.reload();
            });
			
		FB.getLoginStatus(function (response) {
                if (response.session) {
                    // logged in and connected user, someone you know
                //    alert("login success, going to get your FB friends data");
                // window.location.reload();
					getFBData();
                }
            });


      };

      (function() {
        var e = document.createElement('script');
        e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
        e.async = true;
        document.getElementById('fb-root').appendChild(e);
      }());
    </script>
	
    <?php //if ($me): ?>

    <!--
    <div id="logout" align="center" ><br/><a href="<?php echo $logoutUrl; ?>">
      <img src="http://static.ak.fbcdn.net/rsrc.php/z2Y31/hash/cxrz4k7j.gif" border="0">
    </a>
    </div> 
    -->



	
<!--	
<label>place =</label><input type="text" id="place" />
<input type="button" value="getGeoData" onClick="getGeoData()"/>
<div id="result"></div>
<br/>
<br/>
<input type="button" value="Get FB Friends Data" onClick="getFBData()" />
-->


  <!--Using JavaScript &amp; XFBML: -->

<div id="FBresult" align="center" >

   <?php if (!$me): ?> 
   
   	<fb:login-button perms="user_hometown,user_location,friends_hometown,friends_location,publish_stream">Click to see your friends over map</fb:login-button>	  
	
	
    
     
    <?php else: ?>
	<h2>Welcome,<img src="https://graph.facebook.com/<?php echo $uid; ?>/picture"><?php echo $me['name']; ?>, Please be patient while we plot your <?php echo count($friends['data']);?> friends on map.</h2><h4>(that should take approximately 60 seconds)</h4><h2>Once done Zoom-In to your friends location and see how they are spread across the world map.</h2><img src='loading.gif'/>
	<?php endif ?>
</div>

<div id="map_canvas" style="width:100%; height:100%;"></div>

<!--<div id="FBresult"></div>-->

</body>
</html>