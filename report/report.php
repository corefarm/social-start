<html>
    <head> 
        <link href="../report/lib/css.css" rel="stylesheet" />
		<link href="../report/lib/bootstrap.min.css" rel="stylesheet" />
		<link href="../report/lib/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    </head>
<body>

    <label id="lblHeader" style="align-content: center"></label>
	
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="dTranDate" data-lang="lblTranDate" class="col-md-4 col-sm-2 control-label text-right">Date</label>
            <div class='input-group date' id='dTranDate'>
                <input type='text' id="txtTranDate" class="form-control" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </div>

    <div class="col-md-1">
        <input type="button" class="btn btn-info" value="Search" onclick="getData()" />
    </div>
    <div class="col-md-8"></div>
</div>


    <div id="divFilter">
        </div>
    <div class="datagrid" id="datagrid">
        </div>

</body>
<script src="../report/lib/jquery-2.1.0.min.js"></script>
<script src="../report/lib/report.table.js"></script>
<script src="../report/lib/bootstrap.min.js"></script>
<script src="../report/lib/bootstrap-datetimepicker.min.js"></script>
<script src="../report/lib/moment.js"></script>

<script>

	<?php
		
		// $id = $_GET['id'];
		// $cv = $_GET['cv'];
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
        
		$dataPhp = retrieveReportData($_GET['id'], $_GET['cv'], $_GET['date']);

	?>

    var myvar = <?php echo $dataPhp; ?>;
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