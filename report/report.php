<html>
    <head> 
        <link href="../report/lib/css.css" rel="stylesheet" />
		<link href="../report/lib/bootstrap.min.css" rel="stylesheet" />
		<link href="../report/lib/bootstrap-datetimepicker.min.css" rel="stylesheet" />
		
    </head>
<body>

	<div class="row">
		<center><label id="lblHeader" class="col-md-12" style="padding-top: 25px;"></label></center>
		
	</div>
	<div class="row">
		<center><label id="lblHeaderDetail" class="col-md-12" style="padding: 10px;"></label></center>
	</div>

	
	<div class="container">

        <div class="col-md-6 col-sm-6">
            <div class="form-group">
                <div class='input-group date col-md-4' id='dTranDate'>
                    <input type='text' id="txtTranDate" class="form-control" />
                    <span class="input-group-addon">
                        <span id="glyp-icon" class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
			
        </div>
		<div class="col-md-6 col-sm-6">
			<input type="button" class="btn btn-info" value="Search" onclick="retrievePhpfunc()" />
		</div>

    </div>


    <div id="divFilter">
        </div>
    <div class="datagrid" id="datagrid">
        </div>

</body>
<script src="../report/lib/jquery-2.1.0.min.js"></script>
<script src="../report/lib/report.table.js"></script>
<script src="../report/lib/moment.js"></script>
<script src="../report/lib/bootstrap.min.js"></script>
<script src="../report/lib/bootstrap-datetimepicker.min.js"></script>


<script>

	<?php
		
		// $id = $_GET['id'];
		// $cv = $_GET['cv'];
		// $date = $_GET['date'];
		
		
				
		function retrieveReportData ($type, $id, $cv, $date) {
			
			//$url = 'https://mservice-uat.cpf.co.th/Farm/FarmMobileRestService/FarmMobileRestService.svc/json/getreportswstock/123456789,2000020032-0-1-4-36,20170601';
			
			$url = 'https://mservice-uat.cpf.co.th/Farm/FarmMobileRestService/FarmMobileRestService.svc/json/'.$type.'/'.$id.','.$cv.','.$date;
			
			error_log($url);
			
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
        
		$dataPhp = retrieveReportData($_GET['type'],$_GET['id'], $_GET['cv'], $_GET['date']);

	?>

	
	$('#dTranDate').datetimepicker({
		format: 'DD/MM/YYYY'
		 , pickTime: false,
		viewMode: "months",
		minViewMode: "months"
	});
	
	$('#txtTranDate').click(function(event){
		event.preventDefault();
		$('#glyp-icon').click();
	});
	
	function retrievePhpfunc() {
		var dateJs = $('#txtTranDate').val();
		
		var spliteDate = dateJs.split('/');
		
		var url = window.location.href;
		url = url.slice(0,-8) + (spliteDate[2] + spliteDate[1] + spliteDate[0]);
		
		window.location.replace(url);
	}
	
    var myvar = <?php echo $dataPhp; ?>;
    var data = myvar;

	var url_string = window.location.href;
	var url = new URL(url_string);
	var typeStrUrl = url.searchParams.get("type");
	
    var obj = {};

    obj.programName = 'รายงานภาษาไทย';
    obj.template = (typeStrUrl == 'getreportswstock' ? 'sw' : 'fd');
    obj.report = 'report';
    obj.program = (typeStrUrl == 'getreportswstock' ? 'sw' : 'fd');
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