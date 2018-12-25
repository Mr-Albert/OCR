function initDetail(ui) {
    var rowData = ui.rowData;
    var content;
    var responseDataM;
    var $detail;

    $.ajax({
        url: "/DOCSAPI/details",
        method: "GET",
        async: false,
        data: {
            id: rowData.id,
            srch: globalSearch
        },
        success: function (responseData) {
            responseDataM = responseData;
            if (responseData.type == "image") {
                $detail = $("<div style='overflow-y: scroll; height:400px;' id='pq-detail' tabindex='0'>" + highlightAdapter(rowData.id, responseData.hocr) + "</div>");

            } else {
                $detail = $("<div style='overflow-y: scroll; height:400px;text-align: right;' id='pq-detail' tabindex='0' >" + responseData.content + "</div>");
            }

        },
        async: false

    });
    return $detail;
};

function pqDatePicker(ui) {
    var $this = $(this);
    $this.daterangepicker({
        "showCustomRangeLabel": false,
        "showDropdowns": true,
        "showWeekNumbers": true,
        "timePicker": true,
        "timePicker24Hour": true,
        "autoApply": true,
        "dateLimit": {
            "years": 100
        },
        "ranges": {
            "Today": [
                moment().format("YYYY-MM-DD") + "T00:00:00Z",
                moment().format("YYYY-MM-DD") + "T23:59:59Z"
            ],
            "Yesterday": [
                moment().subtract(1, "days").format("YYYY-MM-DD") + "T00:00:00Z",
                moment().subtract(1, "days").format("YYYY-MM-DD") + "T23:59:59Z"
            ],
            "Last 7 Days": [
                moment().subtract(7, "days").format("YYYY-MM-DD") + "T00:00:00Z",
                moment().format("YYYY-MM-DD") + "T23:59:59Z"
            ],
            "This Month": [
                moment().format("YYYY-MM-") + "01T00:00:00Z",
                moment().format("YYYY-MM-DD") + "T23:59:59Z"
            ],
            "Last 30 Days": [
                moment().subtract(30, "days").format("YYYY-MM-DD") + "T00:00:00Z",
                moment().format("YYYY-MM-DD") + "T23:59:59Z"
            ],
            "Last 180 days": [
                moment().subtract(180, "days").format("YYYY-MM-DD") + "T00:00:00Z",
                moment().format("YYYY-MM-DD") + "T23:59:59Z"
            ],
            "This year": [
                moment().format("YYYY-") + "01-01T00:00:00Z",
                moment().format("YYYY-MM-DD") + "T23:59:59Z"
            ]
        },
        "locale": {
            "format": "YYYY-MM-DDTHH:mm:ss",
            "separator": " TO "
        },
        "linkedCalendars": false,
        "showCustomRangeLabel": false,
        "alwaysShowCalendars": true,
        "parentEl": ".panel .panel-primary",
        "startDate": "1950-01-01T00:00:00Z",
        "endDate": moment().format("YYYY-MM-DDTHH:mm:ss") + "Z",
        "opens": "left",
        "drops": "down"
    }, function (start, end, label) {});
}

function highlightAdapter(imageSrc, highlights) {
    var highlightDivs = ""
    for (var i = 0; i < highlights.length; i++) {
        // x y x2 y2
        var currentDiv = highlights[i].split(" ");
        currentDiv[4] = parseInt(currentDiv[4]) - parseInt(currentDiv[2]);
        currentDiv[3] = parseInt(currentDiv[3]) - parseInt(currentDiv[1]);
        // console.log(currentDiv);
        highlightDivs = highlightDivs + (" <div id='highlight' style='position:absolute;width:" + currentDiv[3] + "px;height:" + currentDiv[4] + "px;top:" + currentDiv[2] + "px;left:" + currentDiv[1] + "px;background: rgba(255, 0, 0, 0.2);'></div>");
        console.log(highlightDivs);


    }
    // console.log(highlightDivs);
    content = "<div id='container' style='position:relative;'>\
<img src='" + imageSrc + "' />" + highlightDivs + "</div>";
    return content;
}

