# Author: Mukund
# Desc :  Mysql-Python Connection Script
#

import mysql.connector
import sys
import os
import json


def createCon():

    try:
        mydb = mysql.connector.connect(
        host='localhost',
        user='root',
        passwd='Neetu@123',
        database='bib_DB'
        )


        if mydb.is_connected():
            connection = mydb
            #print('Connected')
        else:
            raise Exception('Error creating mysql connection')	

    except Exception as exce:
        connection = str(exce)
        #print(connection)
        connection = 'E'


    return connection

def closeCon(mydb):
    mydb.close()

# convar = createCon()
# closeCon(convar)


