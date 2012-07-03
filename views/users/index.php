<?php include_once 'views/layout/header.php'; ?>

<script type="text/javascript">
    $(document).ready( function() {
        setTimeout( "$('#message').fadeOut();", 3000);

      });

</script>

<div class="content_holder">
    <div id="message">
    <p style="text-align: center; font-size: 20px; color:red;"><?php 
    if(isset($_SESSION['message'])){
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
    ?><p>
    </div>
    <form action="<?php echo site_url();?>users/sign_in_user" method="POST" name="sign_in">
        <div class="sign_in_holder">
            <div class="fields_holder">
            <label>User Name:</label>
            <input type="text" name="user_name" value="" required="required">
            </div>
            <div class="fields_holder">
            <label>Password:</label>
            <input type="password" name="password" value="" required="required">
            </div>
            <input type="submit" value="submit">
        </div>
    </form>
</div>
<?php include_once 'views/layout/footer.php'; ?>