<?php
/*
 * Created on 03-May-12
 * Developer: Nuthan Santharam
 * E-mail: nuthankumarns@gmail.com
 * Website: http://www.nuthan.in
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.model');
  jimport( 'joomla.user.authentication');
   jimport('joomla.application.component.helper');
   jimport( 'joomla.utilities.simplexml' );
/**
 * Api Model
 */
class ApiModelApi extends JModel
{
	/**
	 * @var string msg
	 */
	protected $msg;

	/**
	 * Get the message
	 * @return string The message to be displayed to the user
	 */
	private function checkParameters() {
	  // datatype checks
	  if ($this->username == '') {
	    die('ERROR: user is blank');
	  }
	  if ($this->password == '') {
	    die('ERROR: password is blank');
	  }

}
	public function getCredentials()
	{

		//if (!isset($this->msg))
		//{
		 $this->username = JRequest::getVar('user', '');
		$this->password = JRequest::getVar('password', '');

		$this->checkParameters();
		$auth =  JAuthentication::getInstance();
		$credentials = array( 'username' => $this->username, 'password' => $this->password );
		JFactory::getApplication()->login(array('username'=>$this->username, 'password'=>$this->password));
		 $options = array();
		  $response = $auth->authenticate($credentials, $options);
		return $response;
	}

	public function getxCredentials()
	{

		//if (!isset($this->msg))
		//{
		 $this->username = JRequest::getVar('user', '');
		$this->password = JRequest::getVar('password', '');

		$this->checkParameters();
		$auth =  JAuthentication::getInstance();
		$credentials = array( 'username' => $this->username, 'password' => $this->password );
		JFactory::getApplication()->login(array('username'=>$this->username, 'password'=>$this->password));
		 $options = array();
		  $response = $auth->authenticate($credentials, $options);
		return $response;
	}

