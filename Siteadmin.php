<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siteadmin extends MY_Controller {
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function page_load($page)
	{
		if(isset($this->session->userdata['admin_logged_in']))
		{	
			$this->load->view('admin/'.$page['page_name'],$page);
		}
		else
		{
			redirect('siteadmin');
			//$this->load->view('admin/index');
		}
	}
	public function pages($page)
	{
		$array = array();
		$array['page_name'] = $page;
		$this->page_load($array);
	}
	public function admin_login()
	{
		$this->load->model('m_admin_home');
		$this->load->library('user_agent');
		$email = $this->input->post('txt_username');
		$password = $this->input->post('txt_password');
		$city = $this->input->post('city');
		$state = $this->input->post('state');
		$country = $this->input->post('country');
		$ip = $_SERVER['REMOTE_ADDR'];
		$datetime = date('Y-m-d H:i:s');
		$result = $this->m_admin_home->admin_login($email,$password);
		
		if($result == 1)
		{
			echo 'Invalid username.';
		}
		else if($result == 2)
		{
			echo "your account is not active.";
		}
		else if($result == 3)
		{
			echo "Invalid password.";
		}
		else
		{
			$device = 'computer';
			$browser = '';
			$platform = '';
		
			if ($this->agent->is_browser())
			{
			        $browser = $this->agent->browser().' '.$this->agent->version();
			}
			if ($this->agent->is_mobile())
			{
			        $device = $this->agent->mobile();
			}
			$platform =  $this->agent->platform();
			

			$admin_id = $result[0]['admin_id'];
			$array_insert_admin_history = array(
												'admin_id'=>$admin_id,
												'login_time'=>$datetime,
												'ip_address'=>$ip,
												'city'=>$city,
												'state'=>$state,
												'country'=>$country,
												'os'=>$platform,
												'browser'=>$browser,
												'device'=>$device
												);	
								
			$admin_history_id =  $this->m_admin_home->insert_admin_history($array_insert_admin_history); // subimit admin login history

			// session created
			$array_admin_session_data = array('admin_username'=>$result[0]['first_name'],
										'admin_user_id'=>$result[0]['admin_id'],
										'admin_history_id'=>$admin_history_id	
										);

			$this->session->set_userdata('admin_logged_in',$array_admin_session_data);	

			//$this->load->library('email');
	        $to_email = "jaydeep@theskinstore.in";
	        $body_data ='<div>
							<h4> Admin Login Detected </h4>
							<p> IP Address : '.$ip.'  </p>
							<p> Login Time : '.$datetime.'  </p>
							<p> City : '.$city.'  </p>
							<p> State : '.$state.'  </p>
							<p> Country : '.$country.'  </p>
							<p> OS : '.$platform.'  </p>
							<p> Browser : '.$browser.'  </p>
							<p> Device : '.$device.'  </p>
						</div>';
	      //  $body = $this->load->view('email_template',$body_data,true);		
			       		
	        $this->email->from('theskinstore.in','Theskinstore admin login');
	        $this->email->to($to_email);
	        $this->email->set_mailtype("html");
	        $this->email->subject('Detect admin login');
	        $this->email->message($body_data);
	        $this->email->send();
			echo  '1';
		}

	}
	public function index()
	{
		if(isset($this->session->userdata['admin_logged_in']))
		{	
			$this->load->view('admin/home');
		}
		else
		{
			$this->load->view('admin/index');
		}
	}
	public function home()
	{
		$array = array();
		$array['page_name'] = 'home';
		$this->page_load($array);
	}
	public function orders()
	{
		$array = array();
		$array['page_name'] = 'orders';		
		$this->load->model('m_admin_home');
		$array['order_status'] = $this->m_admin_home->get_order_status();
		$this->page_load($array);
	}	
	public function order_edit($id=0)
	{
		$this->load->model('m_admin_home');
		$array = array();
		$array['order_data'] = $this->m_admin_home->get_order_data_from_id($id);
		$array['order_status'] = $this->m_admin_home->get_order_status();
		$array['courier_company_data'] = $this->m_admin_home->get_courier_company_data();
		$array['order_product_data'] = $this->m_admin_home->get_order_product_data($id);
		$array['order_product_history'] = $this->m_admin_home->get_order_product_history($id);
		$array['page_name'] = 'order-edit';
		$this->page_load($array);
	}	
	public function order_update($id=0)
	{
		$this->load->model('m_ajax_order');
		$this->load->model('m_admin_home');
		
		$v_action = $this->input->post('p_action');
		if($v_action=='update_order')
		{
			$v_status = $this->input->post('p_status');
			$v_courier_company = $this->input->post('p_courier_company');
			$v_tackurl = $this->input->post('p_tackurl');
			$v_docketno = $this->input->post('p_docketno');
			$v_nofity_customer = $this->input->post('p_notofy_customer');
			$v_comment = $this->input->post('p_comment');
			$v_order_id = $this->input->post('p_order_id');
			$v_customer_id = $this->input->post('p_customer_id');
			
			$v_device = 'computer';
			$v_browser = '';
			$v_platform = '';
			$v_ip = $_SERVER['REMOTE_ADDR'];
			$v_tackingurl = $v_tackurl.$v_docketno;
			$this->load->library('user_agent');
			if ($this->agent->is_browser())
			{
			        $v_browser = $this->agent->browser().' '.$this->agent->version();
			}
			if ($this->agent->is_mobile())
			{
			        $v_device = $this->agent->mobile();
			}
			$v_platform =  $this->agent->platform();
			$v_date = date('Y-m-d H:i:s');
			$v_admin_id = $this->session->userdata['admin_logged_in']['admin_user_id'];	  // pick form session 
			
			if(!empty($v_status))
			{
				$array_order_update = array(
										'status_id'=>$v_status,
										'courier_company_id'=>$v_courier_company,
										'docket_no'=>$v_docketno,
										'trakking_url'=>$v_tackingurl,
										'order_status_updated_date'=>$v_date,
										);
				$order_update = $this->m_admin_home->update_order($v_order_id,$array_order_update);
				
				$result_user = $this->m_ajax_order->get_user_detail($v_customer_id,$v_order_id);
		
				foreach($result_user as $row)
					{
						$customer_name = $row->customer_name;
						$email = $row->email;
						$phone_no = $row->phone;
					}

				$res_status_name = $this->m_admin_home->get_status_name_byID($v_status);
				$status = $res_status_name[0]['status_name'];
				$this->load->library('email');
				$subject = "Order ".$status." - Your order #".$v_order_id." has been ".$status.".";
				$body_data = '
										<tr>	
											<td colspan="2" style="padding: 0px 30px;">Dear <span style="display: inline ;font-weight:700;color:#000;">'.$customer_name.'</span></td>
										</tr>	
										<tr>	
											<td colspan="2" style="color:#000; font-weight:700;padding: 0px 30px;">
												Your order '.$status.' 
											</td>
										</tr>
										<tr>	
											<td colspan="2" style="color:#000; font-weight:300;padding: 0px 30px;">
												On '.$v_date.'
											</td>
										</tr>
										<tr>	
											<td colspan="2" style="color:#000; font-weight:300;padding: 0px 30px;">
												<span style="display: inline ;color : green">Order ID #'.$v_order_id.'</span> .
											</td>
										</tr>
										';
			
			$this->load->library('sms');
			$msg = 'Order '.$status.' - Your order #'.$v_order_id.' has been '.$status.', Thank you for shopping with theskinstore.in.
					Beauty Leis Ahead.';
						
			$send_sms = $this->sms->send_msg($phone_no, $msg); // send msg
		
		
			$v_show_thankYOU_image = "no";	
			$order_summary = $this->order_detail_send_email($v_order_id,$body_data,$v_show_thankYOU_image);
			
			$this->email->send_email_with_smtp($email,$order_summary,$subject); // send email
		}
			$array_order_hitory = array(
										'order_id'=>$v_order_id,
										'order_product_id'=>'',
										'order_status_id'=>$v_status,
										'order_product_status_id'=>'',
										'comment'=>$v_comment,
										'ent_datetime'=>$v_date,
										'ip'=>$v_ip,
										'browser'=>$v_browser,
										'device'=>$v_device,
										'admin_user_id'=>$v_admin_id,
										'customer_id'=>$v_customer_id
										);	
			$admin_history_id =  $this->m_admin_home->insert_order_history($array_order_hitory);
		}
		$r= array();
		$r['STATUS']=1;
		$r['MSG']='Done';
		echo json_encode($r);
		exit ;
	}	
	public function invoice($id)
	{
		// print_r($id);
		// $id = explode(',',$id);
		$ordersid = explode(',',$id);
		$this->load->model('m_admin_home');
		$invoice_data = '';		
		$array = array();
		$array['order_data']= $this->m_admin_home->get_invoice_order_data($id);
		$array['page_name'] = 'invoice';
		$this->page_load($array);
	}
	public function prin_invoice()
	{
		
		$id = $this->input->post('invoice_orders_id');
		$this->load->model('m_admin_home');
		$invoice_data = '';		
		$array = array();
		$array['order_data']= $this->m_admin_home->get_invoice_order_data($id);
		$array['page_name'] = 'invoice';
		$this->page_load($array);
	}
	public function order_displaydata()
	{
		$v_action = $this->input->post('p_action');
		$v_limit = $this->input->post('noofrecode');
		$v_datechk = $this->input->post('p_datechk');
		
		$oldfromDate =  $this->input->post('p_fromdate');
		//$arr = explode('-', $oldfromDate);
		//$v_fromdate = $arr[2].'-'.$arr[1].'-'.$arr[0]; //from date
		$v_fromdate = date('Y-m-d',strtotime($oldfromDate));
		
		
		
		$oldtoDate =  $this->input->post('p_todate');
		//$arr = explode('-', $oldtoDate);
		//$v_todate = $arr[2].'-'.$arr[1].'-'.$arr[0]; // to date
		$v_todate = date('Y-m-d',strtotime($oldtoDate));
		
		
		$v_filterstr= $this->input->post('p_filterstr');
		
		$v_wherefilter = '';
		
		if($v_datechk!='false')
		{
			$v_wherefilter = ' and purchase_date>="'.$v_fromdate.'" and purchase_date<="'.$v_todate.'"';
			
			
		}
		if(!empty($v_filterstr))
		{
			$v_wherefilter .= $v_filterstr;
		}
		
		$this->load->model('m_admin_home');
		$array['order_data'] = $this->m_admin_home->get_order_data($v_wherefilter,$v_limit);
		$order_data = '';
			foreach($array['order_data'] as $order)
			{
				$order_id = $order->order_id ;
				$purchase_date = date('d-m-Y H:i:s',strtotime($order->purchase_date));
				// $discount = ' '; discount code
				// if(!empty($order->coupon_id))
				// {
					// $discount .='Discount coupon : '.$order->coupon_id.' Discount amount : '.$order->coupon_id ;
				// }
				// if(!empty($order->reward_point))
				// {
					// $discount .='Reward point : '.$order->reward_point.' Reward amount : '.$order->reward_point_amount ;
				// }
				// if(!empty($order->gift_voucher_id))
				// {
					// $discount .='GIft Voucher amount : '.$order->gift_voucher_amount ;
				// }
				// if(empty($discount))
				// {
					// $discount ='-';
				// }
				$status= '';
				$color_array=array(
								  'Pending'=>'label-success',
								  'Failure'=>'label-success',
								  'Returnd'=>'label-success',
								  'Exchange'=>'label-success',
								  'Processing'=>'label-success',
								  'Delivered'=>'label-success',
								  'Payment Pending'=>'label-warning',
								  'Payment Failure'=>'label-warning',
								  'Canceled'=>'label-red',
								  'Approved'=>'label-info',
								  'Payment Complete'=>'label-info',
								  'Packed'=>'label-purple',
								  'Shipped'=>'label-inverse',
								  'Return Request'=>'label-danger',
								  'Return Request Rejected'=>'label-danger'
									);
				if(!empty($order->status))
				{
					$status = $color_array[$order->status] ;
				}
				$order_data .='	<tr id="'.$order_id.'">
									<td>
										<input type="checkbox" name="chek_order_'.$order_id.'" id="chek_order_'.$order_id.'" 
											value="'.$order_id.'" onclick="fun_chek_order(this);" 
											class="chek_order_'.$order_id.' master_order_chek">
									</td>
									<td>
										<a href="'.base_url("siteadmin/order_edit/".$order_id).'" target="_blank" color="black">#'.$order_id.'</a>
									</td>
									<td>
										<a href="'.base_url("siteadmin/order_edit/".$order_id).'" target="_blank" color="black">
										'.$order->payment_method.'</a>
									</td>
									<td>
										<a href="'.base_url("siteadmin/order_edit/".$order_id).'" target="_blank" color="black">
										'.$order->customer_name.'</a>
									</td>
									<td>'.$order->order_total_amount.'</td>
									<td>'.$purchase_date.'</td>
									<td>
										<span class="label '.$status.' font-weight-100">'.$order->status.'</span> 
									</td>
									<td>
										<a target="_blank" href="https://api.whatsapp.com/send?phone=9'.$order->customer_phone.'">
										'.$order->customer_phone.'</a>
									</td>									
									<td>
										<a target="_blank" href="'.$order->trakking_url.'">Track :'.$order->docket_no.'</a>
									</td>
								</tr>';
			}
		
			$r= array();
			$r['STATUS']=1;
			$r['order_data']=$order_data;
			$r['MSG']='Done';
			echo json_encode($r);
			exit ;
		
		//$array = array();
		//$this->page_load($array);
	}	
	public function export_order_data()
	{
		$v_action = $this->input->get('p_action');
		//$v_limit = 0;
		$v_datechk = $this->input->get('p_datechk');
	
		
		$oldfromDate =  $this->input->get('p_fromdate');
		$v_fromdate = date('Y-m-d',strtotime($oldfromDate));
		
		$oldtoDate =  $this->input->get('p_todate');
		$v_todate = date('Y-m-d',strtotime($oldtoDate));
	
		$v_filterstr= $this->input->get('p_filterstr');
		
		$v_wherefilter = '';
		
		if($v_datechk != 'false')
		{
			$v_wherefilter = ' and o.purchase_date>="'.$v_fromdate.'" and o.purchase_date<="'.$v_todate.'"';
		}
		if(!empty($v_filterstr))
		{
			$v_wherefilter .= $v_filterstr;
		}
	
		$this->load->model('m_admin_home');
		$records = $this->m_admin_home->export_order_data($v_wherefilter);
			
			$this->load->library("Excel");
			$object = new PHPExcel();

			$object->setActiveSheetIndex(0);

			$table_columns = array("ORDER ID","BRAND","CATEGORY","PRODUCT SKU/CODE","PRODUCT NAME","PRODUCT PRICE","PRODUCT QUANTITY","DISCOUNT","DISCOUNTED PRICE","TOTAL AMOUNT","COUPON CODE","COUPON AMOUNT","GIFT VOUCHER AMOUNT","REWARD AMOUNT","SHIPPING CHARGE","PAYMENT METHOD","DATE PURCHASE","STATUS","CUSTOMER ID","NAME","EMAIL","SHIPPING NAME","SHIPPING ADDRESS","SHIPPING CITY","SHIPPING STATE","SHIPPING POSTCODE","SHIPPING TELEPHONE","BILLING NAME","BILLING ADDRESS","BILLING CITY","BILLING STATE","BILLING POSTCODE","BILLING TELEPHONE");

			$column = 0;

			 $object->getActiveSheet()->setTitle('order report');

			 $object->getActiveSheet()->setCellValueByColumnAndRow('A1', 1, 'ORDER REPORT');

			 $object->getActiveSheet()->mergeCells('A1:R1');

					//set aligment to center for that merged cell (A1 to C1)

			$object->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

					//make the font become bold

			$object->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);

				//    $object->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');
			$object->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('D2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('E2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('F2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('G2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('H2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('I2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('J2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('K2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('L2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('M2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('N2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('O2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('P2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('Q2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('R2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('S2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('T2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('U2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('V2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('W2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('X2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('Y2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('Z2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('AA2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('AB2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('AC2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('AD2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('AE2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('AF2')->getFont()->setBold(true);
			$object->getActiveSheet()->getStyle('AG2')->getFont()->setBold(true);
						
			   

			foreach($table_columns as $field)
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow($column, 2, $field);
				$column++;
			}
			$excel_row = 3;
			
			$old_order_id = '';
			foreach($records as $row)
			{
				$v_order_id = $row->order_id;
				$price = $row->product_amount/$row->product_quantity;
				$p_date = date('d-m-Y H:i:s',strtotime($row->purchase_date));
				if($v_order_id != $old_order_id){
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $v_order_id);
				}	
					$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->brand);
					$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->category);
					$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->product_model_no);
					$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->product_name);
					$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $price);
					$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->product_quantity);
					$object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->discount_amount);
					$object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->final_price);
				if($v_order_id != $old_order_id){	
					$object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row->order_total_amount);
					$object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row->coupon_code);
					$object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row->coupon_amount);
					$object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $row->gift_voucher_amount);
					$object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $row->reward_point_amount);
					$object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, $row->shipping_charge);
					$object->getActiveSheet()->setCellValueByColumnAndRow(15, $excel_row, $row->payment_method);
					$object->getActiveSheet()->setCellValueByColumnAndRow(16, $excel_row, $p_date);
					$object->getActiveSheet()->setCellValueByColumnAndRow(17, $excel_row, $row->status);
					$object->getActiveSheet()->setCellValueByColumnAndRow(18, $excel_row, $row->customer_id);
					$object->getActiveSheet()->setCellValueByColumnAndRow(19, $excel_row, $row->customer_name);
					$object->getActiveSheet()->setCellValueByColumnAndRow(20, $excel_row, $row->email);
					$object->getActiveSheet()->setCellValueByColumnAndRow(21, $excel_row, $row->s_name);
					$object->getActiveSheet()->setCellValueByColumnAndRow(22, $excel_row, $row->s_address);
					$object->getActiveSheet()->setCellValueByColumnAndRow(23, $excel_row, $row->s_city);
					$object->getActiveSheet()->setCellValueByColumnAndRow(24, $excel_row, $row->s_state);
					$object->getActiveSheet()->setCellValueByColumnAndRow(25, $excel_row, $row->s_pincode);
					$object->getActiveSheet()->setCellValueByColumnAndRow(26, $excel_row, $row->s_phone);
					$object->getActiveSheet()->setCellValueByColumnAndRow(27, $excel_row, $row->b_name);
					$object->getActiveSheet()->setCellValueByColumnAndRow(28, $excel_row, $row->b_address);
					$object->getActiveSheet()->setCellValueByColumnAndRow(29, $excel_row, $row->b_city);
					$object->getActiveSheet()->setCellValueByColumnAndRow(30, $excel_row, $row->b_state);
					$object->getActiveSheet()->setCellValueByColumnAndRow(31, $excel_row, $row->b_pincode);
					$object->getActiveSheet()->setCellValueByColumnAndRow(32, $excel_row, $row->b_phone);
					
				}	
				
				$excel_row++;
			
			$old_order_id = $v_order_id;		
			}
			$date = date('Y-m-d H:i:s');
			$fileName = "Order_report_".$date.".xls";
			$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$fileName.'"');
			$object_writer->save('php://output');
		
		
		
		
		/* echo '<pre>';
		print_r($order_data);
		echo '</pre>'; */
	
	}
	public function categories()
	{
		$this->load->model('m_admin_home');
		$array = array();
		$array['page_name'] = 'categories';
		$array['category_list'] = $this->m_admin_home->get_category();
		$this->page_load($array);
	}
	public function sub_category()
	{
		$this->load->model('m_admin_home');
		$array = array();
		$array['page_name'] = 'sub-category';
		$array['sub_category_list'] = $this->m_admin_home->get_sub_category();
		$this->page_load($array);
	}
	public function sup_sub_category()
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['page_name'] = 'sup-sub-category';
		$array['sup_sub_category_list'] = $this->m_admin_home->get_super_sub_category();
		$this->page_load($array);
	}
	public function add_new_category($category_id=0)
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['edit_category'] = $this->m_admin_home->get_category_byID($category_id);
		$array['subcategory_name'] = $this->m_admin_home->get_sub_category_byCatID($category_id);		
		$array['page_name'] = 'add-new-category';
		$this->page_load($array);
	}
	public function add_new_sub_category($sub_category_id=0)
	{
		$this->load->model('m_admin_home');
		$array = array();
		$array['page_name'] = 'add-new-sub-category';
		$array['category_list'] = $this->m_admin_home->get_category_InDorpdown();

		$array['edit_subcategory'] = $this->m_admin_home->get_subcategory_byID($sub_category_id);
		$array['super_subcategory_name'] = $this->m_admin_home->get_sup_sub_category_bysub_CatID($sub_category_id);		
		$this->page_load($array);
	}
	public function add_new_sup_sub_category($sup_sub_category_id=0)
	{
		$this->load->model('m_admin_home');
		$array = array();
		$array['page_name'] = 'add-new-sup-sub-category';
		$array['category_list'] = $this->m_admin_home->get_category_InDorpdown();
		$array['edit_super_subcategory'] = $this->m_admin_home->get_super_subcategory_byID($sup_sub_category_id);
		$this->page_load($array);
	}
	public function skin_type()
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['page_name'] = 'skin-type';
		$array['skin_type_list'] = $this->m_admin_home->get_skin_type();
		$this->page_load($array);
	}
	public function add_new_skin_type($skin_type_id=0)
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['page_name'] = 'add-new-skin-type';
		$array['edit_skin_type'] = $this->m_admin_home->get_skin_type_byID($skin_type_id);
		$this->page_load($array);
	}
	public function segments()
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['page_name'] = 'segments';
		$array['segment_list'] = $this->m_admin_home->get_segment();
		$this->page_load($array);
	}
	public function review()
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['page_name'] = 'review';
		$array['review_list'] = $this->m_admin_home->get_review();
		$this->page_load($array);
	} 
	public function update_response()
	{
		$product_review_id = $this->input->post('pr_id');
		$product_response = $this->input->post('reponse_msg');
		$product_response_id = $this->input->post('response_id');
		$this->load->model('m_admin_home');
		
		$array_update_product_response= array(
										'product_response'=>$product_response,
										'status'=>'y',
										);
		
		$response = $this->m_admin_home->update_product_response($product_response_id, $array_update_product_response);
		
		if($response=='1')
		{	$r= array();
			$r['STATUS']=1;
			$r['MSG']='done';
			echo json_encode($r);
			exit ;
		}
		else
		{
			$r= array();
			$r['STATUS']=0;
			$r['MSG']='error';
			echo json_encode($r);
			exit ;
		}
	}
	public function insert_response()
	{
		$v_userid = $this->session->userdata['admin_logged_in']['admin_user_id'];
		$v_username = $this->session->userdata['admin_logged_in']['admin_username'];
		$product_review_id = $this->input->post('pr_id');
		$product_response = $this->input->post('reponse_msg');
		
		$this->load->model('m_admin_home');
		$ip = $_SERVER['REMOTE_ADDR'];
		$array_add_to_product_response= array(
										'user_id'=>$v_userid,
										'user_name'=>$v_username,
										'product_review_id'=>$product_review_id,
										'product_response'=>$product_response,
										'status'=>'y',
										'ip'=>$ip,
										);
		
		$review_list = $this->m_admin_home->insert_product_response($array_add_to_product_response);
		if($review_list=='1')
		{	$r= array();
			$r['STATUS']=1;
			$r['MSG']='done';
			echo json_encode($r);
			exit ;
		}
		else
		{
			$r= array();
			$r['STATUS']=0;
			$r['MSG']='error';
			echo json_encode($r);
			exit ;
		}
	}
	public function review_updatestatus()
	{
		$p_action = $this->input->post('p_action');
		if($p_action=='status')
		{	
			$v_status = $this->input->post('p_status');
			$v_product_review_id = $this->input->post('p_passid');
			$status = '';
			if($v_status=='active'){$status='y';}
			if($v_status=='deactive'){$status='n';}
			$this->load->model('m_admin_home');
			$review_status = $this->m_admin_home->update_product_review($v_product_review_id,$status);		
			if($review_status=='1')
			{	$r= array();
				$r['STATUS']=1;
				$r['MSG']='done';
				echo json_encode($r);
				exit ;
			}
			else
			{
				$r= array();
				$r['STATUS']=0;
				$r['MSG']='error';
				echo json_encode($r);
				exit ;
			}
		}
	}
	public function review_displaydata()
	{
		$p_action = $this->input->post('p_action');
		if($p_action=='displaydata')
		{	
			$this->load->model('m_admin_home');
			$review_list = $this->m_admin_home->get_review();
			$v_data='';
			$v_data = '<table id="example23" class="display table-striped table table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th width="15%">Product Name</th>
											<th width="10%">User name</th>
											<th width="15%">Subject  </th>
											<th width="5%">Rating</th>
											<th width="25%">Message</th>
											<th width="25%">Replay</th>
											<th width="5%">Hide Comment</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th >Product Name</th>
											<th>User name</th>
											<th>Subject  </th>
											<th>Rating</th>
											<th>Message</th>
											<th>Replay</th>
											<th>Hide Comment</th>
										</tr>
									</tfoot>
									<tbody >';
			foreach ($review_list as  $value) 
			{
				$active = '';
				$redonly = '';
				$btn_submit='<button onclick="return fun_add_response('.$value->product_review_id.');" type="button" class="d-block pull-right btn-link small">Submit<i id="submit_loder_'.$value->product_review_id.'" class="fa fa-spinner fa-spin hide"></i></button>';
				if(!empty($value->product_response)) 
				{
					$redonly='readonly';
					$btn_submit='<span onclick="return fun_edit_responce(this,'.$value->product_review_id.');" 
							 data-responce-id='.$value->product_review_response_id.' class="d-block c-pointer pull-right btn-link small">Edit
							<i id="submit_response_'.$value->product_review_id.'" class="fa fa-spinner fa-spin hide"></i>
							</span>';
				}
				if($value->status=='y')
				{
					$active = '<a href="javascript:void(0);" onclick="fun_reviewstatus(\'deactive\','.$value->product_review_id.')">Deactive</a>';
				}
				else
				{
					$active = '<a href="javascript:void(0);" onclick="fun_reviewstatus(\'active\','.$value->product_review_id.')">Active</a>';
				}
				$v_data.='<tr>
							<td width="15%">'.$value->product_name.'</td>
							<td width="10%">'.$value->user_name.'</td>
							<td width="15%">'.$value->product_review_subject.'</td>
							<td width="5%">'.$value->product_review_rating_star.'</td>
							<td width="25%">'.$value->product_review_message.'</td>
							<td width="25%">
								<textarea '.$redonly.' style="width : 80%; height : 80px !important;" 
								id="prduct_response_'.$value->product_review_id.'" class="responce_msg">'.$value->product_response.'</textarea>
							'.$btn_submit.'
							</td>
							<td width="5%"> '.$active.'</td>
						</tr>';
			}
			$v_data .='	</tbody>
						</table>';
			$r= array();
			$r['STATUS']=1;
			$r['review_data']=$v_data;
			$r['MSG']='Done';
			echo json_encode($r);
			exit ;
		}
		else
		{
			$r= array();
			$r['STATUS']=0;
			$r['MSG']='error';
			echo json_encode($r);
			exit ;
		}
	}
	public function add_new_segment($segment_id=0)
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['page_name'] = 'add-new-segment';
		$array['edit_segment'] = $this->m_admin_home->get_segment_byID($segment_id);
		$this->page_load($array);
	} 
	public function product_form($product_form_id=0)
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['page_name'] = 'product-form';
		$array['product_form_list'] = $this->m_admin_home->get_product_form();
		$array['edit_product_form'] = $this->m_admin_home->get_product_form_byID($product_form_id);
		$this->page_load($array);
	} 
	public function recommended_usage($recommended_use_id=0)
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['page_name'] = 'recommended-usage';
		$array['recommended_use_list'] = $this->m_admin_home->get_recommended_use();
		$array['edit_recommended_use'] = $this->m_admin_home->get_recommended_use_byID($recommended_use_id);
		$this->page_load($array);
	} 
	public function brand()
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['page_name'] = 'brand';	
		$array['brand_list'] = $this->m_admin_home->get_brand();
		$array['category_list'] = $this->m_admin_home->get_category_InListView();
		$this->page_load($array);
	} 
	public function add_new_brand($brand_id='')
	{
		$this->load->model('m_admin_home');	
		$array = array();
		$array['page_name'] = 'add-new-brand';	
		$array['edit_brand'] = $this->m_admin_home->get_brand_byID($brand_id);
		$array['category_list'] = $this->m_admin_home->get_category_InListView();
		$this->page_load($array);
	} 
	public function condition()
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['page_name'] = 'condition';		
		$array['conditions_list'] = $this->m_admin_home->get_conditions();
		$this->page_load($array);
	} 
	public function add_new_condition($conditions_id=0)
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['page_name'] = 'add-new-condition';		
		$array['edit_conditions'] = $this->m_admin_home->get_conditions_byID($conditions_id);
		$this->page_load($array);
	} 
	public function question()
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['page_name'] = 'question';
		$array['question_list'] = $this->m_admin_home->get_question();		
		$this->page_load($array);
	} 
	public function add_new_question($question_id=0)
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['page_name'] = 'add-new-question';		
		$array['edit_question'] = $this->m_admin_home->get_question_byID($question_id);
		$this->page_load($array);
	}
	public function customer()
	{
		$array = array();
		ini_set('memory_limit', '-1');
		$this->load->model('m_admin_home');
		$array['page_name'] = 'customer';	
		$array['customer_list'] = $this->m_admin_home->get_customer();	
		$this->page_load($array);
	} 
	public function add_new_customer()
	{
		$array = array();
		$array['page_name'] = 'add-new-customer';		
		$this->page_load($array);
	} 
	public function customer_detail($customer_id=0)
	{
		$array = array();
		$this->load->model('m_admin_home');
		$this->load->model('m_ajax_order');
		$array['page_name'] = 'customer-detail';
		$array['customer_info'] = $this->m_admin_home->get_customer_info_byID($customer_id);		
		$array['customer_address_info'] = $this->m_admin_home->get_customer_address_byID($customer_id);	
		$array['order_detail'] = $this->m_ajax_order->get_user_order_history($customer_id);	
		$this->page_load($array); 
	} 
	public function add_new_product($product_id=0)
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['page_name'] = 'add-new-product';
		$array['category_list'] = $this->m_admin_home->get_category_InDorpdown();		
		$array['product_form_list'] = $this->m_admin_home->get_product_form_InDorpdown();		
		$result = $this->m_admin_home->get_brand_InListView();	

		$brand_id = array('');
		$brand_name = array('--Select brand--');
		foreach ($result as $value) {
			array_push($brand_id,$value->brand_id);
			array_push($brand_name,$value->brand_name);		
		}
		$array['brand_list'] = array_combine($brand_id, $brand_name);

		$array['skin_type_list'] = $this->m_admin_home->get_skin_type_inProduct();	
		$array['conditions_list'] = $this->m_admin_home->get_conditions_inProduct();	
		$array['segment_list'] = $this->m_admin_home->get_segment_inProduct();	
		$array['recommended_usage_list'] = $this->m_admin_home->get_recommended_usage_inProduct();
		$array['edit_product'] = $this->m_admin_home->get_product_byID($product_id);	 
		$array['edit_product_variant'] = $this->m_admin_home->get_product_variant_byID($product_id);	 

		$this->page_load($array);
	} 
	public function products()
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['page_name'] = 'products';	
		$array['brand_list'] = $this->m_admin_home->get_brand_InListView();	
		
		$this->page_load($array);
	}
	public function products_custome_update() // cusome product data update
	{
		// $this->load->model('m_admin_home');
		// $i=0;
		// $product_id = $this->m_admin_home->get_produc_id();
		// $pro_id='';
		// if(!empty($product_id))
		// {
			// foreach($product_id as $p_id)
			// {
				// $pro_id = $p_id->product_id;
				// $model_no = $p_id->model_no;
				// $product_url = strtolower(str_replace(' ','-',$p_id->product_url));
				
				
				// $array_update = array(
									// 'product_url'=>$product_url,
								// );
				// $this->m_admin_home->update_product_custome($pro_id,$model_no, $array_update);
				// $i++;
			// }			
		// }
		
			echo $i.'done';
			exit ;
	}
	public function update_order_product()
	{
		$this->load->model('m_admin_home');
		$prod_data = $this->m_admin_home->get_order_pro_data();
		
		foreach($prod_data as $pro_data)
		{
			// echo '</br> model_no :'.$model_no = $pro_data->product_model_no ;
			// echo '</br> order_product_id :'.$op_id =  $pro_data->order_product_id ;
			// echo '</br> product_id :'.$pro_id = $pro_data->product_id ;
			
			if(!empty($pro_id))
			{
				$update_arr = array(
								   'product_id'=>$pro_id,
								   );
				// $this->m_admin_home->update_op_data($update_arr, $op_id, $model_no);
			}
		}
		echo 'done';
		exit ;
	}
	public function products_upload()
	{
		$this->load->model('m_admin_home');
		$this->load->library('excel');
		ini_set('memory_limit', '-1');
		$p_action = $this->input->post('p_action');
		// echo $this->input->post('file_pro_excel');
		$input_file = $_FILES['file_pro_excel']['name'];
		$ext = pathinfo($input_file,PATHINFO_EXTENSION);
		$product_excel = '';
		$this->load->library('upload');
		$date = date('dmyHis',time());
		$config['upload_path'] = './assets/excel/product_upload/';
		$config['allowed_types'] = 'xlsx|xls';
		$config['remove_spaces'] = TRUE;
		$config['file_name'] = 'Product_upload_'.$date.'.'.$ext;
		$this->upload->initialize($config);
		if($this->upload->do_upload('file_pro_excel'))
		{
			$upload_data = $this->upload->data();
			$product_excel= $upload_data['file_name'];
		}
		
		$inputFileName = './assets/excel/product_upload/'. $product_excel;
		PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
		// $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
		// $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		// var_dump($sheetData);
		
		try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch (Exception $e) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                        . '": ' . $e->getMessage());
            }
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
		$arrayCount = count($allDataInSheet);
		$flag = 0;
		
		// $createArray = array('First_Name', 'Last_Name', 'Email', 'DOB', 'Contact_NO');
		// $makeArray = array('First_Name' => 'First_Name', 'Last_Name' => 'Last_Name', 'Email' => 'Email', 'DOB' => 'DOB', 'Contact_NO' => 'Contact_NO');
		
		$i=0;
		$j = $k = 0;
		$SheetDataKey = array();
		foreach ($allDataInSheet as $dataInSheet) 
		{
			// print_r($dataInSheet);
			// exit ;// $dataInSheet['A'];
		if($p_action=='update_customer')
		{
			if($dataInSheet['A']!='email')
			{
				$email = $dataInSheet['A'] ; // order_id
				$address = $dataInSheet['B'] ; // c_id
				$c_id ='';
				$p_email=$this->m_admin_home->validate_customer_email($email);
				if(!empty($p_email))
				{
					foreach($p_email as $customer)
					{
						$c_id = $customer->customer_id ; 
					}
					$array_update_customer_address = array(
										   'address'=>$address,
										   );
					
					// $this->m_admin_home->update_address($c_id,$array_update_customer_address);
				}
				else 
				{
					echo '</br>'.$i ;
				}
			}
		}
		else if($p_action=='order_data')
		{
			if($i<3000)
			{
			if($dataInSheet['A']!='orders_id')
			{
				$order_id = $dataInSheet['A'] ; // order_id
				$cu_id = $dataInSheet['B'] ; // c_id
				$c_name = $dataInSheet['C'] ; // c_name
				$c_email = $dataInSheet['D'] ; // customers_email_address
				$ship_email = $dataInSheet['E'] ; // shipping_email
				$ship_name = $dataInSheet['F'] ; // ship_name
				$ship_add_1 = $dataInSheet['G'] ; // ship_add-1
				$ship_add_2 = $dataInSheet['H'] ; // ship_add-2
				$ship_city = $dataInSheet['I'] ; // delivery_city
				$ship_state = $dataInSheet['J'] ; // delivery_state
				$ship_pincode = $dataInSheet['K'] ; // delivery_pincode
				$ship_country = $dataInSheet['L'] ; // delivery_country
				$ship_phone = $dataInSheet['M'] ; // delivery_phone
				$bill_email = $dataInSheet['N'] ; // bill_email
				$bill_name = $dataInSheet['O'] ; // bill_name
				$bill_add_1 = $dataInSheet['P'] ; // bill_add_1
				$bill_add_2 = $dataInSheet['Q'] ; // bill_add_2
				$bill_city = $dataInSheet['R'] ; // bill_city
				$bill_state = $dataInSheet['S'] ; // bill_state
				$bill_pincode = $dataInSheet['T'] ; // bill_pincode
				$bill_country = $dataInSheet['U'] ; // bill_country
				$bill_phone = $dataInSheet['V'] ; // bill_phone
				$payment_method = $dataInSheet['W'] ; // payment_method
				$last_modified = $dataInSheet['X'] ; // last_modified
				$date_purchase = $dataInSheet['Y'] ; // date_purchase
				$order_status = $dataInSheet['Z'] ; // order_status
				$order_sub_tot = $dataInSheet['AA'] ; // order_sub_tot
				$order_total = $dataInSheet['AB'] ; // order_total
				$order_comment = $dataInSheet['AC'] ; // order_comment
				$orders_currency = $dataInSheet['AD'] ; // orders_currency
				$discount_coupon = $dataInSheet['AE'] ; // discount_coupon
				$discount_perc = $dataInSheet['AF'] ; // discount_perc
				$shipping = $dataInSheet['AG'] ; // shipping
				$gift_voucher_code = $dataInSheet['AH'] ; // gift_voucher_code
				$gift_voucher_amount = $dataInSheet['AI'] ; // gift_voucher_amount
				$payment_date = $dataInSheet['AJ'] ; // payment_date
				$company_id = $dataInSheet['AK'] ; // company_id
				$docket_no = $dataInSheet['AL'] ; // docket_no
				$track_url = $dataInSheet['AM'] ; // track_url
				$paymentId = $dataInSheet['AN'] ; // paymentId
				$ipaddress = $dataInSheet['AO'] ; // ipaddress
				
				$p_email='';
				$c_id = '';
				if(!empty($c_email))
				{
					$p_email=$this->m_admin_home->validate_customer_email($c_email);
					if(!empty($p_email))
					{
						foreach($p_email as $customer)
						{
							$c_id = $customer->customer_id ;
							$c_name = $customer->customer_name ;
						}
					}
					else
					{
						if(empty($c_name)){
							$c_name = $ship_name;
						}
						$customer_type='guest';
						$array_insert_customer = array(
										   'customer_name'=>$c_name,
										   'customer_type'=>$customer_type,
										   'email'=>$c_email,
										   'phone'=>'',
										   'oauth_provider'=>'',
										   'dob'=>'',
										   'password'=>'',
										   'profile_image'=>'',
										   'gender'=>'',
										   'default_address_id'=>'',
										   'created_by'=>'self',
										   'ent_datetime'=>$date_purchase,
										   'modified_datetime'=>$last_modified,
										   'hash_string'=>'',
										   'status'=>'y',
										);
						$c_id = $this->m_admin_home->insert_customer($array_insert_customer);
					}
				}
				$ship_id = '';
				if(!empty($ship_pincode))
				{
					$ship_ad = $ship_add_1.' '.$ship_add_2 ;
					$shipp_address=$this->m_admin_home->get_shipp_address_id($ship_name, $ship_ad, $ship_pincode, $c_id, $ship_phone);
					if(!empty($shipp_address))
					{
						foreach($shipp_address as $a_id)
						{
							$ship_id = $a_id->address_book_id ;
						}
						// echo '</br>'.$ship_email.'-'.$ship_id ;
					}
					else
					{
						$address = $ship_add_1.' '.$ship_add_2 ;
						echo '</br> ship-'.$j++;
						$address_type='home';
						$array_insert_customer_address = array(
										   'customer_id'=>$c_id,
										   'name'=>$ship_name,
										   'phone'=>$ship_phone,
										   'pincode'=>$ship_pincode,
										   'locality'=>'',
										   'city'=>$ship_city,
										   'state'=>$ship_state,
										   'country'=>'India',
										   'address'=>$address,
										   'address_type'=>$address_type,
										   'ent_datetime'=>$date_purchase
										);
						if(!empty($c_id) && empty($ship_id))
						{
							$ship_id = $this->m_admin_home->insert_customer_address($array_insert_customer_address);
						}
					}
					
				}
				$bill_id ='';
				if(!empty($bill_pincode))
				{
					$bill_ad = $bill_add_1.' '.$bill_add_2 ;
					$bill_address=$this->m_admin_home->get_shipp_address_id($bill_name, $bill_ad, $bill_pincode, $c_id, $bill_phone);
					if(!empty($bill_address))
					{
						foreach($bill_address as $a_id)
						{
							$bill_id = $a_id->address_book_id ;
						}
					}
					else
					{
						echo '</br>'.$c_id.' bill-'.$k++;
						if(empty($bill_country))
						{
							$bill_country = 'India';
						}
						
						$address = $bill_add_1.' '.$bill_add_2 ;
						$address_type='home';
						$array_insert_bill_address = array(
										   'customer_id'=>$c_id,
										   'name'=>$bill_name,
										   'phone'=>$bill_phone,
										   'pincode'=>$bill_pincode,
										   'locality'=>'',
										   'city'=>$bill_city,
										   'state'=>$bill_state,
										   'country'=>$bill_country,
										   'address'=>$address,
										   'address_type'=>$address_type,
										   'ent_datetime'=>$date_purchase
										);
						if(!empty($c_id) && empty($bill_id))
						{
							 $bill_id = $this->m_admin_home->insert_customer_address($array_insert_bill_address);
						}
					}
					// echo '</br>address_id' ;
					// print_r($shipp_address);
				}
				
				
				$order_tatus_array=array(
								  'Pending'=>'1',
								  'Approved'=>'2',
								  'Canceled'=>'3',
								  'Failure'=>'4',
								  'Packed'=>'5',
								  'Shipped'=>'6'
									); 
									
				$order_status = $order_tatus_array[$order_status] ;
				
				
				
				$order_data = array(
									   'order_id'=>$order_id,
									   'customer_id'=>$c_id,
									   'address_book_id'=>$ship_id,
									   'billing_address_id'=>$bill_id,
									   'payment_method'=>$payment_method,
									   'status_id'=>$order_status,
									   'purchase_date'=>$date_purchase,
									   'order_status_updated_date'=>$last_modified,
									   'payment_date'=>$payment_date,
									   'payment_id'=>$paymentId,
									   'coupon_id'=>$discount_coupon,
									   'coupon_amount'=>$discount_perc,
									   'gift_voucher_id'=>$gift_voucher_code,
									   'gift_voucher_amount'=>$gift_voucher_amount,
									   'reward_point'=>'',
									   'reward_point_amount'=>'',
									   'total_amount'=>$order_sub_tot,
									   'shipping_charge'=>$shipping,
									   'order_total_amount'=>$order_total,
									   'courier_company_id'=>$company_id,
									   'docket_no'=>$docket_no,
									   'trakking_url'=>$track_url,
									   'ent_datetime'=>$date_purchase,
									   'ip'=>$ipaddress,
									   'browser'=>'',
									   'device'=>''
									);
				// echo '</br>';
				// print_r($order_data);
				
				$order = $this->m_admin_home->inser_order_from_excel($order_data);
			}
			
			}
			
			// echo '</br> customer - '.$j ;
			// echo 'gest - '.$k ;
		}
		else if($p_action=='insert_customer')
		{
			if($dataInSheet['A']!='full_name')
			{
				$customer_name = $dataInSheet['A'] ; // full name/ customer_name registered_user
				$customer_name = preg_replace('/\\s+/',' ', $customer_name);
				$email = $dataInSheet['B'] ; // email
				$password = $dataInSheet['C'] ; // password
				$status = $dataInSheet['D'] ; // status
				$customer_ent_date = $dataInSheet['E'] ; // add datetime
				$customer_upd = $dataInSheet['F'] ; // update datetime
				$mobile = $dataInSheet['G'] ; // mobile
				$add_name = $dataInSheet['H'] ; // address name 
				$address = $dataInSheet['I'] ; // address 
				$pincode = $dataInSheet['J'] ; // pincode 
				$add_phone = $dataInSheet['K'] ; // add_phone 
				$city = $dataInSheet['L'] ; // add city 
				$state = $dataInSheet['M'] ; // add state 
				$country = $dataInSheet['N'] ; // add country 
				
				if($status==1)
				{
					$status = 'y';
				}else
				{
					$status = 'n';
				}
				
				$v_userid = $this->session->userdata['admin_logged_in']['admin_user_id'];
				$customer_type = 'registered_user';
				$customer_id = '';
				$p_email = '';
				if(!empty($email))
				{
					$p_email=$this->m_admin_home->validate_customer_email($email);
					if(!empty($p_email))
					{
						// print_r($p_email);
						// exit ;
					}
				}
			if(!empty($email))
			{
				$array_insert_customer = array(
										   'customer_name'=>$customer_name,
										   'customer_type'=>$customer_type,
										   'email'=>$email,
										   'phone'=>$mobile,
										   'oauth_provider'=>'',
										   'dob'=>'',
										   'password'=>$password,
										   'profile_image'=>'',
										   'gender'=>'',
										   'default_address_id'=>'',
										   'created_by'=>'self',
										   'ent_datetime'=>$customer_ent_date,
										   'modified_datetime'=>$customer_upd,
										   'hash_string'=>'',
										   'status'=>$status,
										   'default_address_id'=>'',
										);
										
				$customer_id = $this->m_admin_home->insert_customer($array_insert_customer);
				$address_type = 'home';
				$add_data = $this->m_admin_home->get_country($pincode);
				$locality = '';
				if(!empty($add_data))
				{
					foreach($add_data as $addr)
					{
						$locality = $addr->post_office_name ;
						$city = $addr->district ;
						$state = $addr->state ;
						$country = $addr->country ;
					}
				}
				
				if(empty(trim($add_name)))
				{
					$add_name = $customer_name;
				}
				$array_insert_customer_address = array(
										   'customer_id'=>$customer_id,
										   'name'=>$add_name,
										   'phone'=>$add_phone,
										   'pincode'=>$pincode,
										   'locality'=>$locality,
										   'city'=>$city,
										   'state'=>$state,
										   'country'=>$country,
										   'address'=>$address,
										   'address_type'=>$address_type,
										   'ent_datetime'=>$customer_ent_date
										);
				if(!empty($country) && !empty($customer_id))
				{
					$this->m_admin_home->insert_customer_address($array_insert_customer_address);
				}
				// exit ;
			}
			
			}
		}
		else if($p_action=='update_custome_product')
		{			
			if($dataInSheet['A']!='SKU')
			{
				$model_no = $dataInSheet['A'] ; // model_no
				$page_title = $dataInSheet['B'] ; // page title
				$page_desc = $dataInSheet['C'] ; // page_desc
				$page_keyword  = $dataInSheet['D'] ; // keywords
				$skin_type = $dataInSheet['E'] ; // SKIN TYPE
				$condition = $dataInSheet['F'] ; // CONDTIONS
				$p_skin_id = '';
				$p_condition_id = '';
				
				if(!empty($skin_type))
				{
					$skin_type = explode('-',$skin_type);
					$p_skin_type=$this->m_admin_home->get_product_skin_type($skin_type);
					if(!empty($p_skin_type))
					{	
						$j=0 ;
						foreach($p_skin_type as $id)
						{
							if($j==0)
							{
								$p_skin_id = $id->skin_type_id;
							}
							else
							{
								$p_skin_id .= ', '.$id->skin_type_id;
							}
							$j++;
						}
						// echo $p_skin_id.'</br>' ;
					}
				}
				if(!empty($condition)) // get condition id for product  conditions_id
				{
					$condition = explode('-',$condition);
					$p_condition=$this->m_admin_home->get_product_condition($condition);
					if(!empty($p_condition))
					{
						$j=0 ;
						foreach($p_condition as $id)
						{
							if($j==0)
							{
								$p_condition_id = $id->conditions_id;
							}
							else
							{
								$p_condition_id .= ', '.$id->conditions_id;
							}
							// $p_condition_id.'</br>';
							$j++;
						}
					}
					
					$array_update = array(
											'skin_type_id'=>$p_skin_id,
											'conditions_id'=>$p_condition_id,												
											'page_title'=>$page_title,	
											'page_description'=>$page_desc,	
											'page_keyword'=>$page_keyword
											);
					$product_id = $this->m_admin_home->get_produc_id($model_no);
					$pro_id='';
					foreach($product_id as $p_id)
					{
						$pro_id = $p_id->product_id;
					}
					
					if(!empty($pro_id))
					{
						$this->m_admin_home->update_product_custome($pro_id,$model_no,$array_update);
					}
				}
			}
		}
		else if($p_action=='update_brand')
		{
			if($dataInSheet['A']!='BRAND NAME')
			{
			$brand_name = $dataInSheet['A'] ; // BRAND NAME
			$brand_url = $dataInSheet['B'] ; // brand URL
			$brand_desc = $dataInSheet['C'] ; // brand DESCRIPTION
			$brand_status = $dataInSheet['D'] ; // brand STATUS
			$brand_page_title = $dataInSheet['E'] ; // brand PAGE TITLE
			$brand_page_desc = $dataInSheet['F'] ; // brand PAGE DESC
			$brand_page_keyword = $dataInSheet['G'] ; // brand PAGE keywords
			$b_id = '';$brand_id = '';
			$b_id = $this->m_admin_home->get_brand_id($brand_name);
			
			if(!empty($b_id)){	
					foreach($b_id as $id); 
					echo '</br>-'.$brand_url.$brand_id =$id->id;
				}
				// echo $brand_url.'</br>' ;
				$array_update = array(
									'brand_description'=>$brand_desc,
									'brand_url'=>$brand_url,
									'page_title'=>$brand_page_title,
									'page_description'=>$brand_page_desc,
									'page_keyword'=>$brand_page_keyword
									);
									
			if(!empty($brand_id))
			{
				// $this->m_admin_home->update_brand($brand_name, $brand_id, $array_update);
			}
			}
		}
		else if($p_action=='update_img')
		{
			if($dataInSheet['A']!='SKU')
			{
				$model_no = $dataInSheet['A'] ; // sku (product model no)
				$p_url = $dataInSheet['B'] ; // product_url
				$p_img_url = $dataInSheet['C'] ; // product image url
				$product_id = '';
				$product_id = $this->m_admin_home->get_produc_id($model_no);
				$pro_id='';
				$pm='';
				
				foreach($product_id as $p_id)
				{
					$pro_id = $p_id->product_id;
					$product_name = $p_id->product_name;
					$pm = $p_id->product_image;
				}
				
				$product_name = preg_replace('/\\s+/',' ', $product_name);
				$img_url = strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/',' ', $product_name));
				$img_url = preg_replace('/\\s+/', '_', $img_url);
				$date = date('dmyHis',time());
				// echo $model_no ;
				$ext_main = pathinfo($p_img_url,PATHINFO_EXTENSION);
				$img_name  = $img_url.'_main_'.$date.'.'.$ext_main;
				
				$array_update = array(
									'product_image'=>$img_name,
									'product_url'=>trim($p_url),
									);
				if($model_no=='WEL0005')
				{
					// echo $i.'-'.$pro_id.'-'.$img_name.'</br>' ;
					// copy($p_img_url, './assets/images/product/'.$img_name);
					// $this->m_admin_home->update_product_custome($pro_id,$model_no,$array_update);
				}
			}
		}
		else if($p_action=='update_category')
		{
			if($dataInSheet['A']!='SKU')
			{
				$model_no = $dataInSheet['A'] ; // sku (product model no)
				$category = $dataInSheet['B'] ; // Category name
				$sub_category = $dataInSheet['C'] ; // SUB CATEGORY name
				$sup_sub_category = $dataInSheet['D'] ; // SUPER SUB CATEGORY name
				
				$v_userid = $this->session->userdata['admin_logged_in']['admin_user_id'];
				$p_cat_id = '';
				$p_sub_cat_id = '';
				$p_sup_sub_cat_id = '';
				
				if(!empty($category)) // get category id for product data
				{
					$pcategory=$this->m_admin_home->get_product_category_id($category);
					if(!empty($pcategory))
					{
						foreach($pcategory as $id); 
						$p_cat_id =$id->category_id;
					}else
					{
						// echo '<br/>'.$product_form;
						$p_category = array(
										'category_name'=>$category,
										'category_description'=>$category,
										'category_url'=>strtolower(str_replace(' ','-',$category)),
										'page_title'=>$category,
										'status'=>'y',
										'ent_user_id'=>$v_userid,
										);
						// $this->m_admin_home->insert_product_category($p_category); 
					}
				}
				
				if(!empty($sub_category)) // get sub category id for product data
				{
					$psubcategory=$this->m_admin_home->get_product_sub_category_id($sub_category, $p_cat_id);
					if(!empty($psubcategory))
					{
						foreach($psubcategory as $id); 
						$p_sub_cat_id =$id->sub_category_id;
					}else
					{
						// echo '<br/>'.$product_form;
						$p_sub_category = array(
										'sub_category_name'=>$sub_category,
										'category_id'=>$p_cat_id,
										'sub_category_description'=>$sub_category,
										'sub_category_url'=>strtolower(str_replace(' ','-',$sub_category)),
										'page_title'=>$sub_category,
										'status'=>'y',
										'ent_user_id'=>$v_userid,
										);
						// $this->m_admin_home->insert_product_sub_category($p_sub_category); 
					}
				}
				if(!empty($sup_sub_category)) // get sub category id for product data
				{
					$sup_sub_category =strtolower($sup_sub_category);
					$psupsubcategory=$this->m_admin_home->get_product_sup_sub_category_id($sup_sub_category,$p_sub_cat_id);

					if(!empty($psupsubcategory))
					{
						foreach($psupsubcategory as $id); 
						$p_sup_sub_cat_id =$id->super_sub_category_id;
					}else
					{
						 // echo '<br/> super sub cate'.$sup_sub_category;
						$p_sup_sub_category = array(
										'super_sub_category_name'=>$sup_sub_category,
										'sub_category_id'=>$p_sub_cat_id,
										'super_sub_category_description'=>$sup_sub_category,
										'super_sub_category_url'=>strtolower(str_replace(' ','-',$sup_sub_category)),
										'page_title'=>$sup_sub_category,
										'status'=>'y',
										'ent_user_id'=>$v_userid,
										);
						//$this->m_admin_home->insert_product_sup_sub_category($p_sup_sub_category); 
					}
				}
				// echo $model_no ;
				$product_id = $this->m_admin_home->get_produc_id($model_no);
				// print_r($product_id);
				// exit ;
				$pro_id='';
				foreach($product_id as $p_id)
				{
					$pro_id = $p_id->product_id;
				}
				
				$array_update = array(
									'category_id'=>$p_cat_id,
									'sub_category_id'=>$p_sub_cat_id,
									'super_sub_category_id'=>$p_sup_sub_cat_id,
									);
				if(!empty($p_cat_id) && !empty($p_sub_cat_id) && !empty($p_sup_sub_cat_id) && !empty($pro_id))
				{
					// $this->m_admin_home->update_product_custome($pro_id,$model_no,$array_update);
				}
				else
				{
					echo 'error';
				}
			}
		}
		else if($p_action=='update_product')
		{
			if($dataInSheet['A']!='SKU')
			{
				$model_no = $dataInSheet['A'] ; // sku (product model no)
				$barcode = $dataInSheet['B'] ; // barcode
				$product_name = $dataInSheet['C'] ; // product Title
				$brand_name = $dataInSheet['D'] ; // Brand name
				$category = $dataInSheet['E'] ; // Category name
				$product_form = $dataInSheet['F'] ; // Product Form 
				$product_price = $dataInSheet['G'] ;  // product Price
				$product_mrp = $dataInSheet['H'] ;  // product MRP
				$product_weight = $dataInSheet['I'] ;  // product Weight
				$product_weight_volume = $dataInSheet['J'] ;  // product Weight
				$hsn_sac = $dataInSheet['K'] ;  // product HSN / SAC
				$gst = $dataInSheet['L'] ;  // product GST
				$unit = $dataInSheet['M'] ; // product Unit in Stock
				$reorder_limit = $dataInSheet['N'] ; // product Reorder Limit
				$skin_type = $dataInSheet['O'] ;  // product Skin Types array with - saprated
				$condition = $dataInSheet['P'] ; // product Treatment(condition name) array with - saprated
				$sagment = $dataInSheet['Q'] ; // product Suited for array with - saprated
				$agg_grop = $dataInSheet['R'] ; // product Age Group array with - saprated
				$recommended_usage = $dataInSheet['S'] ; // product Recommendade array with - saprated
				$other_feacher = $dataInSheet['T'] ;  // product Extra Feautures array with - saprated
				$spf_factor = $dataInSheet['U'] ; // product SPF Factor
				$derma_tips = $dataInSheet['V'] ; // product Dermatologist Tips
				$overview = $dataInSheet['W'] ; // product Overview
				$product_description = $dataInSheet['X'] ;  // product Description
				$how_to_use = $dataInSheet['Y'] ;  // product How to Use array with - saprated
				$main_img_url = $dataInSheet['Z'] ; // product Main Image url
				$img_url_1 = $dataInSheet['AA'] ;  // product Image 1 url
				$img_url_2 = $dataInSheet['AB'] ;  // product Image 2 url
				$img_url_3 = $dataInSheet['AC'] ;  // product Image 3 url
				$img_url_4 = $dataInSheet['AD'] ;  // product Image 4 url
				$video_url_5 = $dataInSheet['AE'] ;  // product video 5 url
				
				
				$product_url='';
				$brand_id='';
				$p_form_id='';
				$p_skin_id='';
				$p_condition_id='';
				$p_sagments_id='';
				$status = 'n';
				$page_title=$product_name ;
				$date = date('Y-m-d H:i:s');
				if(empty($gst)){ $gst=0; }
				$v_userid = $this->session->userdata['admin_logged_in']['admin_user_id'];
				if(!empty($product_name)){ $product_url=strtolower(str_replace(' ','-',$product_name)); }
				if(!empty($brand_name)) // get brand id for product data
				{
					$b_id=$this->m_admin_home->get_brand_id($brand_name);
					if(!empty($b_id)){	
						foreach($b_id as $id); 
						$brand_id =$id->brand_id;
					}
				}
				
				if(!empty($product_form)) // get brand id for product data
				{
					$pformid_=$this->m_admin_home->get_product_form_id($product_form);
					if(!empty($pformid_))
					{
						foreach($pformid_ as $id); 
						$p_form_id =$id->form_id;
					}else
					{
						// echo '<br/>'.$product_form;
						$p_form = array(
										'form_name'=>$product_form,
										'form_description'=>$product_form,
										'form_url'=>strtolower(str_replace(' ','-',$product_form)),
										'page_title'=>$product_form,
										'status'=>'y',
										'ent_user_id'=>$v_userid,
										);
						$this->m_admin_home->insert_product_form($p_form); 
					}
				}
				if(!empty($skin_type)) // get brand id for product skin-type
				{
					$skin_type = explode('-',$skin_type);
					$p_skin_type=$this->m_admin_home->get_product_skin_type($skin_type);
					if(!empty($p_skin_type))
					{	// print_r($p_skin_type);
						$j=0 ;
						foreach($p_skin_type as $id)
						{
							if($j==0)
							{
								$p_skin_id = $id->skin_type_id;
							}
							else
							{
								$p_skin_id .= ', '.$id->skin_type_id;
							}
							$j++;
						}
						// echo $p_skin_id ;exit ;
					}
				}
				if(!empty($condition)) // get condition id for product  conditions_id
				{
					$condition = explode('-',$condition);
					$p_condition=$this->m_admin_home->get_product_condition($condition);
					if(!empty($p_condition))
					{
						$j=0 ;
						foreach($p_condition as $id)
						{
							if($j==0)
							{
								$p_condition_id = $id->conditions_id;
							}
							else
							{
								$p_condition_id .= ', '.$id->conditions_id;
							}
							$j++;
						}
					}
				}
				if(!empty($sagment)) // get Suited for for product data
				{
					$sagment = explode('-',$sagment);
					$p_sagments=$this->m_admin_home->get_product_sagment($sagment);
					if(!empty($p_sagments))
					{
						$j=0 ;
						foreach($p_sagments as $id)
						{
							if($j==0)
							{
								$p_sagments_id = $id->segment_id;
							}
							else
							{
								$p_sagments_id .= ', '.$id->segment_id;
							}
							$j++;
						}
					}
				}
				
				
				$variant_option_1='';$variant_option_2='';$variant_option_3='';
				$arr_variant_val_1='';$arr_variant_val_2='';$arr_variant_val_3='';
				$array_variant_1 = array(
									   'variant_option_1'=>$variant_option_1,
									   'variant_value_1'=>$arr_variant_val_1
										);
				
				$array_variant_2 = array(
									   'variant_option_2'=>$variant_option_2,
									   'variant_value_2'=>$arr_variant_val_2
										);
				$array_variant_3 = array(
									   'variant_option_3'=>$variant_option_3,
									   'variant_value_3'=>$arr_variant_val_3
										);
				$array_variant = array_merge($array_variant_1,$array_variant_2,$array_variant_3);	
				
				if($array_variant != null) 
				{	
					$array_variant_name = json_encode($array_variant);   
				}
				
				$array_insert_product = array(
										  'model_no'=>$model_no,	
										  'product_barcode'=>$barcode,	
										  'product_name'=>$product_name,	
										  'product_url'=>$product_url,	
										  'product_description'=>$product_name,	
										  'category_id'=>'',	
										  'sub_category_id'=>'',	
										  'super_sub_category_id'=>'',	
										  'product_form_id'=>$p_form_id,	
										  'brand_id'=>$brand_id,	
										  'product_price'=>$product_price,	
										  'product_mrp'=>$product_mrp,	
										  'discount'=>'0',	
										  'weight'=>$product_weight,	
										  'volume'=>$product_weight_volume,	
										  'hsn_sac'=>$hsn_sac,	
										  'product_gst'=>$gst,	
										  'unit'=>$unit,	
										  'reorder_limit'=>$reorder_limit,	
										  'skin_type_id'=>$p_skin_id,
										  'conditions_id'=>$p_condition_id,	
										  'segment_id'=>$p_sagments_id,	
										  'age_group'=>$agg_grop,	
										  'recommended_usage_note'=>$recommended_usage,	
										  'other_features'=>$other_feacher,	
										  'spf_factor'=>$spf_factor,	
										  'dermatologist_tip'=>$derma_tips,	
										  'refill_frequency'=>'0',	
										  'product_image'=>$main_img_url,	
										  'product_sub_image'=>'',
										  'featured'=>'0',	
										  'recommended_product'=>'0',	
										  'product_review'=>'1',	
										  'product_overview'=>'',	
										  'how_to_use'=>'',	
										  'variant_name'=>$array_variant_name,		
										  'date_disabled'=>'',
										  'expiry_date'=>'',	
										  'status'=>$status,	
										  'questionnaire_product'=>'',	
										  'page_title'=>$page_title,	
										  'page_description'=>'',	
										  'page_keyword'=>'',	
										  'ent_datetime'=>$date,	
										  'ent_user_id'=>$v_userid	
											);
				
				// $this->m_admin_home->insert_product($array_insert_product);
			}
		}
		else
		{
			echo 'thank you'.'</br>';
		}
			$i++;
		}
		echo 'done' ;
		exit ;
		redirect(base_url('siteadmin/products'));
		// echo $product_excel ;
		// echo 'jaydeep';
		// exit ;
		// $array = array();
		// $array['page_name'] = 'products';	
		// $array['brand_list'] = $this->m_admin_home->get_brand_InListView();	
		// $this->page_load($array);
	}
	public function coupons()
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['page_name'] = 'coupons';	
		$array['coupon_list'] = $this->m_admin_home->get_coupon();
		$this->page_load($array);
	}
	public function add_new_coupon($coupon_id=0)
	{
		$this->load->model('m_admin_home');
		$array = array();
		$array['page_name'] = 'add-new-coupon';	
		$array['brand_list'] = $this->m_admin_home->get_brand_InListView();	
		$array['product_list'] = $this->m_admin_home->get_product_InListView();	
		$array['category_list'] = $this->m_admin_home->get_category_InListView();	
		$array['customer_list'] = $this->m_admin_home->get_user_InListView();	
		$array['edit_coupon'] = $this->m_admin_home->get_couponByID($coupon_id);
		$this->page_load($array);
	}	
		public function gift_voucher()
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['page_name'] = 'gift-voucher';	
		$array['gift_voucher_list'] = $this->m_admin_home->get_gift_voucher();
		$this->page_load($array);
	}
	public function add_new_gift_voucher($gift_voucher_id=0)
	{
		$this->load->model('m_admin_home');
		$array = array();
		$array['page_name'] = 'add-new-gift-voucher';	
		$array['edit_gift_voucher'] = $this->m_admin_home->get_gift_voucher_byID($gift_voucher_id);
		$this->page_load($array);
	}
	public function blog_category($blog_category_id=0)
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['page_name'] = 'blog-category';	
		$array['blog_category_list'] = $this->m_admin_home->get_blog_category();
		$array['edit_blog_category'] = $this->m_admin_home->get_blog_category_byID($blog_category_id);
		$this->page_load($array);
	}
	public function post()
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['page_name'] = 'post';	
		$array['blog_list'] = $this->m_admin_home->get_blog();
		$this->page_load($array);
	}
	public function add_new_post($blog_id=0)
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['page_name'] = 'add-new-post';
		$result = $this->m_admin_home->get_blog_category();	
		$array['edit_blog'] = $this->m_admin_home->get_blog_byID($blog_id);	
		
		$blog_category_id = array('');
		$blog_category_name = array('--Select category--');
		foreach ($result as $value) {
			array_push($blog_category_id ,$value->blog_category_id);
			array_push($blog_category_name,$value->blog_category_name);		
		}
		array_push($blog_category_id ,'other');
		array_push($blog_category_name,'Other');	
		$array['blog_category_list'] = array_combine($blog_category_id, $blog_category_name);

		$this->page_load($array);
	}	
	public function change_password()
	{
		$array = array();
		$array['page_name'] = 'change-password';
		$this->page_load($array);
	}
	public function newsletter()
	{
		$array = array();
		$this->load->model('m_admin_home');
		$array['page_name'] = 'newsletter';
		$array['subscriber_list'] = $this->m_admin_home->get_newsletter_data();
		$this->page_load($array);
	}
	public function logout()
	{
		$this->load->model('m_admin_home');
		$admin_id = $this->session->userdata['admin_logged_in']['admin_user_id'];	  // pick form session 
		$admin_history_id = $this->session->userdata['admin_logged_in']['admin_history_id'] ; // pick form session 
		$result = $this->m_admin_home->update_admin_login_history($admin_id,$admin_history_id);
		$this->session->unset_userdata('admin_logged_in');
		redirect('siteadmin');
	}
	
	public function lock_screen()
	{
		$this->load->model('m_admin_home');
		
		if(isset($this->session->userdata['admin_logged_in']))
		{	
			$admin_id = $this->session->userdata['admin_logged_in']['admin_user_id'];	  // pick form session 
			$admin_history_id = $this->session->userdata['admin_logged_in']['admin_history_id'] ; // pick form session 
		
		 	$result['admin_username'] =  $this->m_admin_home->update_admin_login_history($admin_id,$admin_history_id);
			$this->session->unset_userdata('admin_logged_in');
			$this->load->view('admin/lock-screen',$result);
		}
		else
		{
			redirect('siteadmin');
		}
		
	}
	
	public function compress($source, $destination, $quality)
	{

		$info = getimagesize($source);

		if ($info['mime'] == 'image/jpeg') 
			$image = imagecreatefromjpeg($source);

		elseif ($info['mime'] == 'image/gif') 
			$image = imagecreatefromgif($source);

		elseif ($info['mime'] == 'image/png') 
			$image = imagecreatefrompng($source);

		imagejpeg($image, $destination, $quality);

		return $destination;
	}
}
