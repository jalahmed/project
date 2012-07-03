<html>
<head>
    <script language="javascript" type="text/javascript" src="<?php echo site_url();?>public/scripts/niceforms.js"></script>
    <script type="text/javascript" src="<?php echo site_url();?>public/scripts/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo site_url();?>public/scripts/ddaccordion.js"></script>
    <link href="<?php echo site_url(); ?>public/styles/admin.css" type="text/css" rel="stylesheet" />    
    <script type="text/javascript">
ddaccordion.init({
	headerclass: "submenuheader", //Shared CSS class name of headers group
	contentclass: "submenu", //Shared CSS class name of contents group
	revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
	mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
	collapseprev: true, //Collapse previous content (so only one open at any time)? true/false 
	defaultexpanded: [], //index of content(s) open by default [index1, index2, etc] [] denotes no content
	onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
	animatedefault: false, //Should contents open by default be animated into view?
	persiststate: true, //persist state of opened contents within browser session?
	toggleclass: ["", ""], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
	togglehtml: ["suffix", "<img src='<?php echo site_url();?>public/images/plus.gif' class='statusicon' />", "<img src='<?php echo site_url();?>public/images/minus.gif' class='statusicon' />"], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
	animatespeed: "fast", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
	oninit:function(headers, expandedindices){ //custom code to run when headers have initalized
		//do nothing
	},
	onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
		//do nothing
	}
})
</script>

<script type="text/javascript" src="<?php echo site_url();?>public/scripts/jconfirmaction.jquery.js"></script>
<script type="text/javascript">
	
	$(document).ready(function() {
		$('.ask').jConfirmAction();
	});
	
</script>
</head>
<body>

    <div class="wrapper">
        
        <div class="login_main">
        
            <form action="<?php echo site_url();?>admin/loginAdmin" method="POST" name="admin_login">
                <div class="input_holder">
                    <label>Login:</label>
                    <input type="text" name="login_name" id="admin_login"/>
                </div>
                <div class="input_holder">
                    <label>Password:</label>
                    <input type="hidden" name="login_name" id="admin_password"/>
                </div>
                
                
            </form>
        
        
        </div>
        
        
        
        
    </div>
    
    
    
        
</body>
</html>