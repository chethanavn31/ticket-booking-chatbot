from MysqlConnector import createCon,closeCon
from Constants import dbConnError
from MysqlQueries import getMovieQuery
from MysqlQueries import searchMovieQuery

import sys
#import MySQLdb
import json
import os

def getMovie(payload):
    output={}
    status='E'
    message=''
    errCode=3
    conn='E'
    cur=None
    movieData=[]
    location=''
    typeof=''
    userId=''
    genre=''
    movieName=''



    try:
        #connect to db
        conn=createCon()
        if conn=='E':
            errCode=500
            raise Exception(dbConnError)

        cur = conn.cursor()

        #capture data
        jDecode = json.loads(payload)
        if 'location' in jDecode:
            location = jDecode['location']
        if 'type' in jDecode:
            typeof = jDecode['type']
        if 'userId' in jDecode:
            userId = jDecode['userId']
        if 'genre' in jDecode:
            genre = jDecode['genre']
        if 'movie' in jDecode:
            movieName = jDecode['movie']

        # Execute Query

        inputVal = (location,)                  
        
        if typeof=='search':            
            inputVal = (location, genre, movieName)
            cur.execute(searchMovieQuery, inputVal)
        else:
            cur.execute(getMovieQuery,inputVal)
   

        movieDetails = cur.fetchall()

        for val in movieDetails:
            moiveObj={
                'id':val[0],
                'movie':val[1],
                'director':val[2],
                'genre':val[3],
                'poster':val[4]
            }
            movieData.append(moiveObj)

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
            'movieDetails':movieData
        }
    }
    output=json.dumps(output)
    print(output)

if __name__ == "__main__":
    getMovie(sys.argv[1])
