<?php
/*
 * Created on 03-May-12
 * Developer: Nuthan Santharam
 * E-mail: nuthankumarns@gmail.com
 * Website: http://www.nuthan.in
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');
/**
 * Api Component Controller
 */
class ApiController extends JController
{

	function display()
	{
		parent::display();
	}
/*function jump()
  {
  // parent::getView($name= '', $type= 'display', $prefix= '', $config=array());
	parent::display();

  }*/
}
