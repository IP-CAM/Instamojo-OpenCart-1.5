<?php
class ControllerPaymentInstamojo extends Controller {
  private $error = array();
 
  public function index() {
    $this->language->load('payment/instamojo');
    $this->document->setTitle('Instamojo Payment Method Configuration');
    $this->load->model('setting/setting');
 
    if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
      $this->model_setting_setting->editSetting('instamojo', $this->request->post);
      $this->session->data['success'] = 'Saved.';
      $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
    }
 
    $this->data['heading_title'] = $this->language->get('heading_title');
    $this->data['entry_text_instamojo_checkout_label'] = $this->language->get('instamojo_checkout_label');
    $this->data['entry_text_instamojo_api_key'] = $this->language->get('instamojo_api_key');
    $this->data['entry_text_instamojo_auth_token'] = $this->language->get('instamojo_auth_token');
    $this->data['entry_text_instamojo_private_salt'] = $this->language->get('instamojo_private_salt');
    $this->data['entry_text_instamojo_payment_link'] = $this->language->get('instamojo_payment_link');
    $this->data['entry_text_instamojo_custom_field'] = $this->language->get('instamojo_custom_field');
    $this->data['button_save'] = $this->language->get('text_button_save');
    $this->data['button_cancel'] = $this->language->get('text_button_cancel');
    $this->data['entry_order_status'] = $this->language->get('entry_order_status');
    $this->data['text_enabled'] = $this->language->get('text_enabled');
    $this->data['text_disabled'] = $this->language->get('text_disabled');
    $this->data['entry_status'] = $this->language->get('entry_status');
    $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
 
    $this->data['action'] = $this->url->link('payment/instamojo', 'token=' . $this->session->data['token'], 'SSL');
    $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

    if (isset($this->request->post['instamojo_checkout_label'])) {
      $this->data['instamojo_checkout_label'] = $this->request->post['instamojo_checkout_label'];
    } else {
      $this->data['instamojo_checkout_label'] = $this->config->get('instamojo_checkout_label');
    }
 
    if (isset($this->request->post['instamojo_api_key'])) {
      $this->data['instamojo_api_key'] = $this->request->post['instamojo_api_key'];
    } else {
      $this->data['instamojo_api_key'] = $this->config->get('instamojo_api_key');
    }
        
    if (isset($this->request->post['instamojo_auth_token'])) {
      $this->data['instamojo_auth_token'] = $this->request->post['instamojo_auth_token'];
    } else {
      $this->data['instamojo_auth_token'] = $this->config->get('instamojo_auth_token');
    }

    if (isset($this->request->post['instamojo_private_salt'])) {
      $this->data['instamojo_private_salt'] = $this->request->post['instamojo_private_salt'];
    } else {
      $this->data['instamojo_private_salt'] = $this->config->get('instamojo_private_salt');
    }

    if (isset($this->request->post['instamojo_payment_link'])) {
      $this->data['instamojo_payment_link'] = $this->request->post['instamojo_payment_link'];
    } else {
      $this->data['instamojo_payment_link'] = $this->config->get('instamojo_payment_link');
    }

    if (isset($this->request->post['instamojo_custom_field'])) {
      $this->data['instamojo_custom_field'] = $this->request->post['instamojo_custom_field'];
    } else {
      $this->data['instamojo_custom_field'] = $this->config->get('instamojo_custom_field');
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
    $this->template = 'payment/instamojo.tpl';
            
    $this->children = array(
      'common/header',
      'common/footer'
    );
 
    $this->response->setOutput($this->render());
  }
}