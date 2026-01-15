# Messaging API Reference

## Conversations

### List Conversations
```
GET /conversations
```
Returns all conversations for the authenticated user.

**Response:**
```json
[
  {
    "id": 1,
    "type": "private",
    "name": null,
    "participants": [...],
    "latest_message": {...},
    "updated_at": "2026-01-15T12:00:00.000Z"
  }
]
```

### View Conversation
```
GET /conversations/{id}
```
View a specific conversation with paginated messages.

**Response:** HTML view with messages

### Create Conversation
```
POST /conversations
```

**Body:**
```json
{
  "type": "private|group",
  "name": "Group Name (required for groups)",
  "participants": [2, 3, 4]
}
```

### Create Private Conversation
```
POST /conversations/private/{userId}
```
Creates or finds existing private conversation with a user.

### Add Participants
```
POST /conversations/{id}/participants
```

**Body:**
```json
{
  "participants": [5, 6, 7]
}
```

## Messages

### Send Message
```
POST /conversations/{id}/messages
```

**Body (Form Data):**
- `body` (string, optional): Message text
- `type` (string, required): text|voice|video|image
- `file` (file, optional): Uploaded file (max 10MB)

**Response:**
```json
{
  "id": 1,
  "conversation_id": 1,
  "user_id": 1,
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

### Delete Message
```
DELETE /messages/{id}
```
Only the message author can delete their messages.

## Calls

### Initiate Call
```
POST /conversations/{id}/calls
```

**Body:**
```json
{
  "type": "audio|video"
}
```

**Response:**
```json
{
  "id": 1,
  "conversation_id": 1,
  "initiated_by": 1,
  "type": "video",
  "status": "pending",
  "screen_sharing": false,
  "started_at": null,
  "ended_at": null
}
```

### Answer Call
```
POST /calls/{id}/answer
```

**Response:**
```json
{
  "id": 1,
  "status": "active",
  "started_at": "2026-01-15T12:00:00.000Z"
}
```

### End Call
```
POST /calls/{id}/end
```

**Response:**
```json
{
  "id": 1,
  "status": "ended",
  "ended_at": "2026-01-15T12:00:00.000Z"
}
```

### Toggle Screen Share
```
POST /calls/{id}/screen-share
```

**Body:**
```json
{
  "screen_sharing": true
}
```

## Authentication

All endpoints require authentication. Include session cookie or bearer token.

## Error Responses

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "message": "This action is unauthorized."
}
```

### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field": ["Error message"]
  }
}
```
