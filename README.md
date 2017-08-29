This repository provides support for the AlarmDecoder webapp inside of the SmartThings home automation platform.

## Static IP/PORT vs Dynamic Discovery over SSDP
   
* The Smartthings groovy code from https://github.com/nutechsoftware/alarmdecoder-smartthings repository works great.  If you are using a Raspberry PI, no need for using this forked version.
* I am using a Mac Mini (MacOS) as the host of the webserver alarmdecoder code.  When doing so, the MacOS prevents auto-discovery (SSDP) of alarmdecoder server
* The two addtional files in this repository (device_type_static.groovy and service_manager_static.groovy) allow you to manually enter a static IP and Port instead of discoverying the device on your network.
* Follow all of the NutechTechsoftware directions in: https://github.com/nutechsoftware/alarmdecoder-smartthings/blob/master/README.md.  Below are the exceptions to the directions

## Changes to NetchuTechsoftware directions
* Where ever it says it references device_type.groovy use device_type_static.groovy instead
* Where ever it says it references service_manager.groovy use service_manager_static.groovy instead

## Known issues

