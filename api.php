<?php
/*
 * Created on 03-May-12
 * Developer: Nuthan Santharam
 * E-mail: nuthankumarns@gmail.com
 * Website: http://www.nuthan.in
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by Api
$controller = JController::getInstance('Api');


// Perform the Request task
$controller->execute(JRequest::getCmd('task'));


//$url='http://www.google.com';
//redirect($url, $msg='', $msgType='message')
//setRedirect($url, $msg=null, $type= 'message');
// Redirect if set by the controller
$controller->redirect();

