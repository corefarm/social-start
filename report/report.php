<html>
<body>

<h1>My first PHP page</h1>



</body>
</html>


<?php
echo "Hello World!";

$url = 'https://mservice-uat.cpf.co.th/Farm/FarmMobileRestService/FarmMobileRestService.svc/json/getreportswstock/123456789,2000020032-0-1-4-36,20170601,20170630'

// $arrContextOptions = array(
					// 'ssl' => array(
					// 'verify_peer' => false,
					// 'verify_peer_name' => false
					// ),); 
// $content = file_get_contents($url,false, stream_context_create($arrContextOptions));
// $result = json_decode($content, true);

$result = file_get_contents($url);
// Will dump a beauty json :3
var_dump(json_decode($result, true));

echo var_dump;
//echo json_encode($result[$obj['GetReportSWStockResult']]);

?>