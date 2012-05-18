<?php
/*
 * Created on 03-May-12
 * Developer: Nuthan Santharam
 * E-mail: nuthankumarns@gmail.com
 * Website: http://www.nuthan.in
 */

defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for the Api Component
 */
class ApiViewApi extends JView
{
	// Overwriting JView display method
	function display($tpl = null)
	{
	$task=JRequest::getVar('task');
		//echo $task;
		//exit();
		//$this->get('Ister');
		// Assign data to the view
		$this->msg = $this->$task();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// Display the view
		parent::display($tpl);
	}
			
	function isLoggedIn()
	{
	$user=JFactory::getUser();
		if($user->id=='0')
			{
			//echo json_encode("not logged");
			$data['Result']['Data'][0]['Status']="user not logged in or session expired";
			$this->msg=$data;
			parent::display($tpl);
			//return false;
			}
	}	

	function Login()
	{
		//$this->isLoggedIn();
		return $this->get('xCredentials');
	}

	function AllProducts()
	{
		$this->isLoggedIn();
		return $this->get('AllProducts');
	}

	function Products()
	{
		$this->isLoggedIn();
		return $this->get('Products');
	}

	function Ratings()
	{
		$this->isLoggedIn();
		return $this->get('Ratings');
	}

	function UpdateRegistration()
	{
		$this->isLoggedIn();
		return $this->get('updateRegistration');
	}

	function Registration()
	{
		//$this->isLoggedIn();
		return $this->get('Registration');
	}

	function WriteComment()
	{
		$this->isLoggedIn();
		return $this->get('WriteComment');
	}

	function AssignRating()
	{
		$this->isLoggedIn();
		return $this->get('AssignRating');
	}
	
	function UserBooks()
	{
		return $this->get('UserBooks');
	}



}
