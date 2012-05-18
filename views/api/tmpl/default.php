<?php
/*
 * Created on 03-May-12
 * Developer: Nuthan Santharam
 * E-mail: nuthankumarns@gmail.com
 * Website: http://www.nuthan.in
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<?php //$document =& JFactory::getDocument();
//$document->setMimeEncoding( 'text/json' );?>
<?php //echo $this->msg;?>
<?php
$format=JRequest::getVar('format');
if($format=='xml')
{
$document =& JFactory::getDocument();
$document->setMimeEncoding( 'text/xml' );
/*$dom = new DOMDocument('1.0', 'utf-8');
foreach ($this->msg as $key => $value) {
          	   //   $value = str_replace("&#x0;", "", $value);
           //    echo "\t\t\t".'<'.$key.'>'.htmlspecialchars($value).'</'.$key.'>'."\n";
         $element = $dom->createElement($key, $value);
         $dom->appendChild($element);

echo $dom->saveXML();
         }*/


// We insert the new element as root (child of the document)

// Output XML header.
echo '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";

// Output root element.
echo '<root>'."\n";

// Output the data.
echo "\t".'<items>'."\n";
if(!empty($this->msg)){
    // foreach($this->msg as $datum){
      //    echo "\t\t".'<item>'."\n";
          foreach ($this->msg as $key => $value) {
                 	  //  echo var_dump($value);
               echo "\t\t\t".'<'.$key.'>'.htmlspecialchars($value).'</'.$key.'>'."\n";
         }
      //   echo "\t\t".'</item>'."\n";
    // }
}
echo "\t".'</items>'."\n";

// Terminate root element.
echo '</root>'."\n";
}
else
{
	$document =& JFactory::getDocument();
$document->setMimeEncoding( 'text/json' );
	echo json_encode($this->msg);exit();
}?>


