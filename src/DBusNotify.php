<?php

namespace todorowww;

use Exception;

class DBusNotify
{

	/**
	 * Contains Dbus instance
	 * 
	 * @var object 
	 */
    private $dbus;
	/**
	 * Contains Dbus proxy instance
	 * 
	 * @var object
	 */
    private $proxy;

	/**
	 * Initializes Dbus class, and sets up proxy for notifications
	 * 
	 * @throws Exception
	 */
    public function __construct()
    {
        if (!class_exists("Dbus", false)) {
            throw new Exception("Derick Rethans PHP D-BUS extension is required for this class to work");
        }
        $this->dbus = new \Dbus(\Dbus::BUS_SESSION);
        $this->proxy = $this->dbus->createProxy(
            "org.freedesktop.Notifications",
            "/org/freedesktop/Notifications",
            "org.freedesktop.Notifications"
        );
        $this->dbus->addWatch("org.freedesktop.Notifications", "ActionInvoked");
        $this->dbus->addWatch("org.freedesktop.Notifications", "NotificationClosed");
    }

	/**
	 * Sends D-Bus notification
	 * 
	 * @param string $appName Your application name
	 * @param string $appIcon Location to your icon, or system icon name
	 * @param string $notificationSummary Notification title
	 * @param string $notificationBody Notification message
	 * @param array $notificationActions Array of actions (buttons) to add to notification
	 * @param int $timeout Timeout until notification expires (in ms).
	 */
    public function notify($appName, $appIcon, $notificationSummary, $notificationBody, $notificationActions = [], $timeout = 5000)
    {
        $actions = [];
        foreach ($notificationActions as $action => $title) {
            $actions[] = $action;
            $actions[] = $title;
        }
        $this->proxy->Notify(
            $appName,
            new \DBusUInt32(0),
            $appIcon,
            $notificationSummary,
            $notificationBody,
            new \DBusArray(\DBus::STRING, $actions),
            new \DBusDict(\DBus::VARIANT, []),
            $timeout
        );
    }

	/**
	 * Waits for notification feedback, if actions were supplied
	 * 
	 * @param callback $callback Callback to trigger when action happens
	 * @param int $timeout Number of seconds to wait for signal, before exiting
	 */
    public function waitForAction($callback = null, $timeout = 5)
    {
        $timedout = false;
        $start = time();
        do {
            $signal = $this->dbus->waitLoop(1000);
            if ($signal instanceof \DbusSignal) {
                if ($signal->matches("org.freedesktop.Notifications", "ActionInvoked")) {
                    $data = $signal->getData()->getData();
                    if ($callback !== null) {
                        $callbackData = $data;
                        call_user_func($callback, $callbackData);
                        break;
                    }
                }
                if ($signal->matches("org.freedesktop.Notifications", "NotificationClosed")) {
                    $data = $signal->getData()->getData();
                    if ($callback !== null) {
                        $callbackData = $data;
                        call_user_func($callback, $callbackData);
                        break;
                    }
                }
            }
            $timedout = ($start + $timeout) < time();
        } while (!$timedout);
    }
}