This repository provides support for the AlarmDecoder webapp inside of the SmartThings home automation platform.

## Static IP/PORT vs Dynamic Discovery over SSDP
   
* The Smartthings groovy code from https://github.com/nutechsoftware/alarmdecoder-smartthings repository works great.  If you are using a Raspberry PI, no need for using this forked version.
* I am using a Mac Mini (MacOS) as the host of the webserver alarmdecoder code.  When doing so, the MacOS prevents auto-discovery (SSDP) of alarmdecoder server
* The two addtional files in this repository (device_type_static.groovy and service_manager_static.groovy) allow you to manually enter a static IP and Port instead of discoverying the device on your network.
* Follow all of the NutechTechsoftware directions in: https://github.com/nutechsoftware/alarmdecoder-smartthings/blob/master/README.md.  Below are the exceptions to the directions

## Changes to NetchuTechsoftware directions
* Where ever it says it references device_type.groovy use device_type_static.groovy instead
* Where ever it says it references service_manager.groovy use service_manager_static.groovy instead

## Using PHP server
* This is a work in progress.  Was create specifically to run on MacOs, but should run on any OS.  It is really bare bones simple code.
* In order to work with the Smartthings code, I continued the use of the APIKEY.
* To use, you must use the supplied apikey_cntl.php script to add a new APIKEY for your use

## Required software
* This all assumes that somewhere within your local network the ser2sock server is running and talking to an AlarmDecoder device.  https://github.com/nutechsoftware/ser2sock
* Assumes a LAMP stack.  To use out of the box, install MAMP https://www.mamp.info/en/downloads
* Included install.sh script will then configure to the MAMP directory as installed on your Mac.


## Known Issues

## Future Items Planned
* install.sh also configure httpd.conf file (and extra/httpd-ssl.conf)
* Daily log rollover
* HTTPS between Smartthings and Webserver (depends on support from SmartThings)
* API Key generation at installation
* Push notifications of zone changes - Allow a new Smartthings device that could act like a motion dector - GOAL :)
