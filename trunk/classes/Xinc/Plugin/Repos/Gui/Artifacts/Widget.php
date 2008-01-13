<?php
/**
 * Artifacts Widget, displays the artifacts of a build
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
require_once 'Xinc/Plugin/Repos/Gui/Dashboard/Detail/Extension.php';
require_once 'Xinc/Plugin/Repos/Gui/Artifacts/Extension/Dashboard.php';
require_once 'Xinc/Data/Repository.php';

class Xinc_Plugin_Repos_Gui_Artifacts_Widget implements Xinc_Gui_Widget_Interface
{
    protected $_plugin;
    private $_extensions = array();
    public $projects = array();
    
    public $builds;
    
    public function __construct(Xinc_Plugin_Interface &$plugin)
    {
        $this->_plugin = $plugin;
        
    }
    public function mime_content_type2($fileName)
    {
        $contentType = null;
        /**if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME); // return mime type ala mimetype extension
            if(!$finfo) return;
            $contentType = finfo_file($finfo, $fileName);
            finfo_close($finfo);
        } else*/
        if (function_exists('mime_content_type')) {
            $contentType = mime_content_type($fileName);
        }
    
        return $contentType;
    
    }
    public function handleEvent($eventId)
    {
       $query = $_SERVER['REQUEST_URI'];
       
       preg_match("/\/(.*?)\/(.*?)\/(.*?)\/(.*?)\/(.*)/", $query, $matches);
       
       if (count($matches)!=6) {
           echo "Could not find artifact";
           return;
       }
       $projectName = $matches[3];
       $buildTime = $matches[4];
       $file = $matches[5];
       $project = new Xinc_Project();
       $project->setName($projectName);
       try {
           $build = Xinc_Build::unserialize($project,
                                            $buildTime,
                                            Xinc_Gui_Handler::getInstance()->getStatusDir());
           
           $statusDir = Xinc_Gui_Handler::getInstance()->getStatusDir();
           $statusDir .= DIRECTORY_SEPARATOR . $build->getStatusSubDir() . 
                         DIRECTORY_SEPARATOR . Xinc_Plugin_Repos_Artifacts::ARTIFACTS_DIR .
                         DIRECTORY_SEPARATOR;

           /**
            * Replace multiple / slashes with just one
            */
           $fileName = $statusDir.$file;
           $fileName = preg_replace('/\\' . DIRECTORY_SEPARATOR . '+/', DIRECTORY_SEPARATOR, $fileName);
           $realfile = realpath($fileName);
           if ($realfile != $fileName) {
               echo "Could not find artifact";
           } else if (file_exists($fileName)) {
               //echo "here";
               $contentType = $this->mime_content_type2($fileName);
               if (!empty($contentType)) {
                   header("Content-Type: " . $contentType);
               }
               readfile($fileName);
           } else {
               echo "Could not find artifact";
           }
           
       } catch (Exception $e) {
           echo "Could not find any artifacts";
       }
    }
    public function getPaths()
    {
        return array('/artifacts/get', '/artifacts/get/');
    }
    public function getArtifacts(Xinc_Build_Interface &$build)
    {
        $statusDir = Xinc_Gui_Handler::getInstance()->getStatusDir();
        $projectName = $build->getProject()->getName();
        $buildTimestamp = $build->getBuildTime();
        $buildLabel = $build->getLabel();
        
        $templateFile = Xinc_Data_Repository::getInstance()->get('templates' . DIRECTORY_SEPARATOR
                                                                . 'dashboard' . DIRECTORY_SEPARATOR
                                                                . 'detail' . DIRECTORY_SEPARATOR
                                                                . 'extension' . DIRECTORY_SEPARATOR
                                                                .'artifactsJs.phtml');
        
        $template = file_get_contents($templateFile);
        
        $content = str_replace(array('{projectname}', '{buildtime}' , '{buildlabel}'),
                                array($projectName, $buildTimestamp, $buildLabel), $template);
        return $content;
    }
    
    public function init()
    {
        $detailWidget = Xinc_Gui_Widget_Repository::getInstance()->
                                                    getWidgetByClassName('Xinc_Plugin_Repos_Gui_Dashboard_Detail');
        
        $extension = new Xinc_Plugin_Repos_Gui_Artifacts_Extension_Dashboard();
        $extension->setWidget($this);
        
        $detailWidget->registerExtension('BUILD_DETAILS', $extension);
        
    }
    public function registerExtension($extensionPoint, &$extension)
    {
        $this->_extensions[$extensionPoint] = $extension;
    }
    public function getExtensionPoints()
    {
        return array();
    }
}