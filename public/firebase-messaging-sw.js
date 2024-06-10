importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js');
let config = {
        apiKey: "AIzaSyBSbwbh6BgIW2HY81n3dClcaG2B5nFbaaw",
        authDomain: "jinah-fab45.firebaseapp.com",
        projectId: "jinah-fab45",
        storageBucket: "jinah-fab45.appspot.com",
        messagingSenderId: "406672295793",
        appId: "1:406672295793:web:7efa882687963f26e918d0",
        measurementId: "G-LMB2SJ0K9Q",
 };
firebase.initializeApp(config);
const messaging = firebase.messaging();
messaging.onBackgroundMessage((payload) => {
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/images/default/firebase-logo.png'
    };
    self.registration.showNotification(notificationTitle, notificationOptions);
});
