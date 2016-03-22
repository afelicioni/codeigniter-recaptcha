<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('url');
	}
	public function index()
	{
		$this->load->library('recaptcha');
		?>
		<html>
		<body>
		<form method="post">
		<?php
		echo 'Please prove you are not a bot';
		echo $this->recaptcha->widget(array('hl'=>'fr','theme'=>'dark','size'=>'compact'));
		echo '<input type="submit"><br>';
		$grecaptcha = $this->input->post('g-recaptcha-response');
		if(!empty($grecaptcha)) {
			echo "<hr>checking solution...<br>";
			$verifica = $this->recaptcha->verify($this->input->post('g-recaptcha-response'));
			if ($verifica) {
				echo 'check passed!';
			}
		}
		?>
		</form>
		</body>
		</html>
		<?php
	}
}
