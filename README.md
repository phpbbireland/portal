#portal

Fully integrated portal for phpBB 3.1.x

**Development Status:** [![Build Status](https://travis-ci.org/phpbbireland/portal.svg)](https://travis-ci.org/phpbbireland/portal)

No more travis errors but no pass?

## Tested with phpBB 3.1.9
    • Quite a few updates to the portal code including some additions to the code and minor fixes...
    • Note, the process of updating many files at one time, make commenting impractical.
    • Added a sample style (Olympus).  
  
*If you have already installed and want to update, please disable the extension, remove it (Delete Data) and reinstall as the latest updates add an extra database table and may changes others... A complete reinstall in the only way to ensure everything works.*
...  

**Setting up:**  
The main menu options for Admin and Registered members need to be set up (we can't do this automatically as we don't know if you install used default group id's or is an upgrade, in which case id's will differ).  

The links block is disabled but can be enabled from: ACP > PORTAL > Manage right blocks (click on edit block icon for the links block).  

**Cache:**  
At the moment deleting cache automatically after making changes is not enabled, please manually purge cache if you make changes to settings.  

**Tutorials:**  
I intend to add some new Youtube videos on how to set up and use the Portal (you may find some old videos which are still relavent).  

    • Managing Portal Block in ACP (TBA)  
    • Managing Menus in ACP (TBA)  
    • Block visibility (group assess) TBA      
    • Creating Custom Blocks (TBA)  
    • Arrange Blocks (yet to be implemented for 3.1) (https://www.youtube.com/watch?v=pkEWG6yq4as)  

Mike

## Installation:
    • Copy the entire contents from the unzipped folder to "phpBB/ext/phpbbireland/portal/".
    • Navigate in the ACP to "Customise -> Manage extensions".
    • Find "Portal" under "Disabled Extensions" and click "Enable".

## Uninstalling:
    • Navigate in the ACP to "Customise -> Manage extensions".
    • Click the "Disable" link for "Portal".
    • To permanently uninstall, click "Delete Data", then delete the "portal" folder from "phpBB/ext/phpbbireland/".

## License

[GPLv2](license.txt)

**2018**  
Although there is little or no interest in the Portal these days, I have not completely abandoned it.
I update occasionally and will probably continue to do so for the foreseeable future...

**As of December 2016**  
My site is no longer maintained and has been abandoned (people have moved on)...  
I test new releases of phpBB (every now and again) and update my files where issues arise (I haven't till now updated git)...  

I had hope that someone would step up and continue developing the code alas there doesn't appear to be any interest...  

What's need and will it ever happen?  
Basically I did intend to update all the code to C++ (I write in C) but as I don't actually like C++ I didn't do it (I'm too old)...
