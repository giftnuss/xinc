<?php
declare(encoding = 'utf-8');
/**
 * Xinc - Continuous Integration.
 *
 * PHP version 5
 *
 * @category  Development
 * @package   Xinc.Plugin.Repos.ModificationSet
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

require_once 'Xinc/Plugin/Task/Base.php';
require_once 'Xinc/Plugin/Repos/ModificationSet/Interface.php';

class Xinc_Plugin_Repos_ModificationSet_Task extends Xinc_Plugin_Task_Base
{
    public function validate()
    {
        foreach ( $this->_subtasks as $task ) {
            if (!$task instanceof Xinc_Plugin_Repos_ModificationSet_AbstractTask) {
                return false;
            }
        }
        return true;
    }

    public function getName()
    {
        return 'modificationset';
    }

    public function getPluginSlot()
    {
        return Xinc_Plugin_Slot::PRE_PROCESS;
    }

    public function process(Xinc_Build_Interface $build)
    {
        foreach ( $this->_subtasks as $task ) {
            $task->process($build);
            if ( $build->getStatus() == Xinc_Build_Interface::PASSED ) {
                return;
            } else if ($build->getStatus() == Xinc_Build_Interface::FAILED) {
                /**
                 * if the modification cannot be detected make the build fail
                 * See Issue 79
                 */
                return;
            }
        }
        $build->setStatus(Xinc_Build_Interface::STOPPED);
    }
}