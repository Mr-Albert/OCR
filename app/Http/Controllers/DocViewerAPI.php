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
                return array("type" => "pdf", "content" => $id);
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
		// echo $testURl;
  //       return;
        	curl_setopt($ch, CURLOPT_URL, $testURl);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // $output contains the output string
            $output = curl_exec($ch);
            // print_r($testURl);
            // return;

            if (curl_errno($ch)) {
                // return "{}";
                return array("type" => "image", "content" => $id);
            }
            eval("\$output = " . $output . ";");
            // close curl resource to free up system resources
            curl_close($ch);
            //print_r($output['highlighting'][$id]['hocr']);
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
            return array(array("id" => "pdf1.pdf"), array("id" => "img1.png"), array("id" => "img2.jpg"),
                array("id" => "pdf2.pdf"));
        }

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
        return ($responseArr);
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
 