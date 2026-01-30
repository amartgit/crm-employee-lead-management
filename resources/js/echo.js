import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'e58db2b5ebfba72270ca',
    cluster: 'ap2',
    wsHost: "127.0.0.1",
    // wsHost: window.location.hostname === 'localhost' ? '127.0.0.1' : window.location.hostname ,
    forceTLS: false,
    disableStats: true,
    debug: true,  // Enable debug logs
    enabledTransports: ['ws'],

});

Pusher.logToConsole = true;

var pusher = new Pusher('e58db2b5ebfba72270ca', {
    cluster: 'ap2'
});

// var channel = pusher.subscribe('my-channel');
// channel.bind('my-event', function (data) {
//     alert(JSON.stringify(data));
// });

// // Listen to 'leads' channel to receive lead updates
// var leadsChannel = window.Echo.private('leads.' + leadId);  // Ensure leadId is dynamically set
// leadsChannel.listen('LeadUpdated', function (data) {
//     console.log('Lead updated:', data);
//     alert(JSON.stringify(data));
// });



