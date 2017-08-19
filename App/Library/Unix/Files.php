<?php

/**
 *  Copyright (c) 2015, dsphinx
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
 *      This product includes software developed by the dsphinx.
 *   4. Neither the name of the dsphinx nor the
 *      names of its contributors may be used to endorse or promote products
 *     derived from this software without specific prior written permission.
 *
 *  THIS SOFTWARE IS PROVIDED BY dsphinx ''AS IS'' AND ANY
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
 *  Filename: Files.php
 *  Created : 31/8/15 9:51 AM
 */

/**
 * Class Files
 *
 *  For Unix logs files viewer
 */
class Files
{

	static $largeFileChunk=16384;
	static $lineIFS="\r?\n";        // line end , win or unix

	static $handler=array('fh'   =>NULL,
	                      'error'=>array(),
	);

	static $logfiles=array('lighttpd'          =>'/var/log/lighttpd/access.log',
	                       'lighttpd_errorLogs'=>'/var/log/lighttpd/error.log',
	                       'message'           =>'/var/log/messages',
	                       'shorewal'          =>'/var/log/shorewall.log',
	                       'wtmp'              =>'/var/log/wtmp',
	                       'cpu'               =>'/proc/cpuinfo',
	                       'unixsock'          =>'/proc/net/unix'
	);


	/**
	 * @param        $filename
	 * @param string $mode
	 * @param bool   $printOutput
	 *
	 *
	 *   Open System file on Fs, or get error message
	 *
	 *      return self::$handler['fh']
	 */
	static public function openFilename($filename, $mode='r', $printOutput=TRUE)
	{

		self::$handler['error']=array();
		self::$handler['file'] =$filename;

		$fh=fopen($filename, $mode) or self::$handler['error']=error_get_last();

		if ( $fh != FALSE ) {

			self::$handler['fh']=$fh;
		} else {
			if ( $printOutput ) {
				echo self::error();
			}
		}
	}

	/**
	 * @param null $ret
	 *
	 * @return null|string
	 *
	 *   return system error
	 */
	static public function error($ret=NULL)
	{
		if ( isset( self::$handler['error']['type'] ) ) {
			$ret=__CLASS__ . " error type: " . self::$handler['error']['type'] . ",  " . self::$handler['error']['message'];
		}

		return $ret;
	}


	static public function isLastBlock($blockCount)
	{

		$size=filesize(self::$handler['file']);

		$allBlocks=ceil($size / self::$largeFileChunk);

		echo "FileSize= $size,  Blocks # $allBlocks,  Current block : $blockCount,  buffer " . self::$largeFileChunk . " <br/> ";
		$ret=( $blockCount == $allBlocks ) ? TRUE : FALSE;


	}


	static public function seekToLastBlock()
	{

		$size     =filesize(self::$handler['file']);
		$allBlocks=ceil($size / self::$largeFileChunk);

		$seekTo=( $size <= self::$largeFileChunk ) ? 0 : ( $size - self::$largeFileChunk );

		//echo "FileSize= $size,  Blocks # $allBlocks,   ,  buffer " . self::$largeFileChunk . " , Last seek to $seekTo <br/> ";

		return $seekTo;
	}


	static public function processFilename($lastBlockOnly=TRUE)
	{
		$blocks=array();

		if ( self::$handler['fh'] != FALSE ) {

			$blockCounter  =0;
			$unmatchedText ='';
			$matchedPattern="/(.*?" . self::$lineIFS . ")(?:" . self::$lineIFS . ")+/s";

			stream_set_timeout(self::$handler['fh'], 4);

			fseek(self::$handler['fh'], 0, SEEK_CUR);


			while ( !feof(self::$handler['fh']) ) {

				$blockCounter++;
				$block=fread(self::$handler['fh'], self::$largeFileChunk) or self::$handler['error']=error_get_last();

				if ( $lastBlockOnly ) {

					fseek(self::$handler['fh'], self::seekToLastBlock(), SEEK_SET);
					$block=fread(self::$handler['fh'], self::$largeFileChunk) or self::$handler['error']=error_get_last();


					$unmatchedText=$block;
					break;
				}


				$textToSplit=$unmatchedText . $block;
				$matches    =preg_split($matchedPattern, $textToSplit, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

				//
				//
				$lastMatch=$matches[count($matches) - 1];
				if ( !preg_match("/" . self::$lineIFS . self::$lineIFS . "\$/", $lastMatch) ) {
					$unmatchedText=$lastMatch;
					array_pop($matches);

				} else {
					$unmatchedText='';
				}


				$blocks=array_merge($blocks, $matches);
			}


			if ( $unmatchedText ) {
				$blocks[]=$unmatchedText;
			}

			//Echoc::object($blocks);

		} else {
			self::$handler['error']=array('type'   =>'0x01',
			                              'message'=>' cant find file handle :' . __FILE__
			);

		}

		return $blocks;
	}


	static public function  showLogs($file, $name, $print=TRUE)
	{

		require_once 'Unix/UnixFiles.php';

		// $code =highlight_string($file, TRUE); //, TRUE);
		$code =str_replace("\n", '<br />', $file);
		$bytes=strlen($file);
		$file =$name;


		$size     =Unixfile::show_human(filesize(self::$handler['file']));
		$allBlocks=ceil($size / self::$largeFileChunk);

		$seekTo=( $size <= self::$largeFileChunk ) ? 0 : ( $size - self::$largeFileChunk );
		$bytes.=" {  Size= $size,  Blocks # $allBlocks,   ,  buffer " . self::$largeFileChunk . " , Last seek to $seekTo } <br/> ";


		//Split lines
		$lines=explode('<br />', $code);
		//Count
		$lineCount=count($lines);
		//Calc pad length
		$padLength=strlen($lineCount);


		//Re-Print the code and span again
		$html="<div class='showPHPCode'> <span class=\"showPHPCodeLines\"> Tail </span> $file <br/>
 										  <span class=\"showPHPCodeLines\"> Last Lines</span> $lineCount <span class=\"showPHPCodeLines\"> Size </span> $bytes
		</div>
		<div class='showPHPCodeListing'> <code style='background-color: white;'><span style=\"color: #000000; font-size: x-small;\">";

		//Loop lines
		foreach ( $lines as $i=>$line ) {
			//Create line number
			// $lineNumber=str_pad($i + 1, $padLength, '0', STR_PAD_LEFT);
			$lineNumber=str_pad($lineCount - $i - 1, $padLength, '0', STR_PAD_LEFT);
			//Print line


			$html.=sprintf('<br /><span class="noselect showPHPCodeLines" >%s</span><span class="noselect"> </span>%s', $lineNumber, $line); // │
		}

		//Close span
		$html.="</span></code></div><br/>";
		if ( $print ) {
			echo $html;
		}

		return $html;
	}


	static public function tail($file, $changeBlockSize='3128', $print=TRUE)
	{

		$log                 =self::openFilename($file);
		self::$largeFileChunk=$changeBlockSize;
		$lines               =self::processFilename(TRUE);


		return self::showLogs($lines[0], $file, $print);

		//Echoc::object($lines);
	}


}




/*

foreach ( Files::$logfiles as $name=>$val ) {

	Files::tail($val);

}



 */

