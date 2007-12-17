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
require_once 'Xinc/Plugin/Repos/Gui/Menu/Item.php';
require_once 'Xinc/Plugin/Repos/Gui/Dashboard/Projects/Menu.php';

class Xinc_Plugin_Repos_Gui_Dashboard_Widget implements Xinc_Gui_Widget_Interface
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
                
                    $query = $_SERVER['REQUEST_URI'];

                    
                    
                    $handler = Xinc_Gui_Handler::getInstance();
                    $statusDir = $handler->getStatusDir();
                    $dir = opendir($statusDir);
                    while ($file = readdir($dir)) {
                        $project = array();
                        $fullfile = $statusDir . DIRECTORY_SEPARATOR . $file;
                        
                        if (!in_array($file, array('.', '..')) && is_dir($fullfile)) {
                            $project['name']=$file;
                            $statusfile = $fullfile . DIRECTORY_SEPARATOR . 'build.ser';
                            //$xincProject = $fullfile . DIRECTORY_SEPARATOR . '.xinc';
                            
                            if (file_exists($statusfile)) {
                                //$ini = parse_ini_file($statusfile, true);
                                $object = unserialize(file_get_contents($statusfile));
                                //var_dump($object);
                                
                                //$project['build.status'] = $ini['build.status'];
                                //$project['build.label'] = isset($ini['build.label'])?$ini['build.label']:'';
                                //$project['build.time'] = $ini['build.time'];
                                $this->builds->add($object);
                            } else if (file_exists($xincProject)) {
                                $project['build.status'] = -10;
                                $project['build.time'] = 0;
                                $project['build.label'] = '';
                                $this->projects[]=$project;
                            }
                            $this->menu = '';
                            foreach ($this->_extensions['MAIN_MENU'] as $extension) {
                                
                                $this->menu .= call_user_func_array($extension, array($this, 'Dashboard'));
                                
                            }
                            
                        }
                    }
                    if (preg_match('/\/dashboard\/projects.*/', $query)) {
                        include_once 'view/projects.html';
                        
                    } else {
                    include 'view/overview.phtml';
                    }
                    
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
        return 'Dashboard';
    }
    public function getPaths()
    {
        return array('/dashboard', '/dashboard/');
    }
    public function init()
    {
        $menuWidget = Xinc_Gui_Widget_Repository::getInstance()->
                                                  getWidgetByClassName('Xinc_Plugin_Repos_Gui_Menu_Widget');
        
        $menuWidget->registerExtension('MAIN_MENU_ITEMS', array(&$this,'generateDashboardMenuItem'));
        
        $menuWidget->registerExtension('MAIN_MENU_ITEMS', array(&$this,'generateProjectsMenuItem'));
    }
    
    public function generateDashboardMenuItem()
    {
        $menuItem = new Xinc_Plugin_Repos_Gui_Menu_Item('widget-dashboard',
                                                        'Dashboard', 
                                                        true,
                                                        '/dashboard/projects',
                                                        'icon-dashboard',
                                                        'Dashboard',
                                                        true,
                                                        true);
        return $menuItem;
    }
    public function generateProjectsMenuItem()
    {
        
        if (isset($this->_extensions['PROJECT_MENU_ITEM'])) {
            $menuItem = new Xinc_Plugin_Repos_Gui_Dashboard_Projects_Menu('projects',
                                                                          'Projects',
                                                                          true,
                                                                          null,
                                                                          null,
                                                                          'Projects',
                                                                          true,
                                                                          false);
            foreach ($this->_extensions['PROJECT_MENU_ITEM'] as $extension) {
                
                $menuItem->registerSubExtension($extension);
            }
        } else {
            $menuItem = new Xinc_Plugin_Repos_Gui_Dashboard_Projects_Menu('projects',
                                                                          'Projects',
                                                                          true,
                                                                          null,
                                                                          null,
                                                                          'Projects',
                                                                          true,
                                                                          false);
        }
        
        return $menuItem;
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
        return array('PROJECT_MENU_ITEM');
    }
}