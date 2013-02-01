<?php

	/* Sample App: Sending a SMS Alert*/
	/* TODO: EDIT Your User Id, Token and Secret */
	/*------------------------------------------- */
	$userID = "u-phn4phn4phn4phn4phn4phn"; 
	$token = "t-phn4phn4phn4phn4phn4phn4";
	$secret = "phn4phn4phn4phn4phn4phn4phn4phn4phn4phn4";

	// get the incoming SMS details
	$your_bandwidth_phone_num = "+15554443333";  // you must use a Bandwidth number you have added to your account
	$to_phone_num = "+15554443333"; // this is the phone number you want to send an SMS to
	$smsMsgToSend = "Hi world, thanks for the message, visit us http://url.com"; // max length is 160 characters

	/*--- NOTE: YOU DO NOT NEED TO EDIT ANYTHING BELOW THIS LINE ---- */
	/*--------------------------------------------------------------- */	

	/* Here is the REST resource for messages, we are going to POST via https to it:
	--
	--  	POST /v1/{userId}/messages
	-- 			{
	--  				"from": "+15554443333",
	--  				"to": "+15554443333",
	--  				"text": "Good morning, this is a test message"
	--			}
	--
*/

	// Set the URL for the messages resource */
	$restMessagesURI = "https://api.catapult.inetwork.com/v1/users/$userID/messages"; // Set the API URI

	// Create JSON request to with "from", "to" and "text" to send an SMS 
	$data = array(  "from" => $your_bandwidth_phone_num, 
	                "to" =>   $to_phone_num,
	                "text" => $smsMsgToSend); 

	// Pack the data into a JSON-friendly array
	$data_string = json_encode($data); 

	// this is PHP code to create an HTTPS POST request
	$ch=curl_init($restMessagesURI);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_HEADER, TRUE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	    'Content-Type: application/json',
	    'Content-Length: ' . strlen($data_string)
	    )); 
	curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);    // Stop if an error occurred

	// Security: We need to authenticate to BASIC here
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, $token . ":" . $secret);

	// Body: let's POST the body with the JSON array we created
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

	// Run the HTTP POST request to send a SMS 
	curl_exec($ch);

	// Let's check for errors
	if(curl_errno($ch)) {           
	    $headers = (curl_getinfo($ch));
	    die ("Died with error - HTTP ".$headers['http_code']."\n");
	    }

	//clean-up...done.
	curl_close($ch);		

?>

