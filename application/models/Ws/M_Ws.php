<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_Ws extends CI_Model 
{

    public function __construct() {
        parent::__construct();
    }
	
	function Validar_User() {
      
        $result = $this->db->select("*")
                ->from("sys_users u")
                ->join("sys_roles r","u.rol = r.id_roles")
                ->join("sys_preferences_html p","u.id_users = p.id_users","left")
				->where("u.user", $this->input->post("usr"))
				->where("u.password", md5($this->input->post("psw")))
                ->where("u.status", 1)
                ->get();

        if ($result->num_rows() > 0) {
            $reg = $result->row();
            
            $this->db->where("id_users",$reg->id_users);
            $this->db->update("sys_users",array("last_entry"=>date("Y-m-d H:i:s")));
            
            $newdata = array(
                'IdUser' => $reg->id_users,
                'NameUser' => $reg->name,
                'IdRol' => $reg->rol,
                'Rol' => $reg->description,
                'Email' => $reg->email,
            );
            
            
            $this->session->set_userdata($newdata);
			
			$newdata['res'] = 'OK';
            
        } else {
			$newdata['res'] = 'ERROR';
        }
		return $newdata;
		
    }
	
	function GetOrders(){
		
		if(!empty($this->input->post('order')))
			$this->db->where('order',$this->input->post('order'));
		
		$result = $this->db->select('*')
					->from('access_order a')
					->get();
					
		if(!empty($this->input->post('order'))){
			return $result->row();
		}else{
			return $result->result();
		}
		
	}

	function Getsys_messages_restapi(){
		if(!empty($this->input->post('idrequest')))
		// && (!empty($this->input->post('idmethod')))
		{
			$this->db->where('a.id_request_sd',$this->input->post('idrequest'));
			$this->db->where('a.id_method',$this->input->post('idmethod'));

			$result = $this->db->select('*')
								->from('sys_messages_restapi  a ')
								->get();
		}
		
			return $result->result();
	}

	function GetRequestCustomer(){
		/*
		Jaym Jun2019
		Find All request join order info customer, all status 17,19
		use WebServices Send client Rest
		*/
		if(!empty($this->input->post('idrequest')))
		$this->db->where('a.id_request_sd',$this->input->post('idrequest'));

		if(!empty($this->input->post('idstatus')))
		$this->db->where_in('a.id_status',$this->input->post('idstatus'));
		
		$result = $this->db->select(' b.*,a.*,b.type')
							->from('dis_request_sd_detail a ')
							->JOIN('access_order b','a.`order` = b.`order`') 
							->ORDER_BY('a.id_request_sd','asc')
							->ORDER_BY('b.client','asc')
							->ORDER_BY('b.project','asc')
							->ORDER_BY(' b.`order`','asc')
							->ORDER_BY(' a.NAME ','asc')
							->ORDER_BY(' a.pack','asc')
							->get();

		return $result->result();

	}

	function GetRequest(){
		if(!empty($this->input->post('idrequest')))
		$this->db->where('a.id_request_sd',$this->input->post('idrequest'));

		if(!empty($this->input->post('idstatus')))
		$this->db->where_in('a.id_status',$this->input->post('idstatus'));

		$result = $this->db->select(" a.id_request_sd,a.date,a.license_plate,a.dispatch_date,
									  a.quantity_packages,b.description as vehicle_type,b.max_weight,a.id_status,c.description as description_status ")
		->from('dis_request_sd a')
		->JOIN('sys_status c','a.id_status = c.id_status') 
		->JOIN('dis_weight_vehicle b', 'a.id_weight_vehicle = b.id_weight_vehicle')
		->ORDER_BY('a.id_request_sd','asc')
		->get();

		return $result->result();


	}

	/**
	 * Jaym Junio 2019
	 * find data messages to send ws movil
	 * 
	 */
	function getws_message_movil(){
		if(!empty($this->input->post('idrequest')))
				$this->db->where('a.id_request_sd',$this->input->post('idrequest'));

		

		$result = $this->db->select(" a.id_request_sd,a.client_message,a.client_message_type,
		a.last_read_bar_qr,a.client_message_view ")
				->from('ws_message_movil a')
				
				->ORDER_BY('a.id_request_sd','asc')
				->get();

		return $result->row();


	}

/**
 * Jaym Junio 2019
 * select data details table request_sd
 */
	function GetRequestDetails(){
		
		if(!empty($this->input->post('idrequest')))
			$this->db->where('a.id_request_sd',$this->input->post('idrequest'));

		if(!empty($this->input->post('idstatus')))
			$this->db->where_in('a.id_status',$this->input->post('idstatus'));

		$result = $this->db->select('a.id_request_sd,a.`order`,b.`client`,b.project ')
						->from('dis_request_sd_detail a')
						->JOIN('access_order b',' a.`order`=b.`order`')
                                                
						->get();

		
			return $result->result();
		

		
	}

	/**
 * Jaym Junio 2019
 * select and sum value for id_request_sd
 * view resume monitor 
 */
    function SumTotalRequest(){
		
		if(!empty($this->input->post('idrequest')))
			$this->db->where('d.id_request_sd',$this->input->post('idrequest'));

		if(!empty($this->input->post('idstatus')))
			$this->db->where_in('d.id_status',$this->input->post('idstatus'));

		$result = $this->db->select('d.id_request_sd,
                                            SUM(d.quantity_packets) as totalqty_packets,
                                            fsum_orderqtypack(d.id_request_sd,d.order,18) as qty_packetsLoad,
                                            Sum(d.quantity_packets)-fsum_orderqtypack(d.id_request_sd,d.`order`,18) as pendingqtypack,
                                            Sum(d.weight) AS totalweight,
                                            fsum_orderweight(d.id_request_sd,d.`order`,18) as weight_packetsLoad,
                                            SUM(d.weight)-fsum_orderweight(d.id_request_sd,d.`order`,18) as pendingweigthload,
                                            sys_status.description AS status_packets,
                                            dis_request_sd.driver')
						->from('dis_request_sd_detail d')
						->JOIN('sys_status','d.id_status = sys_status.id_status')
                         ->JOIN('dis_request_sd','d.id_request_sd = dis_request_sd.id_request_sd')
						->get();
			
		
			return  $result->row();		
	
	
		}

		
				
}

