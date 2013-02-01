<?php
	/* Sample App: SMS Auto Responder */
	/* TODO: EDIT Your User Id, Token and Secret */
	/*------------------------------------------- */
	$userID = "u-phn4phn4phn4phn4phn4phn"; 
	$token = "t-phn4phn4phn4phn4phn4phn4";
	$secret = "phn4phn4phn4phn4phn4phn4phn4phn4phn4phn4";
	$responseMsg = "Hi there, thanks for registering, visit us http://url.com";
	/*------------------------------------------- */
	
	/* NOTE: YOU DO NOT NEED TO EDIT ANYTHING BELOW THIS LINE */
	$restMessagesURI = "https://api.catapult.inetwork.com/v1/users/$userID/messages"; // Set the API URI base

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
		//parse the POST body and decode the JSON body into fields
		$input = json_decode(file_get_contents("php://input"), true);
		echo "Text: ".$input;

		// get the incoming SMS details
		$your_bandwidth_phone_num = $input['to'];
		$from_phone_num = $input['from'];
		$txt_msg_received = $input['text'];
		
		// Create JSON to SEND an SMS using Bandwidth API /messages 
		$data = array(  "from" => $your_bandwidth_phone_num, 
		                "to" =>   $from_phone_num,
		                "text" => $responseMsg); 

		// Pack the data into a JSON-friendly array
		$data_string = json_encode($data); 

		//check if you have curl loaded
		if(!function_exists("curl_init")) {die("cURL extension is not installed");}
		
		$ch=curl_init($restMessagesURI);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);    // Stop if an error occurred
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

		// We need to authenticate to Catapult here
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $token . ":" . $secret);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'Content-Type: application/json',
		    'Content-Length: ' . strlen($data_string)
		    )); 
		curl_exec($ch);

		if(curl_errno($ch)) {           // Let's check for errors
		    $headers = (curl_getinfo($ch));
		    echo ("Died with error - HTTP ".$headers['http_code']."\n");
		    }

		curl_close($ch);		
    }
    else
    {
	    echo ("Bandwidth API Sample App: SMS Auto-Responder");
    }

?>

