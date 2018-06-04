<?php 
include_once("../includes/global.php"); 

if (isset($_POST["action"])) {
$action = $_POST["action"];

// edit team 
if ($action == 'editTeam') {
$sql = "SELECT * FROM users where company = $companyId and role = 'staff' and id = ".$_POST['teamId'];
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$permissions = json_decode($row['permissions']);
?>
	  <div class="form-group">
	  <div class="col-md-6 p-l-o">
	    <label>First name</label>
	    <input type="text" class="form-control fname" value="<?php echo $row['fname']; ?>" name="fname" placeholder="First name" data-parsley-required="true">
	    <input type="hidden" name="action" value="editTeam">
	    <input type="hidden" name="teamId" value="<?php echo $row['id']; ?>">
	    <input type="hidden" name="oldAvatar" value="<?php echo $row['avatar']; ?>">
	  </div>
	  <div class="col-md-6 p-r-o">
	    <label>Last name</label>
	    <input type="text" class="form-control" name="lname" value="<?php echo $row['lname']; ?>"  placeholder="Last name" data-parsley-required="true">
	  </div>
	  </div>
	  <div class="form-group">
	  <div class="col-md-6 p-l-o">
	    <label>Email address</label>
	    <input type="email" class="form-control" name="email" value="<?php echo $row['email']; ?>" placeholder="Email address" data-parsley-required="true">
	  </div>
	  <div class="col-md-6 p-r-o">
	    <label>Phone number</label>
	    <input type="text" class="form-control" name="phone" value="<?php echo $row['phone']; ?>" placeholder="Phone number">
	  </div>
	  </div>
	  <a href="" class="password-trigger">Change Password.</a>
	  <div class="form-group change-password hidden">
	  <div class="col-md-6 p-l-o">
	    <label>Password</label>
	    <input type="password" class="form-control" name="password" id="newPassword" placeholder="Password" data-parsley-minlength="0" data-parsley-error-message="Password is too short!">
	  </div>
	  <div class="col-md-6 p-r-o">
	    <label>Confirm password</label>
	    <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm password" data-parsley-equalto="#newPassword" data-parsley-error-message="Passwords don't Match!">
	  </div>
	  </div>
	  <div class="form-group">
	  <div class="col-md-12 p-lr-o">
	    <label>Profile picture <span class="text-muted text-xs">Square - atleast 200 * 200</span></label>
	    <input type="file" name="avatar" class="dropify" data-default-file="uploads/avatar/<?php echo $row['avatar']; ?>"  data-min-width="200" data-min-height="200" data-allowed-file-extensions="png jpg">
	  </div>
	  </div>
	  <div class="form-group permissions">
	  <div class="col-md-12 p-l-o">
	  <label>Permissions</label>
	  </div>
	  <div class="col-md-4 p-l-o">
	    <input type="checkbox" class="js-switch-dynamic" name="permissions[]" value="upload" checked readonly />  Can upload
	  </div>
	  <div class="col-md-4">
	    <input type="checkbox" class="js-switch-dynamic" name="permissions[]" value="sign" <?php if(in_array("sign", $permissions)){ ?>checked<? } ?>/> Can sign
	  </div>
	  <div class="col-md-4 p-r-o">
	    <input type="checkbox" class="js-switch-dynamic" name="permissions[]" value="delete" <?php if(in_array("delete", $permissions)){ ?>checked<? } ?>/> Can delete
	  </div>
	  </div>
<?php
	}
}else{ ?>
<div class="center-notify">
	<i class="ion-ios-information-outline"></i>
 	<h3>Team member not found!</h3>
</div>

<?php } }elseif ($action == 'getChats') { 

$sql = "SELECT chat.message, chat.time_, chat.sender, users.avatar, users.fname, users.lname FROM chat LEFT JOIN users ON users.id = chat.sender WHERE chat.id > ".$_POST['lastChat']." and chat.file = '".$_POST['sharingKey']."'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) { ?>
        <div class='chat-message <?php if($userId == $row['sender']){ echo "chat-message-sender"; }else{ echo "chat-message-recipient"; } ?>'>
        <img class='chat-image chat-image-default' src='uploads/avatar/<?php echo $row['avatar']; ?>'  data-toggle="tooltip" data-placement="top" title="<?php echo $row['fname']." ".$row['lname']; ?>" />

        <div class='chat-message-wrapper'>
          <div class='chat-message-content'>
            <p><?php echo $row['message']; ?></p>
          </div>

          <div class='chat-details'>
            <span class='chat-message-localization font-size-small'><?php echo date("F j, Y H:i", strtotime($row['time_'])); ?></span>
          </div>

        </div>
      </div>
    <?php
	  }
  }else{
  	echo "empty";
  	exit();
  }
} ?>
 <?php } ?>
