# Real-Time Communication Features

This document describes the newly implemented real-time communication features in Chipper Together.

## Features Implemented

### 1. Private Messaging
Direct one-on-one conversations between team members.

**Usage:**
- Navigate to Messages from the navigation bar
- Click "New Conversation" and select "Private Message"
- Choose a team member to start chatting
- Send text messages, images, voice clips, or videos

**API Endpoints:**
- `GET /conversations` - List all conversations
- `GET /conversations/{id}` - View a specific conversation
- `POST /conversations` - Create a new conversation
- `POST /conversations/private/{user}` - Create/find private conversation with a user

### 2. Group Chats
Create private group discussions for specific projects or teams.

**Usage:**
- Click "New Conversation" and select "Group Chat"
- Enter a group name
- Select multiple participants
- Start collaborating with your team

**API Endpoints:**
- `POST /conversations/{id}/participants` - Add participants to a group

### 3. Voice Messages
Quick audio clips for more personal communication.

**Usage:**
- In any conversation, click the attachment icon (📎)
- Select an audio file to upload
- The message will be sent as a voice message with an audio player

**Supported Formats:** Any audio format supported by HTML5 audio element

### 4. Video Calls & Audio Calls
Integrated video/audio conferencing for instant communication.

**Usage:**
- Open any conversation
- Click the microphone icon (🎤) for audio calls
- Click the video icon (📹) for video calls
- The call will be initiated and other participants will be notified

**API Endpoints:**
- `POST /conversations/{id}/calls` - Initiate a call
- `POST /calls/{id}/answer` - Answer an incoming call
- `POST /calls/{id}/end` - End an active call

**Note:** WebRTC implementation for actual audio/video streaming needs to be integrated with a service like:
- Twilio Video
- Agora.io
- Daily.co
- Self-hosted Jitsi

### 5. Screen Sharing
Share screens during video calls for collaborative work.

**API Endpoints:**
- `POST /calls/{id}/screen-share` - Toggle screen sharing on/off

**Payload:**
```json
{
  "screen_sharing": true
}
```

## Database Schema

### Conversations Table
- `id` - Primary key
- `type` - 'private' or 'group'
- `name` - Group name (nullable for private chats)
- `created_by` - User who created the conversation
- `timestamps`

### Messages Table
- `id` - Primary key
- `conversation_id` - Foreign key to conversations
- `user_id` - Foreign key to users
- `body` - Message text (nullable)
- `type` - 'text', 'voice', 'video', or 'image'
- `file_path` - Path to uploaded file (nullable)
- `timestamps`

### Conversation Participants Table
- `id` - Primary key
- `conversation_id` - Foreign key to conversations
- `user_id` - Foreign key to users
- `last_read_at` - Timestamp of last read message
- `timestamps`
- Unique constraint on (conversation_id, user_id)

### Calls Table
- `id` - Primary key
- `conversation_id` - Foreign key to conversations
- `initiated_by` - Foreign key to users
- `type` - 'audio' or 'video'
- `status` - 'pending', 'active', 'ended', or 'missed'
- `screen_sharing` - Boolean
- `started_at` - Timestamp when call started
- `ended_at` - Timestamp when call ended
- `timestamps`

## Models

### Conversation
**Relationships:**
- `participants()` - BelongsToMany User
- `messages()` - HasMany Message
- `calls()` - HasMany Call
- `creator()` - BelongsTo User
- `latestMessage()` - HasOne Message

### Message
**Relationships:**
- `conversation()` - BelongsTo Conversation
- `user()` - BelongsTo User

### Call
**Relationships:**
- `conversation()` - BelongsTo Conversation
- `initiator()` - BelongsTo User

### User (Updated)
**New Relationships:**
- `conversations()` - BelongsToMany Conversation
- `messages()` - HasMany Message

## Broadcasting Events

### MessageSent
Broadcast when a new message is sent in a conversation.

