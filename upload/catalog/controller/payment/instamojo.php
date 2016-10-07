<?php
require __DIR__ . "/lib/Instamojo.php";

class ControllerPaymentInstamojo extends Controller {

  private $logger;

  public function __construct($arg)
  {
    $this->logger= new Log('imojo.log');
    parent::__construct($arg);
	
  }
  private function getInstamojoObject()
  {
	$client_id 			= $this->config->get('instamojo_client_id');
	$client_secret 		= $this->config->get('instamojo_client_secret');
	$testmode 			= $this->config->get('instamojo_testmode');
	
	$this->logger->write("Client Id: $client_id | Client Secret $client_secret | TestMode : $testmode");
	return  new Instamojo($client_id,$client_secret,$testmode);
  }
  
  public function start() {
	
    $this->language->load('payment/instamojo');
    $this->load->model('checkout/order');

	# if the phone is not valid update it in DB.
	if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
		$order_id = $this->session->data['order_id'];
		$phone = $this->request->post['telephone'];
		$this->logger->write("Phone no updated to $phone for order id: ".$this->session->data['order_id']);
		$this->db->query("UPDATE ".DB_PREFIX."order set telephone = '" . $this->db->escape($phone)."' where order_id='$order_id'");
	}
	
	$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
	
	$this->logger->write("Step 2: Creating new Order with ".$this->session->data['order_id']);
    if ($order_info) {
	
		$api_data['name'] = substr(trim((html_entity_decode($order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'], ENT_QUOTES, 'UTF-8'))), 0, 20);
		$api_data['email'] 			= substr($order_info['email'], 0, 75);
		$api_data['phone'] 			= substr(html_entity_decode($order_info['telephone'], ENT_QUOTES, 'UTF-8'), 0, 20);
		$api_data['amount'] 		= $this->currency->format($order_info['total'], $order_info['currency_code'] , false, false);
		$api_data['currency'] 		= "INR";
		$api_data['redirect_url'] 	= $this->url->link('payment/instamojo/confirm');
		$api_data['transaction_id'] = time()."-". $this->session->data['order_id'];
		
		try{
			$api = $this->getInstamojoObject();
			$this->logger->write("Data Passed for creating Order : ".print_r($api_data,true));
			$response = $api->createOrderPayment($api_data);
			$this->logger->write("Response from Server". print_R($response,true));
			if(isset($response->order ))
			{
				$this->data['action'] 						= $response->payment_options->payment_url;
				$this->session->data['payment_request_id'] 	= $response->order->id;		
			}
				
		}catch(CurlException $e){
			// handle exception releted to connection to the sever
			$this->logger->write((string)$e);
			$this->data['errors'][] = $e->getMessage();
		}catch(ValidationException $e){
			// handle exceptions releted to response from the server.
			$this->logger->write($e->getMessage()." with ");
			$this->logger->write(print_R($e->getResponse(),true)."");
			$this->data['errors'] = $e->getErrors();			
		}catch(Exception $e)
		{ // handled common exception messages which will not caught above.
			$this->data['errors'][] = $e->getMessage();
			$this->logger->write('Error While Creating Order : ' . $e->getMessage());
		}
		
		$this->data['telephone'] 	= $order_info['telephone'];
		

        
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/payment/instamojo_redirect.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/instamojo/instamojo_redirect.tpl';
		} else {
			$this->template = 'default/template/payment/instamojo/instamojo_redirect.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
				
 		$this->response->setOutput($this->render());
	}else{
		$this->logger->write("Order information not found Quitting.");
		$this->redirect($this->url->link('checkout/checkout', '', 'SSL'));

	}
	
  }
  
  public function index(){
	# make customer redirect to the payment/instamojo/start for avoiding problem releted to Journal2.6.x Quickcheckout
	$this->data['action'] = "";
	$this->logger->write("Step 1: Redirecting to  payment/instamojo/start");
	
	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/instamojo/instamojo.tpl')) {
		$this->template = $this->config->get('config_template') . '/template/payment/instamojo/instamojo.tpl';
	} else {
		$this->template = 'default/template/payment/instamojo/instamojo.tpl';
	}

	$this->render();
  }
  
  
  public function confirm(){

  if (isset($this->request->get['payment_id']) and isset($this->request->get['id']))
  {  
	$payment_id = $this->request->get['payment_id'];
	$payment_request_id = $this->request->get['id']; 
      
	$this->logger->write("Callback called with payment ID: $payment_id and payment request ID : $payment_request_id ");
	  
	if($payment_request_id != $this->session->data['payment_request_id'])
	{
		$this->logger->write("Payment Request ID not matched  payment request stored in session (".$this->session->data['payment_request_id'].") with Get Request ID $payment_request_id.");
		$this->redirect($this->config->get('config_url'));
	}	  
    
	try {
		$api = $this->getInstamojoObject();
		$response = $api->getOrderById($payment_request_id);
		$this->logger->write("Response from server for PaymentRequest ID $payment_request_id ".PHP_EOL .print_R($response,true));
		$payment_status = $api->getPaymentStatus($payment_id, $response->payments);
		$this->logger->write("Payment status for $payment_id is $payment_status");

		if($payment_status === "successful" OR  $payment_status =="failed" )
		{
			$this->logger->write("Response from server is $payment_status.");
			$order_id = $response->transaction_id;
			$order_id = explode("-",$order_id);
			$order_id = $order_id[1];
			$this->logger->write("Extracted order id from trasaction_id: ".$order_id);
			$this->load->model('checkout/order');
				$order_info = $this->model_checkout_order->getOrder($order_id);
				if($order_info)
				{
					if($payment_status == "successful"){
					  $this->logger->write("Payment for $payment_id was credited.");
					  $this->model_checkout_order->confirm($order_id, $this->config->get('instamojo_order_status_id'), "Payment successful for instamojo payment ID: $payment_id", true);
					  $this->redirect($this->url->link('checkout/success', '', 'SSL'));
					}
					else if($payment_status == "failed"){
					  $this->logger->write("Payment for $payment_id failed.");
					  $this->model_checkout_order->confirm($order_id, 10, "Payment failed for instamojo payment ID: $payment_id", false);
					  $this->redirect($this->url->link('checkout/cart', '', 'SSL'));

					}
					
				}else
					$this->logger->write("Order not found with order id $order_id");
			}
		}catch(CurlException $e){
			$this->logger->write($e);
		}catch(Exception $e){
			$this->logger->write($e->getMessage());
			$this->logger->write("Payment for $payment_id was not credited.");
			$this->redirect($this->url->link('checkout/cart', '', 'SSL'));
		}	 
	}else {
      $this->logger->write("Callback called with no payment ID or payment_request Id.");
      $this->redirect($this->config->get('config_url'));
    }
  
  }


}
