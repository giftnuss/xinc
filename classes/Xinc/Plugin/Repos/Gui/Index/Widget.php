<?php
/**
 * This interface represents a publishing mechanism to publish build results
 * 
 * @package Xinc.Plugin
 * @author Arno Schneider
 * @version 2.0
 * @copyright 2007 Arno Schneider, Barcelona
 * @license  http://www.gnu.org/copyleft/lgpl.html GNU/LGPL, see license.php
 *    This file is part of Xinc.
 *    Xinc is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU Lesser General Public License as published
 *    by the Free Software Foundation; either version 2.1 of the License, or    
 *    (at your option) any later version.
 *
 *    Xinc is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public License
 *    along with Xinc, write to the Free Software
 *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once 'Xinc/Gui/Widget/Interface.php';
require_once 'Xinc/Build/Iterator.php';
require_once 'Xinc/Project.php';
require_once 'Xinc/Build.php';

class Xinc_Plugin_Repos_Gui_Index_Widget implements Xinc_Gui_Widget_Interface
{
    protected $_plugin;
    private $_extensions = array();
    public $projects = array();
    public $menu;
    public $builds;
    
    public function __construct(Xinc_Plugin_Interface &$plugin)
    {
        $this->_plugin = $plugin;
        $this->builds = new Xinc_Build_Iterator();
    }
    
    public function handleEvent($eventId)
    {
        switch ($eventId) {
            case Xinc_Gui_Event::PAGE_LOAD: 
                
                    
                    include 'view/index.phtml';
                    
                    
                break;
            default:
                break;
        }
    }
    public function registerMainMenu()
    {
        return true;
    }
    public function getTitle()
    {
        return 'Index';
    }
    public function getPaths()
    {
        return array('/');
    }
    public function init()
    {
        
    }
    public function registerExtension($extension, $callback)
    {
        
        if (!isset($this->_extensions[$extension])) {
            $this->_extensions[$extension] = array();
        }
        $this->_extensions[$extension][] = $callback;
    }
    public function getExtensionPoints()
    {
        return array();
    }
}