from MysqlConnector import createCon,closeCon
from Constants import dbConnError
from MysqlQueries import getDateQuery
from datetime import date

import sys
#import MySQLdb
import json
import os

def getMovieDate(payload):
    output={}
    status='E'
    message=''
    errCode=3
    conn='E'
    cur=None
    dateData=None
    dateDataM=[]

    try:
        #connect to db
        conn=createCon()
        if conn=='E':
            errCode=500
            raise Exception(dbConnError)

        cur = conn.cursor()

        #capture data
        jDecode = json.loads(payload)
        movieID = jDecode['movieId']

        #execute query
        inputVal = (movieID,)
        cur.execute(getDateQuery,inputVal)

        dateDetails = cur.fetchone()

        dateData = dateDetails

        date_time_start = dateData[0].strftime("%Y-%m-%d")
        date_time_end = dateData[1].strftime("%Y-%m-%d")

        dateDataM.append(date_time_start)
        dateDataM.append(date_time_end)

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
            'dateDetails':dateDataM
        }
    }

    output=json.dumps(output)

    print(output)

if __name__ == "__main__":
    getMovieDate(sys.argv[1])