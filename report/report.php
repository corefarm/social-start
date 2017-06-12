<html>
<body>

<h1>My first PHP page</h1>

<?php
echo "Hello World!";

$url = 'https://mservice-uat.cpf.co.th/Farm/FarmMobileRestService/FarmMobileRestService.svc/json/getreportswstock/123456789,2000020032-0-1-4-36,20170601,20170630'

$arrContextOptions = array(
					'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					),); 
//$content = file_get_contents($url,false, stream_context_create($arrContextOptions));
//$result = json_decode($content, true);


?>

</body>
</html>