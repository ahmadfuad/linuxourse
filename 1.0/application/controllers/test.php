<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('application/controllers/base.php');//load base class
class test extends base { //class for public

	public function __construct()
	{
		parent::__construct();
		//only for member
		$this->load->library('user_agent');
		$this->load->model(array('m_test'));		
	}
	public function preview()
	{
		//is owner
		$idtest = $this->uri->segment(3);
		$iduser = $this->session->userdata['student_login']['id_user'];
		$mytest = $this->m_test->isMyTest($iduser,$idtest);
		if($mytest)//is true, go ahead
		{
			
		}else
		{
			echo 'is not your test, create your own test <a href="'.site_url('m/mytest').'">here</a>';
		}
	}
}