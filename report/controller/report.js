functions.report = function (obj) {
	
	var url_string = window.location.href;
	var url = new URL(url_string);
	var dateStrUrl = url.searchParams.get("date");
	
	dateStrUrl = dateStrUrl.slice(0,-2);
	
	debugger
	
	if(obj.program == 'sw') {
		$('#lblHeader').text('รายงานสุกรคงเหลือ เดือน ' + dateStrUrl.slice(4,6) + ' / ' + dateStrUrl.slice(0,-2));
		$('#lblHeaderDetail').text('โรงเรือน ' + url.searchParams.get('cv'));
	}
	else {
		$('#lblHeader').text('รายงานอาหารเหลือ เดือน ' + dateStrUrl.slice(4,6) + ' / ' + dateStrUrl.slice(0,-2));
		$('#lblHeaderDetail').text('โรงเรือน ' + url.searchParams.get('cv'));
	}

    switch (obj.program) {
        case 'sw':
            return swReport(obj);
            break;
		case 'fd':
			return fdReport(obj);
			break;
    }
}


var swReport = function (obj) {

    var tbody = [];

    var sum = new mSw();

    for (var i = 0; i < obj.dataSource.length;i++) {
        var row = new mSw();

        row.formula(obj.dataSource[i]);

        tbody = tbody.concat(row.display({ RowProp: '' }));

        sum.increase(row);
    }
    sum.transaction_date = 'รวม';
    tbody = tbody.concat(sum.display({ RowProp: 'alt' }));

    return tbody;
}

var fdReport = function (obj) {

    var tbody = [];

    var sum = new mFd();

	var product = obj.dataSource.distinctArrayObject('product_code');
	
	for(var ip = 0, lp = product.length; ip < lp; ip++) {
		
		var aData = $.grep(obj.dataSource, function (e) { return e.product_code == product[ip]; });
		
		var mProduct = new mFd();
		mProduct.transaction_date = aData[0].product_code;
		
		for(var i = 0; i < aData.length;i++) {
			var row = new mFd();
			
			row.formula(aData[i]);
			
			tbody = tbody.concat(row.display({ RowProp: '' }));
			
			mProduct.increase(row);
		}
		
		tbody = tbody.concat(mProduct.display({ RowProp: 'alt' }));
		
		sum.increase(mProduct);

	}
	
    sum.transaction_date = 'รวม';
    tbody = tbody.concat(sum.display({ RowProp: 'alt' }));

    return tbody;
}



var mSw = function () {
    this.transaction_date = ''
    this.BfMaleQty = 0
    this.BfFemaleQty = 0
    this.ReceiveMaleQty = 0
    this.ReceiveFemaleQty = 0
    this.CatchMaleQty = 0
    this.CatchFemaleQty = 0
    this.DeadMaleQty = 0
    this.DeadFemaleQty = 0
	this.CfMaleQty = 0
	this.CfFemaleQty = 0
	this.CfQty = 0
}

mSw.prototype.formula = function (obj) {
    this.transaction_date = strDate(obj.transaction_date)
    this.BfMaleQty = obj.BfMaleQty
    this.BfFemaleQty = obj.BfFemaleQty
    this.ReceiveMaleQty = obj.ReceiveMaleQty
    this.ReceiveFemaleQty = obj.ReceiveFemaleQty
    this.CatchMaleQty = obj.CatchMaleQty
    this.CatchFemaleQty = obj.CatchFemaleQty
    this.DeadMaleQty = obj.DeadMaleQty
    this.DeadFemaleQty = obj.DeadFemaleQty
	this.CfMaleQty = obj.CfMaleQty
	this.CfFemaleQty = obj.CfFemaleQty
	this.CfQty = obj.CfQty
}

mSw.prototype.increase = function (obj) {
    this.BfMaleQty += obj.BfMaleQty
    this.BfFemaleQty += obj.BfFemaleQty
    this.ReceiveMaleQty += obj.ReceiveMaleQty
    this.ReceiveFemaleQty += obj.ReceiveFemaleQty
    this.CatchMaleQty += obj.CatchMaleQty
    this.CatchFemaleQty += obj.CatchFemaleQty
    this.DeadMaleQty += obj.DeadMaleQty
    this.DeadFemaleQty += obj.DeadFemaleQty
}

