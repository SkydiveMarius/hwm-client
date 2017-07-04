# hwm-client
A tiny client which measures the ground water level by ultrasonic

## Hardware
* Raspberry Pi (all models supported)
* Ultrasonic sensor (e.g. HC-SR04)

## Software requirements
* PHP > 7.0
* PHP curl extension
* Ready [HWM server](https://github.com/SkydiveMarius/hwm-server)

## Configuration
Copy .env.dist to .env
Fill .env variables

| Variable      | Description                        |
| ------------- | ---------------------------------- |
| SERVER_URL    | Full HWM server URL                |
| AUTH_TOKEN    | Authentication token of HWM server |

## Run the client
```bash
php run.php start [--interval=60]
```
### Options
| Short   | Long        | Description                                | Default |
| ------- | ------------ | ------------------------------------------ | ------- |
| i       | interval     | Interval in seconds of measurement cycles  | 60      |
