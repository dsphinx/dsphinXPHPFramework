<?php
	/**
	 *  Copyright (c) 2014, dsphinx@plug.gr
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
	 *
	 *
	 *    Machine Learning - Semantic Web
	 *
	 *    HTML 5     Tag    Description
	 *
	 *   Section , Article , Header ,  time datetime="2011-10-10T12:16:20Z"
	 *
	 *
	<article>	Defines an article
	<aside>	Defines content aside from the page content
	<details>	Defines additional details that the user can view or hide
	<figcaption>	Defines a caption for a <figure> element
	<figure>	Specifies self-contained content, like illustrations, diagrams, photos, code listings, etc.
	<footer>	Defines a footer for a document or section
	<header>	Specifies a header for a document or section
	<mark>	Defines marked/highlighted text
	<nav>	Defines navigation links
	<section>	Defines a section in a document
	<summary>	Defines a visible heading for a <details> element
	<time>	Defines a date/time
	 *
	 * <div vocab="http://purl.org/dc/terms/">
	 *
	 *
	 */


	class Semantic extends Echoc
	{

		static $tags;

		/*
		 *   from drupal
		 *
		 *                  dublin core standards
		 *
		 *    PREFIX
		 *    NAMESPACES
		 *    Semantic URIs
		 */

		/**
		 *      Semantic::article($html , $semanticAddons);
		 *      Semantic::section($html);
		 *      Semantic::header($html);

		As of PHP 5.3.0
		 *
		 *
		 *                 self::header(  $header   , ' class="articleTitle" property="title" ');
		 *
		 */
		public static function __callStatic($name, $arguments)
		{
			$semanticName = isset($arguments[1]) ? $name . " " . $arguments[1] : $name;
			$_ret         = self::tag($semanticName) . $arguments[0] . self::tag($name, TRUE);

			return ($_ret . self::$_EOL);
		}

		static public function tag($_tagname, $end = FALSE)
		{
			$_ret = $end ? "</$_tagname>" : "<$_tagname>";

			return ($_ret);
		}

		/**
		 * @param      $article
		 * @param null $header
		 * @param null $footer
		 *
		 *
		 *   Return Article for WEB Semantic
		 *
		 * @return mixed
		 *
		 */
		public static function showArticle($article, $header = NULL, $footer = NULL)
		{
			//$_ret = '<div ' . self::$vocabulary . '>';
			$_ret = '<div>';

			if ($header) {
				$_ret .= self::header($header, ' class="articleTitle" property="title" ');
			}

			$_ret .= self::article($article, ' property="http://purl.org/dc/terms/Text" '); // dcmitype

			if ($footer) {
				$_ret .= self::footer($footer);
			}

			$_ret .= '</div>';

			return ($_ret);
		}

		public static function showFoaf($name, $email = NULL, $phone = NULL)
		{
			$_ret = '<div vocab="' . self::getURI('foaf') . '" property="' . self::getURI('creaator') . '" typeof="Person">';
			$_ret .= '<span property="name">' . $name . '</span>';


			if ($email) {
				$_ret .= self::header('<div  >' . $email . '</h2>');
			}

			if ($phone) {
				$_ret .= self::footer($phone);
			}

			$_ret .= '</div>';

			return ($_ret);

		}

		/**
		 * @param $ns
		 *
		 * @return mixed
		 *
		 *    get URI from RDF Namespaces
		 */
		static function getURI($ns)
		{
			$rdf = self::RDFNamespaces();

			return ($rdf [$ns]);
		}


		/**
		 * @return array
		 *
		 *   most significant is DC - dublic core meta data
		 *     and RDFa
		 *
		 * http://www.w3.org/2011/rdfa-context/rdfa-1.1
		 */
		static function RDFNamespaces()
		{
			return array(
				'content'      => 'http://purl.org/dc/elements/1.1/',
				'rdfa'         => 'http://www.w3.org/ns/rdfa#',
				'dc11'         => 'http://purl.org/dc/elements/1.1/',

				'void'         => 'http://rdfs.org/ns/void#',
				// 'content' => 'http://purl.org/rss/1.0/modules/content/',
				// 'vocab' => 'http://www.w3.org/1999/xhtml/vocab',
				'dc'           => 'http://purl.org/dc/terms/', // dublin core
				// 'dc'      => 'http://www.w3.org/2011/rdfa-context/html-rdfa-1.1',           // dublin core
				'foaf'         => 'http://xmlns.com/foaf/0.1/',
				'og'           => 'http://ogp.me/ns#',

				'rdf'          => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
				'rdfs'         => 'http://www.w3.org/2000/01/rdf-schema#',

				'xml'          => 'http://www.w3.org/XML/1998/namespace',

				'ma'           => 'http://www.w3.org/ns/ma-ont#',
				'owl'          => 'http://www.w3.org/2002/07/owl#',
				'sioc'         => 'http://rdfs.org/sioc/ns#',
				'sioct'        => 'http://rdfs.org/sioc/types#',
				'skos'         => 'http://www.w3.org/2004/02/skos/core#',
				'xsd'          => 'http://www.w3.org/2001/XMLSchema#',
				'creator'      => 'http://purl.org/dc/terms/creator',
				'accom'        => 'http://purl.org/acco/ns#',
				'acl'          => 'http://www.w3.org/ns/auth/acl#',
				'app'          => 'http://purl.org/net/app#',
				'audio'        => 'http://purl.org/media/audio#',
				'bookmark'     => 'http://www.w3.org/2002/01/bookmark#',
				'dbpedia'      => 'http://dbpedia.org/resource/',
				'dbpedia-owl'  => 'http://dbpedia.org/ontology/',
				'dppedia-prop' => 'http://dbpedia.org/property/',
				'wsdl'         => 'http://schemas.xmlsoap.org/wsdl/',

				'skos'         => 'http://www.w3.org/2004/02/skos/core#'

			);
		}
		/*
		 *
		 *   <h3 vocab="http://xmlns.com/foaf/0.1/" property="http://purl.org/dc/terms/creator" typeof="Person">
				<span property="name">Alice Birpemswick</span>,
				Email: <a property="mbox" href="mailto:alice@example.com">alice@example.com</a>,
				Phone: <a property="phone" href="tel:+1-617-555-7332">+1 617.555.7332</a>
			  </h3>
		 */


	}


