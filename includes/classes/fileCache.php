<?php
/*
 * Name:	fileCache
 * URL:		http:/neo22s.com/
 * Version:	v0.1
 * Date:	18/12/2009
 * Author:	Chema Garrido
 * Support: http://forum.neo22s.com
 * License: GPL v3
 * Notes:	fileCache class, used in phpMydb
 */

/////////////////////class cache

class fileCache {
	private $cache_path;//path for the cache
	private $cache_expire;//seconds that the cache expires
	private $application;//application object like in ASP
 	private $application_file;//file for the application object
	
	//cache constructor, optional expiring time and cache path
	public function fileCache($exp_time=3600,$path="cache/"){
		$this->cache_expire=$exp_time;
		$this->cache_path=$path;
		$this->APP_start();//starting application cache with filename...
	}
	
	//returns the filename for the cache
	private function fileName($key){
		return $this->cache_path.md5($key);
	}
	
	//deletes cache from folder
	public function deleteCache($older_than=""){
		if (!is_numeric($older_than)) $older_than=$this->cache_expire;
		
		$files = scandir($this->cache_path); 
		foreach($files as $file){
			if (strlen($file)>2 && time() > (filemtime($this->cache_path.$file) + $older_than) ) {
				unlink($this->cache_path.$file);//echo "<br />-".$file; 
			}
		}
		
	}
	
	//write or read the cache
	public function cache($key, $value=""){
		if ($value!=""){//wants to wirte
			if ($this->get($key)!=$value){//only write if it's different
				$this->put($key, $value);
			}
		}
		else return $this->get($key);//reading
	}
	
	//creates new cache files with the given data, $key== name of the cache, data the info/values to store
	private function put($key, $data){
		$values = serialize($data);
		$filename = $this->fileName($key);
		$file = fopen($filename, 'w');
	    if ($file){//able to create the file
	        fwrite($file, $values);
	        fclose($file);
	    }
	    else return false;
	}
	
	//returns cache for the given key
	private function get($key){
		$filename = $this->fileName($key);
		if (!file_exists($filename) || !is_readable($filename)){//can't read the cache
			return false;
		}
		if ( time() < (filemtime($filename) + $this->cache_expire) ) {//cache for the key not expired
			$file = fopen($filename, "r");// read data file
	        if ($file){//able to open the file
	            $data = fread($file, filesize($filename));
	            fclose($file);
	            return unserialize($data);//return the values
	        }
	        else return false;
		}
		else return false;//was expired you need to create new
 	}
 	
 	//load variables from the file
	private function APP_start ($app_file="application"){
		$this->application_file=$app_file;
		
	    if (file_exists($this->cache_path.$this->application_file)){ // if data file exists, load the cached variables
	        $file = fopen($this->cache_path.$this->application_file, "r");// read data file
	        if ($file){
	            $data = fread($file, filesize($this->cache_path.$this->application_file));
	            fclose($file);
	        }
	        // build application variables from data file
	        $this->application = unserialize($data);
	    }
	    else  fopen($this->cache_path.$this->application_file, "w");//if the file does not exist we create it
  		
	    //erase the cache every X minutes before loading next time
		$app_time=filemtime($this->cache_path.$this->application_file)+$this->cache_expire;
		if (time()>$app_time) unlink ($this->cache_path.$this->application_file);//erase the cache
	}
	
	// write application data to file
	private function APP_write(){
		    $data = serialize($this->application);
		    $file = fopen($this->cache_path.$this->application_file, "w");
		    if ($file){
		        fwrite($file, $data);
		        fclose($file);
		    }
	}
	
	//returns the value or stores it
	public function APP($var,$value=""){
		if ($value!=""){//wants to wirte
			if ($this->application[md5($var)]!=$value){
				$this->application[md5($var)]=$value;
				$this->APP_write();
			}
		}
		else {//reading
			$return=$this->application[md5($var)];
			if (!isset($return)) return false;//nothing found
			else return $return;//return value
		}
	}
	
}
?>
