functions.report = function (obj) {
	
	var url_string = window.location.href;
	var url = new URL(url_string);
	var dateStrUrl = url.searchParams.get("date");

	if(obj.report == 'sw') {
		$('#lblHeader').text('รายงานสุกรคงเหลือ เดือน ' + dateStrUrl);
	}
    $('#lblHeader').text(obj.programName);
	$('#lblHeaderDetail').text('ทดสอบภาษาไทย');

    switch (obj.program) {
        case 'sw':
            return swReport(obj);
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
}

mSw.prototype.formula = function (obj) {
    this.transaction_date = obj.transaction_date
    this.BfMaleQty = obj.BfMaleQty
    this.BfFemaleQty = obj.BfFemaleQty
    this.ReceiveMaleQty = obj.ReceiveMaleQty
    this.ReceiveFemaleQty = obj.ReceiveFemaleQty
    this.CatchMaleQty = obj.CatchMaleQty
    this.CatchFemaleQty = obj.CatchFemaleQty
    this.DeadMaleQty = obj.DeadMaleQty
    this.DeadFemaleQty = obj.DeadFemaleQty
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
    var totalMale = this.BfMaleQty + this.ReceiveMaleQty + this.CatchMaleQty + this.DeadMaleQty;
    var totalFemale = this.BfMaleQty + this.ReceiveMaleQty + this.CatchMaleQty + this.DeadMaleQty;
    td.push('<td class="{0}" >{1}</td>'.format('', totalMale.format(0)));
    td.push('<td class="{0}" >{1}</td>'.format('', totalFemale.format(0)));
    td.push('<td class="{0}" >{1}</td>'.format('', (totalMale + totalFemale).format(0)));
    td.push('<tr class="{0}">');
    return td;
}

var mFd = function () {

}