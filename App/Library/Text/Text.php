<?php

/**
 *  Copyright (c) 5/4/15 , dsphinx@plug.gr
 *  All rights reserved.
 *
 *  Redistribution and use in source and binary forms, with or without
 *  modification, are permitted provided that the following conditions are met:
 *   1. Redistributions of source code must retain the above copyright
 *      notice, this list of conditions and the following disclaimer.
 *   2. Redistributions in binary form must reproduce the above copyright
 *      notice, this list of conditions and the following disclaimer in the
 *      documentation and/or other materials provided with the distribution.
 *   3. All advertising materials mentioning features or use of this software
 *      must display the following acknowledgement:
 *      This product includes software developed by the dsphinx@plug.gr.
 *   4. Neither the name of the dsphinx@plug.gr nor the
 *      names of its contributors may be used to endorse or promote products
 *     derived from this software without specific prior written permission.
 *
 *  THIS SOFTWARE IS PROVIDED BY dsphinx@plug.gr ''AS IS'' AND ANY
 *  EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 *  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 *  DISCLAIMED. IN NO EVENT SHALL dsphinx BE LIABLE FOR ANY
 *  DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 *  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 *  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 *  ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 *  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 *  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *  Created : 9:38 PM - 5/4/15
 *
 */
class Text
{

	/**
	 * @param $text
	 *
	 *  Strip all whitespace to only one
	 *
	 * @return mixed
	 */
	static function stripWhitespace($text)
	{
		return preg_replace('/\s+/', ' ', $text);
	}


	/**
	 * @param $text
	 * @param $matches
	 * @param $replace
	 *
	 *  Select $matches all words array with style i u l
	 *
	 *    Text::wordSelector($text, array("plug"),"u");
	 *    Text::wordSelector($text, array("plug"),"label");
	 *
	 * @return mixed
	 */
	static function  wordSelector($text, $matches, $replace)
	{
		foreach ( $matches as $match ) {
			switch ( $replace ) {
				case "label":
					$text=preg_replace("/([^\w]+)($match)([^\w]+)/", "$1<span class=\"label label-info\">$2</span>$3", $text);
					break;
				case "mark":
				case "u":
				case "b":
				case "i":
					$text=preg_replace("/([^\w]+)($match)([^\w]+)/", "$1<$replace>$2</$replace>$3", $text);
					break;
				default:
					$text=preg_replace("/([^\w]+)$match([^\w]+)/", "$1$replace$2", $text);
					break;
			}
		}

		return $text;
	}


	/**
	 * @param $text
	 * @param $max
	 * @param $symbol
	 *
	 *  Truncate text fro $max characters
	 *
	 * @return string
	 */
	static function textTruncate($text, $max, $symbol="...")
	{
		$temp=substr($text, 0, $max);
		$last=strrpos($temp, " ");
		$temp=substr($temp, 0, $last);
		$temp=preg_replace("/([^\w])$/", "", $temp);

		return "$temp$symbol";
	}

	/**
	 * @param $text
	 *
	 * remove accents , last character from $from
	 *
	 * @return mixed
	 */
	static function removeAccents($text)
	{
		$from=array("ç",
		            "æ",
		            "œ",
		            "á",
		            "é",
		            "í",
		            "ó",
		            "ú",
		            "à",
		            "è",
		            "ì",
		            "ò",
		            "ù",
		            "ä",
		            "ë",
		            "ï",
		            "ö",
		            "ü",
		            "ÿ",
		            "â",
		            "ê",
		            "î",
		            "ô",
		            "û",
		            "å",
		            "e",
		            "i",
		            "ø",
		            "u",
		            "Ç",
		            "Æ",
		            "Œ",
		            "Á",
		            "É",
		            "Í",
		            "Ó",
		            "Ú",
		            "À",
		            "È",
		            "Ì",
		            "Ò",
		            "Ù",
		            "Ä",
		            "Ë",
		            "Ï",
		            "Ö",
		            "Ü",
		            "Ÿ",
		            "Â",
		            "Ê",
		            "Î",
		            "Ô",
		            "Û",
		            "Å",
		            "Ø"
		);
		array("c",
		      "ae",
		      "oe",
		      "a",
		      "e",
		      "i",
		      "o",
		      "u",
		      "a",
		      "e",
		      "i",
		      "o",
		      "u",
		      "a",
		      "e",
		      "i",
		      "o",
		      "u",
		      "y",
		      "a",
		      "e",
		      "i",
		      "o",
		      "u",
		      "a",
		      "e",
		      "i",
		      "o",
		      "u",
		      "C",
		      "AE",
		      "OE",
		      "A",
		      "E",
		      "I",
		      "O",
		      "U",
		      "A",
		      "E",
		      "I",
		      "O",
		      "U",
		      "A",
		      "E",
		      "I",
		      "O",
		      "U",
		      "Y",
		      "A",
		      "E",
		      "I",
		      "O",
		      "U",
		      "A",
		      "O"
		);

		return str_replace($from, $to, $text);
	}


