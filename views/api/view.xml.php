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

	function getLogin()
	{
		return $this->get('xCredentials');
	}

	function getAllProducts()
	{
		return $this->get('AllProducts');
	}

	function getProducts()
	{
		return $this->get('Products');
	}

	function getRatings()
	{
		return $this->get('Ratings');
	}

	function getUpdateRegistration()
	{
		return $this->get('updateRegistration');
	}

	function getRegistration()
	{
		return $this->get('Registration');
	}

	function getWriteComment()
	{
		return $this->get('WriteComment');
	}

	function getAssignRating()
	{
		return $this->get('AssignRating');
	}



}