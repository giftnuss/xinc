<?php
/**
 * Xinc - Continuous Integration.
 * Extension to the Dashboard Widget
 *
 * PHP version 5
 *
 * @category  Development
 * @package   Xinc.Plugin.Repos.Gui.Dashboard.Extension
 * @author    Arno Schneider <username@example.org>
 * @copyright 2007 Arno Schneider, Barcelona
 * @license   http://www.gnu.org/copyleft/lgpl.html GNU/LGPL, see license.php
 *            This file is part of Xinc.
 *            Xinc is free software; you can redistribute it and/or modify
 *            it under the terms of the GNU Lesser General Public License as
 *            published by the Free Software Foundation; either version 2.1 of
 *            the License, or (at your option) any later version.
 *
 *            Xinc is distributed in the hope that it will be useful,
 *            but WITHOUT ANY WARRANTY; without even the implied warranty of
 *            MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *            GNU Lesser General Public License for more details.
 *
 *            You should have received a copy of the GNU Lesser General Public
 *            License along with Xinc, write to the Free Software Foundation,
 *            Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * @link      http://xincplus.sourceforge.net
 */

require_once 'Xinc/Gui/Widget/Interface.php';
require_once 'Xinc/Plugin/Repos/Gui/Dashboard/Extension.php';

abstract class Xinc_Plugin_Repos_Gui_Dashboard_Extension_ProjectInfo extends Xinc_Plugin_Repos_Gui_Dashboard_Extension
{
    protected $_widget;

    public function setWidget(Xinc_Gui_Widget_Interface $widget)
    {
        $this->_widget = $widget;
    }

    public function getExtensionPoint()
    {
        return 'BUILD_SUMMARY';
    }
}