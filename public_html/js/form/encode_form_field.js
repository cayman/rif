// Encodes UNICODE text value in current browser codepage like regular POST field form encoded when FORM tags is posted.
// Allows easy use non-ascii characters in AJAX requests to PHP in non-UTF8 codepages
// Provides "encodeFormField" function (drop-in replacement for "escape" and/or "encodeURIComponent")
// Note: Avoid Copy-n-Paste of this file, since most editors with break the encode xlate line
// Note: This file must be loaded in same codepage as code that use it (usually its true, but you have to know it) 
// Free permission to use granted under BSD licence
// Written by Serge Ageyev <justboss777@gmail.com>
// Version 1.0 2009 SA: Initial implementation
// Version 1.1 2010 SA: encodeFormFieldDefaultFallback function support is added

var encodeFormField_xlate_line =        
       // DO NOT EDIT THE LINE BELLOW! It MUST contains single-byte characters with codes from 0x080 to 0xFF (Note: be cariful with Copy-n-Paste!) 
       '€‚ƒ„…†‡ˆ‰Š‹Œ‘’“”•–—˜™š›œŸ ¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏĞÑÒÓÔÕÖ×ØÙÚÛÜİŞßàáâãäåæçèéêëìíîïğñòóôõö÷øùúûüışÿ';

/**
  * Encodes text value in current browser codepage
  * like regular POST field form encoded when FORM tags posted
  * You can use this function instead of encodeURIComponent
  * @param {String} text Text to encode
  * @param {function} fallbackFunc [Optional] to be called to translate chars that not exist in current codepage
  * @return text param encoded like POST form param
  * @type String
  */

var encodeFormField = function(text, fallbackFunc)
 {
  if (encodeFormFieldIsPageOnUTF8())
   {
    // We are on UTF8, use encodeURIComponent
    return(encodeURIComponent(text));
   }

  if (fallbackFunc == null)
   {
    // If no fallback is defined, use current default
    fallbackFunc = encodeFormFieldDefaultFallback;
   }

  text = ''+text; // Force text to be text

  var len = text.length;

  var result = '';

  var pos,text_char;

  for (var i = 0; i < len; i++)
   {
    text_char = text.charAt(i);

    if (text_char.charCodeAt(0) < 0x80)
     {
      result += escape(text_char);
     }
    else
     {
      pos = encodeFormField_xlate_line.indexOf(text_char);

      if (pos >= 0)
       {
        result += '%'+(pos+0x80).toString(16).toUpperCase();
       }
      else
       {
        result += ''+fallbackFunc(text_char);
       }
     }
   }

  return(result);
 }

/**
  * Default fallback function to be called to translate chars that not exist in current codepage
  * @param {String} charToEncode UNICODE character not found in current CP to encode
  * @return string represents encoded text to send instead of original character
  * @type String
  */

var encodeFormFieldDefaultFallback = function(charToEncode)
 {
  // if incoming unicode character is not in current codepage, you have to translate it somehow
  // Examples of possible fallbacks is here:

  // return(escape(text_char)); // Send char as unicode %uXXXX
  // return('');                // Ignore this char
  // return(escape('?'));       // Send encoded question char
  return('%26%23'+charToEncode.charCodeAt(0)+'%3B'); // Send &#{code}; [most browsers do this way on FORM encode]
 }

/**
  * Retruns true if current encoding is UTF8
  * @return true if current encoding is UTF8
  * @type Boolean
  */

var encodeFormFieldIsPageOnUTF8 = function()
 {
  // Try to AutoDetect UTF8:

  // If script is loaded in non-fixed character encoding (UTF-8 for example), switch to encodeURIComponent instead of POST encoding
  // encodeFormField_from is not a valid UTF8 string, but most browsers will try interpret it and broke its length or content.
  // Notes: 
  // * This is quite non-elegant, hope somebody will find better way to detect UTF8
  // * Sure, I suggest to use encodeURIComponent directly if you are know that your site is under UTF8
  //   (for example, you can put "encodeFormField = encodeURIComponent" statement if your code page is UTF8)
  // * Under IE6, when script (page) is loaded in UTF-8, the encodeFormField_xlate_line may be broken completely
  //   (as IE thinks it is unterminated string constant). Hope somebody will find solutions for this.

  if (encodeFormField_xlate_line.length != 128)
   {
    return(true);
   }

  if (encodeFormField_xlate_line.substring(0,1) == encodeFormField_xlate_line.substring(1,2))
   {
    return(true);
   }

  if (encodeFormField_xlate_line.substring(0,1) == encodeFormField_xlate_line.substring(127,128))
   {
    return(true);
   }

  return(false);
 }
