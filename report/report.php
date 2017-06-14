<html>
<body>

<h1>My first PHP page</h1>

    <label id="lblHeader"></label>
    <div id="divFilter">
        </div>
    <div class="row" id="containTbData">
        </div>

    <?php
    echo "Hello World!";

    $url = 'https://mservice-uat.cpf.co.th/Farm/FarmMobileRestService/FarmMobileRestService.svc/json/getreportswstock/123456789,2000020032-0-1-4-36,20170601,20170630';

    $arrContextOptions = array(
                        'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        )); 
    $content = file_get_contents($url,false, stream_context_create($arrcontextoptions));
    $result = json_decode($content, true);

    echo json_encode($result['GetReportSWStockResult']);
    $myVarValue = json_encode($result['GetReportSWStockResult']);
    ?>

</body>

<script>

	

    var myvar = <?php echo $myVarValue; ?>;
    var data = myvar;

    var obj = {};

    obj.programName = 'PROGRAM NAME';
    obj.template = 'sw';
    obj.report = 'sw';
    obj.program = 'sw';
    obj.tableStyle = '';

    $('#containTbData').createTable({
        programName: obj.programName,
        template: obj.template,
        report: obj.report,
        program: obj.program,
        tableStyle: obj.tableStyle,
        dataSource: data
    });

</script>

</html>