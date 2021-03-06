<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2014 (original work) Open Assessment Technologies SA;
 *
 *
 */

return array(
    'name'        => 'taoBooklet',
	'label'       => 'Test Booklets',
	'description' => 'An extension for TAO to create test booklets (publishable in MS-Word and PDF along with Answer Sheets)',
    'license'     => 'GPL-2.0',
    'version'     => '0.1',
	'author'      => 'Open Assessment Technologies SA',
	'requires'    => array(
	   'tao'          => '>=2.7.3',
	   'taoQtiTest'   => '>=2.6',
        'taoQtiPrint' => '>=0.1.0'
    ),
	// for compatibility
	'dependencies' => array('tao','taoQtiTest'),
	'managementRole' => 'http://www.tao.lu/Ontologies/generis.rdf#taoBookletManager',
    'acl' => array(
        array('grant', 'http://www.tao.lu/Ontologies/generis.rdf#taoBookletManager', array('ext'=>'taoBooklet')),
	),
	'models' => array(
	   'http://www.tao.lu/Ontologies/Booklet.rdf#Booklet'
	),
	'install' => array(
    	'php' => array(
    	    dirname(__FILE__).'/scripts/install/setupStorage.php',
    	),
		'rdf' => array(
			dirname(__FILE__). '/scripts/install/booklet.rdf',
		),
		'checks' => array(
		)
	),
    'uninstall' => array(
    ),
    'autoload' => array (
        'psr-4' => array(
            'oat\\taoBooklet\\' => dirname(__FILE__).DIRECTORY_SEPARATOR
        )
    ),
    'routes' => array(
        '/taoBooklet' => 'oat\\taoBooklet\\controller'
    ),
	'constants' => array(
	    # views directory
	    "DIR_VIEWS" => dirname(__FILE__).DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR,

		#BASE URL (usually the domain root)
		'BASE_URL' => ROOT_URL.'taoBooklet/',

	    #BASE WWW required by JS
	    'BASE_WWW' => ROOT_URL.'taoBooklet/views/'
	),
    'extra' => array(
        'structures' => dirname(__FILE__).DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR.'structures.xml',
    )
);
