<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
abstract class MM_Entity
{
	protected $id = 0;
	private $valid = false;
	private $authKey = "";
	protected $notifyServices = true;
	
	public function __construct($id="", $getData=true) 
 	{
 		if(!($id instanceof MM_Response))
 		{
	 		if(isset($id) && intval($id) > 0)
	 		{
	 			$this->id = $id;
	 			
	 			if($getData == true) 
	 			{
	 				$this->getData();
	 			}
	 		}
	 		else 
	 		{
	 			$id = "";
	 		}
 		}
 	}
 	
	abstract protected function getData();
	abstract public function setData($data);
	abstract protected function commitData();
 	
	public function setId($str)
 	{
 		$this->id = $str;
 	}
 	
	public function getId()
 	{
 		return $this->id;
 	}
	
	public function validate()
	{
		$this->valid = true;
	}
	
	public function invalidate() 
	{
		$this->valid = false;	
	}
	
	public function isValid()
	{
		return $this->valid;
	}
	
	public function getAuthKey()
	{
		return $this->authKey;
	}
	
	public function setAuthKey($str)
	{
		$this->authKey = $str;
	}
	
	public function notifiesServices($doServiceNotification)
	{
		if (is_bool($doServiceNotification))
		{
			$this->notifyServices = $doServiceNotification;
		}
	}
}
?>
