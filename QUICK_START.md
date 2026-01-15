# Quick Start Guide - Messaging Features

## Getting Started in 3 Steps

### 1. Database is Ready ✅
Migrations have already been run. Your database now has:
- `conversations` table
- `messages` table  
- `conversation_participants` table
- `calls` table

### 2. Access the Features

Start your server:
```bash
php artisan serve
```

Visit: http://localhost:8000

### 3. Try It Out

**Create Your First Private Chat:**
1. Login to your account
2. Click "💬 Messages" in the top navigation
3. Click "New Conversation" button
4. Select "Private Message"
5. Choose a team member from the list
6. Click "Create"
7. Start chatting!

**Create a Group Chat:**
1. Click "New Conversation"
2. Select "Group Chat"
3. Enter a name like "Project Team"
4. Select multiple participants (hold Ctrl/Cmd)
5. Click "Create"

**Send Different Message Types:**
- **Text:** Just type and hit Send
- **Images/Videos/Audio:** Click 📎 and select a file

**Make a Call:**
1. Open any conversation
2. Click 🎤 for audio call
3. Click 📹 for video call

## Features Available Now

✅ Private messaging (1-on-1)
✅ Group chats (multiple participants)
✅ Text messages
✅ Image sharing
✅ Voice message uploads
✅ Video message uploads
✅ Call initiation (audio/video)
✅ Screen sharing toggle
✅ Message history
✅ Conversation list

## What You See

### Messages Page (`/conversations`)
- List of all your conversations
- Shows latest message preview
- Timestamp of last activity
- Create new conversation button

### Chat Interface (`/conversations/{id}`)
- Message history (scrollable)
- Message input box
- File attachment button
- Audio/Video call buttons
- Real-time message display

## API Endpoints Ready to Use

```bash
# List conversations
GET /conversations

# View conversation
GET /conversations/{id}

# Send message
POST /conversations/{id}/messages
  - body: "Your message"
  - type: text|voice|video|image
  - file: (optional file upload)

# Start call
POST /conversations/{id}/calls
  - type: audio|video

# Answer call
POST /calls/{id}/answer

# End call
POST /calls/{id}/end
```

## File Uploads

Supported file types:
- **Images:** JPG, PNG, GIF, etc.
- **Audio:** MP3, WAV, OGG, etc.
- **Video:** MP4, WebM, etc.

Max file size: **10MB**

Files are stored in: `storage/app/public/messages/`

## Optional: Enable Real-Time Updates

For live message updates without page refresh:

1. **Install Laravel Reverb (Laravel 11+):**
```bash
php artisan install:broadcasting
php artisan reverb:start
```

2. **Or use Pusher:**
Update `.env`:
```
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
```

3. **Run queue worker:**
```bash
php artisan queue:work
```

## Optional: Enable Video/Audio Calls

For actual video/audio streaming, integrate a WebRTC service:

**Option 1: Twilio Video**
```bash
composer require twilio/sdk
```

**Option 2: Daily.co**
- Free tier available
- Simple JavaScript integration
- No backend required

**Option 3: Agora.io**
```bash
composer require agora/rtc-sdk
```

## Troubleshooting

**Can't see Messages link?**
- Make sure you're logged in
- Clear browser cache
- Check navigation bar on the right

**File upload fails?**
- Run: `php artisan storage:link`
- Check file size (max 10MB)
- Verify file permissions

**Messages not sending?**
- Check you're a participant in the conversation
- Verify database connection
- Check browser console for errors

**Routes not found?**
- Run: `php artisan route:clear`
- Run: `php artisan config:clear`
- Restart server

## Need Help?

Check the documentation:
- `MESSAGING_FEATURES.md` - Full feature documentation
- `API_REFERENCE.md` - API endpoint reference
- `IMPLEMENTATION_SUMMARY.md` - Technical overview

## What's Next?

The core messaging system is complete and functional. Consider adding:

1. **Real-time updates** - Messages appear instantly
2. **Typing indicators** - See when someone is typing
3. **Read receipts** - Know when messages are read
4. **Emoji reactions** - React to messages
5. **Message search** - Find old messages
6. **Push notifications** - Get notified of new messages
7. **WebRTC integration** - Enable actual video calls

All the backend infrastructure is in place. These enhancements are primarily frontend additions!

---

**You're all set!** 🎉

The messaging system is ready to use. Login and start chatting with your team!
