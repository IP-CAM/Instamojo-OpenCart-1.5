<?php
class ControllerPaymentInstamojo extends Controller {
  private $error = array();
 
  public function index() {
    $this->language->load('payment/instamojo');
    $this->document->setTitle('Instamojo Payment Method Configuration');
    $this->load->model('setting/setting');
 
    if (($this->request->server['REQUEST_METHOD'] == 'POST') and $this->validate()) {
      $this->model_setting_setting->editSetting('instamojo', $this->request->post);
      $this->session->data['success'] = 'Saved.';
      $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
    }
	
	
	if($this->error)
	{
		$this->data['error_warning'] = implode("<br/>",$this->error);	
	}else
		$this->data['error_warning'] = ""; 
	
    $this->data['heading_title'] = $this->language->get('heading_title');
    $this->data['entry_text_instamojo_checkout_label'] = $this->language->get('instamojo_checkout_label');
    $this->data['entry_text_instamojo_client_id'] = $this->language->get('instamojo_client_id');
    $this->data['entry_text_instamojo_client_secret'] = $this->language->get('instamojo_client_secret');
    
	$this->data['button_save'] = $this->language->get('text_button_save');
    $this->data['button_cancel'] = $this->language->get('text_button_cancel');
    $this->data['entry_order_status'] = $this->language->get('entry_order_status');
   
	$this->data['entry_test_mode'] = $this->language->get('entry_test_mode');
    $this->data['entry_test_mode_on'] = $this->language->get('entry_test_mode_on');
    $this->data['entry_test_mode_off'] = $this->language->get('entry_test_mode_off');
    
	$this->data['text_enabled'] = $this->language->get('text_enabled');
    $this->data['text_disabled'] = $this->language->get('text_disabled');
    $this->data['text_edit'] = $this->language->get('text_edit');
    $this->data['entry_status'] = $this->language->get('entry_status');
    $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
    
 
    $this->data['action'] = $this->url->link('payment/instamojo', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

    if (isset($this->request->post['instamojo_checkout_label'])) {
      $this->data['instamojo_checkout_label'] = $this->request->post['instamojo_checkout_label'];
    } else {
      $this->data['instamojo_checkout_label'] = $this->config->get('instamojo_checkout_label');
    }
 
 
    if (isset($this->request->post['instamojo_client_id'])) {
      $this->data['instamojo_client_id'] = $this->request->post['instamojo_client_id'];
    } else {
      $this->data['instamojo_client_id'] = $this->config->get('instamojo_client_id');
    }
    
	if (isset($this->request->post['instamojo_testmode'])) {
      $this->data['instamojo_testmode'] = $this->request->post['instamojo_testmode'];
    } else {
      $this->data['instamojo_testmode'] = $this->config->get('instamojo_testmode');
    }    
    if (isset($this->request->post['instamojo_client_secret'])) {
      $this->data['instamojo_client_secret'] = $this->request->post['instamojo_client_secret'];
    } else {
      $this->data['instamojo_client_secret'] = $this->config->get('instamojo_client_secret');
    }

 
       
    if (isset($this->request->post['instamojo_status'])) {
      $this->data['instamojo_status'] = $this->request->post['instamojo_status'];
    } else {
      $this->data['instamojo_status'] = $this->config->get('instamojo_status');
    }
        
    if (isset($this->request->post['instamojo_order_status_id'])) {
      $this->data['instamojo_order_status_id'] = $this->request->post['instamojo_order_status_id'];
    } else {
      $this->data['instamojo_order_status_id'] = $this->config->get('instamojo_order_status_id');
    }

    if (isset($this->request->post['instamojo_sort_order'])) {
      $this->data['instamojo_sort_order'] = $this->request->post['instamojo_sort_order'];
    } else {
      $this->data['instamojo_sort_order'] = $this->config->get('instamojo_sort_order');
    }
 
    $this->load->model('localisation/order_status');
    $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
            
    $this->data['breadcrumbs'] = array();
            $this->data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
				'separator'=>false
            );

    $this->data['breadcrumbs'][] = array(
      'text' => 'Payment',
      'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
	  'separator'=>"::"
    );

    $this->data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('payment/instamojo', 'token=' . $this->session->data['token'], 'SSL'),
	  'separator'=>"::"
    );


   $this->template = 'payment/instamojo.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

	$this->response->setOutput($this->render());

  }
  
  private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/instamojo')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['instamojo_checkout_label']) {
			$this->error['instamojo_checkout_label'] = $this->language->get('error_checkout_label');
		}
		if (!$this->request->post['instamojo_client_id']) {
			$this->error['instamojo_client_id'] = $this->language->get('error_client_id');
		}
		if (!$this->request->post['instamojo_client_secret']) {
			$this->error['instamojo_client_secret'] = $this->language->get('error_client_secret');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
  
}