<?php

class DUP_CTRL_Base
{
	
}

class DUP_CTRL_Report
{
	//Properties
	public $Process;
	public $Results;
}

class DUP_CTRL_Result
{
	//Properties
	public $Report;
	public $Payload;
	private $time_start;
	private $time_end;
	
	public function __construct() 
	{
		$this->time_start = $this->microtime_float();
		$this->Report   =  new DUP_CTRL_Report();
	}
	
	public function GetProcessTime()
	{
		$this->time_end = $this->microtime_float();
		$this->Report->Process = $this->time_end - $this->time_start;
	}
	
	private function microtime_float()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	
}
?>