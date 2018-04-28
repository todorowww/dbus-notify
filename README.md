![License](https://img.shields.io/github/license/todorowww/dbus-notify.svg)
![Packagist Version](https://img.shields.io/packagist/v/todorowww/dbus-notify.svg)
![GitHub Release](https://img.shields.io/github/release/todorowww/dbus-notify.svg)

D-Bus Notify
=================================

This class utilizes PHP extension D-Bus by Derick Rethans. In order to use this class, you need this extension.

You can get it from [nephre](https://github.com/nephre/php-dbus), and you can find compilation instructions there.

## Installation ##

Install the latest version of this library by issuing this command

	composer require todorowww/dbus-notify

## Basic Usage ##

	use todorowww\DBusNotify;
	
	// Instantiate DBusNotify class
	$Notify = new DBusNotify();
	
	$icon = "path/to/your/desired/icon.png";
	$summary = "Notification message summary (title)";
	$body = "Notification message body";
	$Notify->notify($appName, $icon, $summary, $body);
	
## Responding to actions ##

	use todorowww\DBusNotify;
	
	// Instantiate DBusNotify class
	$Notify = new DBusNotify();
	
	$icon = "path/to/your/desired/icon.png";
	$summary = "Notification message summary (title)";
	$body = "Notification message body";
	$actions = [
	    "ok" => "OK",
	    "cancel" => "Cancel"
	];
	$Notify->notify($appName, $icon, $summary, $body, $actions);
	$Notify->waitForAction(myCallback, 30); // Wait for 30 seconds before timing out

When user clicks on a button, or closes the notification, myCallback will be triggered. Action info will be passed as a parameter of the function.
