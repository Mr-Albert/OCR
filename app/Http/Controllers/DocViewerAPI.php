<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Ixudra\Curl\Facades\Curl;

class DocViewerAPI extends Controller
{
    public $client;
    private $extentionsMap = array("jpeg" => "images", "jpg" => "images", "pdf" => "pdfs", "png" => "images","tif" => "images");

    public function __construct()
    {
    }

    public function detail(Request $request)
    {
        $id = $request->input('id');
        $srch = $request->input('srch');
        if($srch=="")
            $srch="*";
        else
        {

        $ch = curl_init();
        $testURl = "http://".config('app.engine')["url"] . ":" . config('app.engine')["port"] ."/prepare?sentence=".urlencode($srch); 

        curl_setopt($ch, CURLOPT_URL, $testURl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            // return "{}";
           // return "error";
        }
        else 
        {
            //print_r((json_decode($output)));
            $srch.="~0.8 ";
            foreach(json_decode($output)->sentenceList as $key=>$word)
            {
                $srch.= " OR ".$word."~0.8 ";
            }
            if ($request->input('srch') != '') {
                $srch.= " OR ".$request->input('srch');
            }
            $srch="( ".$srch." )";
            //return $testURl;
        }
        curl_close($ch);
        }
        if (strpos($id, '.pdf') !== false) {
            //get contents from solr
            $ch = curl_init();
            $testURl = config('app.solr')['url'] . ":" . config('app.solr')['port'] . "/solr/" . config('app.solr')['collection'] . "/select?q=(id:" . urlencode($id) .urlencode(" AND content:" . $srch). (")&fl=highlighting&hl.fl=content&hl=on&hl.fragsize=0&wt=php");
            curl_setopt($ch, CURLOPT_URL, $testURl);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // $output contains the output string
            $output = curl_exec($ch);	
		if (curl_errno($ch)) {
                // return "{}";
                return array("type" => "pdf", "content" => $id,"id"=>"test.jpg");
            }
            // print_r($output);
            // return;
            eval("\$output = " . $output . ";");
            // close curl resource to free up system resources
            curl_close($ch);
            
            $responseData = array("type" => "pdf", "content" => $output['highlighting'][$id]['content']);
            return $responseData;
                // echo"<br>";
            
        } else {
            //gets details hocr from solr
            $ch = curl_init();
            $testURl = config('app.solr')['url'] . ":" . config('app.solr')['port'] . "/solr/" . config('app.solr')['collection'] . "/select?q=(id:" . urlencode($id) . urlencode(" AND hocr:" . $srch) . (")&fl=highlighting&hl.fl=hocr&hl=on&hl.fragsize=0&wt=php");            
  
        	curl_setopt($ch, CURLOPT_URL, $testURl);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // $output contains the output string
            $output = curl_exec($ch);
            // print_r($testURl);
            // return;

            if (curl_errno($ch)) {
                // return "{}";
                return array("type" => "image", "content" => $id,"id"=>"test.jpg","hocr"=>["5 500 500 1000 1000","6 100 100 200 200","7 200 200 300 300"]);
            }
            eval("\$output = " . $output . ";");
            // close curl resource to free up system resources
            curl_close($ch);
            //print_r($output['highlighting'][$id]['hocr']);
             return $output;
            $responseData = array("imageSrc" => "/" . $id, "type" => "image", "hocr" => $output['highlighting'][$id]['hocr']);
            return $responseData;
        }

    }
    public function search(Request $request)
    {

        $filter = json_decode($request->input('pq_filter'));
        $queryArray = array("content" => "*");

        if ($request->input("srch") != '') {
            $queryArray["content"] = $request->input("srch");
        }

        if (gettype($filter) == "object" && property_exists($filter, "data")) {
            $filterData = $filter->data;
            foreach ($filterData as $filterDatum) {
                if ($filterDatum->value == "") {
                    $queryArray[$filterDatum->dataIndx] = "*";
                } else {
                    $queryArray[$filterDatum->dataIndx] = $filterDatum->value;
                }

            }
        }

        // print_r($queryArray);
        // return;

        // Send a GET request to: http://www.foo.com/bar?foz=baz
        // $solrConnectionString=config('app.solr')["url"] . ":" . config('app.solr')["port"] . "/solr/" . config('app.solr')["collection"] . "/select";
        // $solrConnectionString="localhost:8000/testInComingQuery";
        // $response = Curl::to($solrConnectionString)
        //     ->withData($queryArray)
        //     ->withResponseHeaders()
        //     ->returnResponseObject()
        //     ->enableDebug('/CurllogFile.txt')
        //     ->get();
        // print_r($response);
        // echo "returning";
        // return;
        $srchValue=$queryArray["content"];
		////////
		
        $ch = curl_init();
        $testURl = "http://".config('app.engine')["url"] . ":" . config('app.engine')["port"] ."/prepare?sentence=".urlencode($srchValue); 
		curl_setopt($ch, CURLOPT_URL, $testURl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);

        if (curl_errno($ch)) {
            // return "{}";
           // return "error";
        }
		else 
		{
            $srchValue.="~0.8 ";
			//print_r((json_decode($output)));
			foreach(json_decode($output)->sentenceList as $key=>$word)
			{
				$srchValue.= " OR ".$word."~0.8 ";
			}
            if ($request->input("srch") != '') {
               $srchValue.= " OR ".$request->input("srch");
            }
			$srchValue="( ".$srchValue." )";
			//return $testURl;
		}
        curl_close($ch);
		//print_r((($srchValue)));
		//////////
        $ch = curl_init();
        $testURl = config('app.solr')["url"] . ":" . config('app.solr')["port"] . "/solr/" . config('app.solr')["collection"] . "/select?q=content:" . urlencode($srchValue) . ("&fl=id,last_modified,title,created_by,created_on,file_description,highlighting&hl.fl=content&hl=on&hl.fragsize=0&wt=php");
  //       echo $testURl;
		// return;
	  // echo $testURl;
   //      return;
    	curl_setopt($ch, CURLOPT_URL, $testURl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        //print_r($output);
        //return;
        if (curl_errno($ch)) {
            // return "{}";
                $responseArr= array(array("id" => "pdf1.pdf"), array("id" => "test.jpg"), array("id" => "test.jpg"),
                array("id" => "pdf2.pdf"));
        }
        else{
        eval("\$output = " . $output . ";");
        curl_close($ch);
        $responseArr=array();
        foreach ($output["response"]["docs"] as $key => $value) {
            // $value["content"] = $output["highlighting"][$value["id"]]["content"][0];
            $responseArr[]=array("title"=>$value["title"][0],"created_by"=>$value["created_by"][0],
                "created_on"=>$value["created_on"],"id"=>$value["id"],
                "file_description"=>$value["file_description"][0]);
            // $value["content"]=
        }
        }
        $graph= array(
            "name"=> "Root",
            "children"=> array(array(
                    "name"=> "AgglomerativeCluster",
                    "size"=> 3938
                ),
                array(
                    "name"=> "CommunityStructure",
                    "size"=> 3812
                ),
                array(
                    "name"=> "HierarchicalCluster",
                    "size"=> 6714
                ),
                array(
                    "name"=> "MergeEdge",
                    "size"=> 743
                ),
                array(
                    "name"=> "BetweennessCentrality",
                    "size"=> 3534
                ),
                array(
                    "name"=> "LinkDistance",
                    "size"=> 5731
                ),
                array(
                    "name"=> "MaxFlowMinCut",
                    "size"=> 7840
                ),
                array(
                    "name"=> "ShortestPaths",
                    "size"=> 5914
                ),
                array(
                    "name"=> "SpanningTree",
                    "size"=> 3416
                ),
    
                array(
                    "name"=> "AspectRatioBanker",
                    "size"=> 7074
                ),
                array(
                    "name"=> "Easing",
                    "size"=> 17010
                ),
                array(
                    "name"=> "FunctionSequence",
                    "size"=> 5842
                ),
                array(
                    "name"=> "ArrayInterpolator",
                    "size"=> 1983
                ),
                array(
                    "name"=> "ColorInterpolator",
                    "size"=> 2047
                ),
                array(
                    "name"=> "DateInterpolator",
                    "size"=> 1375
                ),
                array(
                    "name"=> "Interpolator",
                    "size"=> 8746
                ),
                array(
                    "name"=> "MatrixInterpolator",
                    "size"=> 2202
                ),
                array(
                    "name"=> "NumberInterpolator",
                    "size"=> 1382
                ),
                array(
                    "name"=> "ObjectInterpolator",
                    "size"=> 1629
                ),
                array(
                    "name"=> "PointInterpolator",
                    "size"=> 1675
                ),
                array(
                    "name"=> "RectangleInterpolator",
                    "size"=> 2042
                ),
                array(
                    "name"=> "ISchedulable",
                    "size"=> 1041
                ),
                array(
                    "name"=> "Parallel",
                    "size"=> 5176
                ),
                array(
                    "name"=> "Pause",
                    "size"=> 449
                ),
                array(
                    "name"=> "Scheduler",
                    "size"=> 5593
                ),
                array(
                    "name"=> "Sequence",
                    "size"=> 5534
                ),
                array(
                    "name"=> "Transition",
                    "size"=> 9201
                ),
                array(
                    "name"=> "Transitioner",
                    "size"=> 19975
                ),
                array(
                    "name"=> "TransitionEvent",
                    "size"=> 1116
                ),
                array(
                    "name"=> "Tween",
                    "size"=> 6006
                ),
                array(
                    "name"=> "Converters",
                    "size"=> 721
                ),
                array(
                    "name"=> "DelimitedTextConverter",
                    "size"=> 4294
                ),
                array(
                    "name"=> "GraphMLConverter",
                    "size"=> 9800
                ),
                array(
                    "name"=> "IDataConverter",
                    "size"=> 1314
                ),
                array(
                    "name"=> "JSONConverter",
                    "size"=> 2220
                ),
                array(
                    "name"=> "DataField",
                    "size"=> 1759
                ),
                array(
                    "name"=> "DataSchema",
                    "size"=> 2165
                ),
                array(
                    "name"=> "DataSet",
                    "size"=> 586
                ),
                array(
                    "name"=> "DataSource",
                    "size"=> 3331
                ),
                array(
                    "name"=> "DataTable",
                    "size"=> 772
                ),
                array(
                    "name"=> "DataUtil",
                    "size"=> 3322
                ),
                array(
                    "name"=> "DirtySprite",
                    "size"=> 8833
                ),
                array(
                    "name"=> "LineSprite",
                    "size"=> 1732
                ),
                array(
                    "name"=> "RectSprite",
                    "size"=> 3623
                ),
                array(
                    "name"=> "TextSprite",
                    "size"=> 10066
                ),
                array(
                    "name"=> "FlareVis",
                    "size"=> 4116
                ),
                array(
                    "name"=> "DragForce",
                    "size"=> 1082
                ),
                array(
                    "name"=> "GravityForce",
                    "size"=> 1336
                ),
                array(
                    "name"=> "IForce",
                    "size"=> 319
                ),
                array(
                    "name"=> "NBodyForce",
                    "size"=> 10498
                ),
                array(
                    "name"=> "Particle",
                    "size"=> 2822
                ),
                array(
                    "name"=> "Simulation",
                    "size"=> 9983
                ),
                array(
                    "name"=> "Spring",
                    "size"=> 2213
                ),
                array(
                    "name"=> "SpringForce",
                    "size"=> 1681
                ),
                array(
                    "name"=> "AggregateExpression",
                    "size"=> 1616
                ),
                array(
                    "name"=> "And",
                    "size"=> 1027
                ),
                array(
                    "name"=> "Arithmetic",
                    "size"=> 3891
                ),
                array(
                    "name"=> "Average",
                    "size"=> 891
                ),
                array(
                    "name"=> "BinaryExpression",
                    "size"=> 2893
                ),
                array(
                    "name"=> "Comparison",
                    "size"=> 5103
                ),
                array(
                    "name"=> "CompositeExpression",
                    "size"=> 3677
                ),
                array(
                    "name"=> "Count",
                    "size"=> 781
                ),
                array(
                    "name"=> "DateUtil",
                    "size"=> 4141
                ),
                array(
                    "name"=> "Distinct",
                    "size"=> 933
                ),
                array(
                    "name"=> "Expression",
                    "size"=> 5130
                ),
                array(
                    "name"=> "ExpressionIterator",
                    "size"=> 3617
                ),
                array(
                    "name"=> "Fn",
                    "size"=> 3240
                ),
                array(
                    "name"=> "If",
                    "size"=> 2732
                ),
                array(
                    "name"=> "IsA",
                    "size"=> 2039
                ),
                array(
                    "name"=> "Literal",
                    "size"=> 1214
                ),
                array(
                    "name"=> "Match",
                    "size"=> 3748
                ),
                array(
                    "name"=> "Maximum",
                    "size"=> 843
                ),
    
                array(
                    "name"=> "add",
                    "size"=> 593
                ),
                array(
                    "name"=> "and",
                    "size"=> 330
                ),
                array(
                    "name"=> "average",
                    "size"=> 287
                ),
                array(
                    "name"=> "count",
                    "size"=> 277
                ),
                array(
                    "name"=> "distinct",
                    "size"=> 292
                ),
                array(
                    "name"=> "div",
                    "size"=> 595
                ),
                array(
                    "name"=> "eq",
                    "size"=> 594
                ),
                array(
                    "name"=> "fn",
                    "size"=> 460
                ),
                array(
                    "name"=> "gt",
                    "size"=> 603
                ),
                array(
                    "name"=> "gte",
                    "size"=> 625
                ),
                array(
                    "name"=> "iff",
                    "size"=> 748
                ),
                array(
                    "name"=> "isa",
                    "size"=> 461
                ),
                array(
                    "name"=> "lt",
                    "size"=> 597
                ),
                array(
                    "name"=> "lte",
                    "size"=> 619
                ),
                array(
                    "name"=> "max",
                    "size"=> 283
                ),
                array(
                    "name"=> "min",
                    "size"=> 283
                ),
                array(
                    "name"=> "mod",
                    "size"=> 591
                ),
                array(
                    "name"=> "mul",
                    "size"=> 603
                ),
                array(
                    "name"=> "neq",
                    "size"=> 599
                ),
                array(
                    "name"=> "not",
                    "size"=> 386
                ),
                array(
                    "name"=> "or",
                    "size"=> 323
                ),
                array(
                    "name"=> "orderby",
                    "size"=> 307
                ),
                array(
                    "name"=> "range",
                    "size"=> 772
                ),
                array(
                    "name"=> "select",
                    "size"=> 296
                ),
                array(
                    "name"=> "stddev",
                    "size"=> 363
                ),
                array(
                    "name"=> "sub",
                    "size"=> 600
                ),
                array(
                    "name"=> "sum",
                    "size"=> 280
                ),
                array(
                    "name"=> "update",
                    "size"=> 307
                ),
                array(
                    "name"=> "variance",
                    "size"=> 335
                ),
                array(
                    "name"=> "where",
                    "size"=> 299
                ),
                array(
                    "name"=> "xor",
                    "size"=> 354
                ),
                array(
                    "name"=> "_",
                    "size"=> 264
                ),
                array(
                    "name"=> "Minimum",
                    "size"=> 843
                ),
                array(
                    "name"=> "Not",
                    "size"=> 1554
                ),
                array(
                    "name"=> "Or",
                    "size"=> 970
                ),
                array(
                    "name"=> "Query",
                    "size"=> 13896
                ),
                array(
                    "name"=> "Range",
                    "size"=> 1594
                ),
                array(
                    "name"=> "StringUtil",
                    "size"=> 4130
                ),
                array(
                    "name"=> "Sum",
                    "size"=> 791
                ),
                array(
                    "name"=> "Variable",
                    "size"=> 1124
                ),
                array(
                    "name"=> "Variance",
                    "size"=> 1876
                ),
                array(
                    "name"=> "Xor",
                    "size"=> 1101
                ),
    
    
                array(
                    "name"=> "IScaleMap",
                    "size"=> 2105
                ),
                array(
                    "name"=> "LinearScale",
                    "size"=> 1316
                ),
                array(
                    "name"=> "LogScale",
                    "size"=> 3151
                ),
                array(
                    "name"=> "OrdinalScale",
                    "size"=> 3770
                ),
                array(
                    "name"=> "QuantileScale",
                    "size"=> 2435
                ),
                array(
                    "name"=> "QuantitativeScale",
                    "size"=> 4839
                ),
                array(
                    "name"=> "RootScale",
                    "size"=> 1756
                ),
                array(
                    "name"=> "Scale",
                    "size"=> 4268
                ),
                array(
                    "name"=> "ScaleType",
                    "size"=> 1821
                ),
    
    
                array(
                    "name"=> "Arrays",
                    "size"=> 8258
                ),
                array(
                    "name"=> "Colors",
                    "size"=> 10001
                ),
                array(
                    "name"=> "Dates",
                    "size"=> 8217
                ),
                array(
                    "name"=> "Displays",
                    "size"=> 12555
                ),
                array(
                    "name"=> "Filter",
                    "size"=> 2324
                ),
                array(
                    "name"=> "Geometry",
                    "size"=> 10993
                ),
    
                array(
                    "name"=> "FibonacciHeap",
                    "size"=> 9354
                ),
                array(
                    "name"=> "HeapNode",
                    "size"=> 1233
                ),
                array(
                    "name"=> "IEvaluable",
                    "size"=> 335
                ),
                array(
                    "name"=> "IPredicate",
                    "size"=> 383
                ),
                array(
                    "name"=> "IValueProxy",
                    "size"=> 874
                ),
    
                array(
                    "name"=> "DenseMatrix",
                    "size"=> 3165
                ),
                array(
                    "name"=> "IMatrix",
                    "size"=> 2815
                ),
                array(
                    "name"=> "SparseMatrix",
                    "size"=> 3366
                ),
                array(
                    "name"=> "Maths",
                    "size"=> 17705
                ),
                array(
                    "name"=> "Orientation",
                    "size"=> 1486
                ),
    
                array(
                    "name"=> "ColorPalette",
                    "size"=> 6367
                ),
                array(
                    "name"=> "Palette",
                    "size"=> 1229
                ),
                array(
                    "name"=> "ShapePalette",
                    "size"=> 2059
                ),
                array(
                    "name"=> "SizePalette",
                    "size"=> 2291
                ),
                array(
                    "name"=> "Property",
                    "size"=> 5559
                ),
                array(
                    "name"=> "Shapes",
                    "size"=> 19118
                ),
                array(
                    "name"=> "Sort",
                    "size"=> 6887
                ),
                array(
                    "name"=> "Stats",
                    "size"=> 6557
                ),
                array(
                    "name"=> "Strings",
                    "size"=> 22026
                ),
    
    
    
                array(
                    "name"=> "Axes",
                    "size"=> 1302
                ),
                array(
                    "name"=> "Axis",
                    "size"=> 50593
                ),
                array(
                    "name"=> "AxisGridLine",
                    "size"=> 652
                ),
                array(
                    "name"=> "AxisLabel",
                    "size"=> 636
                ),
                array(
                    "name"=> "CartesianAxes",
                    "size"=> 6703
                ),
    
                array(
                    "name"=> "AnchorControl",
                    "size"=> 2138
                ),
                array(
                    "name"=> "ClickControl",
                    "size"=> 3824
                ),
                array(
                    "name"=> "Control",
                    "size"=> 1353
                ),
                array(
                    "name"=> "ControlList",
                    "size"=> 4665
                ),
                array(
                    "name"=> "DragControl",
                    "size"=> 2649
                ),
                array(
                    "name"=> "ExpandControl",
                    "size"=> 2832
                ),
                array(
                    "name"=> "HoverControl",
                    "size"=> 4896
                ),
                array(
                    "name"=> "IControl",
                    "size"=> 763
                ),
                array(
                    "name"=> "PanZoomControl",
                    "size"=> 5222
                ),
                array(
                    "name"=> "SelectionControl",
                    "size"=> 7862
                ),
                array(
                    "name"=> "TooltipControl",
                    "size"=> 8435
                ),
    
                array(
                    "name"=> "Data",
                    "size"=> 20544
                ),
                array(
                    "name"=> "DataList",
                    "size"=> 19788
                ),
                array(
                    "name"=> "DataSprite",
                    "size"=> 10349
                ),
                array(
                    "name"=> "EdgeSprite",
                    "size"=> 3301
                ),
                array(
                    "name"=> "NodeSprite",
                    "size"=> 19382
                ),
    
                array(
                    "name"=> "ArrowType",
                    "size"=> 698
                ),
                array(
                    "name"=> "EdgeRenderer",
                    "size"=> 5569
                ),
                array(
                    "name"=> "IRenderer",
                    "size"=> 353
                ),
                array(
                    "name"=> "ShapeRenderer",
                    "size"=> 2247
                ),
                array(
                    "name"=> "ScaleBinding",
                    "size"=> 11275
                ),
                array(
                    "name"=> "Tree",
                    "size"=> 7147
                ),
                array(
                    "name"=> "TreeBuilder",
                    "size"=> 9930
                ),
                array(
                    "name"=> "DataEvent",
                    "size"=> 2313
                ),
                array(
                    "name"=> "SelectionEvent",
                    "size"=> 1880
                ),
                array(
                    "name"=> "TooltipEvent",
                    "size"=> 1701
                ),
                array(
                    "name"=> "VisualizationEvent",
                    "size"=> 1117
                ),
                array(
                    "name"=> "Legend",
                    "size"=> 20859
                ),
                array(
                    "name"=> "LegendItem",
                    "size"=> 4614
                ),
                array(
                    "name"=> "LegendRange",
                    "size"=> 10530
                ),
                array(
                    "name"=> "BifocalDistortion",
                    "size"=> 4461
                ),
                array(
                    "name"=> "Distortion",
                    "size"=> 6314
                ),
                array(
                    "name"=> "FisheyeDistortion",
                    "size"=> 3444
                ),
                array(
                    "name"=> "ColorEncoder",
                    "size"=> 3179
                ),
                array(
                    "name"=> "Encoder",
                    "size"=> 4060
                ),
                array(
                    "name"=> "PropertyEncoder",
                    "size"=> 4138
                ),
                array(
                    "name"=> "ShapeEncoder",
                    "size"=> 1690
                ),
                array(
                    "name"=> "SizeEncoder",
                    "size"=> 1830
                ),
                array(
                    "name"=> "FisheyeTreeFilter",
                    "size"=> 5219
                ),
                array(
                    "name"=> "GraphDistanceFilter",
                    "size"=> 3165
                ),
                array(
                    "name"=> "VisibilityFilter",
                    "size"=> 3509
                ),
                array(
                    "name"=> "Labeler",
                    "size"=> 9956
                ),
                array(
                    "name"=> "RadialLabeler",
                    "size"=> 3899
                ),
                array(
                    "name"=> "StackedAreaLabeler",
                    "size"=> 3202
                ),
                array(
                    "name"=> "AxisLayout",
                    "size"=> 6725
                ),
                array(
                    "name"=> "BundledEdgeRouter",
                    "size"=> 3727
                ),
                array(
                    "name"=> "CircleLayout",
                    "size"=> 9317
                ),
                array(
                    "name"=> "CirclePackingLayout",
                    "size"=> 12003
                ),
                array(
                    "name"=> "DendrogramLayout",
                    "size"=> 4853
                ),
                array(
                    "name"=> "ForceDirectedLayout",
                    "size"=> 8411
                ),
                array(
                    "name"=> "IcicleTreeLayout",
                    "size"=> 4864
                ),
                array(
                    "name"=> "IndentedTreeLayout",
                    "size"=> 3174
                ),
                array(
                    "name"=> "Layout",
                    "size"=> 7881
                ),
                array(
                    "name"=> "NodeLinkTreeLayout",
                    "size"=> 12870
                ),
                array(
                    "name"=> "PieLayout",
                    "size"=> 2728
                ),
                array(
                    "name"=> "RadialTreeLayout",
                    "size"=> 12348
                ),
                array(
                    "name"=> "RandomLayout",
                    "size"=> 870
                ),
                array(
                    "name"=> "StackedAreaLayout",
                    "size"=> 9121
                ),
                array(
                    "name"=> "TreeMapLayout",
                    "size"=> 9191
                ),
    
                array(
                    "name"=> "Operator",
                    "size"=> 2490
                ),
                array(
                    "name"=> "OperatorList",
                    "size"=> 5248
                ),
                array(
                    "name"=> "OperatorSequence",
                    "size"=> 4190
                ),
                array(
                    "name"=> "OperatorSwitch",
                    "size"=> 2581
                ),
                array(
                    "name"=> "SortOperator",
                    "size"=> 2023
                ),
    
                array(
                    "name"=> "Visualization",
                    "size"=> 16540
                )
            )
    
    
        );
        return (array("tableData"=>$responseArr,"graphData"=>$graph));
    }
    public function down(Request $request)
    {
        $name = $request->get("fileName");
        $fileExtention = substr($name, strrpos($name, ".") + 1);
        $fileExtention = $this->extentionsMap[strtolower($fileExtention)];
        $file = "app/files/" . $name;
        $headers = array('Content-Type' => 'image/jpeg');

        $rspns = response()->download(storage_path($file));
        ob_end_clean();

        // auth code
        return $rspns;
    }
    public function up(Request $request)
    {

        $files = [];
        foreach ($request->files as $file) {
            $uploadedFile = $file[0];

            $filename = time() . $uploadedFile->getClientOriginalName();
            $fileExtention = substr($filename, strrpos($filename, ".") + 1);
            $fileExtention = $this->extentionsMap[strtolower($fileExtention)];

            Storage::disk('local')->putFileAs(
                'files/' . $fileExtention,
                $uploadedFile,
                $filename
            );
        }
        $app_path = config('app.appPath');
        $file_path = $app_path . "app/files/" . $fileExtention . "/" . $filename;
        $backEndUrl = "http://".config('app.engine')['url'] . ':' . config('app.engine')['port'] . '/extract?path=' . $file_path . "&fileDescription=" . urlencode($request->fileDescription);
        echo $backEndUrl;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $backEndUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 500000);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            return "{}";
        }

        return response()->json([
            'fileLocation' => $backEndUrl,
        ]);
    }
    public function getImage(Request $request)
    {
        $image = Route::current()->parameter('imageName');
        if ($image != "") {
            $storagePath = storage_path('/app/files/images/' . $image);
            return Image::make($storagePath)->response();
        } else {
            return "{}";
        }
    }
public function queryTest(Request $request)
{
    return $request;
}
}
 