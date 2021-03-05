import units._env as env
import time,machine,ubinascii
import network as nw
import ujson as json
from umqtt.simple import MQTTClient

def waitForWifi(station):
    while not station.isconnected():
        print("WiFi not connected")
        time.sleep_ms(1000)


sensor = env.Env([32,33])




station = nw.WLAN(nw.STA_IF)

station.active(True)
station.connect("MikroTik-BOL", "TlPE707*37387!")

waitForWifi(station)

unique_id = ubinascii.hexlify(machine.unique_id())

c = MQTTClient("zeus_sensor", "192.168.0.16")
while True:
    temp = sensor.temperature
    pressure = sensor.pressure
    humidity = sensor.humidity
    
    if not station.isconnected():
        station.connect("MikroTik-BOL", "TlPE707*37387!")
        waitForWifi(station)
    
    data = {
        "temperature": temp,
        "pressure": pressure,
        "humidity": humidity,
        "sensor_id": unique_id
        }
    
    
    c.connect()
    c.publish(b"zeus_sensor", json.dumps(data))
    c.disconnect()
    time.sleep_ms(60000)

    


