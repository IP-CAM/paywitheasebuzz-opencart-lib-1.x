<?php

class ControllerPaymentEasebuzz extends Controller {

    private $error = array();
    private $settings = array();

    public function index() {
        $this->load->language('payment/easebuzz');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');

        //new config
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('easebuzz', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
        }

        //language data
         $this->data['heading_title'] = $this->language->get('heading_title');

         $this->data['text_enabled'] = $this->language->get('text_enabled');
         $this->data['text_disabled'] = $this->language->get('text_disabled');
         $this->data['text_all_zones'] = $this->language->get('text_all_zones');
         $this->data['text_yes'] = $this->language->get('text_yes');
         $this->data['text_no'] = $this->language->get('text_no');
         $this->data['text_edit'] = $this->language->get('text_edit');

         $this->data['entry_currency'] = $this->language->get('entry_currency');
         $this->data['entry_status'] = $this->language->get('entry_status');
         $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
         $this->data['entry_merchant_key'] = $this->language->get('entry_merchant_key');
         $this->data['entry_merchant_salt'] = $this->language->get('entry_merchant_salt');
         $this->data['entry_payment_mode'] = $this->language->get('entry_payment_mode');
         $this->data['entry_complete_status'] = $this->language->get('entry_complete_status');
         $this->data['entry_cancelled_status'] = $this->language->get('entry_cancelled_status');
         $this->data['entry_new_status'] = $this->language->get('entry_new_status');
         $this->data['button_save'] = $this->language->get('button_save');
         $this->data['button_cancel'] = $this->language->get('button_cancel');
         $this->data['entry_total'] = $this->language->get('entry_total');
         $this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');

         $this->data['help_merchant_key'] = $this->language->get('help_merchant_key');
         $this->data['help_merchant_salt'] = $this->language->get('help_merchant_salt');
         $this->data['help_payment_mode'] = $this->language->get('help_payment_mode');
         $this->data['help_total'] = $this->language->get('help_total');

        //Errors
         $this->data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
         $this->data['error_merchant_key'] = isset($this->error['merchant_key']) ? $this->error['merchant_key'] : '';
         $this->data['error_merchant_salt'] = isset($this->error['merchant_salt']) ? $this->error['merchant_salt'] : '';

        //Zones, order statuses
        $this->load->model('localisation/geo_zone');
         $this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
        $this->load->model('localisation/order_status');
         $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        //Settings
         $this->data['easebuzz_total'] = isset($this->request->post['easebuzz_total']) ?
            $this->request->post['easebuzz_total'] : $this->config->get('easebuzz_total');

         $this->data['easebuzz_geo_zone_id'] = isset($this->request->post['easebuzz_geo_zone_id']) ?
            $this->request->post['easebuzz_geo_zone_id'] : $this->config->get('easebuzz_geo_zone_id');

         $this->data['easebuzz_status'] = isset($this->request->post['easebuzz_status']) ?
            $this->request->post['easebuzz_status'] : $this->config->get('easebuzz_status');

         $this->data['easebuzz_sort_order'] = isset($this->request->post['easebuzz_sort_order']) ?
            $this->request->post['easebuzz_sort_order'] :  $this->config->get('easebuzz_sort_order');

        //Status
         $this->data['easebuzz_new_status'] = isset($this->request->post['easebuzz_new_status']) ?
            $this->request->post['easebuzz_new_status'] : $this->config->get('easebuzz_new_status');
        
         $this->data['easebuzz_payment_mode'] = isset($this->request->post['easebuzz_payment_mode']) ?
            $this->request->post['easebuzz_payment_mode'] : $this->config->get('easebuzz_payment_mode');
        
         $this->data['easebuzz_merchant_salt'] = isset($this->request->post['easebuzz_merchant_salt']) ?
            $this->request->post['easebuzz_merchant_salt'] : $this->config->get('easebuzz_merchant_salt');
        
         $this->data['easebuzz_merchant_key'] = isset($this->request->post['easebuzz_merchant_key']) ?
            $this->request->post['easebuzz_merchant_key'] : $this->config->get('easebuzz_merchant_key');
//
         $this->data['easebuzz_cancelled_status'] = isset($this->request->post['easebuzz_cancelled_status']) ?
            $this->request->post['easebuzz_cancelled_status'] : $this->config->get('easebuzz_cancelled_status');
//
         $this->data['easebuzz_complete_status'] = isset($this->request->post['easebuzz_complete_status']) ?
            $this->request->post['easebuzz_complete_status'] : $this->config->get('easebuzz_complete_status');

        //Breadcroumbs
         $this->data['breadcrumbs'] = array();
         $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
        );
         $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
        );
         $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/easebuzz', 'token=' . $this->session->data['token'], 'SSL')
        );

        //links
         $this->data['action'] = $this->url->link('payment/easebuzz', 'token=' . $this->session->data['token'], 'SSL');
         $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');


//         $this->data['header'] = $this->load->controller('common/header');
//         $this->data['column_left'] = $this->load->controller('common/column_left');
//         $this->data['footer'] = $this->load->controller('common/footer');
//
//        $this->response->setOutput($this->load->view('payment/easebuzz.tpl', $data));
            $this->template = 'payment/easebuzz.tpl';
            $this->children = array(
                'common/header',
                'common/footer'
            );

            $this->response->setOutput($this->render());

    }

    //validate
    private function validate()
    {
        //permisions
        if (!$this->user->hasPermission('modify', 'payment/easebuzz')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        //check for errors
        if (!$this->request->post['easebuzz_merchant_key']) {
            $this->error['merchant_key'] = $this->language->get('error_merchant_key');
        }
        if (!$this->request->post['easebuzz_merchant_salt']) {
            $this->error['merchant_salt'] = $this->language->get('error_merchant_salt');
        }

        return !$this->error;
    }
    
    public function install()
    {
        $this->load->model('setting/setting');
        $this->settings = array(
            'easebuzz_new_status' => 1,
            'easebuzz_complete_status' => 5,
            'easebuzz_cancelled_status' => 10,
            'easebuzz_geo_zone_id' => 0,
            'easebuzz_payment_mode'=>'test',
            'easebuzz_sort_order' => 1,
        );
        $this->model_setting_setting->editSetting('easebuzz', $this->settings);
    }

    public function uninstall()
    {
        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting('easebuzz');
    }
}


