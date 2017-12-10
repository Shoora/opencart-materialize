<?php
class ControllerExtensionModuleCallback extends Controller {
	public function index() {
		$this->load->language('extension/module/callback');

		$data['lang'] = $this->language->get('code');

		$module_callback = $this->config->get('module_callback');

		$data['module_callback_title'] = $module_callback[$this->config->get('config_language_id')]['title'];
		$data['module_callback_caption'] = $module_callback[$this->config->get('config_language_id')]['caption'];
		$data['module_callback_description'] = $module_callback[$this->config->get('config_language_id')]['description'];
		$data['module_callback_time'] = $this->config->get('module_callback_time');

		$data['button_submit'] = $this->language->get('button_submit');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_enquiry'] = $this->language->get('entry_enquiry');
		$data['entry_calltime'] = $this->language->get('entry_calltime');

		$data['button_time_done'] = $this->language->get('button_time_done');
		$data['button_time_clear'] = $this->language->get('button_time_clear');
		$data['button_time_cancel'] = $this->language->get('button_time_cancel');
		$data['twelve_hour'] = $this->language->get('twelve_hour');

		$data['error_telephone'] = $this->language->get('error_telephone');
		$data['error_name'] = $this->language->get('error_name');
		$data['error_enquiry'] = $this->language->get('error_enquiry');
		$data['error_calltime'] = $this->language->get('error_calltime');

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['order_page'] = 'https://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		} else {
			$data['order_page'] = 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}

		if ($this->config->get('module_callback_name') == 1) {
			$data['module_callback_name'] = $this->config->get('module_callback_name');

			if ($this->config->get('module_callback_name_required') == 1) {
				$data['module_callback_name_required'] = $this->config->get('module_callback_name_required');
			} else {
				$data['module_callback_name_required'] = '';
			}
		} else {
			$data['module_callback_name'] = '';
			$data['module_callback_name_required'] = '';
		}

		if ($this->config->get('module_callback_enquiry') == 1) {
			$data['module_callback_enquiry'] = $this->config->get('module_callback_enquiry');

			if ($this->config->get('module_callback_enquiry_required') == 1) {
				$data['module_callback_enquiry_required'] = $this->config->get('module_callback_enquiry_required');
			} else {
				$data['module_callback_enquiry_required'] = '';
			}
		} else {
			$data['module_callback_enquiry'] = '';
			$data['module_callback_enquiry_required'] = '';
		}

		if ($this->config->get('module_callback_calltime') == 1) {
			$data['module_callback_calltime'] = $this->config->get('module_callback_calltime');

			if ($this->config->get('module_callback_calltime_required') == 1) {
				$data['module_callback_calltime_required'] = $this->config->get('module_callback_calltime_required');
			} else {
				$data['module_callback_calltime_required'] = '';
			}
		} else {
			$data['module_callback_calltime'] = '';
			$data['module_callback_calltime_required'] = '';
		}

		if ($this->config->get('module_callback_callaction_status') == 1) {
			$data['module_callback_callaction_status'] = $this->config->get('module_callback_callaction_status');
		} else {
			$data['module_callback_callaction_status'] = '';
		}

		if ($this->config->get('module_callback_phonemask_status') == 1) {
			$data['module_callback_phonemask_status'] = $this->config->get('module_callback_phonemask_status');
		} else {
			$data['module_callback_phonemask_status'] = '';
		}

		if (isset($this->request->post['module_callback_agree'])) {
			$data['module_callback_agree'] = $this->request->post['module_callback_agree'];
		} else {
			$data['module_callback_agree'] = false;
		}

		if ($this->config->get('module_callback_agreement')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('module_callback_agreement'));

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_account_id'), true), $information_info['title'], $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}

		if ($this->config->get('module_callback_status') == 1) {
			$data['module_callback_status'] = $this->config->get('module_callback_status');
		} else {
			$data['module_callback_status'] = '';
		}

		return $this->load->view('extension/module/callback', $data);
	}

	public function send() {
		$this->load->language('extension/module/callback');

		$module_callback = $this->config->get('module_callback');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen(trim($this->request->post['module_callback_telephone'])) < 4) || (utf8_strlen(trim($this->request->post['module_callback_telephone'])) > 24)) {
				$json['error'] = $this->language->get('error_telephone');
			}

			if (isset($this->request->post['module_callback_name'])) {
				if (($this->config->get('module_callback_name_required') == 1) && ((utf8_strlen(trim($this->request->post['module_callback_name'])) < 3) || (utf8_strlen(trim($this->request->post['module_callback_name'])) > 25))) {
					$json['error'] = $this->language->get('error_name');
				}
			} else {
				$this->request->post['module_callback_name'] = '';
			}

			if (isset($this->request->post['module_callback_enquiry'])) {
				if (($this->config->get('module_callback_enquiry_required') == 1) && ((utf8_strlen(trim($this->request->post['module_callback_enquiry'])) < 10) || (utf8_strlen(trim($this->request->post['module_callback_enquiry'])) > 360))) {
					$json['error'] = $this->language->get('error_enquiry');
				}
			} else {
				$this->request->post['module_callback_enquiry'] = '';
			}

			if (isset($this->request->post['module_callback_calltime'])) {
				if (($this->config->get('module_callback_calltime_required') == 1) && empty($this->request->post['module_callback_calltime'])) {
					$json['error'] = $this->language->get('error_calltime');
				}
			} else {
				$this->request->post['module_callback_calltime'] = '';
			}

			// Agree to terms
			if ($this->config->get('module_callback_agreement')) {
				$this->load->model('catalog/information');

				$information_info = $this->model_catalog_information->getInformation($this->config->get('module_callback_agreement'));

				if ($information_info && !isset($this->request->post['module_callback_agree'])) {
					$json['error'] = sprintf($this->language->get('error_agree'), '<b>&nbsp;' . $information_info['title'] . '</b>');
				}
			}

			if (!isset($json['error'])) {
				$this->load->model('extension/module/callback');

				$this->model_extension_module_callback->sendCallback($this->request->post);

				$json['success'] = $module_callback[$this->config->get('config_language_id')]['success'];
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}