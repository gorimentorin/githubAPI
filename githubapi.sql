-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Servidor: localhost:3306
-- Tiempo de generación: 20-03-2014 a las 09:19:50
-- Versión del servidor: 5.5.34
-- Versión de PHP: 5.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `githubapi`
--
CREATE DATABASE IF NOT EXISTS `githubapi` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `githubapi`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `busqueda`
--

CREATE TABLE IF NOT EXISTS `busqueda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `busqueda` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Se almacenan las busquedas' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `owner`
--

CREATE TABLE IF NOT EXISTS `owner` (
  `id` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Usuarios GitHub';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phonegap`
--

CREATE TABLE IF NOT EXISTS `phonegap` (
  `nombre` varchar(255) NOT NULL,
  `codigo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `phonegap`
--

INSERT INTO `phonegap` (`nombre`, `codigo`) VALUES
('BarcodeScanner', 'com.phonegap.plugins.barcodescanner'),
('PushPlugin', 'com.phonegap.plugins.pushplugin'),
('Facebook Connect', 'com.phonegap.plugins.facebookconnect'),
('GAPlugin', 'com.adobe.plugins.gaplugin'),
('Child Browser', 'com.phonegap.plugins.childbrowser'),
('StatusBar', 'com.phonegap.plugin.statusbar'),
('SocialSharing', 'nl.xservices.plugins.socialsharing'),
('Pushwoosh', 'com.pushwoosh.plugins.pushwoosh'),
('iOS WebView background color', 'nl.xservices.plugins.ioswebviewcolor'),
('iAd', 'com.rjfun.cordova.plugin.iad'),
('LocalNotification', 'com.simplec.plugins.localnotification'),
('Calendar', 'nl.xservices.plugins.calendar'),
('NFC', 'com.chariotsolutions.nfc.plugin'),
('Custom URL scheme', 'nl.xservices.plugins.launchmyapp'),
('Email Composer with Attachments', 'com.phonegap.plugins.emailcomposer'),
('GoogleNavigate', 'dk.interface.cordova.plugin.googlenavigate'),
('PowerManagement', 'com.simplec.plugins.powermanagement'),
('LocalNotification', 'de.appplant.cordova.plugin.localnotification'),
('Bluetooth Serial', 'com.megster.cordova.bluetoothserial'),
('Flashlight', 'nl.xservices.plugins.flashlight'),
('EmailComposer', 'de.appplant.cordova.plugin.emailcomposer'),
('AppAvailability', 'com.ohh2ahh.plugins.appavailability'),
('IOS Background Location Enabler', 'com.cleartag.plugins.enablebackgroundlocation'),
('SpinnerDialog', 'hu.dpal.phonegap.plugins.spinnerdialog'),
('Insomnia (prevent screen sleep)', 'nl.xservices.plugins.insomnia'),
('PhoneDialer', 'com.teamnemitoff.phonedialer'),
('DatePickerPlugin', 'com.dileep.plugins.datepicker'),
('FastCanvas', 'com.adobe.plugins.fastcanvas'),
('ContactPickerUtil', 'com.mobitel.nalaka.piccon'),
('LowLatencyAudio', 'com.rjfun.cordova.plugin.lowlatencyaudio'),
('VideoCapturePlus', 'nl.xservices.plugins.videocaptureplus'),
('Document Handler', 'ch.ti8m.documenthandler'),
('Toast', 'nl.xservices.plugins.toast'),
('SSL Certificate Checker', 'nl.xservices.plugins.sslcertificatechecker'),
('SpinnerPlugin', 'it.mobimentum.phonegapspinnerplugin'),
('Email Composer with Attachments', 'com.jcjee.plugins.emailcomposer'),
('SMSBuilder', 'com.arnia.plugins.smsbuilder'),
('Appback Phonegap (Cordova) Plugin', 'com.appback.plugins.appback'),
('Clipboard', 'com.verso.cordova.clipboard'),
('WebIntent', 'net.tunts.webintent'),
('Home Shortcuts', 'com.plugins.shortcut'),
('HiddenStatusbarOverlay', 'de.appplant.cordova.plugin.hiddenstatusbaroverlay'),
('WebIntent', 'com.borismus.webintent'),
('American Bible Society Biblesearch', 'org.americanbible.biblesearch'),
('Datepicker', 'de.websector.datepicker'),
('File Opener', 'io.github.pwlin.cordova.plugins.fileopener'),
('TestFlight SDK Plugin', 'com.testflightapp.cordovaplugin'),
('PinDialog', 'hu.dpal.phonegap.plugins.pindialog'),
('Canvas 2 Image', 'org.devgeeks.canvas2imageplugin'),
('SoftKeyboard', 'de.phonostar.softkeyboard'),
('WebSocket', 'com.ququplay.websocket.websocket'),
('Badge', 'de.appplant.cordova.plugin.badge'),
('InAppPurchase', 'com.alexvillaro.plugins.inapppurchase'),
('VideoPlayer', 'com.dawsonloudon.videoplayer'),
('Datepicker', 'com.plugin.datepicker'),
('SMSPlugin', 'info.asankan.phonegap.smsplugin'),
('Update App plugin', 'org.common.plugins.updateapp'),
('TelephoneNumber', 'com.simonmacdonald.telephonenumber'),
('Printer', 'de.appplant.cordova.plugin.printer'),
('Keyboard Toolbar Remover', 'com.chariotsolutions.cordova.plugin.keyboard_toolbar_remover'),
('Android Orientation Changer', 'com.boyvanderlaak.cordova.plugin.orientationchanger'),
('DeviceInformation', 'com.vliesaputra.deviceinformation'),
('BackgroundJS', 'com.badrit.backgroundjs'),
('SqlitePlugin', 'ch.zhaw.sqlite'),
('Passbook', 'com.passslot.cordova.plugin.passbook'),
('BackgroundMode', 'de.appplant.cordova.plugin.backgroundmode'),
('WebView Debug', 'com.jamiestarke.webviewdebug'),
('EmailComposer', 'com.badrit.emailcomposer'),
('ContactPicker', 'com.badrit.contactpicker'),
('InAppPurchase', 'cc.fovea.plugins.inapppurchase'),
('KeyChain Plugin for Cordova iOS', 'com.shazron.cordova.plugin.keychainutil'),
('Calendar', 'genesis.plugins.calendar'),
('File Opener2', 'io.github.pwlin.cordova.plugins.fileopener2'),
('ZoomControl', 'com.kumbe.phonegap.zoom.zoomcontrol'),
('MacAddress', 'com.badrit.macaddress'),
('Bluetooth LE', 'com.randdusing.bluetoothle'),
('LocalNotification', 'ihome.cordova.plugin.localnotification'),
('MeteorCordova', 'dk.gi2.plugins.meteorcordova'),
('CDVBackgroundGeoLocation', 'org.transistorsoft.cordova.backgroundgeolocation'),
('Calendar', 'com.badrit.calendar'),
('Preferences Access Plugin', 'com.simonmacdonald.prefs'),
('Android Media Gesture Setting', 'com.simplec.plugins.videosettings'),
('Auth0', 'com.auth0.sdk'),
('BackgroundLocationEnabler', 'com.wizital.plugins.backgroundlocationenabler'),
('ExternalScreen iOS Plugin', 'com.tricedesigns.ios.externalscreen'),
('ContactNumberPicker', 'hu.dpal.phonegap.plugins.contactnumberpicker'),
('FileOperations', 'com.badrit.fileoperations'),
('PrintPlugin', 'com.badrit.printplugin'),
('Sysinfo', 'com.ankamagames.plugins.sysinfo'),
('iAd', 'com.kickstand.cordova.plugin.iad'),
('WallpaperPlugin', 'ca.purplemad.wallpaper'),
('NetworkInterface', 'com.albahra.plugin.networkinterface'),
('weibo', 'com.polyvi.plugins.weibo'),
('Bluetooth Serial', 'com.tomvanenckevort.cordova.bluetoothserial'),
('Calendar', 'nl.xservices.plugins.calendar.berserk'),
('Android InAppBilling', 'com.mcm.plugins.androidinappbilling'),
('Custom Camera', 'com.performanceactive.plugins.camera'),
('Base64', 'com.badrit.base64'),
('CDVBackgroundNotification', 'org.transistorsoft.cordova.backgroundnotification'),
('Instagram', 'com.vladstirbu.cordova.instagram'),
('InAppPurchase', 'com.retoglobal.plugins.inapppurchase'),
('External Screen', 'com.blueshirtdesign.cordova.plugin.pgexternalscreen'),
('AudioEncode', 'mobi.autarky.audioencode'),
('Video Snapshot', 'com.sebible.cordova.videosnapshot'),
('StringBean DocumentInteraction', 'it.y3web.cordova.documentinteraction'),
('AssetsLib', 'com.flexblast.cordova.plugin.assetslib'),
('Navkolo.Service', 'nav.kolo.service'),
('LocalNotification', 'com.localnotification.gotcakes'),
('InAppPurchaseManager', 'com.rjfun.cordova.plugin.appleiap'),
('ImagePicker', 'com.synconset.imagepicker'),
('Geo Fencing Plugin', 'com.location9.dfencing'),
('Battery', 'org.apache.cordova.batterystatus'),
('Camera', 'org.apache.cordova.camera'),
('Capture', 'org.apache.cordova.mediacapture'),
('Console', 'org.apache.cordova.console'),
('Contacts', 'org.apache.cordova.contacts'),
('Device', 'org.apache.cordova.device'),
('Device motion', 'org.apache.cordova.devicemotion'),
('Device orientation', 'org.apache.cordova.deviceorientation'),
('File', 'org.apache.cordova.file'),
('File transfer', 'org.apache.cordova.filetransfer'),
('Geolocation', 'org.apache.cordova.geolocation'),
('Globalization', 'org.apache.cordova.globalization'),
('Inappbrowser', 'org.apache.cordova.inappbrowser'),
('Media', 'org.apache.cordova.media'),
('Network information', 'org.apache.cordova.networkinformation'),
('Notification', 'org.apache.cordova.dialogs'),
('Splashscreen', 'org.apache.cordova.splashscreen'),
('Vibration', 'org.apache.cordova.vibration');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `repositorio`
--

CREATE TABLE IF NOT EXISTS `repositorio` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `owner_id` varchar(255) NOT NULL,
  `private` tinyint(1) DEFAULT NULL,
  `html_url` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `fork` tinyint(1) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `forks_url` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `pushed_at` datetime NOT NULL,
  `git_url` varchar(255) NOT NULL,
  `svn_url` varchar(255) NOT NULL,
  `homepage` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `stargazers_count` int(11) NOT NULL,
  `watchers_count` int(11) NOT NULL,
  `language` varchar(255) NOT NULL,
  `has_issues` tinyint(1) DEFAULT NULL,
  `has_downloads` tinyint(1) DEFAULT NULL,
  `has_wiki` tinyint(1) DEFAULT NULL,
  `forks_count` int(11) NOT NULL,
  `mirror_url` varchar(255) DEFAULT NULL,
  `open_issues_count` int(11) NOT NULL,
  `forks` int(11) NOT NULL,
  `open_issues` int(11) NOT NULL,
  `watchers` int(11) NOT NULL,
  `default_branch` varchar(255) NOT NULL,
  `master_branch` varchar(255) NOT NULL,
  `score` float NOT NULL,
  `json` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Informacion sobre los repositorios de GitHub';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resultadobusquedarepositorio`
--

CREATE TABLE IF NOT EXISTS `resultadobusquedarepositorio` (
  `id_busqueda` int(11) NOT NULL,
  `id_repositorio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Relaciona las busquedas con el repositorio';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
