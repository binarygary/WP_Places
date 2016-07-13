<?php



function search($location) {
	$apiKey = get_option( 'WP_Places_Google_Id_Setting', '' );
	//thanks bartdyer for the simple solution
	$location=str_replace("&", "and", $location);
	$location=urlencode(trim(preg_replace("/[^0-9a-zA-Z -]/", "", $location)));
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://maps.googleapis.com/maps/api/place/autocomplete/json?input=$location&key=$apiKey");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $response=curl_exec($ch);
    $response=json_decode(stripslashes($response),true);
    
	unset($ch);
	
    if ('ZERO_RESULTS'==$response['status'] || 'INVALID_REQUEST'==$response['status']) {
      
    } else {
      $placeId=$response['predictions'][0]['place_id']; 
	  return($placeId);
    }
  }
  
  function searchGPS($location,$lat='',$lon='') {
	$apiKey = get_option( 'WP_Places_Google_Id_Setting', '' );
  	$location=urlencode(trim(preg_replace("/[^0-9a-zA-Z -]/", "", $location)));
  	$ch = curl_init();
	
	//echo "<h2>https://maps.googleapis.com/maps/api/place/textsearch/json?input=$location&key=$apiKey&location=$lat,$lon&radius=2500<h2>";
      curl_setopt($ch, CURLOPT_URL, "https://maps.googleapis.com/maps/api/place/textsearch/json?input=$location&key=$apiKey&location=$lat,$lon&radius=2500");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_VERBOSE, 0);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      $response=curl_exec($ch);
	  for ($i = 0; $i <= 31; ++$i) { 
	      $response = str_replace(chr($i), "", $response); 
	  }
	  $response = str_replace(chr(127), "", $response);
	  if (0 === strpos(bin2hex($response), 'efbbbf')) {
	     $response = substr($response, 3);
	  }
      $response=json_decode($response,true);
	  //print_r($response);
	  $placeId=$response['results'][0]['place_id'];
  	  return($placeId);
      //}
    }
  
  
  function placeDetails($placeId) {
	  if (!NULL==$placeId) {
	  $apiKey = get_option( 'WP_Places_Google_Id_Setting', '' );
	  $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "https://maps.googleapis.com/maps/api/place/details/json?placeid=$placeId&key=$apiKey");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_VERBOSE, 0);
      curl_setopt($ch, CURLOPT_HEADER, 0);
	  $gp['placeId']=$placeId;
      $response=curl_exec($ch);
	  unset($ch);
      for ($i = 0; $i <= 31; ++$i) { 
          $response = str_replace(chr($i), "", $response); 
      }
      $response = str_replace(chr(127), "", $response);
      $response = str_replace("â€“","-",$response);
    
      // This is the most common part
      // Some file begins with 'efbbbf' to mark the beginning of the file. (binary level)
      // here we detect it and we remove it, basically it's the first 3 characters 
      if (0 === strpos(bin2hex($response), 'efbbbf')) {
        $response = substr($response, 3);
      }
      $response=json_decode($response,true);
      //print_r($response);
      //$this->openNow=$response['result']['opening_hours']['open_now'];
	  if (isset($response['result'])) {
	      $gp['hours']=isset($response['result']['opening_hours']['weekday_text']) ? $response['result']['opening_hours']['weekday_text'] : '';//
	      $gp['openNow']=isset($response['result']['opening_hours']['open_now']) ? $response['result']['opening_hours']['open_now'] : '';
	      $gp['priceLevel']=isset($response['result']['price_level']) ? $response['result']['price_level'] : '';
		  $gp['name']=isset($response['result']['name']) ? $response['result']['name'] : '';//
	      $gp['rating']=isset($response['result']['rating']) ? $response['result']['rating'] : '';
	      $gp['phoneNumber']=isset($response['result']['formatted_phone_number']) ? $response['result']['formatted_phone_number'] : '';//
	      $gp['website']=isset($response['result']['website']) ? $response['result']['website'] : '';//
	      $gp['lat']=isset($response['result']['geometry']['location']['lat']) ? $response['result']['geometry']['location']['lat'] : '';
	      $gp['lng']=isset($response['result']['geometry']['location']['lng']) ? $response['result']['geometry']['location']['lng'] : '';
		  $gp['formattedAddress']=isset($response['result']['formatted_address']) ? $response['result']['formatted_address'] : '';//
	      $gp['permanentlyClosed']=isset($response['result']['permanently_closed']) ? $response['result']['permanently_closed'] : '';
		  $gp['reviews']=isset($response['result']['reviews']) ? $response['result']['reviews'] : '';
		  $gp['photos']=isset($response['result']['photos']) ? $response['result']['photos'] : '';
	  }
      
	  //print_r($gp);
	  return($gp);
  	} else  {
  		return;
  	}
    }