var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var env = require('node-env-file');
env(__dirname + '/.env');

var broadcastChannel = process.env.BROADCAST_CHANNEL;
var broadcastPort = process.env.BROADCAST_SERVER_PORT;
var redisHost = process.env.REDIS_HOST;
var redisPort = process.env.REDIS_PORT;

var redis = new Redis({
  host: redisHost,
  port: redisPort,
});

redis.subscribe(broadcastChannel, function(err, count) {
});
redis.on('message', function(channel, message) {
    message = JSON.parse(message);
    io.emit(channel + ':' + message.event, message.data);
});
http.listen(broadcastPort, function(){
    console.log('Listening on Port ' + broadcastPort);
});