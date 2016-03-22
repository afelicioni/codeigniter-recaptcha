<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recaptcha {
	const TEST_SITEKEY = '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI';
	const TEST_SECRETKEY = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';
	const VERIFY_ENDPOINT = 'https://www.google.com/recaptcha/api/siteverify';
	protected $CI;
	private $allowtestkeys = FALSE;
	private $profile_sitekey = NULL;
	private $profile_secretkey = NULL;
	private $widget_hl = NULL;
	private $widget_theme = NULL;
	private $widget_type = NULL;
	private $widget_size = NULL;
	private $widget_tabindex = NULL;
	private $widget_callback = NULL;
	private $widget_expiredcallback = NULL;
	private $apijsrendered = FALSE;

	public function __construct() {
		$this->CI =& get_instance();
		$this->CI->config->load('Recaptcha', TRUE);
		$this->allowtestkeys = $this->CI->config->item('allowtestkeys', 'Recaptcha');
		$this->profile_sitekey = $this->CI->config->item('profile_sitekey', 'Recaptcha');
		$this->profile_secretkey = $this->CI->config->item('profile_secretkey', 'Recaptcha');
		if ( !isset($this->profile_sitekey) || !isset($this->profile_secretkey ) ) {
			$this->errorConfigWarning('Error for configured keys in Recaptcha library');
		}
		if ( !$this->allowtestkeys && ( $this->profile_sitekey===self::TEST_SITEKEY || $this->profile_secretkey===self::TEST_SECRETKEY ) ) {
			$this->errorConfigWarning('Test keys not allowed in Recaptcha library');
		}
		$this->widget_hl = $this->CI->config->item('widget_hostlanguage', 'Recaptcha') ?: NULL;
		$this->widget_theme = $this->CI->config->item('widget_theme', 'Recaptcha') ?: NULL;
		$this->widget_type = $this->CI->config->item('widget_type', 'Recaptcha') ?: NULL;
		$this->widget_size = $this->CI->config->item('widget_size', 'Recaptcha') ?: NULL;
		$this->widget_tabindex = $this->CI->config->item('widget_tabindex', 'Recaptcha') ?: NULL;
		$this->widget_callback = $this->CI->config->item('widget_callback', 'Recaptcha') ?: NULL;
		$this->widget_expiredcallback = $this->CI->config->item('widget_expiredcallback', 'Recaptcha') ?: NULL;
	}
	public function widget($parameters=array()) {
		$apijspieces = array();
		isset($parameters['hl']) ? $apijspieces['hl'] = $parameters['hl'] : is_null($this->widget_hl) || $apijspieces['hl'] = $this->widget_hl;
		$apijspieces['render'] = 'explicit';
		$apijspieces['onload'] = 'cbOnLoad';
		
		$grecaptchapieces = array();
		$grecaptchapieces['sitekey'] = $this->profile_sitekey;
		isset($parameters['theme']) ? $grecaptchapieces['theme'] = $parameters['theme'] : is_null($this->widget_theme) || $grecaptchapieces['theme'] = $this->widget_theme;
		isset($parameters['type']) ? $grecaptchapieces['type'] = $parameters['type'] : is_null($this->widget_type) || $grecaptchapieces['type'] = $this->widget_type;
		isset($parameters['size']) ? $grecaptchapieces['size'] = $parameters['size'] : is_null($this->widget_size) || $grecaptchapieces['size'] = $this->widget_size;
		isset($parameters['tabindex']) ? $grecaptchapieces['tabindex'] = $parameters['tabindex'] : is_null($this->widget_tabindex) || $grecaptchapieces['tabindex'] = $this->widget_tabindex;
		isset($parameters['callback']) ? $grecaptchapieces['callback'] = $parameters['callback'] : is_null($this->widget_callback) || $grecaptchapieces['callback'] = $this->widget_callback;
		isset($parameters['expired-callback']) ? $grecaptchapieces['expired-callback'] = $parameters['expired-callback'] : is_null($this->widget_expiredcallback) || $grecaptchapieces['expired-callback'] = $this->widget_expiredcallback;
		
		$temporaryID = 'cirecaptcha-'.$this->uuid();
		$return = '';
		if (!$this->apijsrendered) {
			$this->apijsrendered = TRUE;
			$return .= '<script type="text/javascript">';
			$return .= 'var cbCollection = [];';
			$return .= 'var cbOnLoad = function() {';
			$return .= 'for (var i in cbCollection) {';
			$return .= 'grecaptcha.render(cbCollection[i].id, cbCollection[i].options);';
			$return .= '}';
			$return .= '};';
			$return .= '</script>';
			$return .= '<script src="https://www.google.com/recaptcha/api.js';
			count($apijspieces)==0 || $return .= '?'.http_build_query($apijspieces);
			$return .= '" async defer></script>';
		}
		$return .= '<script type="text/javascript">';
		$return .= 'cbCollection.push({id:"'.$temporaryID.'",options:{';
		$return .= implode(',', array_map(
			function ($k, $v) { return '"'.$k.'":"'.$v.'"'; },
			array_keys($grecaptchapieces),
			array_values($grecaptchapieces)
		));
		$return .= '}});';
		$return .= '</script>';
		$return .= '<div id="'.$temporaryID.'"';
		$return .= '></div>';
		return $return;
	}
	public function widgetOne($parameters=array()) {
		$apijspieces = array();
		isset($parameters['hl']) ? $apijspieces['hl'] = $parameters['hl'] : is_null($this->widget_hl) || $apijspieces['hl'] = $this->widget_hl;

		$grecaptchapieces = array();
		$grecaptchapieces['data-sitekey'] = $this->profile_sitekey;
		is_null($this->widget_theme) || $grecaptchapieces['data-theme'] = $this->widget_theme;
		is_null($this->widget_type) || $grecaptchapieces['data-type'] = $this->widget_type;
		is_null($this->widget_size) || $grecaptchapieces['data-size'] = $this->widget_size;
		is_null($this->widget_tabindex) || $grecaptchapieces['data-tabindex'] = $this->widget_tabindex;
		is_null($this->widget_callback) || $grecaptchapieces['data-callback'] = $this->widget_callback;
		is_null($this->widget_expiredcallback) || $grecaptchapieces['data-expired-callback'] = $this->widget_expiredcallback;

		$return = '';
		$return .= '<script src="https://www.google.com/recaptcha/api.js';
		count($apijspieces)==0 || $return .= '?'.http_build_query($apijspieces);
		$return .= '" async defer></script>';
		$return .= '<div class="g-recaptcha" ';
		$return .= implode(' ', array_map(
			function ($k, $v) { return $k.'="'.$v.'"'; },
			array_keys($grecaptchapieces),
			array_values($grecaptchapieces)
		));
		$return .= '></div>';
		return $return;
	}
	public function verify($response, $ip=NULL) {
		$ip = ($ip) ?: $this->CI->input->ip_address();
		$postvars = array();
		$postvars['secret'] = $this->profile_secretkey;
		$postvars['response'] = $response;
		$postvars['remoteip'] = $ip;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::VERIFY_ENDPOINT);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Codeigniter-Recaptcha/0.1; +https://github.com/afelicioni/codeigniter-recaptcha)');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postvars));
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		$siteresponse = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($siteresponse);
		if ($response->success) {
			return true;
		} else {
			return false;
		}
	}
	private function errorConfigWarning($errorMessage) {
		show_error($errorMessage);
	}
	private function uuid() {
		$data = openssl_random_pseudo_bytes(16);
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80);
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}
}