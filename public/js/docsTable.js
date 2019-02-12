//globals
var globaldescription = "*",
    globalClass = "\"*\"",
    globalSearch = "*",
    globalAuthor = "*",
    globalFromDate = "*",
    globalToDate = "*";
var globalChartData="";
var maxR=0,minR=Infinity;
//fucntions
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
                // myStr.replace(/_/g, "-");
                // rowData.id = rowData.id.replace("/","-");
                var tempDetail="<div id='grand-"+rowData.id.substr(0,rowData.id.length - 4).toLowerCase().replace("/","-")+"' style='overflow-y: scroll; height:400px;' id='pq-detail' tabindex='0'><div style='position:absolute;z-index:9000;'><br><button onclick='highlightGetter(\"prev\",\"grand-"+rowData.id.substr(0,rowData.id.length - 4).toLowerCase().replace("/","-")+"\")' style='display:inline-block' class='highlightGetter ui-button-icon-primary ui-icon ui-icon-seek-prev' id='prevHighlight'>PREV</button><button class='highlightGetter ui-button-icon-primary ui-icon ui-icon-seek-next' style='display:inline-block' onclick='highlightGetter(\"next\",\"grand-"+rowData.id.substr(0,rowData.id.length - 4).toLowerCase().replace("/","-")+"\")' id='nextHighlight'>NEXT</button></div>";
                $detail = $(tempDetail
                 + highlightAdapter(rowData.id, responseData.hocr) + "</div>");

            } else {
                $detail = $("<div id='grand' style='text-align: right; white-space: pre-line; overflow-y: scroll; height:400px;text-align: right;' id='pq-detail' tabindex='0' > " + responseData.content + "</div>");
            }

        },
        async: false

    });
    return $detail;
};

