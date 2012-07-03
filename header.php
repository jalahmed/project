<?php
	
if($_SESSION['gender']=="male") {?>
    <?php echo "<script language='javascript' type='text/javascript'>background_male()</script>";
}else {
    echo "<script language='javascript' type='text/javascript'>background_female()</script>";
}


require_once("helpers/twitter/EpiCurl.php");
require_once("helpers/twitter/EpiOAuth.php");
require_once("helpers/twitter/EpiTwitter.php");
require_once("helpers/twitter/keys.php");

include_once 'views/users/connectfacebook.php';

?>



<html
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:addthis="http://www.addthis.com/help/api-spec">
    <head>
        <?php if(!empty($variables_array['share_to_friends']) && $variables_array['share_to_friends']=="yes"){ ?>
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="title" content="<?php echo $result_share['gift_intro'];?>" />
        <meta name="description" content="<?php echo strip_tags(stripslashes($result_share['Description']));?>" />
        <link rel="image_src" href="<?php echo site_url(); ?>public/images/<?php echo $result_share['gift_image']?>" />
     <?php  } else { ?>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="title" content="[-- PRODUCT.MoreInformationTitle --]" />
        <meta name="description" content="[-- PRODUCT.MoreInformationMetaDescription --]" />
        <link rel="image_src" href="<?php echo site_url(); ?>public/images/logo.png" />
        <?php } ?>
        <title>BIRTHDAY</title>



        <?php  if(get_controller()=="vendersController" or get_controller()=="adminController") {?>
        <link href="<?php echo site_url(); ?>public/styles/vender.css" type="text/css" rel="stylesheet" />
            <?php }else {
            if($_SESSION['gender']=="male" && get_controller() !="homeController") {
                ?>
        <link href="<?php echo site_url(); ?>public/styles/male.css" type="text/css" rel="stylesheet" />
                <?php } else {?>
        <link href="<?php echo site_url(); ?>public/styles/style.css" type="text/css" rel="stylesheet" />
                <?php }
}
?>
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo site_url(); ?>public/styles/jsDatePick_ltr.min.css" />
        <link href="<?php echo site_url(); ?>public/styles/commen.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript">
            var site_url = '<?php echo site_url(); ?>';
        </script>
        <script type="text/javascript" src="<?php echo site_url(); ?>public/scripts/jquery.js"></script>

        <script type="text/javascript" src="<?php echo site_url(); ?>public/scripts/script.js"></script>
        <script type="text/javascript" src="<?php echo site_url(); ?>public/scripts/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="<?php echo site_url(); ?>public/scripts/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="<?php echo site_url(); ?>public/scripts/jquery-1.3.1.min.js"></script>
        <script type="text/javascript" src="<?php echo site_url(); ?>public/scripts/ajaxupload.3.5.js"></script>
        <script type="text/javascript" src="<?php echo site_url(); ?>public/scripts/jquery.validate.js"></script>
       <?php  if(get_controller()=="adminController" && $_SESSION['action']=="SitePages" ) {?>
         <script type="text/javascript" src="<?php echo site_url(); ?>helpers/ckeditor-2/ckeditor.js"></script>
       <?php } else{ ?>
         <script type="text/javascript" src="<?php echo site_url(); ?>helpers/ckeditor/ckeditor.js"></script>
         <?php } ?>
        <script src="http://connect.facebook.net/en_US/all.js"></script>
        
        <script type="text/javascript" src="<?php  echo site_url(); ?>public/scripts/default.js"></script>
        <script type="text/javascript" src="<?php echo site_url(); ?>public/scripts/jsDatePick.min.1.3.js"></script>
        <script type="text/javascript">
            
            window.onload = function(){


                g_globalObject = new JsDatePick({
                    useMode:1,
                    isStripped:true,
                    target:"div3_example"
                    /*selectedDate:{				This is an example of what the full configuration offers.
                                        day:5,						For full documentation about these settings please see the full version of the code.
                                        month:9,
                                        year:2006
                                },
                                yearsRange:[1978,2020],
                                limitToToday:false,
                                cellColorScheme:"beige",
                                dateFormat:"%m-%d-%Y",
                                imgPath:"img/",
                                weekStartDay:1*/
                });

                g_globalObject.setOnSelectedDelegate(function(){
                    var obj = g_globalObject.getSelectedDay();
                    var gift_id=document.getElementById('gift-report').value;
                      $('#dateValue').html("");
                    document.getElementById('show_process').style.display='block';
                    document.getElementById('nodata').style.display='none';
                  document.getElementById('shownew').style.display='none';
                  //  alert(gift_id);
                   // alert("a date was just selected and the date is : " + obj.day + "/" + obj.month + "/" + obj.year);
                  
                   if(obj.month < 10){
                       var month='0'+obj.month;
                   }else{
                         var month=obj.month;
                   }
                    if(obj.day < 10){
                       var day='0'+obj.day;
                   } else{
                         var day=obj.day;
                   }
                   var dob=month +"-"+ day;
                    var dobClaim=obj.year +"-"+ month +"-"+ day;
                   $.ajax({
                        type: "POST",
                        url: site_url+"venders/runReportSearch/"+gift_id,
                        data:'dob='+dob+'&dobclaim='+dobClaim,
                        dataType: "json",
                        success:function(data){

                            $('#dateValue').html(data[0]);
                      var showdata='';
                      if(data.length==1){
                        
                         var showdata = " <div id='shownew'></div><div id='nodata'><b>There is no Brithday on this date</b></div>";
                      }else{
                           var showdata= showdata + "<div id=shownew>";
                       for(var i=1; i<data.length;i++){
                         
         var showdata= showdata + "<div><b>Name :</b>"+data[i].first_name+"&nbsp;&nbsp;"+data[i].last_name+"</div><div><b>Birthday:</b>"+data[i].Birthday+"</div><div><b>Claimed Date:</b>"+data[i].Claimed_Date+"</div><div><b>Coupen Code:</b> #"+data[i].Coupen_Code+"</div><br/><br/>";
                       }
                        var showdata= showdata + "</div>";
                      }
                          // alert(datam[0].Coupen_Code);
                         $('#showsearch').html(showdata+'<div class="ajax-progress" id="show_process" style=" display:none"  >&nbsp;</div><div id="nodata" style="display: none"></div><div style="width: 740px;">&nbsp;</div>');
                        },
                        error: function(){
                        },
                        beforeSend: function(){
                            $('#addresses').html("");
                        }
                    });
                  
                });



            };
        </script>
        <script language="JavaScript" type="text/javascript">
            $(document).ready(function() {
                $("#giftform").validate({
                    rules: {
                        // simple rule, converted to {required:true}
                        email: {// compound rule
                            required: true,
                            email: true
                        },
                        pass: "required",
                        cpass:{
                            equalTo: "#password"
                        }
                    },
                    messages: {
                        name: "Please enter a comment."
                    }
                });

                $("#checkoutform").validate({
                    rules: {
                        // simple rule, converted to {required:true}
                        //                        address1: "required"
                    },
                    messages: {

                    }
                });
            });

            $(document).ready(function() {
<?php if(get_controller()!="vendersController" or get_controller()=="adminController") {?>
    <?php if(isset ($_SESSION['gender']) && $_SESSION['gender']=="male") {?>
            //  background_male();
        <?php }else { ?>
            // background_female();
        <?php }
}
?>
        });

        var months = new Array()
        months[1] = "Jan"
        months[2] = "Feb"
        months[3] = "Mar"
        months[4] = "Apr"
        months[5] = "May"
        months[6] = "Jun"
        months[7] = "Jul"
        months[8] = "Augt"
        months[9] = "Sep"
        months[10] = "Oct"
        months[11] = "Nov"
        months[12] = "Dec"

        var today = new Date()
        var month = today.getMonth()
        var date = today.getDate()
        var year = today.getFullYear()
        </script>

        <script language="javascript" type="text/javascript">
            function div1() {
                //W3C DOM  Document Object  Model
                document.getElementById("loginuser").style.display ="none";
                document.getElementById("signupuser").style.display   = "block";
                $("#div_login").removeClass('selected');
                $("#div_signup").addClass('selected');
                // Second  approach
                //window.document.all.login.style.visiblity = "visible";
            }
            function div2() {
                //W3C DOM  Document Object  Model
                document.getElementById("signupuser").style.display  = 'none';
                document.getElementById("loginuser").style.display   = 'block';
                $("#div_signup").removeClass('selected');
                $("#div_login").addClass('selected');
            }
        </script>
        <script language="javascript" type="text/javascript">
            $(document).ready(function(){
                setTimeout(function(){
                    $("#message_div").fadeOut("slow", function () {
                        $("#message_div").remove();
                    });

                }, 5000);

            });
            $(document).ready(function(){

<?php if($_GET['earn_more']) {?>

        $('#tabs_1').removeClass('active');
        $('#tabs_2').removeClass('active');
        $('#tabs_3').addClass('active');
        $('#tabs_4').removeClass('active');
         $('#tabs_5').removeClass('active');

        $('#claim_show').hide();
        $('#fav_show').hide();
        $('#earn_show').show();
        $('#info_show').hide();
         $('#claim_used').hide();
    <?php } ?>
<?php if($_GET['more_info']) {?>

        $('#tabs_1').removeClass('active');
        $('#tabs_2').removeClass('active');
        $('#tabs_3').removeClass('active');
        $('#tabs_4').addClass('active');
         $('#tabs_5').removeClass('active');

        $('#claim_show').hide();
        $('#fav_show').hide();
        $('#earn_show').hide();
        $('#info_show').show();
         $('#claim_used').hide();
    <?php } ?>

                $('#tabs_1').click(function() {
                    $('#tabs_1').addClass('active');
                    $('#tabs_2').removeClass('active');
                    $('#tabs_3').removeClass('active');
                    $('#tabs_4').removeClass('active');
                     $('#tabs_5').removeClass('active');
                    $('#claim_show').show();
                    $('#fav_show').hide();
                    $('#earn_show').hide();
                    $('#info_show').hide();
                     $('#claim_used').hide();
                });
                $('#tabs_2').click(function() {
                    $('#tabs_1').removeClass('active');
                    $('#tabs_2').addClass('active');
                    $('#tabs_3').removeClass('active');
                    $('#tabs_4').removeClass('active');
                     $('#tabs_5').removeClass('active');
                    $('#claim_show').hide();
                    $('#fav_show').show();
                    $('#earn_show').hide();
                    $('#info_show').hide();
                     $('#claim_used').hide();
                });
                $('#tabs_3').click(function() {
                    $('#tabs_1').removeClass('active');
                    $('#tabs_2').removeClass('active');
                    $('#tabs_3').addClass('active');
                    $('#tabs_4').removeClass('active');
                     $('#tabs_5').removeClass('active');

                    $('#claim_show').hide();
                    $('#fav_show').hide();
                    $('#earn_show').show();
                    $('#info_show').hide();
                     $('#claim_used').hide();
                });
                $('#tabs_4').click(function() {

                    $('#tabs_1').removeClass('active');
                    $('#tabs_2').removeClass('active');
                    $('#tabs_3').removeClass('active');
                    $('#tabs_4').addClass('active');
                    $('#tabs_5').removeClass('active');
                    $('#claim_show').hide();
                    $('#fav_show').hide();
                    $('#earn_show').hide();
                    $('#info_show').show();
                     $('#claim_used').hide();
                });
                 $('#tabs_5').click(function() {

                    $('#tabs_1').removeClass('active');
                    $('#tabs_2').removeClass('active');
                    $('#tabs_3').removeClass('active');
                    $('#tabs_4').removeClass('active');
                    $('#tabs_5').addClass('active');
                    $('#claim_show').hide();
                    $('#fav_show').hide();
                    $('#earn_show').hide();
                    $('#info_show').hide();
                    $('#claim_used').show();
                });
                $("[id^=pMore_]").click(function() {


                    rid = $(this).attr("id").split("_")[1];

                    var myClass = $("#textareaNew_"+rid).attr("class");

                    if(myClass=="textareaNew"){
                        $("#textareaNew_"+rid).removeClass('textareaNew').addClass('textareaNewMore');
                        $("#pMore_"+rid).html('Less');
                    } else{
                        $("#textareaNew_"+rid).removeClass('textareaNewMore').addClass('textareaNew');
                        $("#pMore_"+rid).html('More');
                    }
                });
                $("#pMore_v").click(function() {


                    alert("sdfdf");
                });

            });
            function  closemessage(){
                document.getElementById("closemessagediv").style.display  = "none";
                document.getElementById("message_div").style.display  = "none";
            }

        </script>
