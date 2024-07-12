
import sys
import json
import json
import shutil
import os


def logs(e):
    f = open('Logs.txt', 'a')
    f.write(str(e))
    f.close()


try:
    sys.argv[1]
except:
    print()
    print("This script needs to be run programmatically with very specific values from other programs. Not to be run standalone.")
    print("""
          
    syntax: python parseVmx.py 'C:\\xxx\\z.tmp'
          
    The tmp file shall have a JSON string (For Now) shall have 2 Key:Value pairs -> vmxLoc: C:\\somewhere.vmx, port: 5900.
    NOTE: app_config.json holds the common password value. Absence of the file will fail the script!
          
""")

    sys.exit()


def readVncParams(fileLoc):
    try:
        jf = open(fileLoc, 'r')
        jcontent = jf.read()
        argData = json.loads(jcontent)
        return argData
    except Exception as e:
        return False
    finally:
        jf.close()
    

cf = open('app_config.json', 'r')
appConfig = json.load(cf)
cf.close()


def revertVmx(vmxloc: str):

    dirStruct = vmxloc.split("\\")
    num = len(dirStruct)
    fname = dirStruct[-1]
    dirs = dirStruct[:num-1]

    dst = vmxloc
    backup = str("\\".join(dirs)) + "\\" + fname + ".bak"
    os.remove(dst)

    try:
        shutil.copy(src=backup, dst=dst)
        return True
    except:
        return False


def backVmx(vmxloc: str):

    dirStruct = vmxloc.split("\\")
    num = len(dirStruct)
    fname = dirStruct[-1]
    dirs = dirStruct[:num-1]

    src = vmxloc
    dst = str("\\".join(dirs)) + "\\" + fname + ".bak"

    try:
        shutil.copy(src=src, dst=dst)
        return True
    except:
        return False


def parseVmx(vmxloc: str):

    backup = backVmx(vmxloc)
    if backup:
        configDict = {}
        file = open(vmxloc, 'r').readlines()
        for i in file:
            pair = i.split("=", maxsplit=1) # The max split is set to 1 so that only it splits at first occurance. This is to avoid splits in RHS or VALUE part of the equals
            configDict[str(pair[0]).strip()] = str(pair[1]).strip()
        
        return configDict


def parseVNC(ConfigFile, port):

    commonPassw = appConfig["vncCommonPass"]

    ConfigFile['RemoteDisplay.vnc.enabled'] = "TRUE"
    ConfigFile['RemoteDisplay.vnc.port'] = f"\"{str(port)}\""
    ConfigFile['RemoteDisplay.vnc.key'] = f"\"{commonPassw}\""

    return ConfigFile


def writeToVmx(configFile, newFile):
    
    vncFormat = []

    for keys, values in configFile.items():
        vncFormat.append(f"{keys} = {values}\n")

    wf = open(newFile, 'w')
    for i in vncFormat:
        wf.write(i)
    wf.close()


try:
    argData = readVncParams(fileLoc=sys.argv[1])
    vmConfig = parseVmx(argData["vmxLoc"])
    newVmConfig = parseVNC(vmConfig, argData['port'])
    writeToVmx(newVmConfig, argData["vmxLoc"])
    print("True")
except Exception as e:
    print("False")
    revertVmx(vmxloc=argData["vmxLoc"])
