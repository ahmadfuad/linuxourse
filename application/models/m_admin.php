<?php
require_once('application/controllers/base.php');//load base class
class m_admin extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		//Do your magic here
	}
	public function can_login($username,$password){
		$this->db->where('username',$username);
		$this->db->where('password',$password);
		$query = $this->db->get('user_manage');
        //struktur kendali untuk cek apakah data ada atau tidak
		if($query->num_rows() > 0){
            //memasukkan hasil eksekusi query kedalam row_array
			return $query->row_array();
		}
	}

	////////////////
	// MANAGE COURSE
	////////////////
	public function showAllCourse($limit,$offset){
		$sql = "SELECT course.id_course AS 'id_course',course.title AS 'title', course.step AS 'step',course.description AS 'description',
		level.level AS 'level', materi.title AS 'materi',course.status AS 'status'
		FROM course
		INNER JOIN level ON level.id_level = course.id_level
		INNER JOIN materi ON materi.id_materi = level.id_materi
		ORDER BY materi.id_materi ASC,course.step ASC 
		LIMIT ".$offset.",".$limit;
		$query = $this->db->query($sql);
		if($query->num_rows()>0){return $query->result_array();}else{return array();}
	}
	//count show all course
	public function countShowAllCourse(){
		return $this->db->count_all('course'); 
	}
	//count course by materi
	public function countCourseByMateri($idmateri){
		$sql = "SELECT id_course FROM course 
		INNER JOIN level ON level.id_level = course.id_level 
		INNER JOIN materi ON level.id_materi = materi.id_materi WHERE materi.id_materi=?";
		$query = $this->db->query($sql,$idmateri);
		return $query->num_rows();
	}
	//show all course by materi
	public function showAllCourseByMateri($limit,$offset,$idmateri){
		$sql = "SELECT course.id_course AS 'id_course',course.title AS 'title', course.step AS 'step',course.description AS 'description',
		level.level AS 'level', materi.title AS 'materi',course.status AS 'status'
		FROM course
		INNER JOIN level ON level.id_level = course.id_level
		INNER JOIN materi ON materi.id_materi = level.id_materi
		WHERE materi.id_materi = ".$idmateri."
		ORDER BY materi.id_materi ASC,course.step ASC 
		LIMIT ".$offset.",".$limit;
		$query = $this->db->query($sql);
		if($query->num_rows()>0){return $query->result_array();}else{return array();}
	}
	//////////////
	// MANAGE LEVEL
	/////////////
	public function showAllLevel($limit,$offset){
		$sql = "SELECT level.id_level AS 'id_level',level.title AS 'title',level.level AS 'level',materi.title AS 'materi',level.description AS 'description'
		FROM level INNER JOIN materi ON level.id_materi = materi.id_materi LIMIT $offset,$limit";
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result_array();
		}else{
			return array();
		}
	}
	public function countShowAllLevel(){
		return $this->db->count_all_results('level');
	}
	//get detail level
	public function detailLevel($idlevel){
		$this->db->where('id_level',$idlevel);
		$query= $this->db->get('level');
		return $query->row_array();
	}
	//show level by materi
	public function showLevelByMateri($idmateri){
		$sql = "SELECT level.title AS 'title', level.level AS 'level', level.id_level AS 'id_level', level.description AS 'description',
		materi.title AS 'materi' FROM level 
		INNER JOIN materi ON materi.id_materi = level.id_materi
		WHERE materi.id_materi = ?";
		$query = $this->db->query($sql,$idmateri);
		if($query->num_rows()>0){
			return $query->result_array();
		}else{
			return array();
		}
	}
	//count level by materi
	public function countLevelByMateri($idmateri){
		$this->db->where('id_materi',$idmateri);
		$this->db->from('level');
		return $this->db->count_all_results();
	}
	//total course by level
	public function countCourseByLevel($idlevel){
		$this->db->where('id_level',$idlevel);
		$query = $this->db->get('course');
		return $query->num_rows();
	}
	/////////////
	// MANAGE MATERI
	/////////////
	//get materi by id materi
	public function getMateriByIdMateri($idmateri){
		$this->db->where('id_materi',$idmateri);
		$query = $this->db->get('materi');
	return $query->row_array();//get result row array
}
public function showAllMateri($limit,$offset){
	$this->db->limit($limit,$offset);
	$query = $this->db->get('materi');
	return $query->result_array();
}
	//count materi
public function countShowAllMateri(){return $this->db->count_all('materi');}
	/////////////
	// MANAGE STUDENTS
	/////////////
	//count all students
public function countAllStudents(){$this->db->where('level','student');return $this->db->count_all_results('user');}
	//count students by materi
public function countStudentByMateri($idmateri,$filter){
	switch ($filter) {
		case 'incomplete':
		$sql = "SELECT id_user_course FROM user_course WHERE id_materi = ? AND status = 'incomplete'";
		break;
		case 'completed':
		$sql = "SELECT id_user_course FROM user_course WHERE id_materi = ? AND status = 'completed'";
		break;			
		default:
		$sql = "SELECT id_user_course FROM user_course WHERE id_materi = ?  ";
		break;
	}
	$query = $this->db->query($sql,$idmateri);
	return $query->num_rows();
}
	//count students by course
public function countStudentByCourse($idcourse,$filter){
	switch ($filter) {
		case 'incomplete':
		$sql = "SELECT id_user_course FROM user_course WHERE id_course = ? AND status = 'incomplete'";
		break;
		case 'completed':
		$sql = "SELECT id_user_course FROM user_course WHERE id_course = ? AND status = 'completed'";
		break;			
		default:
		$sql = "SELECT id_user_course FROM user_course WHERE id_course = ?  ";
		break;
	}
	$query = $this->db->query($sql,$idcourse);
	return $query->num_rows();
}
	/////////////
	// MANAGE DISCUSSIONS
	/////////////
	//count all discussion
public function countAllDiscussion(){return $this->db->count_all('discussion');}

	/////////////
	// MANAGE COMMENTS
	/////////////
	public function countAllComment(){return $this->db->count_all('discussion_comment');}//count all comments

	/////////////
	// MANAGE DIRECTORIES
	/////////////
	public function countAllDirectories(){return $this->db->count_all('available_dir');}

	/////////////
	// MANAGE FILES
	/////////////
	public function countAllFiles(){return $this->db->count_all('ls_dir');}

	/////////////
	// MANAGE ADMIN
	/////////////
	//count all admin
	public function countAllAdmin(){$this->db->where('level','admin');return $this->db->count_all_results('user');}

	/////////////
	// MANAGE MODERATOR
	/////////////
	public function countAllModerator(){$this->db->where('level','moderator');return $this->db->count_all_results('user');}
}