function pqDatePicker(ui) {
    var $this = $(this);
    $(ui).daterangepicker({
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
    //console.log("highlights\\");
    //console.log(highlights);
    //console.log("highlights/");

    var highlightDivs = ""
    for (var i = 0; i < highlights.length; i++) {
        // x y x2 y2
        var currentDiv = highlights[i].split(" ");
        //console.log("    a highlight\\");
        //console.log(highlights[i]);
         //console.log("    highlight/");
        currentDiv[4] = parseInt(currentDiv[4]) - parseInt(currentDiv[2]);
        currentDiv[3] = parseInt(currentDiv[3]) - parseInt(currentDiv[1]);
        // //console.log(currentDiv);
        highlightDivs = highlightDivs + (" <div id='highlight-"+i+"'class='highlight' style='position:absolute;width:" + currentDiv[3] + "px;height:" + currentDiv[4] + "px;top:" + currentDiv[2] + "px;left:" + currentDiv[1] + "px;background: rgba(238, 238, 0, 0.5);'></div>");
        // //console.log(highlightDivs);


    }
    // //console.log(highlightDivs);
    content = "<div id='container' style='position:relative;'>\
<img src='" + imageSrc + "' />" + highlightDivs + "</div>";
    return content;
}

//deprecated,provided for reference
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
//////////////

function buildChart(id) {
    d3.selectAll('#' + id+" > *").remove();
    var colours = ["rgba(179, 0, 0,0.1)", "rgba(0, 64, 128,0.1)", "rgba(0, 128, 32,0.1)	"];
    // var diameter = $('#' + id).width();
    var diameter=Math.min($(window).height(),$(window).width())*0.85;

    var format = d3.format(",d"),
        dataSource = 0;

    var pack = d3.layout.pack()
        .size([diameter - 4, diameter - 4])
        .sort(function (a, b) {
            return -(a.value - b.value);
        })
        .value(function (d) {
            return d.size;
        });

    var svg = d3.select("#chartArea").append("svg")
        .attr("width", diameter)
        .attr("height", diameter)
        .style("margin-left",$(window).width()/2 - diameter/2+"px");


    var data = globalChartData;

    var vis = svg.datum(data).selectAll(".node")
        .data(pack.nodes)
        .enter()
        .append("g");

        var titles = vis.append("title")
        .attr("x", function (d) {
            return d.x;
        })
        .attr("y", function (d) {
            return d.y;
        })
        .text(function (d) {
            return d.name +
                (d.children ? "" : ": " + format(d.value));
        });
        var labels = vis.append("text")
        .attr("x", function (d) {
            return d.x;
        })
        .attr("y", function (d) {
            return d.y;
        })
        .attr("text-anchor","middle")
        .attr("font-size","0px")
        .text(function (d) {
            if (d.r>maxR/10 && !(d.name=="Root"))
                return d.name;
            return "";    
        })
        .transition()
        .duration(function(d){ 
            //  //console.log(250* ((maxR-minR)/d.r)  );                       
            return Math.min(110 * ((maxR-minR)/d.r),3500) ;}
            )
        .attr("font-size", function (d) {
            // //console.log("radius");
            // //console.log(d.r);
            return "10px";
        });;

    var circles = vis.append("circle")
        .attr("stroke", function (d) {
            if (d.name == "root" || d.name == "Root")
                return "none";
            return "black";
        })
        .style("fill", function (d) {
            if (d.name == "root" || d.name == "Root")
                return "transparent";
            // //console.log(d);
            var coleuer = "rgba(" + Math.floor(Math.random() * (Math.floor(d.r) + 220)) + "," + Math.floor(Math.random() * (Math.floor(d.r) + 220)) +
                "," + Math.floor(Math.random() * (Math.floor(d.r) + 220)) + ",1)";
            // //console.log(coleuer);
            return colours[Math.floor((Math.random() * 3))];
        })
        .attr("cx", function (d) {
            return d.x;
        })
        .attr("cy", function (d) {
            return d.y;
        })
        .attr("r", function (d) {
            if(d.r==NaN||d.r==undefined)
                return 0;
            // //console.log("radius before");
            // //console.log(maxR);
            // //console.log(d.r);
            maxR=Math.max(maxR,d.r);
            minR=Math.min(minR,d.r);

            // //console.log("///////");
            return d.r * 0.1;
        })
        .on("click", function(d) {
            // //console.log("node Data");
            console.log(d);
            $("#seachTextArea").val(d.name);
            searchHandler();
        })
        .transition()
        .duration(function(d){ 
            //  //console.log(250* ((maxR-minR)/d.r)  );                       
            return Math.min(110 * ((maxR-minR)/d.r),3500) ;}
            )
        .attr("r", function (d) {
            // //console.log("radius");
            // //console.log(d.r);
            return d.r;
        });
    // .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")" });


    //updateVis();
}

function updateVis() {

    if (dataSource == 0)
        pack.value(function (d) {
            return d.size;
        });
    if (dataSource == 1)
        pack.value(function (d) {
            return 100;
        });
    if (dataSource == 2)
        pack.value(function (d) {
            return 1 +
                Math.floor(Math.random() * 301);
        });
    //    pack.sort(null)

    var data1 = pack.nodes(data);

    titles.attr("x", function (d) {
            return d.x;
        })
        .attr("y", function (d) {
            return d.y;
        })
        .text(function (d) {
            return d.name +
                (d.children ? "" : ": " + format(d.value));
        });

    circles.transition()
        .duration(5000)
        .attr("cx", function (d) {
            return d.x;
        })
        .attr("cy", function (d) {
            return d.y;
        })
        .attr("r", function (d) {
            return d.r;
        });
};

//get graph Data
function getData() {
    return {
        "name": "Root",
        "children": [{
                "name": "AgglomerativeCluster",
                "size": 3938
            },
            {
                "name": "CommunityStructure",
                "size": 3812
            },
            {
                "name": "HierarchicalCluster",
                "size": 6714
            },
            {
                "name": "MergeEdge",
                "size": 743
            },
            {
                "name": "BetweennessCentrality",
                "size": 3534
            },
            {
                "name": "LinkDistance",
                "size": 5731
            },
            {
                "name": "MaxFlowMinCut",
                "size": 7840
            },
            {
                "name": "ShortestPaths",
                "size": 5914
            },
            {
                "name": "SpanningTree",
                "size": 3416
            },

            {
                "name": "AspectRatioBanker",
                "size": 7074
            },
            {
                "name": "Easing",
                "size": 17010
            },
            {
                "name": "FunctionSequence",
                "size": 5842
            },
            {
                "name": "ArrayInterpolator",
                "size": 1983
            },
            {
                "name": "ColorInterpolator",
                "size": 2047
            },
            {
                "name": "DateInterpolator",
                "size": 1375
            },
            {
                "name": "Interpolator",
                "size": 8746
            },
            {
                "name": "MatrixInterpolator",
                "size": 2202
            },
            {
                "name": "NumberInterpolator",
                "size": 1382
            },
            {
                "name": "ObjectInterpolator",
                "size": 1629
            },
            {
                "name": "PointInterpolator",
                "size": 1675
            },
            {
                "name": "RectangleInterpolator",
                "size": 2042
            },
            {
                "name": "ISchedulable",
                "size": 1041
            },
            {
                "name": "Parallel",
                "size": 5176
            },
            {
                "name": "Pause",
                "size": 449
            },
            {
                "name": "Scheduler",
                "size": 5593
            },
            {
                "name": "Sequence",
                "size": 5534
            },
            {
                "name": "Transition",
                "size": 9201
            },
            {
                "name": "Transitioner",
                "size": 19975
            },
            {
                "name": "TransitionEvent",
                "size": 1116
            },
            {
                "name": "Tween",
                "size": 6006
            },
            {
                "name": "Converters",
                "size": 721
            },
            {
                "name": "DelimitedTextConverter",
                "size": 4294
            },
            {
                "name": "GraphMLConverter",
                "size": 9800
            },
            {
                "name": "IDataConverter",
                "size": 1314
            },
            {
                "name": "JSONConverter",
                "size": 2220
            },
            {
                "name": "DataField",
                "size": 1759
            },
            {
                "name": "DataSchema",
                "size": 2165
            },
            {
                "name": "DataSet",
                "size": 586
            },
            {
                "name": "DataSource",
                "size": 3331
            },
            {
                "name": "DataTable",
                "size": 772
            },
            {
                "name": "DataUtil",
                "size": 3322
            },
            {
                "name": "DirtySprite",
                "size": 8833
            },
            {
                "name": "LineSprite",
                "size": 1732
            },
            {
                "name": "RectSprite",
                "size": 3623
            },
            {
                "name": "TextSprite",
                "size": 10066
            },
            {
                "name": "FlareVis",
                "size": 4116
            },
            {
                "name": "DragForce",
                "size": 1082
            },
            {
                "name": "GravityForce",
                "size": 1336
            },
            {
                "name": "IForce",
                "size": 319
            },
            {
                "name": "NBodyForce",
                "size": 10498
            },
            {
                "name": "Particle",
                "size": 2822
            },
            {
                "name": "Simulation",
                "size": 9983
            },
            {
                "name": "Spring",
                "size": 2213
            },
            {
                "name": "SpringForce",
                "size": 1681
            },
            {
                "name": "AggregateExpression",
                "size": 1616
            },
            {
                "name": "And",
                "size": 1027
            },
            {
                "name": "Arithmetic",
                "size": 3891
            },
            {
                "name": "Average",
                "size": 891
            },
            {
                "name": "BinaryExpression",
                "size": 2893
            },
            {
                "name": "Comparison",
                "size": 5103
            },
            {
                "name": "CompositeExpression",
                "size": 3677
            },
            {
                "name": "Count",
                "size": 781
            },
            {
                "name": "DateUtil",
                "size": 4141
            },
            {
                "name": "Distinct",
                "size": 933
            },
            {
                "name": "Expression",
                "size": 5130
            },
            {
                "name": "ExpressionIterator",
                "size": 3617
            },
            {
                "name": "Fn",
                "size": 3240
            },
            {
                "name": "If",
                "size": 2732
            },
            {
                "name": "IsA",
                "size": 2039
            },
            {
                "name": "Literal",
                "size": 1214
            },
            {
                "name": "Match",
                "size": 3748
            },
            {
                "name": "Maximum",
                "size": 843
            },

            {
                "name": "add",
                "size": 593
            },
            {
                "name": "and",
                "size": 330
            },
            {
                "name": "average",
                "size": 287
            },
            {
                "name": "count",
                "size": 277
            },
            {
                "name": "distinct",
                "size": 292
            },
            {
                "name": "div",
                "size": 595
            },
            {
                "name": "eq",
                "size": 594
            },
            {
                "name": "fn",
                "size": 460
            },
            {
                "name": "gt",
                "size": 603
            },
            {
                "name": "gte",
                "size": 625
            },
            {
                "name": "iff",
                "size": 748
            },
            {
                "name": "isa",
                "size": 461
            },
            {
                "name": "lt",
                "size": 597
            },
            {
                "name": "lte",
                "size": 619
            },
            {
                "name": "max",
                "size": 283
            },
            {
                "name": "min",
                "size": 283
            },
            {
                "name": "mod",
                "size": 591
            },
            {
                "name": "mul",
                "size": 603
            },
            {
                "name": "neq",
                "size": 599
            },
            {
                "name": "not",
                "size": 386
            },
            {
                "name": "or",
                "size": 323
            },
            {
                "name": "orderby",
                "size": 307
            },
            {
                "name": "range",
                "size": 772
            },
            {
                "name": "select",
                "size": 296
            },
            {
                "name": "stddev",
                "size": 363
            },
            {
                "name": "sub",
                "size": 600
            },
            {
                "name": "sum",
                "size": 280
            },
            {
                "name": "update",
                "size": 307
            },
            {
                "name": "variance",
                "size": 335
            },
            {
                "name": "where",
                "size": 299
            },
            {
                "name": "xor",
                "size": 354
            },
            {
                "name": "_",
                "size": 264
            },
            {
                "name": "Minimum",
                "size": 843
            },
            {
                "name": "Not",
                "size": 1554
            },
            {
                "name": "Or",
                "size": 970
            },
            {
                "name": "Query",
                "size": 13896
            },
            {
                "name": "Range",
                "size": 1594
            },
            {
                "name": "StringUtil",
                "size": 4130
            },
            {
                "name": "Sum",
                "size": 791
            },
            {
                "name": "Variable",
                "size": 1124
            },
            {
                "name": "Variance",
                "size": 1876
            },
            {
                "name": "Xor",
                "size": 1101
            },


            {
                "name": "IScaleMap",
                "size": 2105
            },
            {
                "name": "LinearScale",
                "size": 1316
            },
            {
                "name": "LogScale",
                "size": 3151
            },
            {
                "name": "OrdinalScale",
                "size": 3770
            },
            {
                "name": "QuantileScale",
                "size": 2435
            },
            {
                "name": "QuantitativeScale",
                "size": 4839
            },
            {
                "name": "RootScale",
                "size": 1756
            },
            {
                "name": "Scale",
                "size": 4268
            },
            {
                "name": "ScaleType",
                "size": 1821
            },


            {
                "name": "Arrays",
                "size": 8258
            },
            {
                "name": "Colors",
                "size": 10001
            },
            {
                "name": "Dates",
                "size": 8217
            },
            {
                "name": "Displays",
                "size": 12555
            },
            {
                "name": "Filter",
                "size": 2324
            },
            {
                "name": "Geometry",
                "size": 10993
            },

            {
                "name": "FibonacciHeap",
                "size": 9354
            },
            {
                "name": "HeapNode",
                "size": 1233
            },
            {
                "name": "IEvaluable",
                "size": 335
            },
            {
                "name": "IPredicate",
                "size": 383
            },
            {
                "name": "IValueProxy",
                "size": 874
            },

            {
                "name": "DenseMatrix",
                "size": 3165
            },
            {
                "name": "IMatrix",
                "size": 2815
            },
            {
                "name": "SparseMatrix",
                "size": 3366
            },
            {
                "name": "Maths",
                "size": 17705
            },
            {
                "name": "Orientation",
                "size": 1486
            },

            {
                "name": "ColorPalette",
                "size": 6367
            },
            {
                "name": "Palette",
                "size": 1229
            },
            {
                "name": "ShapePalette",
                "size": 2059
            },
            {
                "name": "SizePalette",
                "size": 2291
            },
            {
                "name": "Property",
                "size": 5559
            },
            {
                "name": "Shapes",
                "size": 19118
            },
            {
                "name": "Sort",
                "size": 6887
            },
            {
                "name": "Stats",
                "size": 6557
            },
            {
                "name": "Strings",
                "size": 22026
            },



            {
                "name": "Axes",
                "size": 1302
            },
            {
                "name": "Axis",
                "size": 24593
            },
            {
                "name": "AxisGridLine",
                "size": 652
            },
            {
                "name": "AxisLabel",
                "size": 636
            },
            {
                "name": "CartesianAxes",
                "size": 6703
            },

            {
                "name": "AnchorControl",
                "size": 2138
            },
            {
                "name": "ClickControl",
                "size": 3824
            },
            {
                "name": "Control",
                "size": 1353
            },
            {
                "name": "ControlList",
                "size": 4665
            },
            {
                "name": "DragControl",
                "size": 2649
            },
            {
                "name": "ExpandControl",
                "size": 2832
            },
            {
                "name": "HoverControl",
                "size": 4896
            },
            {
                "name": "IControl",
                "size": 763
            },
            {
                "name": "PanZoomControl",
                "size": 5222
            },
            {
                "name": "SelectionControl",
                "size": 7862
            },
            {
                "name": "TooltipControl",
                "size": 8435
            },

            {
                "name": "Data",
                "size": 20544
            },
            {
                "name": "DataList",
                "size": 19788
            },
            {
                "name": "DataSprite",
                "size": 10349
            },
            {
                "name": "EdgeSprite",
                "size": 3301
            },
            {
                "name": "NodeSprite",
                "size": 19382
            },

            {
                "name": "ArrowType",
                "size": 698
            },
            {
                "name": "EdgeRenderer",
                "size": 5569
            },
            {
                "name": "IRenderer",
                "size": 353
            },
            {
                "name": "ShapeRenderer",
                "size": 2247
            },
            {
                "name": "ScaleBinding",
                "size": 11275
            },
            {
                "name": "Tree",
                "size": 7147
            },
            {
                "name": "TreeBuilder",
                "size": 9930
            },
            {
                "name": "DataEvent",
                "size": 2313
            },
            {
                "name": "SelectionEvent",
                "size": 1880
            },
            {
                "name": "TooltipEvent",
                "size": 1701
            },
            {
                "name": "VisualizationEvent",
                "size": 1117
            },
            {
                "name": "Legend",
                "size": 20859
            },
            {
                "name": "LegendItem",
                "size": 4614
            },
            {
                "name": "LegendRange",
                "size": 10530
            },
            {
                "name": "BifocalDistortion",
                "size": 4461
            },
            {
                "name": "Distortion",
                "size": 6314
            },
            {
                "name": "FisheyeDistortion",
                "size": 3444
            },
            {
                "name": "ColorEncoder",
                "size": 3179
            },
            {
                "name": "Encoder",
                "size": 4060
            },
            {
                "name": "PropertyEncoder",
                "size": 4138
            },
            {
                "name": "ShapeEncoder",
                "size": 1690
            },
            {
                "name": "SizeEncoder",
                "size": 1830
            },
            {
                "name": "FisheyeTreeFilter",
                "size": 5219
            },
            {
                "name": "GraphDistanceFilter",
                "size": 3165
            },
            {
                "name": "VisibilityFilter",
                "size": 3509
            },
            {
                "name": "Labeler",
                "size": 9956
            },
            {
                "name": "RadialLabeler",
                "size": 3899
            },
            {
                "name": "StackedAreaLabeler",
                "size": 3202
            },
            {
                "name": "AxisLayout",
                "size": 6725
            },
            {
                "name": "BundledEdgeRouter",
                "size": 3727
            },
            {
                "name": "CircleLayout",
                "size": 9317
            },
            {
                "name": "CirclePackingLayout",
                "size": 12003
            },
            {
                "name": "DendrogramLayout",
                "size": 4853
            },
            {
                "name": "ForceDirectedLayout",
                "size": 8411
            },
            {
                "name": "IcicleTreeLayout",
                "size": 4864
            },
            {
                "name": "IndentedTreeLayout",
                "size": 3174
            },
            {
                "name": "Layout",
                "size": 7881
            },
            {
                "name": "NodeLinkTreeLayout",
                "size": 12870
            },
            {
                "name": "PieLayout",
                "size": 2728
            },
            {
                "name": "RadialTreeLayout",
                "size": 12348
            },
            {
                "name": "RandomLayout",
                "size": 870
            },
            {
                "name": "StackedAreaLayout",
                "size": 9121
            },
            {
                "name": "TreeMapLayout",
                "size": 9191
            },

            {
                "name": "Operator",
                "size": 2490
            },
            {
                "name": "OperatorList",
                "size": 5248
            },
            {
                "name": "OperatorSequence",
                "size": 4190
            },
            {
                "name": "OperatorSwitch",
                "size": 2581
            },
            {
                "name": "SortOperator",
                "size": 2023
            },

            {
                "name": "Visualization",
                "size": 16540
            }
        ]


    };
};

function searchHandler()
{
    
    $( "#tabs" ).tabs( "option", "active", 0 );
    globalSearch = $("#seachTextArea").val();
    if (globalSearch == "") globalSearch = "*";
    // globaldescription=($("#describtionSearch").val().trim());
    // (globaldescription==""||globaldescription==null)? globaldescription="*":globaldescription=globaldescription;
    // globalAuthor=($("#authorSearch").val().trim());
    // (globalAuthor==""||globalAuthor==null)? globalAuthor="*":globalAuthor=globalAuthor;
    (globalClass==""||globalClass==null||globalClass==[])? globalClass="\"*\"":globalClass=globalClass;
    globalFromDate=($("#DateSearch").val().split("TO")[0].trim());
    (globalFromDate==""||globalFromDate==null)? globalFromDate="*":globalFromDate=globalFromDate;
    globalToDate=($("#DateSearch").val().split("TO")[1].trim());
    (globalToDate==""||globalToDate==null)? globalToDate="*":globalToDate=globalToDate;

    var pq_filter = '{"mode":"AND","data":[{"dataIndx":"class","value":' + globalClass + ',"dataType":"string"\
,"cbFn":""},{"dataIndx":"content","value":"' + globaldescription + '","dataType":"string","cbFn":""}\
,{"dataIndx":"date","value":"' + globalFromDate + ' TO ' + globalToDate + '","dataType":"string","cbFn":""}\
,{"dataIndx":"author","value":"' + globalAuthor + '","dataType":"string","cbFn":""}]}';

    $.ajax({
        url: "/DOCSAPI",
        method: "GET",
        data: {
            pq_filter: pq_filter,
            srch: globalSearch
        },
        success: function (responseData) {
            // //console.log(responseData);
            //fill table
            $("#table").pqGrid("option", "dataModel.data", responseData.tableData);
            $("#table").pqGrid("refreshView");
            //fill graph here
            globalChartData=responseData.graphData;
            // //console.log(globalChartData);
            buildChart("chartArea");
        },

    });

}

function highlightGetter(buttonId,PranetID) {
    //console.log(buttonId,PranetID);
    var $currentHighlight;
    if($("#"+PranetID+" .currentHighlight").length==0)
    {
        $currentHighlight=$("#"+PranetID+" .highlight").first();
    }
    else
    {
        if(buttonId=="next" && $("#"+PranetID+" .currentHighlight").next().length!=0 )
            $currentHighlight=$("#"+PranetID+" .currentHighlight").next();
        else 
        {
            if( $("#"+PranetID+" .currentHighlight").next().length==0)
            {
                $currentHighlight=$("#"+PranetID+" .highlight").first();

            }
        }    
        if(buttonId=="prev" && $("#"+PranetID+" .currentHighlight").prev().length!=0 )
            $currentHighlight=$("#"+PranetID+" .currentHighlight").prev();
        else 
        {
            if( $("#"+PranetID+" .currentHighlight").prev().length==0)
            {
                $currentHighlight=$("#"+PranetID+" .highlight").last();

            }
        }     
        $("#"+PranetID+" .currentHighlight").removeClass("currentHighlight");
    }
    $currentHighlight.addClass("currentHighlight");
    // //console.log($currentHighlight);
    // if(!$(".currentHighlight").length)
    //     $toBeGottenElemen=$('#'+PranetID+" #highlight").first();
    // else    $toBeGottenElemen =$(".currentHighlight").  
    // console.log($currentHighlight.position().top );
    
    var parent = document.querySelector("#"+PranetID),elem=document.querySelector('.currentHighlight');
    var top=-parent.getBoundingClientRect().top+elem.getBoundingClientRect().top-$currentHighlight.height() / 2;
    var left= parent.getBoundingClientRect().left+elem.getBoundingClientRect().left-$currentHighlight.width() / 2;
    console.log(top,left);
    // var top=$currentHighlight.offset().top - $("#"+PranetID).height() / 2 - $currentHighlight.height() / 2;
    // var left= $currentHighlight.offset().left - $("#"+PranetID).width() / 2 - $currentHighlight.height() / 2;
    $('#'+PranetID).animate({
        scrollTop: top,
        scrollLeft:left
    }, 1000);

};


//event handlers goes here
function eventHandlers() {
    window.onresize = function(event) {
        //console.log($( "#tabs" ).tabs( "option", "active" ));
        if($( "#tabs" ).tabs( "option", "active" )==1) 
            buildChart("chartArea");
    };
    //change this
    $("#scroller").click(function () {
        alert("click");
        $('#grand').animate({
            scrollTop: $("#highlight").offset().top - $("#grand").height() / 2 - $("#highlight").height() / 2,
            scrollLeft: $("#highlight").offset().left - $("#grand").width() / 2 - $("#highlight").height() / 2
        }, 1000);


    });
    //
 

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
    //search handler
    $("#searchButton").click(function () {
        //hadnlesearch func should be here
        searchHandler();
    });
    $('#seachTextArea').on("keypress", function(e) {
        /* ENTER PRESSED*/
        if (e.keyCode == 13) {
            searchHandler();
            return false;
        }
    });

    $( "#tabs" ).on( "tabsactivate", function( event, ui ) {
        if($( "#tabs" ).tabs( "option", "active" )==1) 
        {
            buildChart("chartArea");
        }
    } );

    $("#classSearch")
    .on("change", function(evt){
        var val = $(this).val();
        globalClass="\""+val.join(",")+"\"";
        console.log(val);
        searchHandler();
    })
    ;
}
//inits DOM goes here

function DOMInits() {
    $( "#tabs" ).tabs();

    //date picker init
    pqDatePicker("#DateSearch");
    tableInit();
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
                        inputClass = $('#inputClass').val();
                        console.log(inputClass);
                        $("#dialog").dialog("close");
                        if (accepted) {
                            $('#fileDescription').attr('value', description);
                            $('#theInputClass').attr('value', inputClass);
                            $('#loading').text('Uploading...');
                            //data.files[0].description=description;
                            // //console.log(description);
                            // //console.log(data);
                            data.submit();
                        }
                    }
                }]
            });

        },
        done: function (e, data) {
            // //console.log("finished");
            $('#loading').text('');
        }
    });
    buildChart("chartArea");
    
    $("#classSearch").pqSelect({
        multiplePlaceholder: 'Class',    
        maxDisplay: 3,
        checkbox: true //adds checkbox to options    
    })
    // .on("change", function(evt){
    //     var val = $(this).val();
    //     $("#selected_option1")
    //         .text("Selected option: "+val);
    // }).pqSelect( 'open' )
    ;
    
}