<?php if(get_controller()!=false && get_controller()=="vendersController") {?>
        <script language="javascript" type="text/javascript">
            $(function(){

                var btnUpload=$('#upload');
                var status=$('#status');



                new AjaxUpload(btnUpload, {
                    action: site_url+"venders/addupload/",
                    name: 'uploadfile',
                    onSubmit: function(file, ext){
                        if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
                            // extension is not allowed
                            status.text('Only JPG, PNG or GIF files are allowed');
                            return false;
                        }
                        status.text('Uploading...');
                    },
                    onComplete: function(file, response){
                        //On completion clear the status
                        status.text('');
                        //Add uploaded file to list

                        if(response==="success"){

                            $('#selectimage').val('yes');

                            $('#files').html('<li class="success"><img src='+site_url+'public/uploaded_images/'+file+' alt="" /><br />'+file+'</li>');
                        } else{
                            $('<li></li>').appendTo('#files').text(file).addClass('error');
                        }
                    }
                });

            });
        </script>
    <?php } else { ?>
        <script language="javascript" type="text/javascript">
            $(function(){

                var btnUpload=$('#upload2');
                var status=$('#status');
                var uimg=$('#userimage').val();



                new AjaxUpload(btnUpload, {
                    action: site_url+"users/uploadMyImage/",
                    name: 'uploadfile',
                    onSubmit: function(file, ext){
                        if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
                            // extension is not allowed
                            status.text('Only JPG, PNG or GIF files are allowed');
                            return false;
                        }
                        status.text('Uploading...');
                    },
                    onComplete: function(file, response){
                        //On completion clear the status
                        status.text('');
                        //Add uploaded file to list

                        if(response==="success"){



                            $('#upload2').html('<img src='+site_url+'public/uploaded_images/'+uimg+file+' alt="" />');
                        } else{
                            $('<li></li>').appendTo('#files').text(file).addClass('error');
                        }
                    }
                });

            });
        </script>
    <?php }?>
