from MysqlConnector import createCon,closeCon
from Constants import dbConnError
from MysqlQueries import addUserQuery
from MysqlQueries import getUserQuery

import sys
#import MySQLdb
import json
import os

def addUser(payload):
    output={}
    status='E'
    message=''
    errCode=3
    conn='E'
    cur=None
    userData=[]

    try:
        #connect to db
        conn=createCon()
        if conn=='E':
            errCode=500
            raise Exception(dbConnError)

        cur = conn.cursor()

        #capture data
        jDecode = json.loads(payload)

        userID = jDecode['id']
        username = jDecode['name']

        message = 'already exists'

        #execute query
        inputVal = (userID,)
        cur.execute(getUserQuery,inputVal)

        userData = cur.fetchall()
        #print(result)

        if len(userData) == 0:
            inputVal = (userID,username)
            cur.execute(addUserQuery,inputVal)

            if cur.rowcount == 1:
                message='user data inserted'
            else:
                errCode=500
                raise Exception('Error while inserting data')

        status='S'
        
        errCode=0
        conn.commit()

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
            'errorCode':errCode
        }
    }
    output=json.dumps(output)
    print(output)

if __name__ == "__main__":
    addUser(sys.argv[1])