function tableInit() {

    var types = {
        png: "image",
        tif: "image",
        jpg: "image",
        jpeg: "image",
        pdf: "pdf"
    };
    var colM = [{
            title: "",
            width: '5%',
            type: "detail",
            resizable: false,
            editable: false,
            render: function (ui) {
                 //console.log(ui);
                return '<button class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all">&nbspMORE&nbsp</button>';
            }
        },
        {
            width: '10%',
            title: "",
            halign:"center",
            dataIndx: "id",
            render: function (ui) {
                // //console.log(ui);
                var id = ui.rowData.id;
                var iconType = types[(id.substr(id.length - 3)).toLowerCase()] + ".png";
                return "<div id='" + id + "' ><a href='DOCSAPI/down?fileName=" + id + "'  target='_blank'><img style='margin-left:44%;width:22%;height:22%;' src = '/" + iconType.toLowerCase() + "'' /></a></div>";
            }

        },
        // {
        //     minWidth: '10%',
        //     title: "Created on",
        //     halign:"center",
        //     dataIndx: "created_on",
        //     // filter: {
        //     //     type: 'textbox',
        //     //     init: pqDatePicker,
        //     //     listeners: ['change',{'change':function(evt, ui){
        //     //         if (ui.value!="") globalFromDate=ui.value; else globalFromDate="*"; 
        //     //         if (ui.value2!="") globalToDate=ui.value2; else globalToDate="*";
        //     //     }}]
        //     // }
        // },
        // {
        //     minWidth: '10%',
        //     title: "Created by",
        //     halign:"center",
        //     dataIndx: "created_by",
        //     // filter: {
        //     //     type: 'textbox',
        //     //     listeners: ['change',{'change':function(evt, ui){if (ui.value!="") globalAuthor=ui.value; else globalAuthor="*"; }}]
        //     // }
        // },
     
        {
            width: '85%',
            title: "الوصف",
            halign:"right",
            align:"right",
            dataIndx: "file_description",
            render: function (ui) {
                // //console.log(ui);
                var desc = ui.rowData.file_description;
                return '<div style="font-style: bold;font-size: 25px;">\
                '+desc+'\
              </div>\
              <div style="font-size: 13px;color: blue;display:inline-block">'+ui.rowData.created_on+'\
              </div>\
              <div style="font-size: 13px;color: blue;display:inline-block"">  :تاريخ &nbsp</div>\
              <div style="font-size: 13px;color: blue;display:inline-block">'+ui.rowData.created_by+'\
              </div>\
              <div style="font-size: 13px;color: blue;display:inline-block""> :بواسطة</div>\
              ';
                        }
            // filter: {
            //     type: 'textbox',
            //     listeners: ['change',{'change':function(evt, ui){if (ui.value!="") globaldescription=ui.value; else globaldescription="*"; }}]
            // }
        }
    ];
    //define dataModel
    var dataModel = {
        location: "local",
        sorting: "local",
        dataType: "JSON",
        method: "GET",
        url: "/DOCSAPI",
        beforeSend: function (jqXHR, settings) {
            settings.url += "&content=" + globalSearch;
        },
        getData: function (dataJSON) {
            // //console.log(dataJSON);
            return {
                data: dataJSON
            };
        }
    }
    var obj = {
        dataModel: dataModel,
        columnBorders: false,
        colModel: colM,
        virtualX: true,
        numberCell: {show: false},
        virtualY: true,
        pageModel: {
            type: 'local',
            rPP: 20
        },
        height: "flex",
        editable: false,
        selectionModel: {
            type: null
        },
        filterModel: {
            on: true,
            mode: "AND",
            header: true
        },
        title: '\
<form style="  float: right; " action="/upload" method="post">\
Files:<br>\
<input type="hidden" name="_token" value="' + $('meta[name="_token"]').attr('content') + '" />\
<input type="hidden" id="fileDescription" name="fileDescription" value="" />\
<input type="hidden" id="theInputClass"   name="theInputClass"   value="" />\
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
}
$(function () {
    maxR=0;
    DOMInits();
    eventHandlers();
    



});