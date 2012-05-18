<?php
/*
 * Created on 08-May-12
 * Developer: Nuthan Santharam
 * E-mail: nuthankumarns@gmail.com
 * Website: http://www.nuthan.in
 */
class ApiViewApi extends JView
{
	// Overwriting JView display method
	function display($tpl = null)
	{

		//$this->get('Ister');
		// Assign data to the view
		$this->msg = $this->get('Credentials');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// Display the view
		parent::display($tpl);
	}


}
