<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	var $data = array();

  function __construct(){
    parent::__construct();		
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s")." GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");		
		$this->load->model('m_user');		
		if ($this->m_user->is_logged_in() === FALSE) { 
			$this->m_user->remove_pass();
			redirect('login/noaccess');
		} 
    else {
			$this->data['user'] = $this->session->userdata('user');
		}
  }

  public function cities(){
    $data['y']=$this->m_user->select('cities');
    $this->load->view('admin/new_cities',$data);
  }

	public function index(){
		$this->load->view('admin/v_admin_home');
	}
	public function home(){
		$this->load->view('admin/v_admin_home');
	}
  public function brands(){
    $data['h']=$this->m_user->select('vehicle_brands');
    $this->load->view('admin/new_brand',$data);
  }

  public function media(){
    $this->load->view('admin/add_media');
  }

	public function models(){
    $data['z']=$this->m_user->select('vehicals_type_brand_models'); 
    $data['controller']=$this; 
    $this->load->view('admin/new_models',$data);
	}
  
  public function get_brand_name_with_id($id){
    $condition=array('id'=>$id);
    $data['g']=$this->m_user->select_with_condition('vehicle_brands',$condition);
    $res=$data['g']->row();
    return $res->brand_name;    
  }
  
  public function get_model_name_with_id($id){
    $condition=array('id'=>$id);
    $data['g']=$this->m_user->select_with_condition('vehicals_type_brand_models',$condition);
    $res=$data['g']->row();
    return $res->vehical_model;    
  }
  
  public function get_variant_name_with_id($id){
    $condition=array('id'=>$id);
    $data['g']=$this->m_user->select_with_condition('vehicals_type_brand_model_varient',$condition);
    $res=$data['g']->row();
    return $res->varient_name;    
  }

  public function get_city_name_with_id($id){
    $condition=array('city_id'=>$id);
    $data['c']=$this->m_user->select_with_condition('cities',$condition);
    $res=$data['c']->row();
    return $res->city_name;    
  }

  public function variants(){		
    $data['x']=$this->m_user->select('vehicals_type_brand_model_varient'); 
    $data['features']=$this->m_user->select('features');
    $data['controller']=$this; 
		$this->load->view('admin/new_variant', $data);
	}

	public function vehicles(){
		$data['h']=$this->m_user->select('vehicle_brands');
		$data['z']=$this->m_user->select('vehicals_type_brand_models');
		$data['x']=$this->m_user->select('vehicals_type_brand_model_varient'); 
    $data['vehicle']=$this->m_user->select('vehicals_type_brand_model_varient_vehicle');
		$data['y']=$this->m_user->select('cities');
		$data['controller']=$this; 
		$this->load->view('admin/new_vehicle',$data);
	}

	public function features(){
    $data['features']=$this->m_user->select('features');
		$this->load->view('admin/new_feature',$data);
	}

	public function upload($filename){ 
    $config['upload_path']   = './img/'; 
    $config['allowed_types'] = 'jpg|png'; 
    $config['max_size']      = 20000; 
    $config['max_width']     = 3024; 
    $config['max_height']    = 2068;  
    $this->load->library('upload',$config);
    if ( ! $this->upload->do_upload($filename)) {
      $error = array('0' => $this->upload->display_errors(),'1'=>''); 
      $message=$error;
    }	
    else {          	
      $message=array('0' => 'File uploaded Success fully !','1'=>$this->upload->data());
    } 
    return $message;
  }

  public function  model_multy_image_upload(){
   	$data = array();   var_dump($_FILES);     
    $filesCount = count($_FILES['images']['name']);
    for($i = 0; $i < $filesCount;$i++){
      $_FILES['image']['name'] = $_FILES['images']['name'][$i];
      $_FILES['image']['type'] = $_FILES['images']['type'][$i];
      $_FILES['image']['tmp_name'] = $_FILES['images']['tmp_name'][$i];
      $_FILES['image']['error'] = $_FILES['images']['error'][$i];
      $_FILES['image']['size'] = $_FILES['images']['size'][$i];
      $uploadPath = './img/';
      $config['upload_path'] = './img/';
      $config['allowed_types'] = 'jpg|png';                
      $config['max_size']=20000;
      $config['max_width']=3024;
      $config['max_height']=2008;
      $this->load->library('upload',$config);                
      if($this->upload->do_upload('image')){
        $fileData = $this->upload->data();
        $data[$i]['file_name']= $fileData['file_name'];
      }
    }
    if(!empty($data)){
      $message=array('0' => 'File uploaded Success fully !','1'=>$data);
    }
    else{           
      $error = array('0' => $this->upload->display_errors(),'1'=>''); 
      $message=$error;
    }
    return $message;
  }

  public function  color_multy_image_upload(){
    $data = array();   
    // var_dump($_FILES);     
    $filesCount = count($_FILES['c_image']['name']);
    for($i = 0; $i < $filesCount;$i++){
      $_FILES['c_images']['name'] = $_FILES['c_image']['name'][$i];
      $_FILES['c_images']['type'] = $_FILES['c_image']['type'][$i];
      $_FILES['c_images']['tmp_name'] = $_FILES['c_image']['tmp_name'][$i];
      $_FILES['c_images']['error'] = $_FILES['c_image']['error'][$i];
      $_FILES['c_images']['size'] = $_FILES['c_image']['size'][$i];
      $uploadPath = './img/';
      $config['upload_path'] = './img/';
      $config['allowed_types'] = 'jpg|png';                
      $config['max_size']=20000;
      $config['max_width']=3024;
      $config['max_height']=2008;
      $this->load->library('upload',$config);                
      if($this->upload->do_upload('c_images')){
        $fileData = $this->upload->data();
        $data[$i]['file_name']= $fileData['file_name'];
      }
    }
    if(!empty($data)){
      $message=array('0' => 'File uploaded Success fully !','1'=>$data);
    }
    else{           
      $error = array('0' => $this->upload->display_errors(),'1'=>''); 
      $message=$error;
    }
    return $message;
  }

  public function  variant_multy_image_upload(){
   	$data = array();        
    $filesCount = count($_FILES['vimages']['name']);
    for($i = 0; $i < $filesCount;$i++){
      $_FILES['vimage']['name'] = $_FILES['vimages']['name'][$i];
      $_FILES['vimage']['type'] = $_FILES['vimages']['type'][$i];
      $_FILES['vimage']['tmp_name'] = $_FILES['vimages']['tmp_name'][$i];
      $_FILES['vimage']['error'] = $_FILES['vimages']['error'][$i];
      $_FILES['vimage']['size'] = $_FILES['vimages']['size'][$i];
      $uploadPath = './img/';
      $config['upload_path'] = './img/';
      $config['allowed_types'] = 'jpg|png';                
      $config['max_size']=20000;
      $config['max_width']=3024;
      $config['max_height']=2008;
      $this->load->library('upload',$config);                
      if($this->upload->do_upload('vimage')){
        $fileData = $this->upload->data();
        $data[$i]['file_name']= $fileData['file_name'];
      }
    }
    if(!empty($data)){
      $message=array('0' => 'File uploaded Success fully !','1'=>$data);
    }
    else{           
      $error = array('0' => $this->upload->display_errors(),'1'=>''); 
      $message=$error;
    }
    return $message;
  }


  public function  media_multy_image_upload(){
    $data = array(); 
    var_dump($_FILES);      
    $filesCount = count($_FILES['picture']['name']);
    for($i = 0; $i < $filesCount;$i++){
      $_FILES['g_image']['name'] = $_FILES['picture']['name'][$i];
      $_FILES['g_image']['type'] = $_FILES['picture']['type'][$i];
      $_FILES['g_image']['tmp_name'] = $_FILES['picture']['tmp_name'][$i];
      $_FILES['g_image']['error'] = $_FILES['picture']['error'][$i];
      $_FILES['g_image']['size'] = $_FILES['picture']['size'][$i];
      $uploadPath = './img/';
      $config['upload_path'] = './img/';
      $config['allowed_types'] = 'jpg|png';                
      $config['max_size']=20000;
      $config['max_width']=3024;
      $config['max_height']=2008;
      $this->load->library('upload',$config);                
      if($this->upload->do_upload('picture')){
        $fileData = $this->upload->data();
        $data[$i]['file_name']= $fileData['file_name'];
      }
    }
    if(!empty($data)){
      $message=array('0' => 'File uploaded Success fully !','1'=>$data);
    }
    else{           
      $error = array('0' => $this->upload->display_errors(),'1'=>''); 
      $message=$error;
    }
    return $message;
  }

  /***********************Fetch data with ajax *****************************/

  public function model_fetchdata_with_condition(){    
    $val=$_REQUEST['id'];
    $data= array('type =' => $val);
    $data['h']=$this->m_user->mdl_fetchdata_with_condition('vehicle_brands',$data);  
    echo "<option>---Select---</option>";
    foreach ($data['h'] as $key => $value){
      echo "<option value='".$value->id."'>".$value->brand_name."</option>";
    }
  }

  public function brand_fetchdata_with_condition(){    
    $val=$_REQUEST['id'];
    $data= array('vehicle_brand =' => $val);
    $data['h']=$this->m_user->mdl_fetchdata_with_condition('vehicals_type_brand_models',$data);  
    echo "<option>---Select---</option>";
    foreach ($data['h'] as $key => $value){
      echo "<option value='".$value->id."'>".$value->vehical_model."</option>";
    }
  }
  
  public function variant_fetchdata_with_condition(){    
    $val=$_REQUEST['id'];
    $data= array('model_id =' => $val);
    $data['h']=$this->m_user->mdl_fetchdata_with_condition('vehicals_type_brand_model_varient',$data);
    echo "<option>---Select---</option>";
    foreach ($data['h'] as $key => $value){
      echo "<option value='".$value->id."'>".$value->varient_name ."</option>";
    }
  }

  public function editbrands(){
    $id=$this->uri->segment(3);
    $condition=array('id'=>$id);
    $data['h']=$this->m_user->select_with_condition('vehicle_brands',$condition);
    $data['controller']=$this; 
    $this->load->view('admin/edit_brands',$data);
  }
  
  public function editcities(){
    $id=$this->uri->segment(3);
    $condition=array('city_id'=>$id);
    $data['allstates']=$this->m_user->select('cities');
    $data['y']=$this->m_user->select_with_condition('cities',$condition);
    $this->load->view('admin/edit_cities',$data);
  }

  public function editfeatures(){
    $id=$this->uri->segment(3);
    $condition=array('id'=>$id);
    $data['features']=$this->m_user->select_with_condition('features',$condition);
    $this->load->view('admin/edit_features',$data);
  }
  
  public function editmodels(){
    $id=$this->uri->segment(3);
    $condition=array('id'=>$id);
    $data['z']=$this->m_user->select_with_condition('vehicals_type_brand_models',$condition);
    $data['controller']=$this;
    $this->load->view('admin/edit_models',$data);
  }

  public function editvariants(){
    $id=$this->uri->segment(3);
    $condition=array('id'=>$id);
    $data['features']=$this->m_user->select('features');
    $data['x']=$this->m_user->select_with_condition('vehicals_type_brand_model_varient',$condition);
    $data['controller']=$this; 
    $this->load->view('admin/edit_variants',$data);
  }

  public function editvehicles(){
    $id=$this->uri->segment(3);
    $condition=array('id'=>$id);
    $data['y']=$this->m_user->select('cities');
    $data['vehicle']=$this->m_user->select_with_condition('vehicals_type_brand_model_varient_vehicle',$condition);
    $data['controller']=$this; 
    $this->load->view('admin/edit_vehicles',$data);
  }
}