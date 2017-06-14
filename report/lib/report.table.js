var functions = [];
var exStrHeader;
var exTableStyle;
$.fn.extend({
    createTable: function (obj) {

        var strHeader = retrieveText('../report/template/{0}.html'.format(obj.template));
        require('../report/controller/{0}.js'.format(obj.report));
        debugger
        var strBody = functions[obj.report](obj);

        var html = [];

        html.push('<table id="tableID" cellspacing="0" width="100%" class="{0}">'.format(obj.tableStyle));
        html.push(strHeader);
        html.push('<tbody>');
        html = html.concat(strBody);
        html.push('</tbody>');
        html.push('</table>');

        document.getElementById($(this)[0].id).innerHTML = html.join('');

        //var h = window.innerHeight - 210;
        //var scrollH = h + "px";

        //var table = $('#tableID').DataTable({
        //    scrollY: scrollH,
        //    scrollX: true,
        //    scrollCollapse: true,
        //    paging: false,
        //    searching: false,
        //    ordering: false,
        //    bInfo: false,
        //    fixedColumns: {
        //        leftColumns: obj.leftColumns,
        //        rightColumns: 0
        //    }
        //});

        exStrHeader = strHeader;
        exTableStyle = obj.tableStyle;
    },
    tableDataSource: function (obj) {
        var id = $(this)[0].id;
        $('#' + id).empty();
        var html = [];
        html.push('<table id="tableID" cellspacing="0" width="100%" class="{0}">'.format(exTableStyle));
        html.push(exStrHeader);
        html.push('<tbody>');
        html = html.concat(obj.dataSource);
        html.push('</tbody>');
        html.push('</table>');

        document.getElementById(id).innerHTML = html.join('');

        var h = window.innerHeight - 210;
        var scrollH = h + "px";

        var table = $('#tableID').DataTable({
            scrollY: scrollH,
            scrollX: true,
            scrollCollapse: true,
            paging: false,
            searching: false,
            ordering: false,
            bInfo: false,
            fixedColumns: {
                leftColumns: 4,
                rightColumns: 0
            }
        });
    },
    disableElementByCLass: function (obj) {
        var i = obj.className.length;

        while (i--) {
            $(' .' + obj.className[i]).css('visibility', 'hidden');
        }
    },
    createFilter: function (obj) {
        var strDivFilter = retrieveText('../GradingReport/Template/DivFilter/{0}.html'.format(obj.filter));
        document.getElementById($(this)[0].id).innerHTML = strDivFilter;

        if ((obj.filter).search("Date") > 0) {
            $('#txtTranDate').val(obj.defaultDate);
        }
    },
    selectDataSource: function (obj, val, name) {
        //breaking for a day , pls c back to finish this later
        var id = '#' + $(this)[0].id;

        $(id).empty();

        var selectStr = "";
        var i = obj.length;
        //selectStr += '<option value="default" >' + name + '</option>';
        $.each(obj, function (i, item) {
            selectStr += '<option value="' + obj[i][val] + '" >' + obj[i][name] + '</option>';
        });
        $(id).empty();
        $(id).append(selectStr);
        $(id).select2('val', null);
        if (obj.length > 0) {
            //$(id).select2('val', 'default'); //set show first index of list
        }
    }
});

String.prototype.format = function () {
    var s = this,
        i = arguments.length;

    while (i--) {
        s = s.replace(new RegExp('\\{' + i + '\\}', 'gm'), arguments[i]);
    }
    return s;
};

String.prototype.toDate = function (format, delimiter) {
    var formatLowerCase = format.toLowerCase();
    var formatItems = formatLowerCase.split(delimiter);
    var dateItems = this.split(delimiter);
    var monthIndex = formatItems.indexOf('mm');
    var dayIndex = formatItems.indexOf('dd');
    var yearIndex = formatItems.indexOf('yyyy');
    var month = parseInt(dateItems[monthIndex]);
    month -= 1;
    var formatedDate = new Date(dateItems[yearIndex], month, dateItems[dayIndex]);
    return formatedDate;
}

Number.prototype.format = function (fix) {
    var num = this;
    //var num = this.toFixed(fix);

    if (!isFinite(num) || isNaN(num)) {
        var zero = 0;
        return zero.toFixed(fix);

    }
    else {
        return num.toFixed(fix).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

}

Array.prototype.distinctArrayObject = function (prop) {
    var array = this;
    var unique = {};
    var distinct = [];
    for (var i in array) {
        if (typeof (unique[array[i][prop]]) == "undefined") {
            distinct.push(array[i][prop]);
        }
        unique[array[i][prop]] = 0;
    }
    distinct.pop();
    return distinct;
}

var disableDayDatepicker = function (numOfDay) {

    var weekday = [];
    weekday[0] = "Sunday";
    weekday[1] = "Monday";
    weekday[2] = "Tuesday";
    weekday[3] = "Wednesday";
    weekday[4] = "Thursday";
    weekday[5] = "Friday";
    weekday[6] = "Saturday";

    var d = new Date();
    var startDate = new Date(2013, 0, 01);
    var endDate = new Date(d.getFullYear(), 11, 31);
    var object = [];
    var currentDate = startDate;
    while (currentDate <= endDate) {
        if (weekday[currentDate.getDay()] != weekday[numOfDay]) {
            var m = currentDate.getMonth() + 1;
            var d = currentDate.getDate();
            var y = currentDate.getFullYear();
            object.push(m + "/" + d + "/" + y);
        }
        currentDate.setDate(currentDate.getDate() + 1);
    } return object;
}

Date.prototype.toString = function (delimiter) {

    var date = this;
    var dd = date.getDate();
    var mm = date.getMonth() + 1;
    var yyyy = date.getFullYear();

    return [
        (dd > 9 ? '' : '0') + dd,
        (mm > 9 ? '' : '0') + mm,
        yyyy
    ].join(delimiter);
}

function require(script) {
    $.ajax({
        url: script,
        dataType: "script",
        async: false,
        success: function () {
            //console.log('script loaded');
        },
        error: function () {
            throw new Error("Could not load script " + script);
        }
    });
}

function retrieveText(fileUrl) {
    return $.ajax({
        url: fileUrl,
        async: false
    }).responseText;
}