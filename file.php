<?php
session_start();
require 'dao/giftDAO.php';
require 'dao/orderDAO.php';
$xml_data = $HTTP_RAW_POST_DATA;

//Get rid of PHP's magical escaping of quotes

fnWriteXml($xml_data);

if (get_magic_quotes_gpc()) {
$xml_data = stripslashes($xml_data);
// Capture the Return Response XML from the Google Checkout.
//fnWriteXml($xml_data);
}

 function fnWriteXml($string){
$file="testFile.txt";
if(file_exists($file)) {
$fileid = fopen($file,"a");
$strmsg = "";
$strmsg.= "***************************************************\r\n";
$strmsg.=$string;
$strmsg.="\r\n***************************************************\r\n";
fwrite($fileid,$strmsg);
fclose($fileid);
}else{
$fileid = fopen($file,"a");
$strmsg = $string;
fwrite($fileid,$strmsg);
fclose($fileid);
}
}

  function xml2ary($string) {
            $parser = xml_parser_create();
            xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
            xml_parse_into_struct($parser, $string, $vals, $index);
            xml_parser_free($parser);

            $mnary=array();
            $ary=&$mnary;
            foreach ($vals as $r) {
                $t=$r['tag'];
                if ($r['type']=='open') {
                    if (isset($ary[$t])) {
                        if (isset($ary[$t][0])) $ary[$t][]=array(); else $ary[$t]=array($ary[$t], array());
                        $cv=&$ary[$t][count($ary[$t])-1];
                    } else $cv=&$ary[$t];
                    if (isset($r['attributes'])) {
                        foreach ($r['attributes'] as $k=>$v) $cv['_a'][$k]=$v;
                    }
                    $cv['_c']=array();
                    $cv['_c']['_p']=&$ary;
                    $ary=&$cv['_c'];

                } elseif ($r['type']=='complete') {
                    if (isset($ary[$t])) { // same as open
                        if (isset($ary[$t][0])) $ary[$t][]=array(); else $ary[$t]=array($ary[$t], array());
                        $cv=&$ary[$t][count($ary[$t])-1];
                    } else $cv=&$ary[$t];
                    if (isset($r['attributes'])) {
                        foreach ($r['attributes'] as $k=>$v) $cv['_a'][$k]=$v;
                    }
                    $cv['_v']=(isset($r['value']) ? $r['value'] : '');

                } elseif ($r['type']=='close') {
                    $ary=&$ary['_p'];
                }
            }

            _del_p($mnary);
            return $mnary;
        }

// _Internal: Remove recursion in result array
        function _del_p(&$ary) {
            foreach ($ary as $k=>$v) {
                if ($k==='_p') unset($ary[$k]);
                elseif (is_array($ary[$k])) _del_p($ary[$k]);
            }
        }

// Array to XML
        function ary2xml($cary, $d=0, $forcetag='') {
            $res=array();
            foreach ($cary as $tag=>$r) {
                if (isset($r[0])) {
                    $res[]=ary2xml($r, $d, $tag);
                } else {
                    if ($forcetag) $tag=$forcetag;
                    $sp=str_repeat("\t", $d);
                    $res[]="$sp<$tag";
                    if (isset($r['_a'])) {
                        foreach ($r['_a'] as $at=>$av) $res[]=" $at=\"$av\"";
                    }
                    $res[]=">".((isset($r['_c'])) ? "\n" : '');
                    if (isset($r['_c'])) $res[]=ary2xml($r['_c'], $d+1);
                    elseif (isset($r['_v'])) $res[]=$r['_v'];
                    $res[]=(isset($r['_c']) ? $sp : '')."</$tag>\n";
                }

            }
            return implode('', $res);
        }

// Insert element into array
        function ins2ary(&$ary, $element, $pos) {
            $ar1=array_slice($ary, 0, $pos);
            $ar1[]=$element;
            $ary=array_merge($ar1, array_slice($ary, $pos));
        }
        $data=xml2ary($xml_data);
