# WhoYou PH: Dynamic Phone Book System

A lightweight, local web application designed to manage and display critical emergency contact information in the Philippines. This project bridges a structured MySQL backend with a responsive frontend dashboard, allowing administrators to view, search, and dynamically update emergency data on the fly.

## 🌟 Key Features

* **Pre-loaded Directory:** Loaded with 50+ official Philippine hotlines (National Hotlines, Police, BFP, Coast Guard, Red Cross, and major Metro Manila/provincial hospitals).
* **Pre-allocated Scalability:** Includes 150 pre-configured blank data slots ready for immediate deployment and on-the-fly website updates.
* **Unified Dashboard Interface:**
  * **Sidebar Navigation:** Smooth access to independent workspace sections.
  * **Summary Metrics:** Real-time data counters indicating Total Contacts, Starred Favorites, and Available Free Slots.
  * **Filtered Sections:** Displays separated blocks for core Emergency Hotlines and user-defined Favorites.
* **Full CRUD Operations via Pop-up Modals:** Users can update existing placeholder records with complete Contact Profiles (Name, Number, Email, and Resource Description) using interactive JavaScript pop-up forms without changing database architecture manually.

## 🛠️ Tech Stack
* **Database:** MySQL (relational table structure utilizing AUTO_INCREMENT keys)
* **Backend Logic:** PHP (data mapping, update queries, and asynchronous communication)
* **Frontend UI:** HTML5, CSS3 Grid layouts, and vanilla JavaScript (modal triggers)
