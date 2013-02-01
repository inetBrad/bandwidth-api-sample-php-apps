<?php
	/* Sample App: Basic Inbound IVR Application */
	/* TODO: EDIT Your User Id, Token and Secret */
	/*------------------------------------------- */
	$userID = "u-phn4phn4phn4phn4phn4phn"; 
	$token = "t-phn4phn4phn4phn4phn4phn4";
	$secret = "phn4phn4phn4phn4phn4phn4phn4phn4phn4phn4";
	
	$greeting = "Hello. Press one or press two.";
	$one = "You pressed one";
	$two = "You pressed two";
	$sorry =  "Sorry, please try again, press one or press two.";
	
	/* NOTE: YOU DO NOT NEED TO EDIT ANYTHING BELOW THIS LINE */
	$restBaseURI = "https://api.catapult.inetwork.com/v1/users/$userID"; // Set the API URI base

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
		//parse the POST body and decode the JSON body into fields
		$input = json_decode(file_get_contents("php://input"), true);
		$call_id = $input['callId'];		
		$event_type = $input['eventType'];		
		
		switch ($event_type) {
			case 'answer':
				// execute a speak event, asking for DTMF input
				$data = array("sentence" => $greeting);
				break;

			case 'dtmf':

				if ($input['dtmfDigit'] == "1" ) {
					$data = array("sentence" => $one);
				}
				elseif ($input['dtmfDigit'] == "2" ) {
					$data = array("sentence" => $two);
				}
				else {
					$data = array("sentence" => $sorry);
				}
				break;

			default:
				exit;

		}  // end switch


		// set the resource for Text-To-Speech to the current call 
		$resource = "/calls/".$call_id."/audio";  							

		// Pack the data into a JSON-friendly array
		$data_string = json_encode($data); 
					
		$ch=curl_init($restBaseURI.$resource);
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
		    die ("Died with error - HTTP ".$headers['http_code']."\n");
		    }

		curl_close($ch);
					
    }
	// NOT A POST from the Bandwidth API callbacks or apps, must be a GET from a web browser, ignore it
    else  { 
		echo "Sample App: Basic Inbound IVR Application";
    }
?>


