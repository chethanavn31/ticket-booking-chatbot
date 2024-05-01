from MysqlConnector import createCon,closeCon
from Constants import dbConnError
from MysqlQueries import getLocationQuery

import sys
#import MySQLdb
import json
import os

def getLocation():
    output={}
    status='E'
    message=''
    errCode=3
    conn='E'
    cur=None
    locationData=[]

    try:
        #connect to db
        conn=createCon()
        if conn=='E':
            errCode=500
            raise Exception(dbConnError)

        cur = conn.cursor()

        #capture data

        #execute query
        cur.execute(getLocationQuery)

        locDetails = cur.fetchall()

        for val in locDetails:
            locObj={
                'locId':val[0],
                'locName':val[1]                
            }
            locationData.append(locObj)

        status='S'
        message='Success'
        errCode=0

    except Exception as exce:
        exc_type, exc_obj, exc_tb = sys.exc_info()
        status='E'
        if errCode==3:
            errCode=1005
        message = str(exce)+' '+str(exc_tb.tb_lineno)

    
        if conn!='E':
            conn.rollback()
        
    finally:
        if conn!='E':
            cur.close()
            closeCon(conn)

    output={
        'data':{
            'status':status,
            'message':message,
            'errorCode':errCode,
            'locDetails':locationData
        }
    }
    output=json.dumps(output)
    print(output)

if __name__ == "__main__":
    getLocation()