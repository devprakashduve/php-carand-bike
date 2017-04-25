<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include('admin.php');
class Login extends CI_Controller {
	// data for view, we do this so we can set value in __construct
	// and pass to other functions if needed
	var $data = array(); 	
    function __construct()
    {
        // Call the Controller constructor
        parent::__construct();
		$this->load->model('m_user');
	    $this->load->library('form_validation');
	    $this->load->helper(array('form', 'url'));
       
    }

	// route /login
	public function index()
	{
		if ($this->m_user->is_logged_in()) { redirect('admin'); }
	
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		if ($this->form_validation->run()) {
			$username = $this->input->post('email');
			$password = $this->input->post('password');
		
			if ($user = $this->m_user->get_by_username($username)) {
				if ($this->m_user->check_password( $password, $user['password'] )) {
					$this->m_user->allow_pass( $user );
					redirect('admin');
				} else { $this->data['login_error'] = 'Invalid username or password'; }
			} else { $this->data['login_error'] = 'Username not found'; }
		}
		$this->load->view('login/v_login', $this->data);
	}	
	// route /register -- check settings in /application/config/routes.php
	public function register(){
		if ($this->m_user->is_logged_in()) { redirect('admin');}
		$this->form_validation->set_rules('fullname', 'Full Name', 'required');
		/*$this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');*/
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('cpassword', 'Password', 'required|matches[password]');	
		if ($this->form_validation->run()){
			$user = array(
				'username' => $this->input->post('fullname'),
				'email' => $this->input->post('email'),				
				'password' => $this->m_user->hash_password( $this->input->post('password') )
			);
			if ( $this->m_user->save($user) ) {
				$this->data['register_success'] = 'Registration successful. <a href="'.site_url('login').'">Click here to login</a>.';
			} else { $this->data['register_error'] = 'Saving data failed. Contact administrator.'; }
		}
		$this->load->view('login/v_register', $this->data);
	}	
	// route /logout -- check settings in /application/config/routes.php
	public function logout() {
		$this->m_user->remove_pass();
		$this->data['login_success'] = 'You have been logged out. Thank you.';
		$this->load->view('login/v_login', $this->data);
	}	
	// noaccess to show no access message
	public function noaccess() {
		$this->data['login_error'] = 'You do not have access or your login has expired.';
		$this->load->view('login/v_login', $this->data);
	}

public function insertcities(){
		if ($this->m_user->is_logged_in()){
		$this->form_validation->set_rules('city_state','State','trim|required');
		$this->form_validation->set_rules('city_name','City','trim|required');
				
		
		if($this->form_validation->run()){
			$inputdata=array(
				'city_name'=>ucwords($this->input->post('city_name')),
				'city_state'=>ucwords($this->input->post('city_state')));

			$inputdata = $this->security->xss_clean($inputdata);
			if($this->m_user->savedata('cities',$inputdata))
			{
			$this->session->set_flashdata("message","<div class='alert alert-success col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>data successfuly saved</strong></div>");
			} 
			else{ 
			$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong> Saving failed try again with valid data</strong></div>");
			    }
	       }else{$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>Invalid Data Please fill the valid data</strong></div>");}
	      redirect('admin/cities');
	 }
	}
	
	/********************Insert brands function**************************/
	public function insertbrands(){		
		$admin=new admin();
		$s_image1 = '';
		$s_image2 = '';
		$s_image3 = '';
		$b_logo = '';

		if ($this->m_user->is_logged_in()){
		$this->form_validation->set_rules('vehicaltype','Type','trim|required');
		$this->form_validation->set_rules('brandname','Brand Name','trim|required');
		$this->form_validation->set_rules('s_description1','Slider Description 1','trim|required');
		$this->form_validation->set_rules('s_price1','Slider Price 1','trim|required');
		$this->form_validation->set_rules('s_description2','Slider Description 2','trim|required');
		$this->form_validation->set_rules('s_price2','Slider Price 2','trim|required');
		$this->form_validation->set_rules('s_description3','Slider Description 3','trim|required');
		$this->form_validation->set_rules('s_price3','Slider Price 3','trim|required');
		$this->form_validation->set_rules('heading1','Heading 1','trim|required');
		$this->form_validation->set_rules('description1','Description 1','trim|required');
		$this->form_validation->set_rules('heading2','Heading 2','trim|required');
		$this->form_validation->set_rules('description2','Description 2','trim|required');
		$this->form_validation->set_rules('heading3','Heading 3','trim|required');
		$this->form_validation->set_rules('description3','Description 3','trim|required');
		$this->form_validation->set_rules('heading4','Heading 4','trim|required');
		$this->form_validation->set_rules('description4','Description 4','trim|required');
		$this->form_validation->set_rules('heading5','Heading 5','trim|required');
		$this->form_validation->set_rules('description5','Description 5','trim|required');
		$this->form_validation->set_rules('heading6','Heading 6','trim|required');
		$this->form_validation->set_rules('description6','Description 6','trim|required');		
		
		if($this->form_validation->run()){

			$fileupload=$admin->upload('b_logo');
			$b_logo=$fileupload['1']['file_name'];

			$fileupload1=$admin->upload('s_image1');
			$s_image1=$fileupload1['1']['file_name'];

			$fileupload2=$admin->upload('s_image2');
			$s_image2=$fileupload2['1']['file_name'];

			$fileupload3=$admin->upload('s_image3');
			$s_image3=$fileupload3['1']['file_name'];

			$inputdata=array(
				'type'=>ucwords($this->input->post('vehicaltype')),
				'brand_name'=>ucwords($this->input->post('brandname')),
				'b_logo' => $b_logo,

				's_image1' => $s_image1,
				's_description1'=>$this->input->post('s_description1'),
				's_price1'=>$this->input->post('s_price1'),
				's_image2' => $s_image2,
				's_description2'=>$this->input->post('s_description2'),
				's_price2'=>$this->input->post('s_price2'),
				's_image3' => $s_image3,
				's_description3'=>$this->input->post('s_description3'),
				's_price3'=>$this->input->post('s_price3'),

				'heading1'=>$this->input->post('heading1'),
				'description1'=>$this->input->post('description1'),
				'heading2'=>$this->input->post('heading2'),
				'description2'=>$this->input->post('description2'),
				'heading3'=>$this->input->post('heading3'),
				'description3'=>$this->input->post('description3'),
				'heading4'=>$this->input->post('heading4'),
				'description4'=>$this->input->post('description4'),
				'heading5'=>$this->input->post('heading5'),
				'description5'=>$this->input->post('description5'),
				'heading6'=>$this->input->post('heading6'),
				'description6'=>$this->input->post('description6'));

			$inputdata = $this->security->xss_clean($inputdata);
			if($this->m_user->savedata('vehicle_brands',$inputdata))
			{
			$this->session->set_flashdata("message","<div class='alert alert-success col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>data successfuly saved</strong><br>".$fileupload[0]."</div>");
			} 
			else{ 
			$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong> Saving failed try again with valid data</strong></div>");
			    }
	       }else{$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>Invalid Data Please fill the valid data</strong></div>");}
	      redirect('admin/brands');
	       }
	}
	
/********************Insert models function**************************/
public function insertmodels(){	
$admin=new admin();
$img = '';
$c_img = '';
if ($this->m_user->is_logged_in()){
        $this->form_validation->set_rules('vehicaltype','Type','trim|required');
		$this->form_validation->set_rules('brandname','Brand Name','trim|required');
		$this->form_validation->set_rules('vehical_model','Model Name','trim|required');
		$this->form_validation->set_rules('model_type','Model Type','trim|required');
		$this->form_validation->set_rules('preferences','Preferences','trim|required');
		$this->form_validation->set_rules('vehicalmodel','Model Name','trim|required');
		$this->form_validation->set_rules('overview','Overview','trim|required');
		$this->form_validation->set_rules('m_video','Video','trim|required');

if($this->form_validation->run()){

$fileupload=$admin->upload('f_image');
$f_image=$fileupload['1']['file_name'];

$fileupload=$admin->model_multy_image_upload();
$name=count($fileupload[1]);

for ($i=0; $i <$name; $i++){ 
$img.=$fileupload[1][$i]['file_name'].',';
}

$c_title = implode($this->input->post('c_title'),',');
$c_code = implode($this->input->post('c_code'),',');

$fileupload=$admin->color_multy_image_upload();
$name=count($fileupload[1]);

for ($i=0; $i <$name; $i++){ 
$c_img.=$fileupload[1][$i]['file_name'].',';
}

$inputdata=array(
	'vehicle_type'=>$this->input->post('vehicaltype'),
	'vehicle_brand'=>$this->input->post('brandname'),
	'vehical_model'=>ucwords($this->input->post('vehicalmodel')),
	'model_type'=>ucwords($this->input->post('model_type')),
	'preferences'=>$this->input->post('preferences'),
	'c_title' => $c_title,
	'c_code' => $c_code,
	'c_image' =>$c_img,
	'overview'=>$this->input->post('overview'),
	'f_image' => $f_image,
    'image'=>$img,
    'm_video' =>$this->input->post('m_video') 
);

$inputdata = $this->security->xss_clean($inputdata);
if($this->m_user->savedata('vehicals_type_brand_models',$inputdata))
{
$this->session->set_flashdata("message","<div class='alert alert-success col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>data successfuly saved</strong><br>".$fileupload[0]."</div>");
}
else{ 
$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong> Saving failed try again with valid data</strong></div>");
   }
      }else{$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>Invalid Data Please fill the valid data</strong></div>");}
    redirect('admin/models');
  }
}

	public function insertmedia(){

		$admin=new admin();
		$img = '';

		if ($this->m_user->is_logged_in()){
		$this->form_validation->set_rules('vehicaltype','Type','trim|required');
		$this->form_validation->set_rules('brandname','Brand Name','trim|required');
		$this->form_validation->set_rules('vehicalmodel','Model Name','trim|required');

		if($this->form_validation->run()){

			$fileupload=$admin->upload('f_image');
			$f_image=$fileupload['1']['file_name'];

			$fileupload=$admin->media_multy_image_upload();
			$name=count($fileupload[1]);

			for ($i=0; $i <$name; $i++){ 
				$img.=$fileupload[1][$i]['file_name'].',';
				}

			$inputdata=array(
				'type_id'=>$this->input->post('vehicaltype'),
				'brand_id'=>$this->input->post('brandname'),
				'model_id'=>$this->input->post('vehicalmodel'),
				'f_image' => $f_image,
    			'g_images'=>$img,
				);

			$inputdata = $this->security->xss_clean($inputdata);
			if($this->m_user->savedata('media',$inputdata))
			{
			$this->session->set_flashdata("message","<div class='alert alert-success col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>data successfuly saved</strong><br>".$fileupload[0]."</div>");
			} 
			else{ 
			$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong> Saving failed try again with valid data</strong></div>");
			    }
	       }else{$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>Invalid Data Please fill the valid data</strong></div>");}
	      //redirect('admin/media');

		}
	}
	

	public function insertvariants(){	
	$admin=new admin();
	$img = '';
		if ($this->m_user->is_logged_in()){
		$this->form_validation->set_rules('vehicaltype','Type','trim|required');
		$this->form_validation->set_rules('brandname','Brand Name','trim|required');
		$this->form_validation->set_rules('vehicalmodel','Model Name','trim|required');
		$this->form_validation->set_rules('varianttype','Variant Type','required');
		$this->form_validation->set_rules('fuel_type','Fuel Type','required');
		$this->form_validation->set_rules('enginetype','Engine Type','required');
		$this->form_validation->set_rules('displacement','Displacement','required');
		$this->form_validation->set_rules('cylinder','No Of Cylinder','required');
		$this->form_validation->set_rules('power','Power','required');
		$this->form_validation->set_rules('torque','Torque','required');
		$this->form_validation->set_rules('drive_train','Drive Train','required');
		$this->form_validation->set_rules('length','Length','required');
		$this->form_validation->set_rules('width','Width','required');
		$this->form_validation->set_rules('height','Height','required');
		$this->form_validation->set_rules('wheelbase','Wheelbase','required');
		$this->form_validation->set_rules('ground_clearance','Ground Clearance','required');
		$this->form_validation->set_rules('boot_space','Boot Space','required');
		$this->form_validation->set_rules('kerb_weight','Kerb Weight','required');
		$this->form_validation->set_rules('gross_weight','Gross Weight','required');
		$this->form_validation->set_rules('front_track','Front Track','required');
		$this->form_validation->set_rules('rear_track','Rear Track','required');
		$this->form_validation->set_rules('min_turning_radius','Minimum Turning Radius','required');
		$this->form_validation->set_rules('doors','No. Of Doors','required');
		$this->form_validation->set_rules('seating','Seating Capacity','required');
		$this->form_validation->set_rules('gears','No. of gears','required');
		$this->form_validation->set_rules('clutch','Clutch Type','required');
		$this->form_validation->set_rules('front_break','Front Brake Type','required');
		$this->form_validation->set_rules('rear_break','Rear Brake Type','required');
		$this->form_validation->set_rules('mileage_city','Mileage City','required');
		$this->form_validation->set_rules('mileage_highway','Mileage Highway','required');
		$this->form_validation->set_rules('mileage','Mileage','required');
		$this->form_validation->set_rules('fuel_tank_capacity','Fuel Tank Capacity','required');
		$this->form_validation->set_rules('wheel_type','Wheel Type','required');
		$this->form_validation->set_rules('tyre_type','Tyre Type','required');
		$this->form_validation->set_rules('front_tyre_size','Front Tyre Size','required');
		$this->form_validation->set_rules('rear_tyre_size','Rear Tyre Size','required');
		$this->form_validation->set_rules('performance','Performance 0 To 100 Kmph','required');
		$this->form_validation->set_rules('max_speed','Max Speed','required');
		$this->form_validation->set_rules('f_suspension','Front Suspension','required');
		$this->form_validation->set_rules('r_suspension','Rear Suspension','required');
		$this->form_validation->set_rules('power_steering','Power Steering','required');
		$this->form_validation->set_rules('steering_type','Steering Type','required');
		$this->form_validation->set_rules('adj_power_steering','Adjustable Power Steering','required');
		$this->form_validation->set_rules('air_conditioner','Air Conditioner','required');
		$this->form_validation->set_rules('steering_adjustment','Steering Adjustment','required');
		$this->form_validation->set_rules('upholstery','Upholstery','required');
		$this->form_validation->set_rules('light_type','Light Type','required');
		$this->form_validation->set_rules('features','Features','required');

				//echo "string";
		
		if($this->form_validation->run()){



				/*$fileupload=$admin->upload('vimage');
				$img=$fileupload['1']['file_name'];*/

				$fileupload=$admin->upload('vimage');
				$img=$fileupload['1']['file_name'];

				$features = implode($this->input->post('features'),',');

				/*$fileupload=$admin->variant_multy_image_upload();
				$name=count($fileupload[1]);

				for ($i=0; $i < $name; $i++){ 
				$img.=$fileupload[1][$i]['file_name'].',';}*/

			$inputdata=array(
				'type_id'=>$this->input->post('vehicaltype'),
				'brand_id'=>$this->input->post('brandname'),
				'model_id'=>$this->input->post('vehicalmodel'),
				'varient_name'=>ucwords($this->input->post('varianttype')),
				'fuel_type'=>$this->input->post('fuel_type'),
				'vimage'=>$img,
				'enginetype'=>$this->input->post('enginetype'),
				'displacement'=>$this->input->post('displacement'),
				'cylinder'=>$this->input->post('cylinder'),
				'power'=>$this->input->post('power'),
				'torque'=>$this->input->post('torque'),
				'drive_train'=>$this->input->post('drive_train'),
				'gears'=>$this->input->post('gears'),
				'clutch'=>$this->input->post('clutch'),
				'front_break'=>$this->input->post('front_break'),
				'rear_break'=>$this->input->post('rear_break'),
				'mileage_city'=>$this->input->post('mileage_city'),
				'mileage_highway'=>$this->input->post('mileage_highway'),
				'mileage'=>$this->input->post('mileage'),
				'fuel_tank_capacity'=>$this->input->post('fuel_tank_capacity'),
				'performance'=>$this->input->post('performance'),
				'max_speed'=>$this->input->post('max_speed'),				
				'length'=>$this->input->post('length'),
				'width'=>$this->input->post('width'),
				'height'=>$this->input->post('height'),
				'wheelbase'=>$this->input->post('wheelbase'),
				'ground_clearance'=>$this->input->post('ground_clearance'),
				'boot_space'=>$this->input->post('boot_space'),
				'kerb_weight'=>$this->input->post('kerb_weight'),
				'gross_weight'=>$this->input->post('gross_weight'),
				'front_track'=>$this->input->post('front_track'),
				'rear_track'=>$this->input->post('rear_track'),
				'min_turning_radius'=>$this->input->post('min_turning_radius'),
				'doors'=>$this->input->post('doors'),
				'seating'=>$this->input->post('seating'),				
				'wheel_type'=>$this->input->post('wheel_type'),
				'tyre_type'=>$this->input->post('tyre_type'),
				'front_tyre_size'=>$this->input->post('front_tyre_size'),
				'rear_tyre_size'=>$this->input->post('rear_tyre_size'),				
				'f_suspension'=>$this->input->post('f_suspension'),
				'r_suspension'=>$this->input->post('r_suspension'),
				'power_steering'=>$this->input->post('power_steering'),
				'steering_type'=>$this->input->post('steering_type'),
				'adj_power_steering'=>$this->input->post('adj_power_steering'),
				'air_conditioner'=>$this->input->post('air_conditioner'),
				'steering_adjustment'=>$this->input->post('steering_adjustment'),
				'upholstery'=>$this->input->post('upholstery'),
				'light_type'=>$this->input->post('light_type'),
				'features'=>$features
				);
				
			$inputdata = $this->security->xss_clean($inputdata);
			if($this->m_user->savedata('vehicals_type_brand_model_varient',$inputdata))
			{
			$this->session->set_flashdata("message","<div class='alert alert-success col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>data successfuly saved</strong><br>".$fileupload[0]."</div>");
			} 
			else{ 
			$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong> Saving failed try again with valid data</strong></div>");
			    }
	       }else{$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>Invalid Data Please fill the valid data</strong></div>");}
	      redirect('admin/variants');
		
		
		
	 }
	}

	public function insertvehicle(){		
		if ($this->m_user->is_logged_in()){
		$this->form_validation->set_rules('vehicaltype','Type','trim|required');
		$this->form_validation->set_rules('brandname','Brand Name','trim|required');
		$this->form_validation->set_rules('vehicalmodel','Model Name','trim|required');
		$this->form_validation->set_rules('varianttype','Variant Type','required');
		$this->form_validation->set_rules('city','City','required');
		$this->form_validation->set_rules('sr_price','Showroom Price','required');
		$this->form_validation->set_rules('ins_price','Insurance Price','required');
		$this->form_validation->set_rules('rto_price','RTO Price','required');
		$this->form_validation->set_rules('gd_link','Get Deal Link','required');	
		$this->form_validation->set_rules('vd_link','View Details Link','required');
				
		
		if($this->form_validation->run()){

				$city = implode($this->input->post('city'),',');
				$sr_price = implode($this->input->post('sr_price'),',');
				$ins_price = implode($this->input->post('ins_price'),',');
				$rto_price = implode($this->input->post('rto_price'),',');
				
				//echo "string";

			$inputdata=array(
				'type_id'=>$this->input->post('vehicaltype'),
				'brand_id'=>$this->input->post('brandname'),
				'model_id'=>$this->input->post('vehicalmodel'),
				'varient_id'=>$this->input->post('varianttype'),
				'city'=>$city,
				'sr_price'=>$sr_price,
				'rto_price'=>$rto_price,
				'ins_price'=>$ins_price,
				'gd_link'=>$this->input->post('gd_link'),
				'vd_link'=>$this->input->post('vd_link')
				);



			$inputdata = $this->security->xss_clean($inputdata);
			if($this->m_user->savedata('vehicals_type_brand_model_varient_vehicle',$inputdata))
			{
			$this->session->set_flashdata("message","<div class='alert alert-success col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>data successfuly saved</strong></div>");
			} 
			else{ 
			$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong> Saving failed try again with valid data</strong></div>");
			    }
	       }else{$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>Invalid Data Please fill the valid data</strong></div>");}
	     redirect('admin/vehicles');
		
		
		
	 }
	}

	public function insertfeature(){
		if ($this->m_user->is_logged_in()){
			$this->form_validation->set_rules('vehicaltype','Type','trim|required');
			$this->form_validation->set_rules('feature_name','Feature','trim|required');
				
		
		if($this->form_validation->run()){
			$inputdata=array(
				'feature_name'=>ucwords($this->input->post('feature_name')),
				'type_id'=>$this->input->post('vehicaltype'));

			$inputdata = $this->security->xss_clean($inputdata);
			if($this->m_user->savedata('features',$inputdata))
			{
			$this->session->set_flashdata("message","<div class='alert alert-success col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>data successfuly saved</strong></div>");
			} 
			else{ 
			$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong> Saving failed try again with valid data</strong></div>");
			    }
	       }else{$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>Invalid Data Please fill the valid data</strong></div>");}
	      redirect('admin/features');
	 }
	}

	public function editbrands(){		
		$admin=new admin();
		$s_image1 = '';
		$s_image2 = '';
		$s_image3 = '';
		$b_logo = '';

		if ($this->m_user->is_logged_in()){
		$this->form_validation->set_rules('vehicaltype','Type','trim|required');
		$this->form_validation->set_rules('brandname','Brand Name','trim|required');
		$this->form_validation->set_rules('s_description1','Slider Description 1','trim|required');
		$this->form_validation->set_rules('s_price1','Slider Price 1','trim|required');
		$this->form_validation->set_rules('s_description2','Slider Description 2','trim|required');
		$this->form_validation->set_rules('s_price2','Slider Price 2','trim|required');
		$this->form_validation->set_rules('s_description3','Slider Description 3','trim|required');
		$this->form_validation->set_rules('s_price3','Slider Price 3','trim|required');
		$this->form_validation->set_rules('heading1','Heading 1','trim|required');
		$this->form_validation->set_rules('description1','Description 1','trim|required');
		$this->form_validation->set_rules('heading2','Heading 2','trim|required');
		$this->form_validation->set_rules('description2','Description 2','trim|required');
		$this->form_validation->set_rules('heading3','Heading 3','trim|required');
		$this->form_validation->set_rules('description3','Description 3','trim|required');
		$this->form_validation->set_rules('heading4','Heading 4','trim|required');
		$this->form_validation->set_rules('description4','Description 4','trim|required');
		$this->form_validation->set_rules('heading5','Heading 5','trim|required');
		$this->form_validation->set_rules('description5','Description 5','trim|required');
		$this->form_validation->set_rules('heading6','Heading 6','trim|required');
		$this->form_validation->set_rules('description6','Description 6','trim|required');		
		
		if($this->form_validation->run()){

if (isset($_FILES['b_logo']) && !empty($_FILES['b_logo']['name'])){	
  $fileupload=$admin->upload('b_logo');
  $b_logo=$fileupload['1']['file_name'];

}
else{
$b_logo=$this->input->post('b_logo');
}

if (isset($_FILES['s_image1']) && !empty($_FILES['s_image1']['name'])){	
  $fileupload1=$admin->upload('s_image1');
  $s_image1=$fileupload1['1']['file_name'];

}
else{
$s_image1=$this->input->post('img1');
}
if (isset($_FILES['s_image2']) && !empty($_FILES['s_image2']['name'])){	
$fileupload2=$admin->upload('s_image2');
$s_image2=$fileupload2['1']['file_name'];
}
else{
$s_image2=$this->input->post('img2');
}
if (isset($_FILES['s_image3']) && !empty($_FILES['s_image3']['name']))
{
   $fileupload3=$admin->upload('s_image3');
$s_image3=$fileupload3['1']['file_name'];
}
else{	
$s_image3=$this->input->post('img3');
}

			$id=$this->input->post('id');
			
			echo $this->input->post('s_image1');
			echo $s_image1;
			echo $s_image2;
			echo $s_image3;
			

			$inputdata=array(
			    'id'=>$id,
				'type'=>ucwords($this->input->post('vehicaltype')),
				'brand_name'=>ucwords($this->input->post('brandname')),
				'b_logo' => $b_logo,

				's_image1' => $s_image1,
				's_description1'=>$this->input->post('s_description1'),
				's_price1'=>$this->input->post('s_price1'),
				's_image2' => $s_image2,
				's_description2'=>$this->input->post('s_description2'),
				's_price2'=>$this->input->post('s_price2'),
				's_image3' => $s_image3,
				's_description3'=>$this->input->post('s_description3'),
				's_price3'=>$this->input->post('s_price3'),

				'heading1'=>$this->input->post('heading1'),
				'description1'=>$this->input->post('description1'),
				'heading2'=>$this->input->post('heading2'),
				'description2'=>$this->input->post('description2'),
				'heading3'=>$this->input->post('heading3'),
				'description3'=>$this->input->post('description3'),
				'heading4'=>$this->input->post('heading4'),
				'description4'=>$this->input->post('description4'),
				'heading5'=>$this->input->post('heading5'),
				'description5'=>$this->input->post('description5'),
				'heading6'=>$this->input->post('heading6'),
				'description6'=>$this->input->post('description6'));

			$inputdata = $this->security->xss_clean($inputdata);
			var_dump($inputdata); 	
			if($this->m_user->updatedata('vehicle_brands',$inputdata))
			{
			$this->session->set_flashdata("message","<div class='alert alert-success col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>data successfuly saved</strong><br>".$fileupload[0]."</div>");
			} 
			else{ 
			$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong> Saving failed try again with valid data</strong></div>");
			    }
	       }else{
	       	$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>Invalid Data Please fill the valid data</strong></div>");
	   			}
	      redirect('admin/editbrands/'.$id.'/'.$data);
	       }
	}


	public function editmodels(){
		$admin=new admin();
		$images1 = '';
		$f_image1 = '';
		$c_image1 = '';

		if ($this->m_user->is_logged_in()){
        	$this->form_validation->set_rules('vehicaltype','Type','trim|required');
			$this->form_validation->set_rules('brandname','Brand Name','trim|required');
			$this->form_validation->set_rules('vehical_model','Model Name','trim|required');
			$this->form_validation->set_rules('model_type','Model Type','trim|required');
			$this->form_validation->set_rules('preferences','Preferences','trim|required');
			$this->form_validation->set_rules('vehicalmodel','Model Name','trim|required');
			$this->form_validation->set_rules('overview','Overview','trim|required');
			$this->form_validation->set_rules('m_video','Video','trim|required');

		if($this->form_validation->run()){
		
			if (isset($_FILES['f_image']) && !empty($_FILES['f_image']['name'])){	
  				$fileupload=$admin->upload('f_image');
  				$f_image=$fileupload['1']['file_name'];
  			}
		else{
			$f_image=$this->input->post('f_image1');
		}

		if (isset($_FILES['images']) && !empty($_FILES['images']['name'])){	
  			$fileupload=$admin->model_multy_image_upload();
			$name=count($fileupload[1]);

				for ($i=0; $i <$name; $i++){ 
					$img.=$fileupload[1][$i]['file_name'].',';
				}

		}
		else{
			$img=$this->input->post('images1');
		}
		

		$c_title = implode($this->input->post('c_title'),',');
		$c_code = implode($this->input->post('c_code'),',');

		if (isset($_FILES['c_image']) && !empty($_FILES['c_image']['name'])){	
  			$fileupload=$admin->color_multy_image_upload();
			$name=count($fileupload[1]);

				for ($i=0; $i <$name; $i++){ 
					$c_img.=$fileupload[1][$i]['file_name'].',';
				}

		}
		else{
			$c_img=$this->input->post('c_image1[$i]');
		}

		$id=$this->input->post('id');

		$inputdata=array(
			'id'=>$id,
			'vehicle_type'=>$this->input->post('vehicaltype'),
			'vehicle_brand'=>$this->input->post('brandname'),
			'vehical_model'=>ucwords($this->input->post('vehicalmodel')),
			'model_type'=>ucwords($this->input->post('model_type')),
			'preferences'=>$this->input->post('preferences'),
			'c_title' => $c_title,
			'c_code' => $c_code,
			'overview'=>$this->input->post('overview'),
			'f_image' => $f_image,
	    	'image'=>$img,
	    	'm_video' =>$this->input->post('m_video') 
		);

		$inputdata = $this->security->xss_clean($inputdata);
		
		if($this->m_user->updatedata('vehicals_type_brand_models',$inputdata))
		{
		$this->session->set_flashdata("message","<div class='alert alert-success col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>data successfuly saved</strong><br>".$fileupload[0]."</div>");
		}	
		else{ 
		$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong> Saving failed try again with valid data</strong></div>");
   		}
    }else{ 
    	$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>Invalid Data Please fill the valid data</strong></div>");}
    // redirect('admin/editmodels/'.$id.'/'.$data);

  		}
	}

	public function editvariants(){
		$admin=new admin();
		$vimg = '';
		if ($this->m_user->is_logged_in()){
		$this->form_validation->set_rules('vehicaltype','Type','trim|required');
		$this->form_validation->set_rules('brandname','Brand Name','trim|required');
		$this->form_validation->set_rules('vehicalmodel','Model Name','trim|required');
		$this->form_validation->set_rules('varianttype','Variant Type','required');
		$this->form_validation->set_rules('fuel_type','Fuel Type','required');
		$this->form_validation->set_rules('enginetype','Engine Type','required');
		$this->form_validation->set_rules('displacement','Displacement','required');
		$this->form_validation->set_rules('cylinder','No Of Cylinder','required');
		$this->form_validation->set_rules('power','Power','required');
		$this->form_validation->set_rules('torque','Torque','required');
		$this->form_validation->set_rules('drive_train','Drive Train','required');
		$this->form_validation->set_rules('length','Length','required');
		$this->form_validation->set_rules('width','Width','required');
		$this->form_validation->set_rules('height','Height','required');
		$this->form_validation->set_rules('wheelbase','Wheelbase','required');
		$this->form_validation->set_rules('ground_clearance','Ground Clearance','required');
		$this->form_validation->set_rules('boot_space','Boot Space','required');
		$this->form_validation->set_rules('kerb_weight','Kerb Weight','required');
		$this->form_validation->set_rules('gross_weight','Gross Weight','required');
		$this->form_validation->set_rules('front_track','Front Track','required');
		$this->form_validation->set_rules('rear_track','Rear Track','required');
		$this->form_validation->set_rules('min_turning_radius','Minimum Turning Radius','required');
		$this->form_validation->set_rules('doors','No. Of Doors','required');
		$this->form_validation->set_rules('seating','Seating Capacity','required');
		$this->form_validation->set_rules('gears','No. of gears','required');
		$this->form_validation->set_rules('clutch','Clutch Type','required');
		$this->form_validation->set_rules('front_break','Front Brake Type','required');
		$this->form_validation->set_rules('rear_break','Rear Brake Type','required');
		$this->form_validation->set_rules('mileage_city','Mileage City','required');
		$this->form_validation->set_rules('mileage_highway','Mileage Highway','required');
		$this->form_validation->set_rules('mileage','Mileage','required');
		$this->form_validation->set_rules('fuel_tank_capacity','Fuel Tank Capacity','required');
		$this->form_validation->set_rules('wheel_type','Wheel Type','required');
		$this->form_validation->set_rules('tyre_type','Tyre Type','required');
		$this->form_validation->set_rules('front_tyre_size','Front Tyre Size','required');
		$this->form_validation->set_rules('rear_tyre_size','Rear Tyre Size','required');
		$this->form_validation->set_rules('performance','Performance 0 To 100 Kmph','required');
		$this->form_validation->set_rules('max_speed','Max Speed','required');
		$this->form_validation->set_rules('f_suspension','Front Suspension','required');
		$this->form_validation->set_rules('r_suspension','Rear Suspension','required');
		$this->form_validation->set_rules('power_steering','Power Steering','required');
		$this->form_validation->set_rules('steering_type','Steering Type','required');
		$this->form_validation->set_rules('adj_power_steering','Adjustable Power Steering','required');
		$this->form_validation->set_rules('air_conditioner','Air Conditioner','required');
		$this->form_validation->set_rules('steering_adjustment','Steering Adjustment','required');
		$this->form_validation->set_rules('upholstery','Upholstery','required');
		$this->form_validation->set_rules('light_type','Light Type','required');
		$this->form_validation->set_rules('features','Features','required');

		if($this->form_validation->run()){

				$features = implode($this->input->post('features'),',');
				
				if (isset($_FILES['vimage']) && !empty($_FILES['vimage']['name'])){	
  					$fileupload=$admin->upload('vimage');
					$img=$fileupload['1']['file_name'];

				}
				else{
					$img=$this->input->post('vimg');
				}

		/*		if (isset($_FILES['f_image']) && !empty($_FILES['f_image']['name'])){	
  				$fileupload=$admin->upload('f_image');
  				$f_image=$fileupload['1']['file_name'];
  			}
		else{
			$f_image=$this->input->post('f_image1');
		}*/

				$id=$this->input->post('id');

			$inputdata=array(
				'id'=>$id,
				'type_id'=>$this->input->post('vehicaltype'),
				'brand_id'=>$this->input->post('brandname'),
				'model_id'=>$this->input->post('vehicalmodel'),
				'varient_name'=>ucwords($this->input->post('varianttype')),
				'fuel_type'=>$this->input->post('fuel_type'),
				'vimage'=>$img,
				//'video'=>$this->input->post('video'),
				'enginetype'=>$this->input->post('enginetype'),
				'displacement'=>$this->input->post('displacement'),
				'cylinder'=>$this->input->post('cylinder'),
				'power'=>$this->input->post('power'),
				'torque'=>$this->input->post('torque'),
				'drive_train'=>$this->input->post('drive_train'),
				'length'=>$this->input->post('length'),
				'width'=>$this->input->post('width'),
				'height'=>$this->input->post('height'),
				'wheelbase'=>$this->input->post('wheelbase'),
				'ground_clearance'=>$this->input->post('ground_clearance'),
				'boot_space'=>$this->input->post('boot_space'),
				'kerb_weight'=>$this->input->post('kerb_weight'),
				'gross_weight'=>$this->input->post('gross_weight'),
				'front_track'=>$this->input->post('front_track'),
				'rear_track'=>$this->input->post('rear_track'),
				'min_turning_radius'=>$this->input->post('min_turning_radius'),
				'doors'=>$this->input->post('doors'),
				'seating'=>$this->input->post('seating'),
				'gears'=>$this->input->post('gears'),
				'clutch'=>$this->input->post('clutch'),
				'front_break'=>$this->input->post('front_break'),
				'rear_break'=>$this->input->post('rear_break'),
				'mileage_city'=>$this->input->post('mileage_city'),
				'mileage_highway'=>$this->input->post('mileage_highway'),
				'mileage'=>$this->input->post('mileage'),
				'fuel_tank_capacity'=>$this->input->post('fuel_tank_capacity'),
				'wheel_type'=>$this->input->post('wheel_type'),
				'tyre_type'=>$this->input->post('tyre_type'),
				'front_tyre_size'=>$this->input->post('front_tyre_size'),
				'rear_tyre_size'=>$this->input->post('rear_tyre_size'),
				'performance'=>$this->input->post('performance'),
				'max_speed'=>$this->input->post('max_speed'),
				'f_suspension'=>$this->input->post('f_suspension'),
				'r_suspension'=>$this->input->post('r_suspension'),
				'power_steering'=>$this->input->post('power_steering'),
				'steering_type'=>$this->input->post('steering_type'),
				'adj_power_steering'=>$this->input->post('adj_power_steering'),
				'air_conditioner'=>$this->input->post('air_conditioner'),
				'steering_adjustment'=>$this->input->post('steering_adjustment'),
				'upholstery'=>$this->input->post('upholstery'),
				'light_type'=>$this->input->post('light_type'),
				'features'=>$features
				);
				
			$inputdata = $this->security->xss_clean($inputdata);
			if($this->m_user->updatedata('vehicals_type_brand_model_varient',$inputdata))
			{
			$this->session->set_flashdata("message","<div class='alert alert-success col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>data successfuly saved</strong><br>".$fileupload[0]."</div>");
			} 
			else{ 
			$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong> Saving failed try again with valid data</strong></div>");
			    }
	       }else{$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>Invalid Data Please fill the valid data</strong></div>");}
	      redirect('admin/editvariants/'.$id.'/'.$data);
	 }
	}

	public function editvehicles(){
		if ($this->m_user->is_logged_in()){
		$this->form_validation->set_rules('vehicaltype','Type','trim|required');
		$this->form_validation->set_rules('brandname','Brand Name','trim|required');
		$this->form_validation->set_rules('vehicalmodel','Model Name','trim|required');
		$this->form_validation->set_rules('varianttype','Variant Type','required');
		$this->form_validation->set_rules('city','City','required');
		$this->form_validation->set_rules('sr_price','Showroom Price','required');
		/*$this->form_validation->set_rules('ins_price','Insurance Price','required');
		$this->form_validation->set_rules('rto_price','RTO Price','required');
		$this->form_validation->set_rules('gd_link','Get Deal Link','required');	
		$this->form_validation->set_rules('vd_link','View Details Link','required');*/
				
		
		if($this->form_validation->run()){

				$city = implode($this->input->post('city'),',');
				$sr_price = implode($this->input->post('sr_price'),',');
				$ins_price = implode($this->input->post('ins_price'),',');
				$rto_price = implode($this->input->post('rto_price'),',');

				$id=$this->input->post('id');
				
			$inputdata=array(
				'id'=>$id,
				'type_id'=>$this->input->post('vehicaltype'),
				'brand_id'=>$this->input->post('brandname'),
				'model_id'=>$this->input->post('vehicalmodel'),
				'varient_id'=>$this->input->post('varianttype'),
				'city'=>$city,
				'sr_price'=>$sr_price,
				'rto_price'=>$rto_price,
				'ins_price'=>$ins_price,
				'gd_link'=>$this->input->post('gd_link'),
				'vd_link'=>$this->input->post('vd_link')
				);



			$inputdata = $this->security->xss_clean($inputdata);
			if($this->m_user->updatedata('vehicals_type_brand_model_varient_vehicle',$inputdata))
			{
			$this->session->set_flashdata("message","<div class='alert alert-success col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>data successfuly saved</strong></div>");
			} 
			else{ 
			$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong> Saving failed try again with valid data</strong></div>");
			    }
	       }else{$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>Invalid Data Please fill the valid data</strong></div>");}
	     redirect('admin/editvehicles/'.$id.'/'.$data);
		
		
		
	 }
	}

	public function editfeatures(){
		if ($this->m_user->is_logged_in()){
			$this->form_validation->set_rules('vehicaltype','Type','trim|required');
			$this->form_validation->set_rules('feature_name','Feature','trim|required');
			
		
		if($this->form_validation->run()){

			$id=$this->input->post('id');

			$inputdata=array(
				'id'=>$id,
				'feature_name'=>ucwords($this->input->post('feature_name')),
				'type_id'=>$this->input->post('vehicaltype'));

			$inputdata = $this->security->xss_clean($inputdata);
			if($this->m_user->updatedata('features',$inputdata))
			{
			$this->session->set_flashdata("message","<div class='alert alert-success col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>data successfuly saved</strong></div>");
			} 
			else{ 
			$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong> Saving failed try again with valid data</strong></div>");
			    }
	       }else{$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>Invalid Data Please fill the valid data</strong></div>");}
	      redirect('admin/editfeatures/'.$id.'/'.$data);
	 }
	}	

	public function editcities(){
		if ($this->m_user->is_logged_in()){
		$this->form_validation->set_rules('city_state','State','trim|required');
		$this->form_validation->set_rules('city_name','City','trim|required');
				
		
		if($this->form_validation->run()){

			$id=$this->input->post('id');
			
			$inputdata=array(
				'city_id'=>$id,
				'city_state'=>ucwords($this->input->post('city_state')),
				'city_name'=>ucwords($this->input->post('city_name')),
				);

			$inputdata = $this->security->xss_clean($inputdata);
			if($this->m_user->updatedata1('cities',$inputdata))
			{
			$this->session->set_flashdata("message","<div class='alert alert-success col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>data successfuly saved</strong></div>");
			} 
			else{ 
			$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong> Saving failed try again with valid data</strong></div>");
			    }
	       }else{$this->session->set_flashdata("message","<div class='alert alert-error col-xs-12'><button class='close' data-dismiss='alert'>&times;</button><strong>Invalid Data Please fill the valid data</strong></div>");}
	      redirect('admin/editcities/'.$id.'/'.$data);
	 }
	}

	public function update_brand_status(){
		if ($this->m_user->is_logged_in()){
		
			$status=$this->input->post('status');
			
			$inputdata=array(
				'status'=>$status,
				);

			$data = $this->m_user->updatestatus('vehicle_brands',$inputdata);
			
			}
	      }

	public function update_model_status(){
		if ($this->m_user->is_logged_in()){
		
			$status=$this->input->post('status');
			
			$inputdata=array(
				'status'=>$status,
				);

			$data = $this->m_user->updatestatus('vehicals_type_brand_models',$inputdata);
			
			}
	      }

	public function update_variant_status(){
		if ($this->m_user->is_logged_in()){
		
			$status=$this->input->post('status');
			
			$inputdata=array(
				'status'=>$status,
				);

			$data = $this->m_user->updatestatus('vehicals_type_brand_model_varient',$inputdata);
			
			}
	      }

	public function update_vehicle_status(){
		if ($this->m_user->is_logged_in()){
		
			$status=$this->input->post('status');
			
			$inputdata=array(
				'status'=>$status,
				);

			$data = $this->m_user->updatestatus('vehicals_type_brand_model_varient_vehicle',$inputdata);
			
			}
	      }

	public function update_city_status(){
		if ($this->m_user->is_logged_in()){
		
			$status=$this->input->post('status');
			
			$inputdata=array(
				'status'=>$status,
				);

			$data = $this->m_user->updatecitystatus('cities',$inputdata);
			
			}
	      }

	public function update_feature_status(){
		if ($this->m_user->is_logged_in()){
		
			$status=$this->input->post('status');
			
			$inputdata=array(
				'status'=>$status,
				);

			$data = $this->m_user->updatestatus('features',$inputdata);
			
			}
	      }

	
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */