<?php

/**
 * Hoa Framework
 *
 *
 * @license
 *
 * GNU General Public License
 *
 * This file is part of HOA Open Accessibility.
 * Copyright (c) 2007, 2010 Ivan ENDERLIN. All rights reserved.
 *
 * HOA Open Accessibility is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * HOA Open Accessibility is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with HOA Open Accessibility; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 *
 * @category    Framework
 * @package     Hoa_Xyl
 * @subpackage  Hoa_Xyl_Element_Basic
 *
 */

/**
 * Hoa_Core
 */
require_once 'Core.php';

/**
 * Hoa_Xyl_Element
 */
import('Xyl.Element') and load();

/**
 * Hoa_Xml_Element_Read
 */
import('Xml.Element.Read') and load();

/**
 * Class Hoa_Xyl_Element_Basic.
 *
 * 
 *
 * @author      Ivan ENDERLIN <ivan.enderlin@hoa-project.net>
 * @copyright   Copyright (c) 2007, 2010 Ivan ENDERLIN.
 * @license     http://gnu.org/licenses/gpl.txt GNU GPL
 * @since       PHP 5
 * @version     0.1
 * @package     Hoa_Xyl
 * @subpackage  Hoa_Xyl_Element_Basic
 */

class          Hoa_Xyl_Element_Basic
    extends    Hoa_Xml_Element_Read
    implements Hoa_Xyl_Element {

    /**
     * Distribute data into the XYL tree. Data are linked to element through a
     * reference to the data bucket in the super root.
     *
     * @access  public
     * @return  void
     */
    public function linkData ( Array &$data, Array &$parent = null ) {

        if(false === $this->attributeExists('value')) {

            foreach($this as $element)
                $element->linkData($data, $parent);

            return;
        }

        $source  = $this->readAttribute('value');
        $branche = substr($source, 1);
        $store   = &$this->selectSuperRoot()->_getStore($this);
        $store   = array();

        if(!isset($data[0][$branche])) {

            var_dump('fuck off :-)');

            exit;

            /*
            $store['data'][] = &$data[$branche];
            $store['child']  = &$data[$branche][0];

            /*
            foreach($this as $element)
                $element->linkData($data[$branche][0]);
            */
        }
        else {

            $store['branche'] = $branche;

            if(is_array($data[0][$branche])) {

                if(null === $parent) {

                    $store['data']    = &$data;
                    $store['current'] = 0;
                }
                else {

                    $store['data']    = null;
                    $store['current'] = 0;
                }

                $store['parent'] = &$parent;

                //print_r($store);

                foreach($this as $element)
                    $element->linkData($data[0][$branche], $store);
            }
            else {

                if(null === $parent) {

                    $store['data']    = &$data;
                    $store['current'] = 0;
                }
                else {

                    $store['data']    = null;
                    $store['current'] = 0;
                }

                $store['parent'] = &$parent;

                //print_r($store);

                /*
                $store['data'][] = &$data[0][$branche];

                if(null === $parent)
                    $store['curr'] = &$store['data'];
                else
                    $store['curr'] = &$parent['curr'][$branche];
                */
            }
        }

        return;
    }

    /**
     * Get data of this element.
     *
     * @access  public
     * @return  array
     */
    public function &getData ( ) {

        $store = &$this->selectSuperRoot()->_getStore($this);

        return $store['data'];
    }

    /**
     * Get current data of this element.
     *
     * @access  public
     * @return  mixed
     */
    public function getCurrentData ( ) {

        $store = &$this->selectSuperRoot()->_getStore($this);
        $current = current($store['data']);

        return $current[$store['branche']];
    }

    public function getCurrentCurr ( ) {

        $store = &$this->selectSuperRoot()->_getStore($this);

        if(!isset($store['curr']))
            $store['curr'] = array();

        return $store['curr'];
    }

    public function firstUpdate ( ) {

        $store = &$this->selectSuperRoot()->_getStore($this);

        if(!isset($store['parent']))
            return;

        $parent = &$store['parent'];
        $store['data'] = &$parent['data'][$parent['current']][$parent['branche']];
        $store['current'] = 0;

        return $store;
    }

    public function update ( ) {

        $store            = &$this->selectSuperRoot()->_getStore($this);

        if(empty($store))
            return;

        if(!is_array($store['data']))
            return;

        $store['current'] = key($store['data']);

        return $store;
    }
}
