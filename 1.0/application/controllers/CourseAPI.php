<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('application/controllers/base.php');//load base class
class CourseAPI extends base { //class for public

	public function __construct()
	{
		parent::__construct();
		//only for member
		$this->load->library('user_agent');	
		$this->load->model('m_test');			
	}
	//get courselist
	public function getCourse()
	{
		$type = $this->uri->segment(3);
		$idStudent = $this->session->userdata['student_login']['id_user'];
		$results = array();
		switch ($type) {
				//ON PROGRESS || COMPLETED COURSE
			case 'onprogress' || 'completed':
			$userCourse = $this->m_course->courseByUser($idStudent);
				// print_r($userCourse);
			foreach($userCourse as $uc):
				if($uc['status']==$type):
					$listTotalnow = $this->m_course->countCourseStepByMateri($uc['id_materi'],$uc['id_level'],$uc['id_course']);
				$listTotalCourse = $this->m_course->countCourseByMateri($uc['id_materi']);
				$listRecentPercentage = number_format(($listTotalnow*100/$listTotalCourse),1);
				$id = base64_encode(base64_encode($uc['id_materi']));
				$id = str_replace('=', '', $id);
					//last course
				$today = date_create(date('Y-m-d'));
				$last = date_create(date('Y-m-d', strtotime($uc['lastdate'])));
				$diff=date_diff($last,$today);
				if($diff->y != 0){
					$log = $diff->y.' Years ago';
				}else if($diff->m != 0){
					$log = $diff->m.' Months ago';
				}else if($diff->d != 0){
					$log = $diff->d.' Days ago';
				}else{
					$log = 'today';
				}						
				//setup results
				$materi = array(
					'idmateri'=>$uc['id_materi'],
					'title'=>$uc['title'],
					'percentage'=>$listRecentPercentage,
					'log'=>$log
					);
				array_push($results,$materi);
				endif;
				endforeach;
				break;
				
				case 'mytest':

				break;
			}
			// print_r($results);
			echo json_encode($results);
		}
		//UNIQUE LIST CHECKER
		public function checkUniqueLink()
		{
			$link=$_GET['q'];
			$this->db->like('testUniqueLink',$link);
			$query = $this->db->get('test');
			if($query->num_rows()>0){echo 'true';}//is exist
			else{echo 'false';}//ready to use
		}
		//NEW TEST
		public function newTest()
		{
			$data = file_get_contents("php://input");
	        $data = json_decode($data,true);
	        //get id user
	        $iduser = $this->session->userdata['student_login']['id_user'];
	        $data['id_user']=$iduser;
	        $data['testUniqueLink'] = str_replace(' ','-',$data['testUniqueLink']);
	        $data['testCreated']= date('Y-m-d H:i:s');
	        $data['testUpdated']=date('Y-m-d H:i:s');
	        // print_r($data);
	        // insert to database
	        $this->db->insert('test',$data);
		}
		//UPDATE TEST
		public function updateTest()
		{
			$data = file_get_contents("php://input");
	        $data = json_decode($data,true);
	        $data = $data['test'];
	        $idtest = $data['idTest'];
	        $data['testUpdated'] = date('Y-m-d H:i:s');
	        unset($data['idTest']);
	        $this->db->where('idTest',$idtest);
	        return $this->db->update('test',$data);
		}
		//GET MY TEST
		public function getMyTest()
		{
			
			$results = array();
			$data = file_get_contents("php://input");
	        $data = json_decode($data);
			$status = $data->status;
			$iduser = $this->session->userdata['student_login']['id_user'];
			$test = $this->m_test->myTest($iduser,$status)->result_array();
			$json = json_encode($test);
			echo $json;
		}
		//TEST DETAIL
		public function detailTest()
		{
			
			$data = file_get_contents("php://input");
	        $data = json_decode($data);
	        $idtest = $data->idtest;
	        $result = $this->m_test->detailTest($idtest);
	        echo json_encode($result);
		}
		//STEP DETAIL
		public function detailStep()
		{
			$data = file_get_contents("php://input");
	        $data = json_decode($data);
	        $idtest = $data->idtest;
	        $step = $data->step;
	        $this->db->where('idTest',$idtest);
	        $this->db->where('testCaseStep',$step);
	        $result = $this->db->get('testCase')->row_array();
	        echo json_encode($result);
		}
		//NEW STEP ACTION
		public function newStepTest()
		{
			
			$data = file_get_contents("php://input");
	        $data = json_decode($data,true);
	        $idtest = $data['idtest'];
	        $step = $data['newstep'];
	        $step['idTest'] = $idtest;
	        $step['addTestCase'] = date('Y-m-d H:i:s');
	        $step['updatedTestCase'] = date('Y-m-d H:i:s');
	        return $this->db->insert('testCase',$step);
		}
		//TEST CASE LIST
		public function getCase()
		{
			
			$data = file_get_contents("php://input");
	        $data = json_decode($data);
	        $idtest = $data->idtest;
	        $this->db->where('idTest',$idtest);
	        $this->db->order_by('testCaseStep','ASC');
	        if(!empty($_GET['act'])):
	        	switch ($_GET['act']) {
	        		case 'latest':
	        			$laststep = $data->laststep;
	        			$this->db->where('testCaseStep >',$laststep);
	        			break;
	        		
	        		default:
	        			# code...
	        			break;
	        	}
	        $results = $this->db->get('testCase')->row_array();
	        else:
	        $results = $this->db->get('testCase')->result_array();
	        endif;
	        echo json_encode($results);
		}
		//UPDATE CASE
		public function updateCase()
		{
			$data = file_get_contents("php://input");
	        $data = json_decode($data,true);
	        $case = $data['case'];
	        $case['updatedTestCase'] = date('Y-m-d H:i:s');
	        //remove primary key
	        $this->db->where('idTestCase',$case['idTestCase']);
	        unset($case['idTestCase']);
	        return $this->db->update('testCase',$case);
		}
		//DELETE CASE
		public function deleteCase()
		{
			$data = file_get_contents("php://input");
	        $data = json_decode($data);
	        $idcase = $data->idcase;
	        $this->db->where('idTestCase',$idcase);
	        return $this->db->delete('testCase');
		}
		//CHECK TEST STEP BY ID TEST
		public function checkStep()
		{
			
			$data = file_get_contents("php://input");
	        $data = json_decode($data);
	        $idtest = $data->idtest;
	        $step = $data->step;
	        $this->db->where('idTest',$idtest);
	        $this->db->where('testCaseStep',$step);
	        $query = $this->db->get('testCase');
	        if($query->num_rows()>0){echo 'true';}//step is exist
	        else{echo 'false';}//step ready to use
		}