//Displaying the Array
//echo print_r($data['new-order-notification']['_c']['google-order-number']['_v']);
//echo "<br/>";
//echo print_r($data['new-order-notification']['_c']['order-total']['_v']);
//echo "<br/>";
//echo "<pre>";
//print_r($data);
//echo "</pre>";


  $TRANSACTIONID=$data['new-order-notification']['_c']['google-order-number']['_v'];
        $amouttotal=$data['new-order-notification']['_c']['order-total']['_v'];
        $order_id=$data['new-order-notification']['_c']['shopping-cart']['_c']['merchant-private-data']['_v'];
        if(empty($order_id)){
        $order_id=$data['new-order-notification']['_c']['shopping-cart']['_c']['items']['_c']['item']['_c']['merchant-private-item-data']['_v'];
        }
      $order = explode('-', $order_id);
      
        if(!empty($TRANSACTIONID)){
if(!isset($order[1])){
       $orderDAO = new orderDAO();
       $trans_id = $orderDAO->GetOrderHis($order_id);
       if(empty($trans_id) || $trans_id=='Recurring Payment' ){
           $orderDAO->updateOrder($order_id,$TRANSACTIONID);
           
           $orderDAO1 = new orderDAO();
           $gift_id = $orderDAO1->getGiftid($order_id);

           $giftDAO = new giftDAO();
           $giftDAO->activateGift($gift_id);
           $giftDAO->activateGiftAdminPending($gift_id);
       }else{
       
       
        $amount=$amouttotal;
        $recurring_last_payment_date=date("Y-m-d");
        $next = mktime(0, 0, 0, date("m")+1, date("d"), date("y"));
        $recurring_next_payment_date = date("Y-m-d", $next);
        $recurring_cycle_done=1;
        $orderDAO->setReOrder($order_id,'approve',$TRANSACTIONID,$amouttotal,'Google',$recurring_last_payment_date,$recurring_next_payment_date);
        $userDAO->updateOrderRecurringGoogle($order_id,$amount,$recurring_last_payment_date,$recurring_next_payment_date,$recurring_cycle_done);
       }
       
        } else{


              $orderDAO = new orderDAO();
       $trans_id = $orderDAO->GetReOrderHis($order[1]);
       if(empty($trans_id) || $trans_id=='Recurring Payment'){
           $orderDAO->updateReOrder($order[1],$TRANSACTIONID);

           $orderDAO1 = new orderDAO();
           $gift_id = $orderDAO1->getGiftid($order[0]);

           $giftDAO = new giftDAO();
           $giftDAO->activateGift($gift_id);
          // $giftDAO->activateGiftAdminPending($gift_id);

            $amount=$amouttotal;
        $recurring_last_payment_date=date("Y-m-d");
        $next = mktime(0, 0, 0, date("m")+1, date("d"), date("y"));
        $recurring_next_payment_date = date("Y-m-d", $next);
        $recurring_cycle_done=1;
        $userDAO->updateOrderRecurringGoogle($order[0],$amount,$recurring_last_payment_date,$recurring_next_payment_date,$recurring_cycle_done);



       }else{
       

        $amount=$amouttotal;
        $recurring_last_payment_date=date("Y-m-d");
        $next = mktime(0, 0, 0, date("m")+1, date("d"), date("y"));
        $recurring_next_payment_date = date("Y-m-d", $next);
        $recurring_cycle_done=1;
        $orderDAO->setReOrder($order[0],'approve',$TRANSACTIONID,$amouttotal,'Google',$recurring_last_payment_date,$recurring_next_payment_date);
        $userDAO->updateOrderRecurringGoogle($order[0],$amount,$recurring_last_payment_date,$recurring_next_payment_date,$recurring_cycle_done);

          $giftDAO = new giftDAO();
           $giftDAO->activateGift($gift_id);
           $giftDAO->activateGiftAdminPending($gift_id);
       }


        }
       



        }

?>