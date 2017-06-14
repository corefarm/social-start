functions.report = function (obj) {
    debugger

    $('#lblHeader').text(obj.programName);

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

    //var aOperId = obj.dataSource.distinctArrayObject('OPER_ID').sort();

    //var mTotal = new mDamage();
    //mTotal.GRADING = 'TOTAL THAILAND';

    //for (var iOperId = 0, lOperId = aOperId.length; iOperId < lOperId; iOperId++) {

    //    var aOperData = $.grep(obj.dataSource, function (e) { return e.OPER_ID == aOperId[iOperId]; });
    //    var aSubOperId = aOperData.distinctArrayObject('SUB_OPER_ID').sort();

    //    var mOper = new mDamage();
    //    mOper.GRADING = 'TOTAL {0}'.format(aOperData[0].OPER_NAME);

    //    for (var iSubOperId = 0, lSubOperId = aSubOperId.length; iSubOperId < lSubOperId; iSubOperId++) {

    //        var aSubOperData = $.grep(aOperData, function (e) { return e.SUB_OPER_ID == aSubOperId[iSubOperId]; });
    //        var aOrgId = aSubOperData.distinctArrayObject('ORG_CODE').sort();

    //        var mSubOper = new mDamage();
    //        mSubOper.GRADING = aSubOperData[0].SUB_OPER_NAME;

    //        for (var iOrgId = 0, lOrgId = aOrgId.length; iOrgId < lOrgId; iOrgId++) {

    //            var aOrgData = $.grep(aSubOperData, function (e) { return e.ORG_CODE == aOrgId[iOrgId]; });

    //            var mOrg = new mDamage();
    //            mOrg.GRADING = aOrgData[0].ORG_SHORT_NAME;

    //            for (var i = aOrgData.length; i--;) {

    //                var row = new mDamage();

    //                row.formula(aOrgData[i]);

    //                mOrg.increase(row);
    //            }

    //            mSubOper.increase(mOrg);

    //            tbody = tbody.concat(mOrg.display({ RowProp: '' }));
    //        }

    //        mOper.increase(mSubOper);

    //        tbody = tbody.concat(mSubOper.display({ RowProp: 'trLevel02' }));
    //    }
    //    mTotal.increase(mOper);

    //    tbody = tbody.concat(mOper.display({ RowProp: 'trLevel03' }));
    //}
    //if (aOperId.length > 0) {
    //    tbody = tbody.concat(mTotal.display({ RowProp: 'trLevel04' }));
    //}

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