		/*****************************************/
		//REGEX
		/*****************************************/
		public function saveStep()
		{
			$data = file_get_contents("php://input");
	        $data = json_decode($data);
	        $commands = $data->commands;
	        $iduser = $this->session->userdata['student_login']['id_user'];
	        $idtest = $data->idtest;
	        $step = $data->step;
	        $iddotest = $iduser.'-'.$idtest;
	        //get step detail
	        $casedetail = $this->m_test->getCase($idtest,$step)->row_array();
	        $casecommand = trim($casedetail['command']);
	        $casecommand = explode(':',$casecommand);//case commands as array
	        $totalcasecommand = count($casecommand);//total case commands
	        $totalcorrectanswer = 0;
	        //get real commands
	        preg_match_all('#\$(.*):#Us', $commands,$purecommands,PREG_SET_ORDER);
	        //get total correct answer
	        foreach($purecommands as $pc):
	        	$command = trim(strip_tags($pc[1]));
	        	if(in_array($command,$casecommand)):
	        		$totalcorrectanswer = $totalcorrectanswer + 1;
	        	endif;
	        endforeach;
	        // echo $totalcorrectanswer;
	        //answer presentation
	        $presentationcorrect = ($totalcorrectanswer*100) / $totalcasecommand;
	        //database session modification
	        $databasesession = $this->m_test->getDatabaseSession($iduser,$idtest);
	        $resultdata = $databasesession['doTestResult'];
	        //update result data
	        $this->updateDoTestResult($iddotest,$resultdata,$step,$presentationcorrect,0);//[WORKED]
		}
		//update do test result data
		public function updateDoTestResult($iddotest,$resultdata,$step,$presentationcorrect,$totaltime)
		{
			$resultupdate = array();
			$resultdata = json_decode($resultdata,true);
			$resultdata[$step]['correct'] = $presentationcorrect;
			$resultdata[$step]['time'] = $totaltime;
			$resultupdatejson = json_encode($resultdata);
			//update database
			$this->db->where('idDotest',$iddotest);
			return $this->db->update('doTest',array('doTestResult'=>$resultupdatejson));
		}
	}