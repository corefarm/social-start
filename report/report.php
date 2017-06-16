<html>
    <head> 
        <link href="../report/lib/css.css" rel="stylesheet" />
    </head>
<body>

    <label id="lblHeader" style="align-content: center"></label>
    <div id="divFilter">
        </div>
    <div class="datagrid" id="datagrid">
        </div>

</body>
<script src="../report/lib/jquery-2.1.0.min.js"></script>
<script src="../report/lib/report.table.js"></script>

<script>

	<?php
		
		// $id = $_GET['id'];
		// $cv = $_GET['cvFarm'];
		// $date = $_GET['date'];
		
		function retrieveReportData ($id, $cv, $date) {
			
			//$url = 'https://mservice-uat.cpf.co.th/Farm/FarmMobileRestService/FarmMobileRestService.svc/json/getreportswstock/123456789,2000020032-0-1-4-36,20170601';

			$url = 'https://mservice-uat.cpf.co.th/Farm/FarmMobileRestService/FarmMobileRestService.svc/json/getreportswstock/'.$id.','.$cv.','.$date;

			
			$arrContextOptions = array(
								'ssl' => array(
								'verify_peer' => false,
								'verify_peer_name' => false,
								)); 
			$content = file_get_contents($url,false, stream_context_create($arrcontextoptions));
			$result = json_decode($content, true);

			echo json_encode($result['GetReportSWStockResult']);
			$ret = json_encode($result['GetReportSWStockResult']);
			
			return $ret;
		}
        
	?>

    var myvar = <?php echo retrieveReportData($_GET['id'], $_GET['cvFarm'], $_GET['date']); ?>;
    var data = myvar;

    var obj = {};

    obj.programName = 'รายงาน';
    obj.template = 'sw';
    obj.report = 'report';
    obj.program = 'sw';
    obj.tableStyle = '';

    $('#datagrid').createTable({
        programName: obj.programName,
        template: obj.template,
        report: obj.report,
        program: obj.program,
        tableStyle: obj.tableStyle,
        dataSource: data
    });

</script>

</html>