mSw.prototype.display = function (obj) {
    var td = [];
    //com
    td.push('<tr class="{0}">'.format(obj.RowProp));
    td.push('<td class="{0}" >{1}</td>'.format('', this.transaction_date));
    td.push('<td class="{0}" >{1}</td>'.format('', this.BfMaleQty.format(0)));
    td.push('<td class="{0}" >{1}</td>'.format('', this.BfFemaleQty.format(0)));
    td.push('<td class="{0}" >{1}</td>'.format('', this.ReceiveMaleQty.format(0)));
    td.push('<td class="{0}" >{1}</td>'.format('', this.ReceiveFemaleQty.format(0)));
    td.push('<td class="{0}" >{1}</td>'.format('', this.CatchMaleQty.format(0)));
    td.push('<td class="{0}" >{1}</td>'.format('', this.CatchFemaleQty.format(0)));
    td.push('<td class="{0}" >{1}</td>'.format('', this.DeadMaleQty.format(0)));
    td.push('<td class="{0}" >{1}</td>'.format('', this.DeadFemaleQty.format(0)));
	if(obj.RowProp == 'alt') {
		var totalMale = (this.BfMaleQty + this.ReceiveMaleQty) - (this.CatchMaleQty + this.DeadMaleQty);
		var totalFemale = (this.BfFemaleQty + this.ReceiveFemaleQty) - (this.CatchFemaleQty + this.DeadFemaleQty);
		td.push('<td class="{0}" >{1}</td>'.format('', totalMale.format(0)));
		td.push('<td class="{0}" >{1}</td>'.format('', totalFemale.format(0)));
		td.push('<td class="{0}" >{1}</td>'.format('', (totalMale + totalFemale).format(0)));
	}
	else {
		td.push('<td class="{0}" >{1}</td>'.format('', this.CfMaleQty.format(0)));
		td.push('<td class="{0}" >{1}</td>'.format('', this.CfFemaleQty.format(0)));
		td.push('<td class="{0}" >{1}</td>'.format('', this.CfQty.format(0)));
	}
    
    td.push('</tr>');
    return td;
}

var mFd = function () {
	this.transaction_date = ''
	this.BfQty = 0
	this.BfWgh = 0
	this.CfQty = 0
	this.CfWgh = 0
	this.IssueQty = 0
	this.IssueWgh = 0
	this.ReceiveQty = 0
	this.ReceiveWgh = 0
	this.UseQty = 0
	this.UseWgh = 0
	this.product_code = 0
}

mFd.prototype.formula = function(obj) {
	this.transaction_date = strDate(obj.transaction_date)
	this.BfQty = obj.BfQty
	this.BfWgh = obj.BfWgh
	this.CfQty = obj.CfQty
	this.CfWgh = obj.CfWgh
	this.IssueQty = obj.IssueQty
	this.IssueWgh = obj.IssueWgh
	this.ReceiveQty = obj.ReceiveQty
	this.ReceiveWgh = obj.ReceiveWgh
	this.UseQty = obj.UseQty
	this.UseWgh = obj.UseWgh
	this.product_code = obj.product_code
}

mFd.prototype.increase = function(obj) {
	this.BfQty += obj.BfQty
	this.BfWgh += obj.BfWgh
	this.IssueQty += obj.IssueQty
	this.IssueWgh += obj.IssueWgh
	this.ReceiveQty += obj.ReceiveQty
	this.ReceiveWgh += obj.ReceiveWgh
	this.UseQty += obj.UseQty
	this.UseWgh += obj.UseWgh
	this.product_code += obj.product_code
}

mFd.prototype.display = function(obj) {
	var td = [];
    td.push('<tr class="{0}">'.format(obj.RowProp));
    td.push('<td class="{0}" >{1}</td>'.format('', this.transaction_date));
    td.push('<td class="{0}" >{1}</td>'.format('', this.BfQty.format(0)));
    td.push('<td class="{0}" >{1}</td>'.format('', this.BfWgh.format(0)));
    td.push('<td class="{0}" >{1}</td>'.format('', this.ReceiveQty.format(0)));
    td.push('<td class="{0}" >{1}</td>'.format('', this.ReceiveWgh.format(0)));
    td.push('<td class="{0}" >{1}</td>'.format('', this.UseQty.format(0)));
    td.push('<td class="{0}" >{1}</td>'.format('', this.UseWgh.format(0)));
    td.push('<td class="{0}" >{1}</td>'.format('', this.IssueQty.format(0)));
    td.push('<td class="{0}" >{1}</td>'.format('', this.IssueWgh.format(0)));
	
	if(obj.RowProp == 'alt') {
		var totalQty = (this.BfQty + this.ReceiveQty) - (this.UseQty + this.IssueQty);
		var totalWgh = (this.BfWgh + this.ReceiveWgh) - (this.UseWgh + this.IssueWgh);
		td.push('<td class="{0}" >{1}</td>'.format('', totalQty.format(0)));
		td.push('<td class="{0}" >{1}</td>'.format('', totalWgh.format(0)));
	}
	else {
		td.push('<td class="{0}" >{1}</td>'.format('', CfQty.format(0)));
		td.push('<td class="{0}" >{1}</td>'.format('', CfWgh.format(0)));
	}
    
    td.push('</tr>');
    return td;
	
}

function strDate(val) {
	//20171212
	var day = val.charAt(6) + val.charAt(7);
	var month = val.charAt(4) + val.charAt(5);
	var year = val.slice(0,-4);
	
	return day+'/'+month+'/'+year;
}