<?php
/**
 * Extension to the Dashboard Widget
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
class Xinc_Plugin_Repos_Gui_Dashboard_Detail_Extension
{

    const TEMPLATE = '<div class="tab-page" id="%s-page">
<h2 class="tab">%s</h2>
<script type="text/javascript">
%s.addTabPage( document.getElementById( "%s-page" ) );
</script>
%s
</div>
';
    
    
    private $_title;
    
    private $_content;
    
    public function __construct($title)
    {
        $this->_title = $title;
    }
    public function getTitle()
    {
        return $this->_title;
    }
    public function setContent($content)
    {
        $this->_content = $content;
    }
    
    public function getContent()
    {
        return $this->_content;
    }
    
    public function generate($tabPaneName)
    {
        $id = strtolower(str_replace(' ', '-', $this->getTitle()));
        $result = call_user_func_array('sprintf', array(self::TEMPLATE, $id, $this->getTitle(),
                                       $tabPaneName, $id, $this->getContent()));
        
        return $result;
    }
}