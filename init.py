import mysql.connector
import subprocess, sys
import datetime, time
import json
import schedule
import shlex
import os

os.chdir(os.path.dirname(__file__))


# # INCASE ANYONE NEEDS SSL
# cert = "security\\SSL\\server.crt"
# ckey = "security\\SSL\\server.key"
# cmode = '--ssl-only'
# #####



def cprint(message):
    current_time = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    print(" [+] " + str(current_time) + " ---> LOG: " + str(message))

 
fc = open('app_config.json', 'r')
appConfig = json.load(fc)


def initVMRestServer():

    cprint("Initiating VMRest Server!")

    vmrestLoc = appConfig['vmrest_location']
    vmrestPort = appConfig['vmrest_port']

    command = ['powershell', '-Command',
                'Start-Process',
                shlex.quote(vmrestLoc),
                '-ArgumentList', f'@("-p {vmrestPort}", "-d")']

    try:
        serverProc = subprocess.Popen(command)
    except Exception as e:
        serverProc = None
        cprint("Error Launching VMRest API. Error: " + str(e))
        cprint("Exiting Program! ")
        sys.exit()


def initWebServer():

    cprint("Initiating Web Server! ")

    try:
        xamppLoc = appConfig['xampp_loc']
        cmd = xamppLoc + "xampp-control.exe"
        cprint(cmd)
        subprocess.Popen(cmd)

    except Exception as e:
        cprint("Error Starting Web and DB Server. Error: " + str(e))
        cprint('Admin Privilage required!')
        sys.exit()


initVMRestServer()
initWebServer()
time.sleep(10)


def MySqlConn():

    sName = appConfig['mysql_host']
    uName = appConfig['mysql_user']
    passw = appConfig['mysql_pass']
    db_name = appConfig['mysql_DB']

    conn = mysql.connector.connect(
        host=sName,
        user=uName,
        password=passw,
        database=db_name
    )

    return conn

# myCursor = conn.cursor(dictionary=True)

processes = {}

def TaskManager(vmStates):

    conn = MySqlConn()

    cursor = conn.cursor(dictionary=True)

    vmQueue = {}

    for vms in vmStates:
        vmName = vms['name']
        vmLoc = vms['loc']
        vmState = vms['status']
        vmID = vms['vmID']

        qry = ("SELECT * FROM vms, vnc_servers WHERE vms.vms_id = vnc_servers.vms_id AND vms.location = %s")
        qryParams = (vmLoc,)
        cursor.execute(qry, qryParams)
        res = cursor.fetchone()
        if cursor.rowcount > 0:
            vmPort = res['port']
            wsPort = res['websockify_port']

            vmQueue[vmID] = [vmName, vmPort, wsPort]

    cursor.close()


    for vmID, vmDetails in vmQueue.items():
        vmID = vmID
        name = vmDetails[0]
        vncport = vmDetails[1]
        wsport = vmDetails[2]

        if vmID in processes.keys():
            cprint("VmID already in task manager! Skipping Process Open!")

        if vmID not in processes.keys():
            cprint("VmID not in task manager. Starting Process!")

            wsargs = str(wsport) + " " + appConfig['hostIP'] + ":" + str(vncport)
            # keyargs = "--cert " + cert + " --key " + ckey + " " + cmode + " " # Use above certificate settings in case of SSL USE. Add + keyargs + wsargs bellow

            # procObj = subprocess.Popen("python -m websockify " + keyargs + wsargs) # FOR SSL use only
            procObj = subprocess.Popen("python -m websockify -v " + wsargs)
            cprint("VmID Started at PID: " + str(procObj.pid))
            processes[vmID] = procObj


    removalList = []
    for vmID in processes.keys():
        if vmID not in vmQueue.keys():
            cprint("VM Not-Running information received!")
            procObj = processes[vmID]
            try:
                removalList.append(vmID)
                procObj.kill()
                cprint("Process for " + vmID + " At process: " + str(procObj.pid) + " Killed! ")
            except Exception as e:
                cprint("Failed to Kill Process for " + vmID + " on process ID " + str(procObj.pid) , e)

    for vmIDs in removalList:
        processes.pop(vmIDs)
        cprint("Removed VmID: " + vmIDs + " From Task Manager! ")
            

def checkChanges():

    cprint("Checking for Changes!")

    vmf = open('vmStates.json', 'r')
    vmStates = json.load(vmf)

    TaskManager(vmStates=vmStates)


schedule.every(30).seconds.do(checkChanges)


try:
    while True:
        schedule.run_pending()
        time.sleep(1)
except Exception as e:
    cprint("Terminating Self! Error: ", str(e))
    sys.exit()

