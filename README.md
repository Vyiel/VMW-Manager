# VMW-Manager

# MAJOR UPDATES #

A single place to Start-Stop-Pause-Resume and finally RDP into VMware Workstation VMs remotely, somewhat like ESXi


If one is regular VMWare Workstation user, there is no way to manage them remotely. By manage I mean start-stop-pause-resume. ESXI supports these things but not VMWare Workstation. This project does exactly that. 

Basic Setup:

This program is tailored and built for and from Windows and XAMPP only.
This program is only tested against VMWare Workstation 17, the preferred.
Install Python and another library: websockify.
Download noVNC from github and set it up within vmops directory.

Download XAMPP. Setup Apache and MySQL servers. Don't install as service IF your download/upload location is set to a Network Drive.
SQL file is uploaded. You can just import it and will be good to go. If you make any changes to table names, program will fail and you would have to change all the files.

Start Up: Once all setup is done, To start the VM, just run init.bat as administrator. Rest is self explanatory.

DB Config (NEW as OLD one is discarded):

- DB Name: vm
- Table 1: users (id, full_name, email, password)
- Table 2: vms (vms_id, name, description, location, port)
- Table 3: vnc_servers (vnc_id, vms_id, websockify_port)

Edit app_config.json to change host location, internal IPs, usernames, passwords ... etc.

Edit .htpasswd to password protect directory access. Use an online htpasswd generetor to generate one.

Router Configuration:
- Open Port: 80 OR the one you set from app_config.json.
- Open Ports RANGE(40000 - 40200) for websockify connections.


ABOUT and MAIN CHANGES: 
This project was somewhat of an old project with capabilities to start, pause, stop, resume a VM AND use a VNCViewer tool to Remote into the VMs.
Problems: 
- VM State Changes AND VM State Checks were implemented with vmrun.exe, that included complex System Calls and STDOUT string manipulation. Which made it unsafe, slow to update, and sometimes resulted in page timeout.
- Password and VNC Port number memorization was a real problem as each VM would have different passwords aswell as ports. Also, everything had to be set from host computer manually using VMware UI. There would be no control from remote, which was a pain.
- A dedicated VNC client would be needed to remote in.
- Files couldn't be uploaded or downloaded


NEW UPDATES (Major):
- VM State Changes AND VM State Checks are now implemented with VMREST API (vmrest.exe), allowing NO system calls, slow responses, or complex string manipulation along and a legitimate way to do these things.
- VMREST API does need a separate setup which is easy to do. Launch vmrest.exe from CMD and it will ask for configuring an username and password. Once set, we can just go .\vmrest.exe and a REST API server will be served. (Without which, VMOPS will be unusable. Although old vmrun code is still kept for fallback as comments).
- Now I have implemented a shady and not an exactly good way to set VNC PORT remotely when adding a VM to our list and implemented a way to enable and configure VM VNC with one common password accross all VMs, thus not needing to remember them at all, along with having control over VNC Ports over remote. I have achieved this with python, where I copy the target VMX file and keep it a backup. Load it's configuration and change/add the VNC settings to the configuration and export a new VNC file which now has VNC turned on with a custom port and a password.
The password encryption mechanism wasn't found thus instead of generating, a password from a plaintext, I used VMware Workstaion UI to set the password to a VM and from it's VMX files, copied the encrypted password and stored them in the app_config.json. Now when adding or editing a VM, this password would automatically be set. I know it's a security issue but as we are already pushing the limits, this is a risk that we have to bear. If you have a suggestion, I am all keyboards.
- We can't create VMware VMs like ESXI but there's a work around. If we can make some baseline VMs for multiple OSes, We can create a new VM by cloning the baseline which I implemented this time. After creating the VM, we can add it to our lists from AddVM and the VNC configuration will be written to it. Thus achieving creation of VM along with VNC setup giving the illusion of almost an ESXI.
- With noVNC along with Websockify (Configuration not needed), now no third party VNC clients are needed and we can remote in from the web on a click of a button. Thus hugely decreasing manual work for connection.
- Lastly, EditVM privileges added along with File Upload and Download capabilities. NOTE: The way I have done it is again a bit shady but works fine. I have made a folder "anyone of your choice", that can and will be mounted to all VMs as the common shared directory. The files are also uploaded to this location, thus accessible by all VMs at once.


Security:
- Only border security is kept in mind when building this. I.E Access to website, Access to modules, Access to files, SQL injection and that's it. No internal application security practices were maintained as I originally developed this as a personal pet and an internal only application. If you want to introduce security to it, I am more than happy to accept.

Thanks and Warm Regards,
VYIEL