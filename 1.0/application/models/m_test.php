<?php

class m_test extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		//Do your magic here
	}
	//MY TEST
	public function myTest($iduser,$status)
	{
		switch ($status) {
			case 'open':
				$this->db->where('testClose >= CURTIME()');
				break;
			case 'clossed':
				$this->db->where('testClose < CURTIME()');
				break;
		}
		$this->db->where('id_user',$iduser);
		return $this->db->get('test');
	}
	
}