function highlight(imageSrc, fromtTopLefX, fromtTopLefY, rechieght = 1, recwidth = 1, maxXforScale = 0, maxYforScale = 0) {
    if (maxXforScale != 0 && maxXforScale != 0)
    //need to rescale
    {
        var img = new Image();
        img.onload = function () {
            var height = img.height;
            var width = img.width;
            fromtTopLefX = fromtTopLefX * width / maxXforScale;
            fromtTopLefY = fromtTopLefY * height / maxYforScale;
            content = "<div id='container' style='position:relative;'>\
<img src='" + imageSrc + "' />\
<div id='highlight' style='position:absolute;width:" + recwidth + "px;height:" + rechieght + "px;top:" + fromtTopLefY + "px;left:" + fromtTopLefX + "px;\
background: rgba(255, 0, 0, 0.4);'></div>\
</div>";
            return content;
        }
        img.src = imageSrc;

    } else {
        content = "<div id='container' style='position:relative;'>\
<img src='" + imageSrc + "' />\
<div id='highlight' style='position:absolute;width:" + recwidth + "px;height:" + rechieght + "px;top:" + fromtTopLefY + "px;left:" + fromtTopLefX + "px;\
background: rgba(255, 0, 0, 0.4);'></div>\
</div>";
        return content;

    }
}
var globaldescription="*",globalTitle="*",globalSearch="*",globalAuthor="*",globalFromDate="*",globalToDate="*";
$(function () {

    var types = {
        png: "image",
        tif: "image",
        jpg: "image",
        jpeg: "image",
        pdf: "pdf"
    };
    var colM = [{
            title: "",
            minWidth: 27,
            maxWidth: 27,
            type: "detail",
            resizable: false,
            editable: false,
        },
        {
            minWidth: '10%',
            title: "title",
            dataIndx: "title",
            filter: {
                type: 'textbox',
                listeners: ['change',{'change':function(evt, ui){if (ui.value!="") globalTitle=ui.value; else globalTitle="*"; }}]
            }
        },
        {
            minWidth: '50%',
            title: "Describtion",
            dataIndx: "content",
            filter: {
                type: 'textbox',
                listeners: ['change',{'change':function(evt, ui){if (ui.value!="") globaldescription=ui.value; else globaldescription="*"; }}]
            }
        },

        {
            minWidth: '10%',
            title: "Created_on",
            dataIndx: "date",
            filter: {
                type: 'textbox',
                init: pqDatePicker,
                listeners: ['change',{'change':function(evt, ui){
                    if (ui.value!="") globalFromDate=ui.value; else globalFromDate="*"; 
                    if (ui.value2!="") globalToDate=ui.value2; else globalToDate="*";
                }}]
            }
        },
        {
            minWidth: '10%',
            title: "Created by",
            dataIndx: "author",
            filter: {
                type: 'textbox',
                listeners: ['change',{'change':function(evt, ui){if (ui.value!="") globalAuthor=ui.value; else globalAuthor="*"; }}]
            }
        },
        {
            minWidth: '10%',
            title: "File",
            dataIndx: "id",
            render: function (ui) {
                // console.log(ui);
                var id = ui.rowData.id;
                var iconType = types[(id.substr(id.length - 3))] + ".png";
                return "<div id='" + id + "' ><a href='DOCSAPI/down?fileName=" + id + "'  target='_blank'><img style='margin-left:44%;width:12%;height:12%;' src = '/" + iconType + "'' /></a></div>";
            }

        }
    ];
    //define dataModel
    var dataModel = {
        location: "remote",
        sorting: "local",
        dataType: "JSON",
        method: "GET",
        url: "/DOCSAPI",
        beforeSend:function( jqXHR, settings ){
            settings.url+="&content="+globalSearch;
        },
        getData: function (dataJSON) {
            // console.log(dataJSON);
            return {
                data: dataJSON
            };
        }
    }
    var obj = {
        dataModel: dataModel,
        colModel: colM,
        virtualX: true,
        virtualY: true,
        pageModel: {
            type: 'local',
            rPP: 20
        },
        height: "flex",
        editable: false,
        selectionModel: {
            type: 'cell'
        },
        filterModel: {
            on: true,
            mode: "AND",
            header: true
        },
        title: '<textarea id="seachTextArea" rows="2" cols="100" style="color:black">test</textarea>\
        <button id="searchButton" style="color:black;" >Search</button>\
        <form style="  float: right; " action="/upload" method="post">\
        Files:<br>\
        <input type="hidden" name="_token" value="' + $('meta[name="_token"]').attr('content') + '" />\
        <input type="hidden" id="fileDescription" name="fileDescription" value="" />\
        <input class="ui-widget-header ui-widget-header ui-state-active" type="file" id="fileupload" name="files[]" data-url="/upload" />\
        <p id="loading"></p>\
        </form>',
        resizable: true,
        hwrap: true,
        detailModel: {
            init: initDetail
        }
    };
    var $grid = $("#table").pqGrid(obj);
    $(".filesGetter").click(
        function (event) {
            alert(event.target.id);
            $.ajax({
                url: "DOCSAPI/down",
                data: {
                    fileName: event.target.id
                }
            }).done(function () {
                alert("done");
            });
        }
    );

    $('#fileupload').fileupload({
        dataType: 'json',
        add: function (e, data) {
            var accepted = false,
                description = "";

            $('#description').val(description);
            $("#dialog").dialog({
                modal: true,
                buttons: [{
                    text: "Upload",
                    icon: "ui-icon-heart",
                    click: function () {
                        accepted = true;
                        description = $('#description').val();
                        $("#dialog").dialog("close");
                        if (accepted) {
                            $('#fileDescription').attr('value', description);
                            // console.log($('#fileDescription').attr('value'));
                            $('#loading').text('Uploading...');
                            //data.files[0].description=description;
                            // console.log(description);
                            // console.log(data);
                            data.submit();
                        }
                    }
                }]
            });

        },
        done: function (e, data) {
            console.log("finished");
            $('#loading').text('');
        }
    });
    $("#searchButton").click(function(){ 
        var pq_filter = 'pq_filter: {"mode":"AND","data":[{"dataIndx":"title","value":'+globalTitle+',"dataType":"string"\
        ,"cbFn":""},{"dataIndx":"content","value":"'+globaldescription+'","dataType":"string","cbFn":""}\
        ,{"dataIndx":"date","value":"'+globalFromDate+' TO '+globalToDate+'","dataType":"string","cbFn":""}\
        ,{"dataIndx":"author","value":"'+globalAuthor+'","dataType":"string","cbFn":""}]}';
		globalSearch=$("#seachTextArea").val();
		if(globalSearch=="") globalSearch="*";
        $.ajax({
            url: "/DOCSAPI",
            method: "GET",
            async: false,
            data: {
                pq_filter: pq_filter,
                srch: globalSearch
            },
            success: function (responseData) {
                console.log(responseData);
                $( "#table" ).pqGrid( "option", "dataModel.data", responseData );
                $( "#table" ).pqGrid( "refreshView" );
            },
    
        });
    });
});