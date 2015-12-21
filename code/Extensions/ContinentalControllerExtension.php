<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 2/9/15
 * Time: 10:43 AM
 * To change this template use File | Settings | File Templates.
 */

class ContinentalControllerExtension extends Extension
{

    public function onBeforeInit()
    {
        if (isset($_REQUEST['FAKE_IP'])) {
            Session::set('FAKE_IP', $_REQUEST['FAKE_IP']);
        }

        if (isset($_REQUEST['CLEAR_FAKE_IP'])) {
            Session::clear('FAKE_IP');
        }
    }
}
