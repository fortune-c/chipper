# Real-Time Broadcasting Setup

Laravel Broadcasting with Reverb has been installed. Follow these steps to enable real-time updates across your app.

## Quick Setup

### 1. Update your `.env` file

Add these lines to your `.env`:

```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=chipper
REVERB_APP_KEY=local-key
REVERB_APP_SECRET=local-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

### 2. Start Reverb Server

In a new terminal:
```bash
php artisan reverb:start
```

### 3. Start Queue Worker

In another terminal:
```bash
php artisan queue:work
```

### 4. Start Your App

```bash
php artisan serve
```

## What You Get

With broadcasting enabled:
- ✅ Messages appear instantly without refresh
- ✅ New posts show up in real-time
- ✅ Task updates broadcast to all users
- ✅ Call notifications appear immediately

## For Production (Render/Cloud)

Update your `.env` on the server:

```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-secure-key
REVERB_APP_SECRET=your-secure-secret
REVERB_HOST=your-domain.com
REVERB_PORT=443
REVERB_SCHEME=https

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

Then run:
```bash
php artisan reverb:start --host=0.0.0.0 --port=8080
```

## Without Broadcasting (Current Setup)

The app currently uses AJAX polling (refreshes every 3 seconds) for messages. This works fine but isn't truly real-time.

To keep using polling without broadcasting:
- Leave `BROADCAST_CONNECTION=log` in `.env`
- No need to run Reverb or queue worker
- Messages still auto-refresh every 3 seconds

## Troubleshooting

**Reverb won't start?**
```bash
php artisan config:clear
php artisan cache:clear
```

**Messages not appearing?**
- Check Reverb is running: `php artisan reverb:start`
- Check queue worker is running: `php artisan queue:work`
- Check browser console for WebSocket errors

**Port 8080 already in use?**
Change `REVERB_PORT` to another port like 8081 or 9000.
