<?php

class mytime
{
	var $timestart;
	var $timelast;
	var $timescript;
    var $digits;

	public function __construct()
	{
		$this->timestart 		= self::microtime_float();//explode (' ', microtime());
		$this->timelast 		= $this->timestart;
		$this->timescript 		= isset($_SERVER["REQUEST_TIME_FLOAT"]) ? $_SERVER["REQUEST_TIME_FLOAT"] : 0;
//		$this->digits 			= 5;
	}


	public function script_timestamp()
	{
		return $this->timescript;
	}


	public function microtime_float()
	{
		list($usec, $sec) 		= explode(' ', microtime());
		//list($usec, $sec) 		= explode(' ', microtime(true));
		//return ((float)$usec + (float)$sec);
		return bcadd((float)$usec, (float)$sec, 6);
	}
	
	public function took()
	{
		$timefinish 			= self::microtime_float();//explode (' ', microtime());
		if (!isset($this->timelast) || empty($this->timelast) || $this->timelast==$this->timestart)
		{
//			if ($last_time!=$start_time)
				$this->timelast = self::microtime_float();
//			return (self::microtime_float() - 0). '|'. (0 + $this->timestart).
			return (self::microtime_float() - $this->timestart).
				'[from exec:'. (self::microtime_float() - $this->script_timestamp()). ']';
//				'[from exec:'. (self::microtime_float() - 0). '|'. (0 + $this->script_timestamp()). ']';
		} else
		{
			$return 			= (self::microtime_float() - $this->timelast).
									'(from start:'. (self::microtime_float() - $this->timestart). ')';
			$this->timelast 	= self::microtime_float();
			return $return;
		}
/*
		if (!isset($this->timebeforelast) || is_null($this->timebeforelast) || !$this->timebeforelast)
		{
			$this->timebeforelast = $this->timestart;
		}
		if ($this->digits 		== '')
		{
			$runtime_float 		= $timefinish[0] - $this->timelast[0];//$this->timestart[0];
		} else
		{
			$runtime_float 		= round(($timefinish[0] - $this->timelast[0]), $this->digits);
//			$runtime_float 		= round(($timefinish[0] - $this->timestart[0]), $this->digits);
		}
		$runtime 				= ($timefinish[1] - $this->timelast[1]) + $runtime_float;
//		$runtime 				= ($timefinish[1] - $this->timestart[1]) + $runtime_float;
		$this->timebeforelast 	= $this->timelast;

		return $runtime;*/
    }
}