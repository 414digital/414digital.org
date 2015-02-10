<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
class LogMe
{
	public static function ShowError($str)
	{
		if(preg_match("/[0-9]+/", $str))
		{
			//echo $str."<br />";
		}
	}
	public function to_string($obj)
	{
		return @json_encode($obj);
	}
	private static function format_bytes($b,$p = null) {
	    /**
	     * returns formatted number of bytes. 
	     * two parameters: the bytes and the precision (optional).
	     * if no precision is set, function will determine clean
	     * result automatically.
	     * 
	     **/
	    $units = array("B","kB","MB","GB","TB","PB","EB","ZB","YB");
	    $c=0;
	    if(!$p && $p !== 0) {
	        foreach($units as $k => $u) {
	            if(($b / pow(1024,$k)) >= 1) {
	                $r["bytes"] = $b / pow(1024,$k);
	                $r["units"] = $u;
	                $c++;
	            }
	        }
	        return array('result'=>number_format($r["bytes"],2), 'units'=> $r["units"]);
	    } else {
	        return array('result'=>number_format($b / pow(1024,$p)), 'units'=> $units[$p]);
	    }
	}
	public static function write($str)
	{
		$dir = dirname(dirname(dirname(dirname(__FILE__))));
		$dir = preg_replace("/(\/)$/","", $dir);

		if(defined("MM_PLUGIN_ABSPATH"))
		{
			$dir = MM_PLUGIN_ABSPATH;	
		}
		
		$l = new LogMe();		
		$output = $l->to_string($str);
		$file = $dir."/logs/".Date("Y-m-d").".log";
		if(!file_exists($file))
		{
			$fh = @fopen($file, "w");
			if(!@fwrite($fh, $output."\n\n"))
			{
				return false;
			}
			fclose($fh);		
		}
		if(!is_writable($file))
			return false;
		
		$a = "a";
		$fs = filesize($file);
		
		//$size_ret = self::format_bytes($fs);
		
//		if(floatval($size_ret["result"])>MM_LOGSIZE)
//		{
//			$a="w";
//		}
		
		$prefix = Date("m/d/Y h:i:s a")." :\n";
		$fh = @fopen($file, $a);
		if(!@fwrite($fh, $prefix.$output."\n\n"))
		{
			return false;
		}
		fclose($fh);		
	}
}
?>
