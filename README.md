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
	$Notify->$Notify->notify($appName, $icon, $summary, $body);