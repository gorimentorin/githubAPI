<?php
require_once("SearchGithub.php");
$lol = new Search();
//$lol->connect();
$lol->reset();
$array= array('pushed' => '>2007-01-01' );
$lol->FindRepositorios('org.americanbible.biblesearch');
$lol->FindRepositorios('com.mcm.plugins.androidinappbilling');
$lol->FindRepositorios('com.simplec.plugins.videosettings');
$lol->FindRepositorios('com.boyvanderlaak.cordova.plugin.orientationchanger');
$lol->FindRepositorios('com.ohh2ahh.plugins.appavailability');
$lol->FindRepositorios('com.appback.plugins.appback');
$lol->FindRepositorios('com.flexblast.cordova.plugin.assetslib');
$lol->FindRepositorios('mobi.autarky.audioencode');
$lol->FindRepositorios('com.auth0.sdk');
$lol->FindRepositorios('com.badrit.backgroundjs');
$lol->FindRepositorios('com.wizital.plugins.backgroundlocationenabler');
$lol->FindRepositorios('de.appplant.cordova.plugin.backgroundmode');
$lol->FindRepositorios('de.appplant.cordova.plugin.badge');
$lol->FindRepositorios('com.phonegap.plugins.barcodescanner');
$lol->FindRepositorios('com.badrit.base64');
$lol->FindRepositorios('org.apache.cordova.batterystatus');
$lol->FindRepositorios('com.randdusing.bluetoothle');
$lol->FindRepositorios('com.megster.cordova.bluetoothserial');
$lol->FindRepositorios('com.tomvanenckevort.cordova.bluetoothserial');
$lol->FindRepositorios('nl.xservices.plugins.calendar');
$lol->FindRepositorios('genesis.plugins.calendar');
$lol->FindRepositorios('com.badrit.calendar');
$lol->FindRepositorios('nl.xservices.plugins.calendar.berserk');
$lol->FindRepositorios('org.apache.cordova.camera');
$lol->FindRepositorios('org.devgeeks.canvas2imageplugin');
$lol->FindRepositorios('org.apache.cordova.mediacapture');
$lol->FindRepositorios('org.transistorsoft.cordova.backgroundgeolocation');
$lol->FindRepositorios('org.transistorsoft.cordova.backgroundnotification');
$lol->FindRepositorios('com.phonegap.plugins.childbrowser');
$lol->FindRepositorios('com.verso.cordova.clipboard');
$lol->FindRepositorios('org.apache.cordova.console');
$lol->FindRepositorios('hu.dpal.phonegap.plugins.contactnumberpicker');
$lol->FindRepositorios('com.badrit.contactpicker');
$lol->FindRepositorios('com.mobitel.nalaka.piccon');
$lol->FindRepositorios('org.apache.cordova.contacts');
$lol->FindRepositorios('com.performanceactive.plugins.camera');
$lol->FindRepositorios('nl.xservices.plugins.launchmyapp');
$lol->FindRepositorios('de.websector.datepicker');
$lol->FindRepositorios('com.plugin.datepicker');
$lol->FindRepositorios('com.dileep.plugins.datepicker');
$lol->FindRepositorios('org.apache.cordova.device');
$lol->FindRepositorios('org.apache.cordova.devicemotion');
$lol->FindRepositorios('org.apache.cordova.deviceorientation');
$lol->FindRepositorios('com.vliesaputra.deviceinformation');
$lol->FindRepositorios('ch.ti8m.documenthandler');
$lol->FindRepositorios('com.phonegap.plugins.emailcomposer');
$lol->FindRepositorios('com.jcjee.plugins.emailcomposer');
$lol->FindRepositorios('de.appplant.cordova.plugin.emailcomposer');
$lol->FindRepositorios('com.badrit.emailcomposer');
$lol->FindRepositorios('com.blueshirtdesign.cordova.plugin.pgexternalscreen');
$lol->FindRepositorios('com.tricedesigns.ios.externalscreen');
$lol->FindRepositorios('com.phonegap.plugins.facebookconnect');
$lol->FindRepositorios('com.adobe.plugins.fastcanvas');
$lol->FindRepositorios('org.apache.cordova.file');
$lol->FindRepositorios('io.github.pwlin.cordova.plugins.fileopener');
$lol->FindRepositorios('io.github.pwlin.cordova.plugins.fileopener2');
$lol->FindRepositorios('org.apache.cordova.filetransfer');
$lol->FindRepositorios('com.badrit.fileoperations');
$lol->FindRepositorios('nl.xservices.plugins.flashlight');
$lol->FindRepositorios('com.adobe.plugins.gaplugin');
$lol->FindRepositorios('com.location9.dfencing');
$lol->FindRepositorios('org.apache.cordova.geolocation');
$lol->FindRepositorios('org.apache.cordova.globalization');
$lol->FindRepositorios('dk.interface.cordova.plugin.googlenavigate');
$lol->FindRepositorios('de.appplant.cordova.plugin.hiddenstatusbaroverlay');
$lol->FindRepositorios('com.plugins.shortcut');
$lol->FindRepositorios('com.rjfun.cordova.plugin.iad');
$lol->FindRepositorios('com.kickstand.cordova.plugin.iad');
$lol->FindRepositorios('com.synconset.imagepicker');
$lol->FindRepositorios('org.apache.cordova.inappbrowser');
$lol->FindRepositorios('com.alexvillaro.plugins.inapppurchase');
$lol->FindRepositorios('cc.fovea.plugins.inapppurchase');
$lol->FindRepositorios('com.retoglobal.plugins.inapppurchase');
$lol->FindRepositorios('com.rjfun.cordova.plugin.appleiap');
$lol->FindRepositorios('nl.xservices.plugins.insomnia');
$lol->FindRepositorios('com.vladstirbu.cordova.instagram');
$lol->FindRepositorios('com.cleartag.plugins.enablebackgroundlocation');
$lol->FindRepositorios('nl.xservices.plugins.ioswebviewcolor');
$lol->FindRepositorios('com.chariotsolutions.cordova.plugin.keyboard_toolbar_remover');
$lol->FindRepositorios('com.shazron.cordova.plugin.keychainutil');
$lol->FindRepositorios('com.simplec.plugins.localnotification');
$lol->FindRepositorios('de.appplant.cordova.plugin.localnotification');
$lol->FindRepositorios('ihome.cordova.plugin.localnotification');
$lol->FindRepositorios('com.localnotification.gotcakes');
$lol->FindRepositorios('com.rjfun.cordova.plugin.lowlatencyaudio');
$lol->FindRepositorios('com.badrit.macaddress');
$lol->FindRepositorios('org.apache.cordova.media');
$lol->FindRepositorios('dk.gi2.plugins.meteorcordova');
$lol->FindRepositorios('nav.kolo.service');
$lol->FindRepositorios('org.apache.cordova.networkinformation');
$lol->FindRepositorios('com.albahra.plugin.networkinterface');
$lol->FindRepositorios('com.chariotsolutions.nfc.plugin');
$lol->FindRepositorios('org.apache.cordova.dialogs');
$lol->FindRepositorios('com.passslot.cordova.plugin.passbook');
$lol->FindRepositorios('com.teamnemitoff.phonedialer');
$lol->FindRepositorios('hu.dpal.phonegap.plugins.pindialog');
$lol->FindRepositorios('com.simplec.plugins.powermanagement');
$lol->FindRepositorios('com.simonmacdonald.prefs');
$lol->FindRepositorios('de.appplant.cordova.plugin.printer');
$lol->FindRepositorios('com.badrit.printplugin');
$lol->FindRepositorios('com.phonegap.plugins.pushplugin');
$lol->FindRepositorios('com.pushwoosh.plugins.pushwoosh');
$lol->FindRepositorios('com.arnia.plugins.smsbuilder');
$lol->FindRepositorios('info.asankan.phonegap.smsplugin');
$lol->FindRepositorios('nl.xservices.plugins.socialsharing');
$lol->FindRepositorios('de.phonostar.softkeyboard');
$lol->FindRepositorios('hu.dpal.phonegap.plugins.spinnerdialog');
$lol->FindRepositorios('it.mobimentum.phonegapspinnerplugin');
$lol->FindRepositorios('org.apache.cordova.splashscreen');
$lol->FindRepositorios('ch.zhaw.sqlite');
$lol->FindRepositorios('nl.xservices.plugins.sslcertificatechecker');
$lol->FindRepositorios('com.phonegap.plugin.statusbar');
$lol->FindRepositorios('it.y3web.cordova.documentinteraction');
$lol->FindRepositorios('com.ankamagames.plugins.sysinfo');
$lol->FindRepositorios('com.simonmacdonald.telephonenumber');
$lol->FindRepositorios('com.testflightapp.cordovaplugin');
$lol->FindRepositorios('nl.xservices.plugins.toast');
$lol->FindRepositorios('org.common.plugins.updateapp');
$lol->FindRepositorios('org.apache.cordova.vibration');
$lol->FindRepositorios('com.sebible.cordova.videosnapshot');
$lol->FindRepositorios('nl.xservices.plugins.videocaptureplus');
$lol->FindRepositorios('com.dawsonloudon.videoplayer');
$lol->FindRepositorios('ca.purplemad.wallpaper');
$lol->FindRepositorios('net.tunts.webintent');
$lol->FindRepositorios('com.borismus.webintent');
$lol->FindRepositorios('com.ququplay.websocket.websocket');
$lol->FindRepositorios('com.jamiestarke.webviewdebug');
$lol->FindRepositorios('com.polyvi.plugins.weibo');
$lol->FindRepositorios('com.kumbe.phonegap.zoom.zoomcontrol');
$lol->FindRepositorios('<gap:platform name="ios”/>');
$lol->FindRepositorios('<gap:platform name=“android”/>');
$lol->FindRepositorios('cordova.js');
$lol->FindRepositorios('api.phonegap.com');
$lol->FindRepositorios('http://api.phonegap.com/1.0/geolocation');
$lol->FindRepositorios('http://api.phonegap.com/1.0/camera');
$lol->FindRepositorios('http://api.phonegap.com/1.0/battery');
$lol->FindRepositorios('http://api.phonegap.com/1.0/device');
$lol->FindRepositorios('http://api.phonegap.com/1.0/network');
$lol->FindRepositorios('http://api.phonegap.com/1.0/app');
$lol->FindRepositorios('http://api.phonegap.com/1.0/notification');
$lol->FindRepositorios('http://api.phonegap.com/1.0/network');
$lol->FindRepositorios('http://api.phonegap.com/1.0/contacts');
$lol->FindRepositorios('http://api.phonegap.com/1.0/media');
$lol->FindRepositorios('gap:platform=“android"');
$lol->FindRepositorios('name="phonegap-version”');
$lol->FindRepositorios('com.phonegap.plugins.');
$lol->FindRepositorios('com.phonegap.plugins.analytics.');
$lol->FindRepositorios('com.phonegap.plugins.mapkit');
$lol->FindRepositorios('com.phonegap.plugins.barcodescanner');
$lol->FindRepositorios('com.phonegap.plugins.PushPlugin');
$lol->FindRepositorios('com.phonegap.DroidGap');
$lol->FindRepositorios('xmlns:gap');
?>