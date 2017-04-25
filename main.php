<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Main extends CI_Controller {

	var $data = array();

  function __construct(){

    parent::__construct();	

    $this->load->model('m_user');

    $this->session->set_userdata(array('city_id' => 325 ));

}



	public function index(){

    $condition2 = array('id =' => '2');
    $data['brands']=$this->m_user->select_with_condition('vehicle_brands',$condition2);

    $condition1 = array('vehicle_brand =' => '2');
    $data['models']=$this->m_user->select_with_condition('vehicals_type_brand_models',$condition1);

    $condition= array('brand_id =' => '2');
    $data['variant']=$this->m_user->select_with_condition('vehicals_type_brand_model_varient',$condition);

    $condition4 = array('brand_id =' => '2');
    $data['vehicle']=$this->m_user->select_with_condition('vehicals_type_brand_model_varient_vehicle',$condition4);

    $data['controller']=$this;

		$this->load->view('index',$data);

	}



	public function models(){

    $data['controller']=$this;

    $brand = $this->uri->segment(3);
    $condition1 = array('brand_name =' => $brand);
    $data['brand']=$this->m_user->select_with_condition('vehicle_brands',$condition1);
    $result=$data['brand']->row();
    $b_id=$result->id;

    $this->session->set_userdata("brand_name",$brand);

    $condition2 = array('id =' => $b_id);
    $data['brands']=$this->m_user->select_with_condition('vehicle_brands',$condition2);

    $condition1 = array('vehicle_brand =' => $b_id);
    $data['models']=$this->m_user->select_with_condition('vehicals_type_brand_models',$condition1);

    $condition3= array('brand_id =' => $b_id);
    $data['var']=$this->m_user->select_with_condition('vehicals_type_brand_model_varient',$condition3);

    $condition= array('brand_id =' => $b_id);
    $data['variant']=$this->m_user->select_with_condition('vehicals_type_brand_model_varient',$condition);

    $condition4 = array('brand_id =' => $b_id);
    $data['vehicle']=$this->m_user->select_with_condition('vehicals_type_brand_model_varient_vehicle',$condition4);

    $this->load->view('models',$data);

	}

	public function details(){

    $brand = $this->uri->segment(3);
    $condition1 = array('brand_name =' => $brand);
    $data['brand']=$this->m_user->select_with_condition('vehicle_brands',$condition1);
    $result=$data['brand']->row();
    $b_id=$result->id;

    $this->session->set_userdata("brand_name",$brand);

    $m_name=$this->uri->segment(4);
    $m_name=str_replace('-', ' ', trim($m_name));
    
    $v_name=$this->uri->segment(6);
    $v_name=str_replace('variant-', '',$v_name);
    $v_name=str_replace('-', ' ',trim($v_name));
    $data['var_name']=$v_name;
    $v_id = $this->get_variant_id_with_name($v_name);

    $c_name=$this->uri->segment(5);
    $c_name=str_replace('price-in-', '',$c_name);
    $c_name=str_replace('-',' ',trim($c_name));
    $data['city_name']=$c_name;
    $city = $c_name;
     $city = str_replace('Price in ','',$city);
     $c_id = $this->get_city_id_with_name($city);

     $data['price_d'] = $this->get_price($c_id,$v_id);
     $res = $data['price_d'];
     // echo $res;

    $condition2 = array('id =' => $b_id);
    $data['brands']=$this->m_user->select_with_condition('vehicle_brands',$condition2);

    $condition5 = array('vehicle_brand =' => $b_id);
    $data['mod']=$this->m_user->select_with_condition('vehicals_type_brand_models',$condition5);

    $condition1 = array('vehical_model =' => $m_name);
    $data['models']=$this->m_user->select_with_condition('vehicals_type_brand_models',$condition1);
    
    $result=$data['models']->row();
    $m_id=$result->id;

    $condition3= array('model_id =' => $m_id);
    $data['vars']=$this->m_user->select_with_condition('vehicals_type_brand_model_varient',$condition3);

    $condition0= array('brand_id =' => $b_id);
    $data['bvar']=$this->m_user->select_with_condition('vehicals_type_brand_model_varient',$condition0);

    $condition4 = array('model_id =' => $m_id);
    $data['vehicle']=$this->m_user->select_with_condition('vehicals_type_brand_model_varient_vehicle',$condition4);

	$condition= array('id =' => $v_id);
    $data['variant']=$this->m_user->select_with_condition('vehicals_type_brand_model_varient',$condition);

    $condition9 = array('varient_id =' => $v_id);
    $data['vehical']=$this->m_user->select_with_condition('vehicals_type_brand_model_varient_vehicle',$condition9);

    $data['controller']=$this;

   	$data['features']=$this->m_user->select('features');

    $data['city']=$this->m_user->select('cities');

    $this->session->set_userdata("model_id",$m_name);

    $this->load->view('detail',$data);

	}

	public function get_feature_name_with_id($id){

    $condition=array('id'=>$id);

    $data['features']=$this->m_user->select_with_condition('features',$condition);

    $res=$data['features']->row();

     return $res->feature_name;

   }

   

   public function get_city_name_with_id($id){

     $condition=array('city_id'=>$id);

     $data['city']=$this->m_user->select_with_condition('cities',$condition);

     $res=$data['city']->row();

     return $res->city_name;    

   }

   public function get_city_id_with_name($city){

     $condition=array('city_name'=>$city);

     $data['city']=$this->m_user->select_with_condition('cities',$condition);

     $res=$data['city']->row();

     return $res->city_id;    

   }

   public function get_variant_name_with_id($id){

     $condition=array('id'=>$id);

     $data['g']=$this->m_user->select_with_condition('vehicals_type_brand_model_varient',$condition);

     $res=$data['g']->row();

     return $res->varient_name;    

   }

   public function get_variant_id_with_name($vname){

     $condition=array('varient_name'=>$vname);

     $data['g']=$this->m_user->select_with_condition('vehicals_type_brand_model_varient',$condition);

     $res=$data['g']->row();

     return $res->id;    

   }

   public function get_variant_price_with_id($id){

    // echo $id;

     $condition=array('varient_id'=>$id);

     $data['g']=$this->m_user->select_with_condition('vehicals_type_brand_model_varient_vehicle',$condition);

     $res=$data['g']->row();

     $sr_price = $res->sr_price;

     $parray = explode(',',$sr_price);

     return $parray[0];    

   }

public function indian_number_format($num) {
    $num = "".$num;
    if( strlen($num) < 4) return $num;
    $tail = substr($num,-3);
    $head = substr($num,0,-3);
    $head = preg_replace("/\B(?=(?:\d{2})+(?!\d))/",",",$head);
    return $head.",".$tail;
    }

   public function get_price($cid,$vid){
     
     $c_id = $cid;
     $v_id = $vid;

     $this->db->where("FIND_IN_SET('$c_id',city) !=", 0);
     $this->db->where("varient_id",$v_id);

    $qry = $this->db->get('vehicals_type_brand_model_varient_vehicle');
    foreach ($qry->result() as $price) {
        $p_arr = explode(',', $price->sr_price);
        $c_arr = explode(',', $price->city);
        $num = array_search($c_id, $c_arr);
        $p = $this->indian_number_format($p_arr[$num]);
        
    }
    return '₹. '.$p;

   }

public function get_model_type(){
    $type = $this->input->post('type');
    
    $brand = $this->uri->segment(3);
    $condition1 = array('brand_name =' => $brand);
    $data['brand']=$this->m_user->select_with_condition('vehicle_brands',$condition1);
    $result=$data['brand']->row();
    $b_id=$result->id;

    $condition2 = array('id =' => $b_id);
    $brands=$this->m_user->select_with_condition('vehicle_brands',$condition2);

    $condition1 = array('vehicle_brand =' => $b_id);
    $this->db->where("model_type",$type);
    $models=$this->m_user->select_with_condition('vehicals_type_brand_models',$condition1);

    $condition3= array('brand_id =' => $b_id);
    $var=$this->m_user->select_with_condition('vehicals_type_brand_model_varient',$condition3);

    $condition= array('brand_id =' => $b_id);
    $variant=$this->m_user->select_with_condition('vehicals_type_brand_model_varient',$condition);

    $condition4 = array('brand_id =' => $b_id);
    $vehicle=$this->m_user->select_with_condition('vehicals_type_brand_model_varient_vehicle',$condition4);

    $data = '';
    foreach($models->result() as $model){ 
        $m = $model->vehical_model; $m_name = str_replace(' ','-',trim($m));
        foreach($brands->result() as $brand){
        $b = $brand->brand_name; 
        $b_name = str_replace(' ','-',trim($b));
    }
    
    foreach ($variant->result() as $var) {
        if($model->id == $var->model_id){ 
            $v = $var->varient_name;
            $v = str_replace(' ','-',$v);
            break;
        }
    }
        $data.= '<div class="col-md-4 col-sm-6 col-xs-12 col-xxs-12 stm-isotope-listing-item stm_moto_single_grid_item all 2015-74 black-302 motorcycles-480 px-488 sportbike-485 used-cars-113" data-price="27000" id="'.$model->model_type.'" data-date="201608231210" data-mileage="14400">
                <input type="hidden" name="model_type" id="model_type" value="'.$model->model_type.'"><a title="'.$brand->brand_name.$model->vehical_model.' Price In Delhi" href="'.base_url('main/details').'/'.$b_name .'/'.$m_name.'/Price-in-Delhi/variant-'.$v.'" class="rmv_txt_drctn"><div class="image"><img data-original="'.base_url('img/'.$model->f_image) .'" src="'.base_url('img/'.$model->f_image) .'" class="lazy img-responsive" alt="" id="model_img"/><div class="stm_moto_hover_unit"><div class="heading-font"><div class="price"><div class="sale-price">';
    foreach ($vehicle->result() as $veh) {
        if($model->id == $veh->model_id){
            $var_id = $veh->varient_id;
            break;
        }
    }
    foreach ($vehicle->result() as $veh) {
        if($model->id == $veh->model_id && $veh->varient_id == $var_id){
            $sr_price = $veh->sr_price;
            $sprice_arr = explode(',', $sr_price);
            $p = $this->indian_number_format($sprice_arr[0]);
            $e = '<span id="show_price">'.$p.'</span>';
        } 
    }  
    if(isset($e)){
        $data.= '₹. '.$e;
    }
    $data.='</div></div></div></div></div><div class="listing-car-item-meta"><div class="car-meta-top heading-font clearfix"><div class="car-title"><span class="stm-label-title">'.$brand->brand_name.'</span>'.$model->vehical_model.'</div></div><div class="car-meta-bottom" style="height: 110px"><ul>';
    foreach ($variant->result() as $var) {
        if($model->id == $var->model_id){ 
            $engine=explode('Engine', $var->enginetype);
            $d = '<li>
                    <span class="stm_label">Engine</span>
                    <span>'.$engine[0].'</span>
                  </li><br>
                  <li>
                    <span class="stm_label">Displacement:</span>
                    <span>'.$var->displacement.'</span>
                  </li><br>
                  <li>
                    <span class="stm_label">Mileage:</span>
                    <span>'.$var->mileage.'</span>
                  </li>';
        } 
    } 
    if(isset($d)){
        $data.= $d;
    }
    $data.='</ul></div></div></a></div>';
}
echo $data;
}
   

   
}

