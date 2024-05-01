from MysqlConnector import createCon,closeCon
from Constants import dbConnError
from MysqlQueries import getLocationIDQuery
from MysqlQueries import getMovieIDQuery
from MysqlQueries import getTheatreIDQuery
from MysqlQueries import getShowIDQuery

import sys
#import MySQLdb
import json
import os

def getID(payload):
    output={}
    status='E'
    message=''
    errCode=3
    conn='E'
    cur=None
    idData=[]

    try:
        #connect to db
        conn=createCon()
        if conn=='E':
            errCode=500
            raise Exception(dbConnError)

        cur = conn.cursor()

        #capture data
        jDecode = json.loads(payload)

        name = jDecode['name']
        typeof = jDecode['type']
        thId = jDecode['thId']

        #execute query
        inputVal = (name,)
        
        if typeof=='location':            
            cur.execute(getLocationIDQuery,inputVal)
        if typeof=='movie':
            cur.execute(getMovieIDQuery,inputVal)
        if typeof=='theatre':
            cur.execute(getTheatreIDQuery,inputVal)
        if typeof=='show':
            inputVal = (name,thId)
            cur.execute(getShowIDQuery,inputVal)

        idDetails = cur.fetchone()
        idData = idDetails[0]

        if typeof=='show' or typeof=='theatre' or typeof=='movie':
            idData = idDetails
        
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
            'details':idData
        }
    }
    output=json.dumps(output)
    print(output)

if __name__ == "__main__":
    getID(sys.argv[1])