# Production Setup on Render

## Option 1: Use Pusher (Recommended for Render)

Pusher is easier for production and has a free tier.

### 1. Sign up for Pusher
- Go to https://pusher.com
- Create a free account
- Create a new app
- Get your credentials

### 2. Update `.env` on Render

In your Render dashboard, add these environment variables:

```env
BROADCAST_CONNECTION=pusher

PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=your-cluster

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### 3. Update echo.js

Change `resources/js/echo.js` to:

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});
```

### 4. Rebuild and Deploy

```bash
npm run build
git add .
git commit -m "Configure Pusher for production"
git push origin main
```

Render will auto-deploy.

## Option 2: Keep AJAX Polling (Current Setup)

If you don't want to set up Pusher, the current AJAX polling (refreshes every 3 seconds) works fine and requires no additional setup.

Just leave:
```env
BROADCAST_CONNECTION=log
```

Messages will auto-refresh every 3 seconds without real-time WebSockets.

## Recommended: Use Pusher

For production, Pusher is the easiest:
- ✅ No extra server needed
- ✅ Free tier (100 connections, 200k messages/day)
- ✅ Works perfectly with Render
- ✅ True real-time updates
- ✅ No WebSocket server to manage

The AJAX polling works but uses more bandwidth and isn't truly instant.
