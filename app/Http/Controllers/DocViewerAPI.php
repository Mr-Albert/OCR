<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use  Illuminate\Support\Facades\Route;

class DocViewerAPI extends Controller
{
    public $client;
    private $extentionsMap = array("jpeg" => "images", "jpg" => "images", "pdf" => "pdfs", "png" => "images");

    public function __construct()
    {
    }

   
    public function detail(Request $request)
    {
        $id = $request->input('id');
		$srch = $request->input('srch');
        if (strpos($id, '.pdf') !== false) {
            //get contents from solr
            $ch = curl_init();
            $testURl =  config('app.solr')['url'].":".config('app.solr')['port']."/solr/".config('app.solr')['collection']."/select?q=id:" . urlencode($id) . ("&fl=highlighting&hl.fl=content&hl=on&hl.fragsize=0&wt=php");
            curl_setopt($ch, CURLOPT_URL, $testURl);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // $output contains the output string
            $output = curl_exec($ch);
            if (curl_errno($ch)) {
                // return "{}";
                return array("type"=>"image","content"=>$id);
            }
            $output=array();
            eval("\$output = " . $output . ";");
        // close curl resource to free up system resources
            curl_close($ch);
            $responseArr;
        foreach ($output["response"]["docs"] as $key => $value) {
            $value["content"] = $output["highlighting"][$value["id"]]["content"][0];
            $responseArr[] = ($value);
            // echo"<br>";
        }        
    } else {
            //gets details hocr from solr
			$ch = curl_init();
            $testURl =  config('app.solr')['url'].":".config('app.solr')['port']."/solr/".config('app.solr')['collection']."/select?q=(id:" . urlencode($id) .urlencode(" AND hocr:".$srch).(")&fl=highlighting&hl.fl=hocr&hl=on&hl.fragsize=0&wt=php");
            curl_setopt($ch, CURLOPT_URL, $testURl);
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // $output contains the output string
            $output = curl_exec($ch);
        			
		if (curl_errno($ch)) {
                // return "{}";
                return array("type"=>"image","content"=>$id);
            }
            eval("\$output = " . $output . ";");
        // close curl resource to free up system resources
            curl_close($ch);
			//print_r($output['highlighting'][$id]['hocr']);
            $responseData = array("imageSrc" => "/".$id, "type" => "image","hocr"=>$output['highlighting'][$id]['hocr']);
            return $responseData;
        }

    }
    public function search(Request $request)
    {

        $filter = json_decode($request->input('pq_filter'));
        // print_r($filter);
        // return ;
        if (gettype($filter)=="object" &&property_exists($filter,"data") && property_exists( $filter->data[0],"dataIndx")) {
            $srchField = $filter->data[0]->dataIndx;
            $srchValue = $filter->data[0]->value;
        } else {
            $srchField = "content";
            $srchValue = "*";

        }
        $ch = curl_init();
        $testURl =  config('app.solr')["url"].":".config('app.solr')["port"]."/solr/".config('app.solr')["collection"]."/select?q=content:" . urlencode($srchValue) . ("&fl=id,last_modified,title,author,highlighting&hl.fl=content&hl=on&hl.fragsize=0&wt=php");
        //echo $testURl;
        // set url
        curl_setopt($ch, CURLOPT_URL, $testURl);
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $output contains the output string
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            // return "{}";
            return array(array("id" => "pdf1.pdf"), array("id" => "img1.png"), array("id" => "img2.jpg"), 
            array("id" => "pdf2.pdf"));
        }

        eval("\$output = " . $output . ";");
        // close curl resource to free up system resources
        curl_close($ch);
        $responseArr;
        foreach ($output["response"]["docs"] as $key => $value) {
            $value["content"] = $output["highlighting"][$value["id"]]["content"][0];
            $responseArr[] = ($value);
        }
        return ($responseArr);
    }
    public function down(Request $request)
    {
        $name = $request->get("fileName");
        $fileExtention = substr($name, strrpos($name, ".") + 1);
        $fileExtention = $this->extentionsMap[strtolower($fileExtention)];
        $file = "app/files/". $name;
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
        $file_path = $app_path . "/app/files/" . $fileExtention . "/" . $filename;
        $backEndUrl = config('app.engine')['url'].':'.config('app.engine')['port'].'/extract?path=' . $file_path."&fileDescription=".$request->fileDescription;
		//echo $backEndUrl;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $backEndUrl);
		curl_setopt($ch, CURLOPT_TIMEOUT_MS, 500000);
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $output contains the output string
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
        $image=Route::current()->parameter('imageName');
        if($image!="")
        {
            $storagePath = storage_path('/app/files/images/'.$image);
            return Image::make($storagePath)->response();
        }
        else 
        {
            return "{}";
        }
    }
}
