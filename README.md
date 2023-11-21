# VMW-Manager
A single place to Start-Stop-Pause-Resume and finally RDP into VMware Workstation VMs remotely, somewhat like ESXi


If one is regular VMWare Workstation user, there is no way to manage them remotely. By manage I mean start-stop-pause-resume. ESXI supports these things but not VMWare Workstation. This project does exactly that. Application security was kept in mind during the build to ensure protection as this would be an outward facing site running from a main computer.

How to: 
edit db_config.php for connecting to MySQL DB:
DB Config:
- DB Name: vm
- Table 1: users (full_name, email, password)
- Table 2: vms (name, description, location, port)

edit app_config.php to give the location of your vmware installation directory and the DNS record or the public IP.

Edit .htpasswd to password protect directory access.

VNC_Connections to be enabled from VMW and those ports to be forwarded and also to be inserted into the ADD VM Page section.

If everything works, then starting the VM will start the VM and in that host:port sequence, VNCViewer will be able to RDP into the hosts.

Updates to expect:
Add users,
Edit fields - all pages,

Possibility of feature:
Deploy ready made template VMs
