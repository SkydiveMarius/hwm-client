import RPi.GPIO as GPIO
import time
 
GPIO.setmode(GPIO.BCM)
GPIO_TRIGGER = 19
GPIO_ECHO = 20
GPIO.setup(GPIO_TRIGGER, GPIO.OUT)
GPIO.setup(GPIO_ECHO, GPIO.IN)

GPIO.output(GPIO_TRIGGER, True)
time.sleep(0.00001)
GPIO.output(GPIO_TRIGGER, False)

startTime = time.time()
endTime = time.time()

while GPIO.input(GPIO_ECHO) == 0:
    startTime = time.time()

while GPIO.input(GPIO_ECHO) == 1:
    endTime = time.time()

timeDelta = endTime - startTime
distance = (timeDelta * 34300) / 2

print ("%.1f" % distance)

GPIO.cleanup()