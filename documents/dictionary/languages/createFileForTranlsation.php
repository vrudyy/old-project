<?php

/**************
Steps:
1. Copy the existing, live dictionary Greek.php into the current directory
2. In all the source files, search for all the instances where the string $dic->translate appears
3. Copy the results of the search and add them into a new file.
4. Save the file into the current directory with the name allInstances.txt
5. Run this php script from the terminal: php createFileForTranlsation.php
6. The script will look for all the new words, which do not exist in the current dictiionry and that need to be translated. 
7. The results will be saved into the file called translatedWords.php
8. Edit that file by translating each word, adding the translation within the quotes ""
9. Copy the entries and append them at the end of the Greek.php dictionary.
10. Copy the dictionary onto the server.
*************/


//First make an array of all the words that are currently on the dictionary

$currentDict = array();
$uniqWords = array();

$handle = fopen("Greek.php", "r");
if ($handle) 
{
  while (($line = fgets($handle)) !== false) 
  {
     $mystring = $line;
     $findme   = "\$this->words[\"";
     $pos = strpos($mystring, $findme);

     if ($pos === false) 
     {
          //The line doesn't contain what you look for
     } 
     else 
     {
        //echo $mystring."\n";
        $str1 = substr($mystring, $pos+14);
        //echo $str1."\n";


        $mystring = $str1;
        $findme   = "\"]";
        $pos = strpos($mystring, $findme);
        
        if ($pos === false) 
        {
             //The line doesn't contain what you look for
        } 
        else 
        {
           //echo $mystring."\n";
           $str2 = substr($mystring,0, $pos - strlen($str1));
           //echo $str2."\n";
           array_push($currentDict, $str2);
        }
     }
  }
}

//print_r($currentDict);

//Now read all the translate requests and find out which ones are not already translated.
$handle = fopen("allInstances.txt", "r");
$fp = fopen('translatedWords.php', 'w');
fwrite($fp, "<?\n");

if ($handle) 
{
    while (($line = fgets($handle)) !== false) 
    {
       $mystring = $line;
       $findme   = "\$dic->translate(\"";
       $pos = strpos($mystring, $findme);

       // Note our use of ===.  Simply == would not work as expected
       // because the position of 'a' was the 0th (first) character.
       if ($pos === false) 
       {
       	  	//The line doesn't contain what you look for
       } 
       else 
       {
           $str1 = substr($mystring, $pos+17);
           //echo $str1."\n";

           //Trim everything from the end
           $mystring = $str1;
           $findme   = "\")";
           $pos = strpos($mystring, $findme);
           if ($pos === false) 
           {
           		//The line doesn't contain what you look for
           } 
           else 
           {
           	 	$str2 = substr( $str1, 0, $pos - strlen($str1) );

          		//Ignore the Validator lines
          		$mystring = $str2;
          		$findme   = "Validator";
          		$pos = strpos($mystring, $findme);
          		if ($pos === false) 
          		{
          			 $str2lower = strtolower($str2);
                 //echo $str2lower."\n";

                 if (in_array($str2lower, $currentDict) )
                 {
                   //echo "Match found\n";
                 }
                 else
                 {
                  if (in_array($str2lower, $uniqWords) )
                  {
                  
                  }
                  else
                  {  
                   //echo "Match not found: ".$str2."\n";
                    array_push($uniqWords, $str2lower);
                  }
                 }

          		} 
          		else 
          		{
                //do nothing
          		}
           }
       }
    }

    foreach ($uniqWords as $string) 
    {
       $str3 = "\$this->words[\"".$string."\"] = \"\";\n";
      //echo $str3;
       fwrite($fp, $str3);
    }
    fwrite($fp, "\n?>");

    fclose($handle);
    fclose($fp);
} 
else 
{
    // error opening the file.
} ?>