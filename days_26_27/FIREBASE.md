Firebase Setup

1. Visit https://console.firebase.google.com

2. Create a new project

3. Keep the default settings in place

4. Wait a few moments — Firebase will provision your project

5. Click "Project Overview" 

6. Click the Web (</>) icon

7. Name your app

8. Skip "Firebase Hosting" (we will host our project elsewhere)

9. Click "Register App"

10. Click "Use a <script> tag"

11. Copy the code that appears and paste it into this 'script' tag. Note that you will have to remove the
    new 'script' tag that gets copied since we already have one set up. This script tag must be set up
    with the 'type="module"' attribute set.

12. Back in the Firebase console, click "Continue to Console"

13. Click "Cloud Firestore" to set up our database

14. Click "Create Database"

15. Select any of the US based data warehouse locations

16. Choose "Start in Test Mode" - we can secure this later

17. Come back to the HTML document and add the following imports to the 'script' tag - this gives your application
    access to your Firebase database:

    import { getFirestore, collection, doc, getDoc, getDocs, updateDoc, writeBatch, onSnapshot } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-firestore.js";

18. Under the "Initialize Firebase" section, add this line of code to connect to your database:

    const db = getFirestore(app);

19. The final code that should appear in your HTML document should be the following:

    <script type="module">

            // Import the functions you need from the SDKs you need
            import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-app.js";
            import { getAnalytics } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-analytics.js";
            import { getFirestore, collection, doc, getDoc, getDocs, updateDoc, writeBatch, onSnapshot } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-firestore.js";

            // TODO: Add SDKs for Firebase products that you want to use
            // https://firebase.google.com/docs/web/setup#available-libraries

            // Your web app's Firebase configuration
            // For Firebase JS SDK v7.20.0 and later, measurementId is optional
            // PASTE YOUR FIREBASE CONFIG OBJECT HERE - THE CODE WILL NOT WORK WITHOUT THIS!
            const firebaseConfig = {
                
            };

            // Initialize Firebase
            const app = initializeApp(firebaseConfig);
            const analytics = getAnalytics(app);
            const db = getFirestore(app);


            // Vue code goes here ....


    </script>

20. Next we are going to set up records for the 44 "drop zones" our application is going to need. 
    Create a new collection in Firestore named "dancefloor" -> "state" -> "dropzones" (string)
    Paste in the following starter JSON object:

    [ { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null }, { "gif": null } ]

21. Next we need to set up our Vue application to load in this JSON object when the application starts up,
    as well as whenever the object changes. We can do this by implementing a new method:

    // this function reads the contents of the Firestore collection once when the page loads
    // it also sets up an event listener to receive data whenever the Firestore collection changes
    loadAndListenToDropzones() {

        // obtain a reference to the "dancefloor" collection, and find the "state" document inside of it
        const stateDoc = doc(db, "dancefloor", "state");

        // set up a listener to listen for changes on this document
        // when a change is detected, run the callback function described here
        // note we have to use "fat arrow" syntax to allow Vue to access its reactive data object
        // also note that this is guarenteed to run at least once to get an initial set of data
        onSnapshot(stateDoc, (snapshot) => {

            // if data exists in the document
            if (snapshot.exists()) {

                // extract the data into a local variable
                const dataFromFirebase = snapshot.data();

                // store the data into a reactive variable 'dropZones'
                // note 'dataFromFirebase.dropZones' is the JSON object that we uploaded
                this.dropZones = JSON.parse(dataFromFirebase.dropZones);

                console.log("Dropzones updated via snapshot!");
            } else {
                console.error("Dancefloor state document missing!");
            }
        });
    }

22. Call this method from within the 'mounted' function, and disable the manual creation of drop zones:

    mounted: function () {

        this.loadAndListenToDropzones();

        /*
        // create drop zones (44 fit nicely)
        for (let i = 0; i < 44; i++) {
            this.dropZones.push({
                'gif': ''
            })
        }
        */

    }

23. Finally, update the 'dropDancer' method to send the current copy of the 'dropZones' array to Firestore:

    dropDancer: async function (index) {
        if (this.selectedGif) {
            if (this.selectedGif == 'REMOVE') {
                this.dropZones[index].gif = null;
            }
            else {
                this.dropZones[index].gif = this.selectedGif;
            }
            this.selectedGif = '';
            for (let object of this.dancerGifs) {
                object.selected = false;
            }

            // save the updated array back to Firebase

            // get a reference to the "dancefloor" collection's "state" document
            const stateDoc = doc(db, "dancefloor", "state");

            // update the document with a new value (the stringified version of our dropZones reactive variable)
            await updateDoc(stateDoc, {
                dropZones: JSON.stringify(this.dropZones)
            });

        }
    }