**Channel:** `conversation.{id}`
**Data:**
```json
{
  "id": 1,
  "body": "Hello!",
  "type": "text",
  "file_path": null,
  "user": {
    "id": 1,
    "name": "John Doe"
  },
  "created_at": "2026-01-15T12:00:00.000Z"
}
```

### CallInitiated
Broadcast when a call is initiated in a conversation.

**Channel:** `conversation.{id}`
**Data:**
```json
{
  "id": 1,
  "type": "video",
  "status": "pending",
  "screen_sharing": false,
  "initiator": {
    "id": 1,
    "name": "John Doe"
  }
}
```

## Authorization

### ConversationPolicy
- `view()` - User must be a participant
- `update()` - User must be a participant (group chats only)

### MessagePolicy
- `delete()` - User must be the message author

## Setup Instructions

1. **Run Migrations:**
   ```bash
   php artisan migrate
   ```

2. **Configure Broadcasting (Optional):**
   For real-time updates, configure Laravel Broadcasting in `.env`:
   ```
   BROADCAST_DRIVER=pusher
   PUSHER_APP_ID=your-app-id
   PUSHER_APP_KEY=your-app-key
   PUSHER_APP_SECRET=your-app-secret
   PUSHER_APP_CLUSTER=your-cluster
   ```

   Or use Laravel Reverb (Laravel 11+):
   ```bash
   php artisan reverb:start
   ```

3. **Storage Link:**
   Ensure storage is linked for file uploads:
   ```bash
   php artisan storage:link
   ```

4. **Queue Worker (Optional):**
   For broadcasting to work properly:
   ```bash
   php artisan queue:work
   ```

## WebRTC Integration (Future Enhancement)

To enable actual audio/video calling, integrate a WebRTC service:

### Option 1: Twilio Video
```bash
composer require twilio/sdk
```

### Option 2: Agora.io
```bash
composer require agora/rtc-sdk
```

### Option 3: Daily.co
Use their JavaScript SDK in the frontend

### Option 4: Self-hosted Jitsi
Embed Jitsi Meet in an iframe

## Frontend Enhancements (Recommended)

1. **Real-time Message Updates:**
   - Install Laravel Echo and Pusher JS
   - Listen for MessageSent events
   - Update UI without page refresh

2. **Typing Indicators:**
   - Broadcast typing events
   - Show "User is typing..." indicator

3. **Read Receipts:**
   - Update `last_read_at` when user views messages
   - Show read/unread status

4. **File Upload Progress:**
   - Show upload progress bar
   - Preview images before sending

5. **Emoji Picker:**
   - Add emoji selector for messages
   - Support emoji reactions

## Security Considerations

1. **File Upload Validation:**
   - Current limit: 10MB per file
   - Validate file types on server-side
   - Scan for malware if handling sensitive data

2. **Message Encryption:**
   - Consider end-to-end encryption for sensitive conversations
   - Use Laravel's encryption helpers

3. **Rate Limiting:**
   - Add rate limiting to prevent spam
   - Throttle message sending

4. **Authorization:**
   - All endpoints check user permissions
   - Users can only access conversations they're part of

## Testing

Run the test suite:
```bash
php artisan test
```

Create tests for:
- Creating conversations
- Sending messages
- Initiating calls
- Authorization policies

## Troubleshooting

### Messages not appearing
- Check if migrations ran successfully
- Verify user is a participant in the conversation
- Check browser console for JavaScript errors

### File uploads failing
- Ensure `storage/app/public` exists
- Run `php artisan storage:link`
- Check file size limits in `php.ini`

### Broadcasting not working
- Verify `.env` broadcasting configuration
- Check queue worker is running
- Test with `php artisan tinker` and `broadcast(new MessageSent($message))`

## Future Enhancements

- [ ] Message search functionality
- [ ] Message editing
- [ ] Message reactions (emoji)
- [ ] Typing indicators
- [ ] Read receipts
- [ ] Online/offline status
- [ ] Push notifications
- [ ] Message threading/replies
- [ ] File sharing with preview
- [ ] Voice/video call recording
- [ ] Call history and analytics
