<?php
class Response
{
	const SUCCESS = "success";
	const ERROR = "error";
	
	public $message = "";
	public $type = "";
	public $responseCode = "";
	public $name = "";
	public $data = "";
	
	public function __construct($rest, $data=null, $msg="", $responseCode="200", $name="", $type="success") 
 	{
 		$this->message = $msg;
 		$this->type = $type;
 		$this->responseCode = $responseCode;
 		$this->name = $name;
 		$this->data = $data;
 		
 		return $this->send($rest);
 	}

	private function send($rest)
	{
        $response = $this->prepareResponse();
        MM_LogApi::logRequest($response);
        $rest->getResponse()->addHeader("HTTP/1.1 {$this->responseCode} {$this->name}");
        $rest->getResponse()->setResponse($response);
        return $rest;
	}
	
	private function prepareResponse()
	{
		$responseArr = array(
			'response_code'=>$this->responseCode,
			'response_message'=>$this->message,
			'response_data' => $this->data,
		);
		return json_encode($responseArr);
	}
}