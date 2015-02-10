<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 * 
 * Local Version
 */
class MM_License extends MM_Entity
{	
	private $memberId;
	private $name;
	private $url;
	private $apiKey;
	private $apiSecret;
	private $majorVersion;
	private $minorVersion;
	private $permissionsProfileId;
	private $permissions;
	private $status;
	private $isArchivedFlag;
	private $lastUpdated;
	private $dateAdded;
	
	public function __construct($id="", $getData=true)
	{
		if($getData)
		{
			$this->getData();
		}
	}
	
	public function getData()
	{
		if(class_exists("MM_MemberMouseService"))
		{
			if(method_exists("MM_MemberMouseService", "getLicense"))
			{
				MM_MemberMouseService::getLicense($this);
			}
			else
			{
				// TODO remove this after all customers migrated from 1.x
				// protect from 1.x versions of MemberMouse trying to upgrade directly
				$error = "<div style='font-family: sans-serif; font-size: 13px;'>";
			
				$error .= "<h3 style='color:#BC0B0B; margin-top:0px; margin-bottom:5px; font-size: 14px;'>Cannot Upgrade to MemberMouse 2.0</h3>";
			
				$error .= "<p style='margin-top:0px; margin-bottom:5px;'>Before you can upgrade MemberMouse 2.0 you must export your data and uninstall the current version of MemberMouse.</p>";
				$error .= "<p style='margin-top:0px; margin-bottom:5px;'>Please contact us at <a href='mailto:support@membermouse.com'>support@membermouse.com</a> for more information on upgrading to MemberMouse 2.0.</p>";
			
				$error .= "</div>";
			
				$vars = new stdClass();
				$vars->content = $error;
				echo $error; 
				@deactivate_plugins(MM_PLUGIN_ABSPATH."/index.php", false);
				exit;
			}
		}
		else
		{
			parent::invalidate();
		}
	}
	
	public function setData($data)
	{
		try 
		{
			$this->id = $data->id;
			$this->memberId = $data->memberId;
			$this->name = $data->name;
			$this->url = $data->url;
			$this->apiKey = $data->apiKey;
			$this->apiSecret = $data->apiSecret;
			$this->majorVersion = $data->majorVersion;
			$this->minorVersion = $data->minorVersion;
			$this->permissionsProfileId = $data->permissionsProfileId;
			$this->permissions = $data->permissions;
			$this->status = $data->status;
			$this->isArchivedFlag = $data->isArchivedFlag;
			$this->dateAdded = $data->dateAdded;
			$this->lastUpdated = $data->lastUpdated;
			
			$this->validate();
		}
		catch (Exception $ex) 
		{
			parent::invalidate();
		}
	}
	
	public function commitData() {}
	
	
	/** GETTERS / SETTERS **/
	
	public function setMemberId($str)
	{
		$this->memberId = $str;
	}
	
	public function getMemberId()
	{
		return $this->memberId;
	}
	
	public function setName($str) 
	{
		$this->name = $str;
	}
	
	public function getName() 
	{
		return $this->name;
	}
	
	public function setUrl($str) 
	{
		$this->url = $str;
	}
	
	public function getUrl() 
	{
		return $this->url;
	}
	
	public function setApiKey($str)
	{
		$this->apiKey = $str;
	}
	
	public function getApiKey()
	{
		return $this->apiKey;
	}
	
	public function setApiSecret($str)
	{
		$this->apiSecret = $str;
	}
	
	public function getApiSecret()
	{
		return $this->apiSecret;
	}
	
	public function setMajorVersion($str) 
	{
		$this->majorVersion = $str;
	}
	
	public function getMajorVersion() 
	{
		return $this->majorVersion;
	}
	
	public function setMinorVersion($str) 
	{
		$this->minorVersion = $str;
	}
	
	public function getMinorVersion() 
	{
		return $this->minorVersion;
	}
	
	public function setPermissionsProfileId($str) 
	{
		$this->permissionsProfileId = $str;
	}
	
	public function getpermissionsProfileId() 
	{
		return $this->permissionsProfileId;
	}
	
	public function setPermissions($str) 
	{
		$this->permissions = $str;
	}
	
	public function getPermissions() 
	{
		return $this->permissions;
	}

	public function setStatus($str)
	{
		$this->status = $str;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function setLastUpdated($str) 
	{
		$this->lastUpdated = $str;
	}
	
	public function getDateAdded() 
	{
		return $this->dateAdded;
	}
	
	public function setDateAdded($str) 
	{
		$this->dateAdded = $str;
	}
	
	public function getLastUpdated() 
	{
		return $this->lastUpdated;
	}
	
	public function isArchived() 
	{
		return ($this->isArchivedFlag == "1") ? true : false;
	}
}
?>