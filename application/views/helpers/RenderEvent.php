<?php

class SimpleCal_View_Helper_RenderEvent extends Zend_View_Helper_Abstract
{

    public function renderEvent(SimpleCal_Model_Event $event)
    {    
        $startTime = $this->view->dateFormatter($event->getStartTime())->hour();
        $title = htmlspecialchars($event->getTitle());
        $html = <<<EOT
<div dojoType="dijit.form.DropDownButton">
    <span>
        <div style="width: 81px">
            <img src="/img/information.png" title="More information" />&nbsp;&nbsp;$startTime                      
        </div>
    </span>
    <div dojoType="dijit.TooltipDialog" id="tooltipDlg{$event->getId()}" title="More information">
        <table style="width:300px">
            <tr>
                <td valign="top">Description:</td>
                <td valign="top">{$event->getDescription()}</td>
            </tr>
            <tr>
                <td valign="top">Invitations:</td>
                <td valign="top">{$event->getInvite()}</td>
            </tr>
        </table>
        <table style="width:300px">
            <tr>
                <td width="33%">
                    <a style="text-decoration: none" href="/event/edit/id/{$event->getId()}">
                        <img border="0" src="/img/page_edit.png" title="Edit" />
                        &nbsp;&nbsp;Edit
                    </a>
                </td>
                <td width="33%">
                    <a style="text-decoration: none" href="/event/delete/id/{$event->getId()}" onclick="return confirm('Do you really want to delete this event?')">
                        <img border="0" src="/img/cross.png" title="Delete" />
                        &nbsp;&nbsp;Delete
                    </a>
                </td>
                <td>
                    <a style="text-decoration: none" href="/event/reinvent/id/{$event->getId()}" onclick="return confirm('Do you really want to reinvent the users?')">
                        <img border="0" src="/img/user_go.png" title="Reinvent" />
                        &nbsp;&nbsp;Reinvent
                    </a>
                </td>
            </tr>
        </table>
    </div>
</div>
<br />
$title
EOT;
                    
        return $html;
    }

    /**
     * Check if the timestamp provided is for today at 00:00
     *
     * @param  integer $day
     * @return boolean
     */
    private function _isToday($day)
    {
        $now = $_SERVER['REQUEST_TIME'];
        return ($now >= $day && $now <= ($day + 24 * 3600 - 1));
    }
}