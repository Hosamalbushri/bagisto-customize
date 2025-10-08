@php
    $firebaseConfig = firebase_helper()->getFirebaseConfig();
    $notificationsEnabled = firebase_helper()->isNotificationEnabled();
@endphp

@if(!empty($firebaseConfig['apiKey']) && !empty($firebaseConfig['projectId']) && !empty($firebaseConfig['messagingSenderId']))

    <script src="https://www.gstatic.com/firebasejs/10.14.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.14.1/firebase-messaging-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.14.1/firebase-analytics-compat.js"></script>

    <script>
        window.firebaseConfig = {
            apiKey: "{{$firebaseConfig['apiKey']}}",
            authDomain: "{{$firebaseConfig['authDomain']}}",
            projectId: "{{$firebaseConfig['projectId']}}",
            storageBucket: "{{$firebaseConfig['storageBucket']}}",
            messagingSenderId: "{{$firebaseConfig['messagingSenderId']}}",
            appId: "{{$firebaseConfig['appId']}}",
            measurementId: "{{$firebaseConfig['measurementId']}}"
        };

        window.firebaseVapidKey = "{{firebase_helper()->getVapidKey()}}";
        window.serviceWorkerUrl = "{{ route('firebase.messaging.sw') }}";
    </script>
@endif
