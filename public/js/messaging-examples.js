// Example JavaScript code for interacting with the Messaging API

// ============================================
// SENDING MESSAGES
// ============================================

// Send a text message
async function sendTextMessage(conversationId, messageText) {
    const formData = new FormData();
    formData.append('body', messageText);
    formData.append('type', 'text');
    
    const response = await fetch(`/conversations/${conversationId}/messages`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    });
    
    return await response.json();
}

// Send a voice message
async function sendVoiceMessage(conversationId, audioFile) {
    const formData = new FormData();
    formData.append('file', audioFile);
    formData.append('type', 'voice');
    
    const response = await fetch(`/conversations/${conversationId}/messages`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    });
    
    return await response.json();
}

// Send an image
async function sendImage(conversationId, imageFile, caption = '') {
    const formData = new FormData();
    formData.append('file', imageFile);
    formData.append('type', 'image');
    if (caption) {
        formData.append('body', caption);
    }
    
    const response = await fetch(`/conversations/${conversationId}/messages`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    });
    
    return await response.json();
}

// ============================================
// CONVERSATIONS
// ============================================

// Create a private conversation
async function createPrivateConversation(userId) {
    const response = await fetch(`/conversations/private/${userId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    });
    
    // This redirects, so handle accordingly
    window.location.href = response.url;
}

// Create a group conversation
async function createGroupConversation(groupName, participantIds) {
    const formData = new FormData();
    formData.append('type', 'group');
    formData.append('name', groupName);
    participantIds.forEach(id => {
        formData.append('participants[]', id);
    });
    
    const response = await fetch('/conversations', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    });
    
    // This redirects, so handle accordingly
    window.location.href = response.url;
}

// ============================================
// CALLS
// ============================================

// Initiate an audio call
async function initiateAudioCall(conversationId) {
    const response = await fetch(`/conversations/${conversationId}/calls`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ type: 'audio' })
    });
    
    return await response.json();
}

// Initiate a video call
async function initiateVideoCall(conversationId) {
    const response = await fetch(`/conversations/${conversationId}/calls`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ type: 'video' })
    });
    
    return await response.json();
}

// Answer a call
async function answerCall(callId) {
    const response = await fetch(`/calls/${callId}/answer`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    });
    
    return await response.json();
}

// End a call
async function endCall(callId) {
    const response = await fetch(`/calls/${callId}/end`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    });
    
    return await response.json();
}

// Toggle screen sharing
async function toggleScreenShare(callId, enabled) {
    const response = await fetch(`/calls/${callId}/screen-share`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ screen_sharing: enabled })
    });
    
    return await response.json();
}

// ============================================
// REAL-TIME UPDATES (with Laravel Echo)
// ============================================

// Install: npm install --save laravel-echo pusher-js

/*
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// Listen for new messages in a conversation
function listenForMessages(conversationId, callback) {
    window.Echo.join(`conversation.${conversationId}`)
        .listen('MessageSent', (e) => {
            callback(e);
            // e.id, e.body, e.type, e.user, e.created_at
        });
}

// Listen for incoming calls
function listenForCalls(conversationId, callback) {
    window.Echo.join(`conversation.${conversationId}`)
        .listen('CallInitiated', (e) => {
            callback(e);
            // e.id, e.type, e.status, e.initiator
        });
}

// Example usage:
listenForMessages(1, (message) => {
    console.log('New message:', message);
    // Update UI with new message
    appendMessageToUI(message);
});

listenForCalls(1, (call) => {
    console.log('Incoming call:', call);
    // Show call notification
    showIncomingCallNotification(call);
});
*/

// ============================================
// VOICE RECORDING (Browser API)
// ============================================

let mediaRecorder;
let audioChunks = [];

async function startRecording() {
    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
    mediaRecorder = new MediaRecorder(stream);
    
    mediaRecorder.ondataavailable = (event) => {
        audioChunks.push(event.data);
    };
    
    mediaRecorder.onstop = async () => {
        const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
        audioChunks = [];
        
        // Send the voice message
        const conversationId = 1; // Get from context
        await sendVoiceMessage(conversationId, audioBlob);
    };
    
    mediaRecorder.start();
}

function stopRecording() {
    if (mediaRecorder && mediaRecorder.state !== 'inactive') {
        mediaRecorder.stop();
    }
}

// ============================================
// FILE UPLOAD WITH PROGRESS
// ============================================

async function uploadFileWithProgress(conversationId, file, onProgress) {
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        const formData = new FormData();
        
        formData.append('file', file);
        formData.append('type', file.type.startsWith('image/') ? 'image' : 
                                file.type.startsWith('video/') ? 'video' : 
                                file.type.startsWith('audio/') ? 'voice' : 'text');
        
        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                onProgress(percentComplete);
            }
        });
        
        xhr.addEventListener('load', () => {
            if (xhr.status === 200) {
                resolve(JSON.parse(xhr.responseText));
            } else {
                reject(new Error('Upload failed'));
            }
        });
        
        xhr.addEventListener('error', () => reject(new Error('Upload failed')));
        
        xhr.open('POST', `/conversations/${conversationId}/messages`);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        xhr.send(formData);
    });
}

// Usage:
/*
const fileInput = document.getElementById('fileInput');
fileInput.addEventListener('change', async (e) => {
    const file = e.target.files[0];
    if (file) {
        await uploadFileWithProgress(1, file, (progress) => {
            console.log(`Upload progress: ${progress}%`);
            // Update progress bar
        });
    }
});
*/

// ============================================
// UTILITY FUNCTIONS
// ============================================

// Format timestamp
function formatMessageTime(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diff = now - date;
    
    if (diff < 60000) return 'Just now';
    if (diff < 3600000) return `${Math.floor(diff / 60000)}m ago`;
    if (diff < 86400000) return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
}

// Append message to UI
function appendMessageToUI(message) {
    const container = document.getElementById('messagesContainer');
    const isOwn = message.user.id === currentUserId; // Define currentUserId globally
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex ${isOwn ? 'justify-end' : 'justify-start'}`;
    
    messageDiv.innerHTML = `
        <div class="max-w-xs lg:max-w-md">
            ${!isOwn ? `<p class="text-xs text-gray-600 mb-1">${message.user.name}</p>` : ''}
            <div class="rounded-lg p-3 ${isOwn ? 'bg-blue-500 text-white' : 'bg-gray-200'}">
                ${message.type === 'text' ? `<p>${message.body}</p>` : ''}
                ${message.type === 'image' ? `<img src="/storage/${message.file_path}" class="max-w-full rounded">` : ''}
                ${message.type === 'voice' ? `<audio controls src="/storage/${message.file_path}"></audio>` : ''}
            </div>
            <p class="text-xs text-gray-500 mt-1">${formatMessageTime(message.created_at)}</p>
        </div>
    `;
    
    container.appendChild(messageDiv);
    container.scrollTop = container.scrollHeight;
}

// Show incoming call notification
function showIncomingCallNotification(call) {
    const notification = confirm(`Incoming ${call.type} call from ${call.initiator.name}. Answer?`);
    if (notification) {
        answerCall(call.id);
    }
}
