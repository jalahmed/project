<?php
session_start();
require_once("helpers/CallerService.php");

class application_controller{
    protected $variables_array = array();
  function __construct_job() {
//     if(session_id() != $_SESSION['session_id']){
//         $_SESSION['session_id']=session_id();
//           $userDAO =new userDAO();
//         $TotalSiteViews = $userDAO->TotalSiteViews();
//      if(empty($TotalSiteViews)){
//           $TotalSiteset = $userDAO->SetSiteViews();
//      }else{
//           $TotalSiteupdate = $userDAO->UpdateSiteViews();
//      }
//     }
          $ipaddress = $_SERVER['REMOTE_ADDR'];
           $userDAO =new userDAO();
         $Ipview = $userDAO->GetIpViews($ipaddress);
      if(empty($Ipview)){
           $TotalSiteset = $userDAO->SetIpViews($ipaddress);
      }
     $TotalSiteViews = $userDAO->GetTotalViews();

 $GetNewTerm = $userDAO->GetNewTerm();
if(empty($GetNewTerm)){ 
     $userDAO->SetNewTerm();
}

     /////////////////////////////////////// corn job /////////////////////////////////////
     
     $OrderContract = $userDAO->getcontractExp();
      while($ContractInfo=mysql_fetch_array($OrderContract)){
         
                         $Contractterminfo=explode('-', $ContractInfo['terminfo']);
                        if($Contractterminfo[3]=="Year") {
                         $ContractExp_date = mktime(0, 0, 0, date("m",strtotime($ContractInfo['order_date'])),   date("d",strtotime($ContractInfo['order_date'])),   date("Y",strtotime($ContractInfo['order_date']))+$Contractterminfo[2]);
                      
                         }
                        if($Contractterminfo[3]=="Month") {
                            $ContractExp_date = mktime(0, 0, 0, date("m",strtotime($ContractInfo['order_date']))+$Contractterminfo[2],   date("d",strtotime($ContractInfo['order_date'])),   date("Y",strtotime($ContractInfo['order_date'])));

                        }
         $ContractdateDiff= $ContractExp_date - time();
    $ContractfullDaysDiff = floor($ContractdateDiff/(60*60*24));
   
 if($ContractfullDaysDiff < 0 ) {
        $OrderdExpired= $userDAO->updateOrderdExpired($ContractInfo['gift_id'],$ContractInfo['order_id']);
    }
      }
$getorderInfoRecurring = $userDAO->getorderUpdateRecurring();
 $getorderInfoNumberRecurring=mysql_num_rows($getorderInfoRecurring);
     if(!empty($getorderInfoNumberRecurring)){ 
     while($InfoRecurring=mysql_fetch_array($getorderInfoRecurring)){
 $profileID=urlencode($InfoRecurring['PROFILEID']);
$nvpStr="&PROFILEID=$profileID";
$resArray=hash_call("GetRecurringPaymentsProfileDetails",$nvpStr);
//var_dump($resArray);
$ack = strtoupper($resArray["ACK"]);
if($ack!="SUCCESS"){

	} else{
        $order_id=$InfoRecurring['order_id'];
        $amount=$resArray["AGGREGATEAMT"];
        $recurring_last_payment_date=$resArray["LASTPAYMENTDATE"];
        $recurring_next_payment_date=$resArray["NEXTBILLINGDATE"];
        $recurring_cycle_done=$resArray["NUMCYCLESCOMPLETED"];
        $userDAO->updateOrderRecurring($order_id,$amount,$recurring_last_payment_date,$recurring_next_payment_date,$recurring_cycle_done);
 if($InfoRecurring['gift_status']=='5'){
        $userDAO->updateOrderdGiftTAfterRecurring($InfoRecurring['gift_id']);
 }
        }

     } }
     $getorderInfo1 = $userDAO->getorderInfo();
     $getorderInfoNumber=mysql_num_rows($getorderInfo1);
     if(!empty($getorderInfoNumber)){
     while($Info=mysql_fetch_array($getorderInfo1)){


 
          
          $getReorderInfo = $userDAO->getReorderInfo($Info['order_id']);
         $numberReorder=mysql_num_rows($getReorderInfo);
          if(!empty($numberReorder)){

              $ReInfo= mysql_fetch_array($getReorderInfo);

               $orderDate2 = mktime(0, 0, 0, date("m",mktime($ReInfo['Re_order_date']))+1,   date("d",mktime($ReInfo['Re_order_date'])),   date("Y",mktime($ReInfo['Re_order_date'])));
         


               }else{
             // $orderDate2 = mktime(0, 0, 0, date("m",strtotime($Info['order_date']))+1,   date("d",strtotime($Info['order_date'])),   date("Y",strtotime($Info['order_date'])));
$orderDate2 = mktime(0, 0, 0, date("m",strtotime($Info['recurring_next_payment_date'])),   date("d",strtotime($Info['recurring_next_payment_date'])),   date("Y",strtotime($Info['recurring_next_payment_date'])));
              
          }
    
     
     $dateDiff= $orderDate2 -time();
   $fullDaysDiff = floor($dateDiff/(60*60*24));
 
    if($fullDaysDiff < 0 ) {

      

        $getorderInfo = $userDAO->updateOrderdGiftToReOrder($Info['gift_id']);
    }
     }
     }
     /////////////////////////////////////// END corn job /////////////////////////////////////
   }
    protected function root() {
        return dirname(__FILE__);
    }
    protected function render($file_name, $variables_array) {
        if(isset($variables_array)) {
            extract($variables_array); // Include given variables to use in a template
        }
        require_once($file_name);
    }
    public function login_user($name,$id){
       
             $_SESSION['loginname']=$name;
             $_SESSION['userid']=$id;
              $_SESSION['login_type']="mysite";
              $userDAO =new userDAO();
              $result = $userDAO->LogTable(time(),date("Y-m-d"),$_SESSION['userid'],'login','3');
               return TRUE;
    }
     public function login_user_fb($name,$id){
             $_SESSION['loginname']=$name;
             $_SESSION['userid']=$id;
             $_SESSION['login_type']="facebook";
              $userDAO =new userDAO();
              $result = $userDAO->LogTable(time(),date("Y-m-d"),$_SESSION['userid'],'login','3');
               return TRUE;
    }
    public function login_user_twitter($name,$id){
             $_SESSION['loginname']=$name;
             $_SESSION['userid']=$id;
             $_SESSION['login_type']="twitter";
              $userDAO =new userDAO();
              $result = $userDAO->LogTable(time(),date("Y-m-d"),$_SESSION['userid'],'login','3');
               return TRUE;
    }
    public function login_vender($name,$id){
         $_SESSION['login_type']="business";
             $_SESSION['vender_name']=$name;
             $_SESSION['venderid']=$id;
              $userDAO =new userDAO();
               $business_contact = $userDAO->GetBusinessContact($id);
               $_SESSION['business_contact']=$business_contact;
              $result = $userDAO->LogTable(time(),date("Y-m-d"),$_SESSION['venderid'],'login','2');
               return TRUE;
    }
        public function login_admin($name,$id){
             $_SESSION['admin_name']=$name;
             $_SESSION['admin_session_id']=$id;
               return TRUE;
    }
            public function login_sales($name,$id){
             $_SESSION['sales_name']=$name;
             $_SESSION['sales_session_id']=$id;
               return TRUE;
    }
    public function is_login(){

            if(isset($_SESSION['userid']) && $_SESSION['userid']!="")
                return TRUE;
            else
                // return false;
            redirect_to(site_url()."home/index");
//                return $this->render("views/users/index.php", compact('var', 'var2','var3'));
              //  redirect_to(site_url()."home/index");
    }
  
   
}
?>
