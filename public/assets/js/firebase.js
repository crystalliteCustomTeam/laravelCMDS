import firebase from "firebase/app";
import "firebase/firestore";


// Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyCgnvSZKBwb0cp47hdv_BgYOutXq0iGb0c",
  authDomain: "seoresultspro-1ccde.firebaseapp.com",
  databaseURL: "https://seoresultspro-1ccde-default-rtdb.firebaseio.com/",
  projectId: "seoresultspro-1ccde",
  storageBucket: "seoresultspro-1ccde.appspot.com",
  messagingSenderId: "677031361232",
  appId: "1:677031361232:web:83c6c579bab313f306bcd5",
  measurementId: "G-YZBV98Z1QD"
};


// Initialize Firebase
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);
