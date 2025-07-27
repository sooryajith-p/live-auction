# 🎯 Live Auction Platform

A real-time auction platform built with **Laravel**, featuring role-based authentication, live bidding, countdown timers, real-time chat, and basic video integration. Designed to simulate a modern bidding system with synchronized WebSocket updates.

---

## 📦 Features

- 🔐 Role-based authentication (Admin & Bidder)
- 🛒 Admin CRUD for auction products
- 🕐 Real-time countdown timers using JavaScript
- 💬 Live chat for bidders (Pusher WebSocket)
- 📈 Live bid updates without page refresh
- 📹 Live stream integration via YouTube embed
- 🕛 Auto time extension on last-minute bids
- 📣 Outbid notifications

---

## 🛠️ Tech Stack

| Layer       | Technology           |
|-------------|----------------------|
| Backend     | Laravel 12.21.0      |
| Frontend    | Blade + Tailwind CSS |
| Real-Time   | Pusher (Laravel Echo)|
| Database    | MySQL                |
| Auth        | Laravel Breeze       |

---

## ⚙️ Installation & Setup

1. **Clone the repository**
   git clone https://github.com/yourusername/live-auction.git
   cd live-auction

1. **Install dependencies**
    composer install
    npm install && npm run build

2. **Environment Setup**
    **Copy .env.example to .env and update:**
    cp .env.example .env
    php artisan key:generate

3. **Configure your .env**
    **Database settings**
    **Pusher credentials**
    BROADCAST_DRIVER=pusher
    PUSHER_APP_ID=your_app_id
    PUSHER_APP_KEY=your_key
    PUSHER_APP_SECRET=your_secret
    PUSHER_APP_CLUSTER=mt1

4. **Run migrations and seeders**
    php artisan migrate --seed

5. **Start the server**
    php artisan serve

6. **Access**
    Admin login: admin@example.com / password
    Bidder login: bidder1@example.com / password

## 📂 Project Structure

    /app/Models – Models (User, Product, Bid, Message)
    /app/Http/Controllers – Handles logic (Profile, Product, LiveAuction)
    /resources/views – Blade views for admin & frontend
    /routes/web.php – Route definitions
    /database/seeders – Seeds sample data
    /public/assets/js/auction.js – Frontend bid & chat logic

## 🔐 User Roles & Permissions
    Admin
        Can log in to the admin panel.
        Can create, edit, and delete auction products.
        Can view all bids and manage auctions.

    Bidder
        Can register and log in as a bidder.
        Can view live auctions.
        Can place bids on products in real time.
        Can participate in the live chat.
        Can view auction countdowns and get live updates.

## 📡 Real-Time Features

    Implemented using Pusher and Laravel's Broadcasting
    Events:
        BidPlaced: Fires when a bid is submitted
        MessageSent: Fires when a chat message is sent

## 🧠 Auto Time Extension Logic

    If a new bid is placed in the last 30 seconds, the auction time extends by 1 minute, keeping the competition alive.

## 📃 Additional Notes

    All time-based logic is handled via JavaScript with server-provided end_time.
    Bidders cannot place a bid after the countdown ends.
    The interface is responsive and tested on desktop and mobile.