	/**
	 * @param $text
	 * @param $size
	 * @param $mark
	 *
	 *To shorten a URL (or other string)
	 *
	 * echo shortenText($text, 60, "/-/-/");
	 *
	 * @return string
	 */
	static function shortenText($text, $size, $mark="/-/-/")
	{
		$len=strlen($text);
		if ( $size >= $len ) return $text;
		$a=substr($text, 0, $size / 2 - 1);
		$b=substr($text, $len - $size / 2 + 1, $size / 2 - 1);

		return $a . $mark . $b;
	}


	/**
	 * Remove HTML tags, including invisible text such as style and
	 * script code, and embedded objects.  Add line breaks around
	 * block-level tags to prevent word joining after tag removal.
	 *
	 *
	 * Read an HTML file, convert to UTF-8, strip out HTML tags and invisible content, and decode HTML entities into
	 * UTF-8:
	 *
	 * $raw_text = file_get_contents( $filename );
	 *
	 * Get the file's character encoding from a <meta> tag
	 * preg_match( '@<meta\s+http-equiv="Content-Type"\s+content="([\w/]+)(;\s+charset=([^\s"]+))?@i',
	 * $raw_Text, $matches );
	 * $encoding = $matches[3];
	 *
	 * /* Convert to UTF-8 before doing anything else
	 * $utf8_text = iconv( $encoding, "utf-8", $raw_text );
	 *
	 * /* Strip HTML tags and invisible text
	 * $utf8_text = strip_html_tags( $utf8_text );
	 *
	 * /* Decode HTML entities
	 * $utf8_text = html_entity_decode( $utf8_text, ENT_QUOTES, "UTF-8" );
	 * Explanation
	 *
	 * http://nadeausoftware.com/articles/2007/09/php_tip_how_strip_html_tags_web_page
	 */
	static function strip_html_tags($text)
	{
		$text=preg_replace(array(// Remove invisible content
		                         '@<head[^>]*?>.*?</head>@siu',
		                         '@<style[^>]*?>.*?</style>@siu',
		                         '@<script[^>]*?.*?</script>@siu',
		                         '@<object[^>]*?.*?</object>@siu',
		                         '@<embed[^>]*?.*?</embed>@siu',
		                         '@<applet[^>]*?.*?</applet>@siu',
		                         '@<noframes[^>]*?.*?</noframes>@siu',
		                         '@<noscript[^>]*?.*?</noscript>@siu',
		                         '@<noembed[^>]*?.*?</noembed>@siu',
		                         // Add line breaks before and after blocks
		                         '@</?((address)|(blockquote)|(center)|(del))@iu',
		                         '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
		                         '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
		                         '@</?((table)|(th)|(td)|(caption))@iu',
		                         '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
		                         '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
		                         '@</?((frameset)|(frame)|(iframe))@iu',
		), array(' ',
		         ' ',
		         ' ',
		         ' ',
		         ' ',
		         ' ',
		         ' ',
		         ' ',
		         ' ',
		         "\n\$0",
		         "\n\$0",
		         "\n\$0",
		         "\n\$0",
		         "\n\$0",
		         "\n\$0",
		         "\n\$0",
		         "\n\$0",
			), $text);

		return strip_tags($text);
	}

}