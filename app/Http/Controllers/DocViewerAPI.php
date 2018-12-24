<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        
        if (strpos($id, '.pdf') !== false) {
            //get contents from solr
            $ch = curl_init();
            $testURl =  Config::get('solr.url').":".Config::get('solr.port')."/solr/".Config::get('solr.collection')."/select?q=id:" . urlencode($id) . ("&fl=highlighting&hl.fl=content&hl=on&hl.fragsize=0&wt=php");
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
            $responseData = array("imageSrc" => "/" . $id, "fromtTopLefX" => "13", "fromtTopLefY" => "13");
            return $responseData;
        }

    }
    public function search(Request $request)
    {

        $filter = json_decode($request->input('pq_filter'));
        // if(array_key_exists())
        if (property_exists("data", $filter) && property_exists("dataIndx", $filter->data[0])) {
            $srchField = $filter->data[0]->dataIndx;
            $srchValue = $filter->data[0]->value;
            // return ($filter->data[0]->dataIndx);
        } else {
            $srchField = "content";
            $srchValue = "*";

        }
        $ch = curl_init();
        $testURl =  Config::get('solr.url').":".Config::get('solr.port')."/solr/".Config::get('solr.collection')."/select?q=content:" . urlencode($srchValue) . ("&fl=id,last_modified,title,author,highlighting&hl.fl=content&hl=on&hl.fragsize=0&wt=php");
        //     echo $testURl;
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
            // echo"<br>";
        }
        return ($responseArr);
    }
    public function down(Request $request)
    {
       
        $name = $request->get("fileName");
        $fileExtention = substr($name, strrpos($name, ".") + 1);
        $fileExtention = $this->extentionsMap[strtolower($fileExtention)];
        $file = "app/public/" .$fileExtention."/". $name;
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
                'public/' . $fileExtention,
                $uploadedFile,
                $filename
            );
        }
        $app_path = Config::get('appPath');
        $file_path = $app_path . "/storage/app/public/" . $fileExtention . "/" . $filename;
        $backEndUrl = Config::get('engine.url').':'.Config::get('engine.port').'/extract?path=' . $file_path;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $backEndUrl);
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
}