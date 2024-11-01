<?php

session_start();
$all_letters = "A B C D E F G H I J K L M N O P Q R S T U V W X Y Z";
$all_angles= "-40 -20 10 20 40";
$all_fonts = "ARIALNBI.TTF ARIALN.TTF";
 
// create arrays
$letter_array = explode(" ", $all_letters);
$angle_array = explode(" ",$all_angles);
$font_array = explode(" ", $all_fonts);

// mix arrays
shuffle ($letter_array );
shuffle($angle_array);
shuffle($font_array);
 
// the first 5 element from the mixed array
$text = array_slice($letter_array, 0, 5);
 
// save variable in SESSION
$_SESSION['captcha_wert'] = $text;
$_SESSION['captcha_time'] = time();

Header ("Content-type: image/png");
 
$background_image = ImageCreateFromPNG ("captcha_background.png");
 
// define letter color
$letter_color = ImageColorAllocate ($background_image, 0,0,0);

// fontsize, angel, X-Pos., Y-Pos, color, font, letter
ImageTTFText ($background_image, 30, $angle_array[0], 20,  35, $letter_color, $font_array[0],
              $text[0]);
ImageTTFText ($background_image, 30, $angle_array[1], 55, 40, $letter_color, $font_array[1],
              $text[1]);
ImageTTFText ($background_image, 30,  $angle_array[2], 85, 40, $letter_color, $font_array[0],
              $text[2]);
ImageTTFText ($background_image, 30,  $angle_array[3],  120, 40, $letter_color,$font_array[1],
              $text[3]);
ImageTTFText ($background_image, 30,  $angle_array[4],  150, 40, $letter_color, $font_array[0],
              $text[4]);
 
// Output format PNG
ImagePng     ($background_image);
 
// Release resource
ImageDestroy ($background_image);
?>