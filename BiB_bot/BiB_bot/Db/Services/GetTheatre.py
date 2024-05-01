from MysqlConnector import createCon,closeCon
from Constants import dbConnError
from MysqlQueries import getTheatreQuery

import sys
#import MySQLdb
import json
import os

def getTheatre(payload):
    output={}
    status='E'
    message=''
    errCode=3
    conn='E'
    cur=None
    theatreData=[]

    try:
        #connect to db
        conn=createCon()
        if conn=='E':
            errCode=500
            raise Exception(dbConnError)

        cur = conn.cursor()

        #capture data
        jDecode = json.loads(payload)

        moviename = jDecode['moviename']
        locId = jDecode['locationId']
        
        #execute query
        inputVal = (moviename,locId)
        cur.execute(getTheatreQuery,inputVal)

        theatreDetails = cur.fetchall()

        for val in theatreDetails:
            obj={
                'theatre_id':val[0],
                'name':val[1]
            }
            theatreData.append(obj)

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
            'theatreDetails':theatreData
        }
    }
    output=json.dumps(output)
    print(output)

if __name__ == "__main__":
    getTheatre(sys.argv[1])