<?php if($_SESSION['action']=="user_home" || $_SESSION['action']=="sortby_businessgift" || $_SESSION['action']=="viewbycategory" || $_SESSION['action']=="searchby_businessgift" || $_SESSION['action']=="fav_businessgift" ) {
            if(isset ($_SESSION['gender']) && $_SESSION['gender']=="male") {?>
        <link href="<?php echo site_url(); ?>public/styles/body_male_light.css" type="text/css" rel="stylesheet" />
            <?php }else { ?>
        <link href="<?php echo site_url(); ?>public/styles/body_female_light.css" type="text/css" rel="stylesheet" />

        <?php }
        }
?>
    </head>
<?php if($_SESSION['logTwitter']) {?>
    <body onLoad="showtwitterEmail()" >
                            <?php  }  else { ?>
    <body >
                            <?php } ?>
        <div align="center" >
            <div class="header" id="header_change">
                <div class="center">
                    <div class="logo">   <?php
if($_SESSION['gender']=="male") {
                            $logo="logoBlue.png";
}else {
                            $logo="logo.png";
}
                        if(isset($_SESSION['loginname']) && $_SESSION['loginname']!="") {
if(!empty($_SESSION['term_is_active'])){ ?>
    <a href="#"><span id="logo_change"><img src="<?php echo site_url(); ?>public/images/<?php echo $logo; ?>" alt="" /></span></a>
    <?php } else{ ?>
                        <a href="<?php echo site_url()."users/user_home/all"; ?>"><span id="logo_change"><img src="<?php echo site_url(); ?>public/images/<?php echo $logo; ?>" alt="" /></span></a>
                            <?php } }else if(get_controller()=="vendersController") { ?>
                        <a href="<?php echo site_url()."venders/venderDashBoard"; ?>"><span id="logo_change"><img src="<?php echo site_url(); ?>public/images/logoBlue.png" alt="" /></span></a>
    <?php }else if(get_controller()=="adminController") { ?>
                        <a href="<?php echo site_url()."admin/index"; ?>"><span id="logo_change"><img src="<?php echo site_url(); ?>public/images/logoBlue.png" alt="" /></span></a>
                            <?php } else {?>

                        <a href="<?php echo site_url(); ?>"><span id="logo_change"><img src="<?php echo site_url(); ?>public/images/<?php echo $logo;?>" alt="" /></span></a>
                            <?php } ?></div>

                    <div class="login">
                        <?php if((get_controller()!=false && get_controller()=="vendersController" or get_controller()=="adminController")) {
                            if(isset($_SESSION['vender_name']) && $_SESSION['vender_name']!="") {
        ?>
        <?php echo $_SESSION["vender_name"]; ?>&nbsp;&nbsp;<a href="<?php echo site_url();?>venders/logoutVender">Sign out</a><br/><br/>
                                <?php
                            } else if(!empty($_SESSION["admin_session_id"])) { ?>
                        Admin&nbsp;&nbsp;<a href="<?php echo site_url();?>admin/logoutadmin">Sign out</a><br/><br/>
                                <?php   }else {
                                ?>
                        <a href="javascript:void(0);" onclick="showLoginPopUp()">Business Login</a><br />
                        <a href="<?php echo site_url(); ?>venders/getFaq">Sign up my Busines</a>
                                <?php
                            }
}else {
    if(!empty($_SESSION['twitter_id'])) {

        ?>
                        &nbsp;&nbsp;<a href="<?php echo site_url();?>users/logoutTwitter">Sign out</a><br /><br />
                                <?php     } else if(isset($_SESSION['loginname']) && $_SESSION['loginname']!="") {
                                ?>




                                <?php
                                if(!empty($session)) { ?>
                                    <?php echo $_SESSION["name"]; ?>&nbsp;&nbsp;<a href="<?php echo site_url();?>users/logoutFb">Sign out</a><br /><br />
            <?php }  else { ?>

                                    <?php echo $_SESSION["name"]; ?>&nbsp;&nbsp;<a href="<?php echo site_url();?>users/logout">Sign out</a><br /><br />
                                    <?php } ?>
                                <?php
                            }else {
                                ?>
                        <a href="javascript:void(0);" onclick="showLoginPopUp()">Business Login</a><br />
                        <a href="<?php echo site_url(); ?>venders/getFaq">Sign up my Busines</a>
        <?php
    }


}
?>

                    </div>
                    <div class="headingBar" id="headerBar_change">Free Gifts For You<br />
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;On Your Birthday ...</div>
                </div>
            </div>    <div class="map" id="map_location" style="display:none;"></div>
            <div class="dim" id="dim"></div><div id="login_div" style="display: none"></div>