<?php
/**
 * Xinc - Continuous Integration.
 *
 * PHP version 5
 *
 * @category  Development
 * @package   Xinc.Plugin.Repos.Builder
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
 * @link      http://code.google.com/p/xinc/
 */

require_once 'Xinc/Plugin/Task/Abstract.php';

class Xinc_Plugin_Repos_Builder_Task extends Xinc_Plugin_Task_Abstract
{
    /**
     * Validates if a task can run by checking configs, directries and so on.
     *
     * @return boolean Is true if task can run.
     */
    public function validate()
    {
        foreach ($this->arSubtasks as $task) {
            if (!$task instanceof Xinc_Plugin_Repos_Builder_AbstractTask) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns name of task.
     *
     * @return string Name of task.
     */
    public function getName()
    {
        return 'builders';
    }

    /**
     * Returns the slot of this task inside a build.
     *
     * @return integer The slot number.
     * @see Xinc/Plugin/Slot.php for available slots
     */
    public function getPluginSlot()
    {
        return Xinc_Plugin_Slot::PROCESS;
    }

    public function process(Xinc_Build_Interface $build)
    {
        $build->info('Processing builders');
        foreach ( $this->arSubtasks as $task ) {
            $task->process($build);
            if ($build->getStatus() != Xinc_Build_Interface::PASSED) {
                $build->error('Build FAILED ');
                return;
            }
        }
        $build->info('Processing builders done');
        //$project->setStatus(Xinc_Build_Interface::STOPPED);
    }
}
