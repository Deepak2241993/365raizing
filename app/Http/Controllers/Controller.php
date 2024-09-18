<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Websitesetting;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function upload_single_image($file,$folder)
    {
    	$data = $file->getClientOriginalName();
	    $file->move(public_path('images/'.$folder."/"), $data);
    	return $data;
    }

    public function upload_images($files,$folder)
    {
	    $data = array();
        foreach($files as $file){
	        $imageName = $file->getClientOriginalName();
	        $file->move(public_path('images/'.$folder."/"), $imageName);
	        $data[] = $folder."/".$imageName;
		}
	    return $data;
	}

	public function delete_image($name,$folder){
		
		if(file_exists(public_path('images/'.$folder."/").$name)){
                unlink(public_path('images/'.$folder."/".$name));
                return true;
        }else{
               return false;
        } 

	}

	public function slugCreate($title){
		$value= strtolower(trim($title));
            $string = str_replace('   ', '-', $value); 
            $string = str_replace('  ', '-', $string); 
            $string = str_replace(' ', '-', $string); 
            $slug= preg_replace('/[^A-Za-z0-9\-]/', '', $string);
			return $slug;
	}

	public function setting(){
		return $setting=Websitesetting::where('id',1)->get();
	}
}
