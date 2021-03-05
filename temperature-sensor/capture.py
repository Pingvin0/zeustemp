import paho.mqtt.client as mqtt
import mysql.connector
import json

def connect_to_mysql():
    return mysql.connector.connect(
        host="192.168.0.16",
        user="zeus",
        password="exTs3wDlf9MAgVsT",
        database="zeus")


def on_connect(client, userdata, flags, rc):
    print("Connected with result code "+str(rc))    
    client.subscribe("zeus_sensor")

def on_message(client, userdata, msg):
    obj = json.loads(msg.payload)
    print(str(obj))

    database = connect_to_mysql()
    cursor = database.cursor()
    
    sql = "INSERT INTO sensor (sensor_id, pressure, temperature, humidity) VALUES (%s, %s, %s, %s)"
    data = (obj["sensor_id"], obj["pressure"], obj["temperature"], obj["humidity"])
    
    cursor.execute(sql, data)
    
    database.commit()
    database.close()
    
    # todo: comment


client = mqtt.Client()
client.on_connect = on_connect
client.on_message = on_message



client.connect("192.168.0.16", 1883, 60)
client.loop_forever()