	public function getAllProducts()
	{
 $user =  JFactory::getUser();

	$db =& JFactory::getDBO();
	$db->setQuery("SELECT * FROM #__hikashop_product");
	$db->query();
	$pcount=$db->getNumRows();
	 $column= $db->loadAssocList();
	//echo "<pre>";
	for ( $i = 0; $i < $pcount; $i++ ) {
	$result[Result][Data][$i]['product_id']=$column[$i]['product_id'];
	$result[Result][Data][$i]['product_name']=$column[$i]['product_name'];
	$result[Result][Data][$i]['product_description']=strip_tags($column[$i]['product_description']);
			$result[Result][Data][$i]['product_quantity']=$column[$i]['product_quantity'];
	$data[$i]=$column[$i]['product_id'];
	$db->setQuery("SELECT * FROM #__jcomments WHERE object_id='$data[$i]'");
	$db->query();
	$ccount=$db->getNumRows();
	 $column1= $db->loadAssocList();
		if($ccount==0)
		{
		$result[Result][Data][$i][comments]['comment'][0]="No comments";
		}
		else
		{
	 	for($j=0;$j<$ccount;$j++)
	 		{
		$result[Result][Data][$i][comments]['comment'][$j]=$column1[$j]['comment'];
		$result[Result][Data][$i][comments]['name'][$j]=$column1[$j]['name'];

		//$result[$i][$j]['object_id']=$column1[$j]['object_id'];
		//$result[$i][$j]['object_id']=$column1[$j]['object_id'];
		//$result[$i][$j]['object_id']=$column1[$j]['object_id'];
		//$result[$i][$j]['object_id']=$column1[$j]['object_id'];
		//$result[$i][$j]['object_id']=$column1[$j]['object_id'];
		//$result[$i][$j]['object_id']=$column1[$j]['object_id'];
	 		}
	}
	$db->setQuery("SELECT * FROM #__hikashop_file WHERE file_ref_id='$data[$i]'");
	$db->query();
	$icount=$db->getNumRows();
	 $column3= $db->loadAssocList();
		if($icount!=0)
		{
			for($l=0;$l<$icount;$l++)
	 		{
	 			$result[Result][Data][$i]['file_path']=$column3[$l]['file_path'];
	 			$result[Result][Data][$i]['file_id']=$column3[$l]['file_ref_id'];

	 		}
		}
		else
		{
			$result[Result][Data][$i]['file_path']='';
	 			$result[Result][Data][$i]['file_id']='';
		}

	 		$db->setQuery("SELECT AVG( vote_rating ) AS average_rating,vote_ref_id
			FROM #__hikashop_vote
			WHERE vote_ref_id =  '$data[$i]'
			GROUP BY vote_ref_id");
			$column2= $db->loadResult();
			$db->query();
				$vcount=$db->getNumRows();
				 $column2= $db->loadAssocList();
				 for($k=0;$k<$vcount;$k++)
				 {
				$result[Result][Data][$i]['average_rating']=$column2[$k]['average_rating'];
		$result[Result][Data][$i]['vote_ref_id']=$column2[$k]['vote_ref_id'];
				 }
				  $db->setQuery("SELECT user_id FROM #__hikashop_user WHERE user_cms_id='$user->id'");
				$hika_user_id = $db->loadResult();
				//echo $hika_user_id;
				$db->setQuery("SELECT vote_rating FROM #__hikashop_vote WHERE vote_user_id='$hika_user_id' AND vote_ref_id='$data[$i]'");
		$rating = $db->loadResult();
			$result[Result][Data][$i]['my_rating']=$rating;

}


return $result;
	}

	public function getProducts()
	{
$product_id=JRequest::getVar('prod_id');
//echo $product_id;
  if($product_id=='')
{
$dataDB['Result']['Data'][0]['Status']="missing paramaters";
			echo json_encode($dataDB);exit();
}
		$db =& JFactory::getDBO();
/*$db->setQuery("SET GLOBAL log_bin_trust_function_creators=1;
DROP FUNCTION IF EXISTS fnStripTags;
DELIMITER |
CREATE FUNCTION fnStripTags( Dirty varchar(4000) )
RETURNS varchar(4000)
DETERMINISTIC
BEGIN
  DECLARE iStart, iEnd, iLength int;
    WHILE Locate( '<', Dirty ) > 0 And Locate( '>', Dirty, Locate( '<', Dirty )) > 0 DO
      BEGIN
        SET iStart = Locate( '<', Dirty ), iEnd = Locate( '>', Dirty, Locate('<', Dirty ));
        SET iLength = ( iEnd - iStart) + 1;
        IF iLength > 0 THEN
          BEGIN
            SET Dirty = Insert( Dirty, iStart, iLength, '');
          END;
        END IF;
      END;
    END WHILE;
    RETURN Dirty;
END;
|
DELIMITER ;
SELECT fnStripTags(product_description) AS description from #__hikashop_product");*/
$db->setQuery("SELECT * FROM #__hikashop_product WHERE product_id='$product_id");

//echo "<pre>";
echo $db->getNumRows();
//$db->query();

/*echo "<pre>";
//$result = $db->loadResult();
for ( $i = 0; $i <= $db->getNumRows(); $i++ ) {
  $column= $db->loadAssocList($i);
  print_r($column);
}*/
$row['Result']['Data'] = $db->loadAssocList();

return $row;
	}

	function getRatings()
	{
		 $user =  JFactory::getUser();
  $prod_id= JRequest::getVar("prod_id");
  if($prod_id=='')
{
$dataDB['Result']['Data'][0]['Status']="missing paramaters";
			echo json_encode($dataDB);exit();
}

 $db =& JFactory::getDBO();
 $db->setQuery("SELECT user_id FROM #__hikashop_user WHERE user_cms_id='$user->id'");

   //$db->query();
  $hika_user_id = $db->loadResult();
$db->setQuery("SELECT AVG( vote_rating ) AS average_rating
FROM #__hikashop_vote
WHERE vote_ref_id =  '$prod_id'
GROUP BY vote_ref_id");
$average_rating = $db->loadResult();

$db->setQuery("SELECT vote_rating FROM #__hikashop_vote WHERE vote_user_id='$hika_user_id'");
$rating = $db->loadResult();

$dataDB['Result']['Data'][0]['average_votes']=$average_rating;
$dataDB['Result']['Data'][0]['my_rating']=$rating;
return $dataDB;
	}

	public function getUpdateRegistration()
	{
		 $user =  JFactory::getUser();
//echo JRequest::getVar("name");exit();
if(JRequest::getVar("name"))
{
	$user->name = JRequest::getVar("name");
//echo $user->name;exit();
	$user->save($user->id);
	$data['name']="success";
	}
if(JRequest::getVar("user"))
{
	$user->username= JRequest::getVar("user");
	$user->save($user->id);
	$data['username']=(!($user->save($user->id)))?"username already exist":"success";

	}

if(JRequest::getVar("password"))
{
	$pass=JRequest::getVar("password");
	//echo $pass;exit();
	jimport('joomla.user.helper');
	//$pass=JRequest::getVar("password");
	$salt = JUserHelper::genRandomPassword(32);
	$crypt = JUserHelper::getCryptedPassword($pass, $salt);
	$password = $crypt.':'.$salt;
	$user->password=$password;
	$user->save($user->id);
	$data['Result']['Data'][0]['password']="success";}
if(JRequest::getVar("email")){$user->email= JRequest::getVar("email");$user->save($user->id);$data['email']=(!($user->save($user->id)))?"email already exist":"success";}

return $data;
	}

	function validate($email, $password, $firstname, $lastname, $username)
{
if ($username == '') {
   // die('ERROR: user is blank');
$data=array('status'=>"username is blank");
	die(json_encode($data));
  }
  if ($firstname == '') {
    //die('ERROR: password is blank');
$data=array('status'=>"firstname is blank");
	die(json_encode($data));
}
if ($lastname == '') {
    //die('ERROR: password is blank');
$data=array('status'=>"lastname is blank");
	die(json_encode($data));
}
if ($email == '') {
    //die('ERROR: password is blank');
$data=array('status'=>"email is blank");
	die(json_encode($data));
}
if ($password == '') {
    //die('ERROR: password is blank');
$data=array('status'=>"password is blank");
	die(json_encode($data));
}
}


function register_user ($email, $password, $firstname, $lastname, $username){

self::validate($email, $password, $firstname, $lastname, $username);

 jimport('joomla.application.component.helper');
		$config	= JComponentHelper::getParams('com_users');
		//print_r($config);exit();
		// Default to Registered.
		$defaultUserGroup = $config->get('new_usertype', 2);


 $usersParams = &JComponentHelper::getParams( 'com_users' ); // load the Params

 // "generate" a new JUser Object
 $user = JFactory::getUser(0); // it's important to set the "0" otherwise your admin user information will be loaded
//print_r($user);exit();
 $data = array(); // array for all user settings

 // get the default usertype
 $usertype = $usersParams->get( 'new_usertype' );
 if (!$usertype) {
     $usertype = 'Registered';
 }
jimport( 'joomla.factory' );
 // set up the "main" user information

 //original logic of name creation
 //$data['name'] = $firstname.' '.$lastname; // add first- and lastname
 $string = array($firstname, $lastname); // add first- and lastname
$data['name']=implode(' ',$string);
//echo $data['name'];exit();
 $data['username'] = $username; // add username
 $data['email'] = $email; // add email

$usertype = 'Registered';
$data['usertype']=$usertype;
//default to Registered
$data['groups']=array($defaultUserGroup);
 $data['password'] = $password; // set the password
 $data['password2'] = $password; // confirm the password
 $data['sendEmail'] = 1; // should the user receive system mails?
 /* Now we can decide, if the user will need an activation */

 $useractivation = $usersParams->get( 'useractivation' ); // in this example, we load the config-setting
 //echo $useractivation;exit();
 if ($useractivation == 1) { // yeah we want an activation

     jimport('joomla.user.helper'); // include libraries/user/helper.php
     $data['block'] = 0; // block the User
     $data['activation'] =JUtility::getHash( JUserHelper::genRandomPassword() ); // set activation hash (don't forget to send an activation email)
$token=$data['activation'];
//echo $email;exit();
$this->activationEmail($token,$email,$firstname,$lastname,$username,$password);
//exit();
 }
 else { // no we need no activation

     $data['block'] = 1; // don't block the user

 }

 if (!$user->bind($data)) { // now bind the data to the JUser Object, if it not works....
//print_r($user);exit();

     JError::raiseWarning('', JText::_( $user->getError())); // ...raise an Warning
    // echo "nuthan";
     return false; // if you're in a method/function return false

 }

 if (!$user->save()) { // if the user is NOT saved...
//print_r($user);exit();
     JError::raiseWarning('', JText::_( $user->getError())); // ...raise an Warning
     //echo "kumar";
     return false; // if you're in a method/function return false

 }

 return $user; // else return the new JUser object

 }

function activationEmail($token,$email,$firstname,$lastname,$username,$password)
	{
//echo $email;exit();
/*$mail =& JFactory::getMailer();
 print_r($mail);exit();
$config =& JFactory::getConfig();
$mail->addRecipient( 'nuthan@tritonetech.com' );
$mail->setSubject( 'Test message' );
$mail->setBody( 'This is an example email to test the Joomla! JFactory::getMailer() method.  Please ignore it' );
 
if ($mail->Send()) {
  echo "Mail sent successfully.";
} else {
  echo "An error occurred.  Mail was not sent.";
}*/
$site=JUri::base();
//$data['siteurl'].'index.php?option=com_users&task=registration.activate&token='.$token
	

$mail =& JFactory::getMailer();
 //print_r($email);exit();
//$config =& JFactory::getConfig();
$mail->addRecipient($email);
$mail->setSubject( 'Pondoli' );
$mail->setBody( "Hello $firstname $lastname,


You have been added as a User to panchatantra by an Administrator.

This email contains your username and password to log in to $site"."index.php

Username: $username
Password: $password
Please do not respond to this message as it is automatically generated and is for information purposes only.");
 
if ($mail->Send()) {
  return true;
} else {
 return false;
}

		}




 function getRegistration()
 {
 	 $email = JRequest::getVar('email');
 $password = JRequest::getVar('password');
  $firstname = JRequest::getVar('firstname');
   $lastname = JRequest::getVar('lastname');
    $username = JRequest::getVar('username');

 	 if(!self::register_user($email, $password, $firstname, $lastname, $username))
{
	$data['status']="failure";

	}
else
{
	$data['status']="success";
}
return $data;
 }

 function getWriteComment()
 {
 		$user =  JFactory::getUser();
 	$prod_id= JRequest::getVar("prod_id");
 $comment= JRequest::getVar("comment");
  if($prod_id=='' || $comment=='')
{
$dataDB['Result']['Data'][0]['Status']="missing paramaters";
			echo json_encode($dataDB);exit();
}

  $date = new JDate();
  //echo "<pre>";
 //object_id object_group, lang, 	userid	name	username email comment date published
$db =& JFactory::getDBO();
//var_dump($db);
$Remote=$_SERVER[REMOTE_ADDR];
$query = "INSERT INTO #__jcomments(object_id, object_group, lang, userid, name, username, email, comment, ip, date, published
)VALUES('$prod_id','com_hikashop','en-GB','$user->id','$user->name','$user->username','$user->email','$comment','$_SERVER[REMOTE_ADDR]','$date','1')";
//$query="SELECT * FROM #__users";

$db->setQuery($query);


//print_r($db);
$result = $db->query();
//print_r($result);
if($result)
{
	$data['status']="success";

}
else
{
	$data['status']="failure";

}
return $data;
 }

 function getAssignRating()
 {
 	$user =  JFactory::getUser();
  $prod_id= JRequest::getVar("prod_id");
 $rating= JRequest::getVar("rating");
/* include_once(rtrim(JPATH_ADMINISTRATOR,DS).DS.'components'.DS.'com_hikashop'.DS.'helpers'.DS.'helper.php');
  include_once(rtrim(JPATH_ADMINISTRATOR,DS).DS.'components'.DS.'com_hikashop'.DS.'classes'.DS.'user.php');
 $class = hikashop_get('class.user');*/

 //echo "<pre>";
 //print_r($class);
 if($prod_id=='' || $rating=='')
{
$dataDB['Result']['Data'][0]['Status']="missing paramaters";
			echo json_encode($dataDB);exit();
}
 $db =& JFactory::getDBO();
   //$date = new JDate();
   $now=time();
   //print_r($date);exit();
   $db->setQuery("SELECT user_id FROM #__hikashop_user WHERE user_cms_id='$user->id'");

   //$db->query();
  $hika_user_id = $db->loadResult();
 //  print_r($result);
   //echo $hika_user_id;
 $query = "INSERT INTO #__hikashop_vote(vote_ref_id, vote_type,	vote_user_id, vote_rating, vote_date, vote_published
)VALUES('$prod_id','product','$hika_user_id','$rating','$now','1')";
$db->setQuery($query);
//INSERT INTO ebook_hikashop_vote(vote_ref_id , vote_user_id) VALUES(  '8' , '3') ON DUPLICATE KEY UPDATE vote_rating='8'
//INSERT INTO ebook_hikashop_vote(vote_ref_id , vote_user_id, vote_rating) VALUES(  '8' , '3','7') ON DUPLICATE KEY UPDATE vote_rating='7'
//print_r($db);
$result = $db->query();
//print_r($result);
if($result)
{
	$data['status']="success";
}
else
{
	$data['status']="failure";
}
return $data;
 }

	function getUserBooks( $params )
    {
        $db =& JFactory::getDBO();
			$db->setQuery("SELECT DISTINCT a.user_id, a.read_start_time, a.book_id, b.product_id, b.product_name, c.name, c.id
		FROM #__hikashop_track AS a
		LEFT JOIN #__hikashop_product AS b ON a.book_id = b.product_id
		LEFT JOIN #__users AS c ON a.user_id = c.id
		ORDER BY a.read_start_time DESC 
		LIMIT 5");
	$db->query();
	$pcount=$db->getNumRows();
	 $column= $db->loadAssocList();
	// print_r($column);
	 return $column;
    }

}
