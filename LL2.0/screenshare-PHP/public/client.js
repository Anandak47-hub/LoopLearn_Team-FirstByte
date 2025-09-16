// public/client.js
'use strict';


const wsProtocol = (location.protocol === 'https:') ? 'wss://' : 'ws://';
const wsHost = window.location.hostname + ':8080'; // adapt if your ws server is on another host/port
const wsUrl = wsProtocol + wsHost;


let ws;
let pc; // RTCPeerConnection
let localStream = null;
let role = 'viewer';
let roomId = 'room1';
const statusEl = document.getElementById('status');
const remoteVideo = document.getElementById('remoteVideo');


const rtcConfig = {
iceServers: [
{ urls: 'stun:stun.l.google.com:19302' }
// Add TURN servers here for production
]
};
function logStatus(s) { console.log(s); statusEl.textContent = 'Status: ' + s; }


function connectWS() {
ws = new WebSocket(wsUrl);
ws.onopen = () => {
ws.send(JSON.stringify({ type: 'join', room: roomId }));
logStatus('WS connected');
};
ws.onmessage = async (ev) => {
const msg = JSON.parse(ev.data);
if (msg.type === 'signal') {
const payload = msg.payload;
if (payload.type === 'offer' && role === 'viewer') {
await ensurePeer();
await pc.setRemoteDescription(new RTCSessionDescription(payload));
const answer = await pc.createAnswer();
await pc.setLocalDescription(answer);
ws.send(JSON.stringify({ type: 'signal', room: roomId, payload: pc.localDescription }));
} else if (payload.type === 'answer' && role === 'broadcaster') {
await pc.setRemoteDescription(new RTCSessionDescription(payload));
} else if (payload.candidate) {
try {
await pc.addIceCandidate(new RTCIceCandidate(payload.candidate));
} catch (e) {
console.warn('Error adding candidate', e);
}
}
}
};
ws.onclose = () => logStatus('WS closed');
ws.onerror = (e) => logStatus('WS error');
}
async function ensurePeer() {
if (pc) return;
pc = new RTCPeerConnection(rtcConfig);
pc.onicecandidate = (e) => {
if (e.candidate) {
ws.send(JSON.stringify({ type: 'signal', room: roomId, payload: { candidate: e.candidate } }));
}
};
pc.ontrack = (e) => {
remoteVideo.srcObject = e.streams[0];
};
}


async function startSharing() {
if (role !== 'broadcaster') return;
// getDisplayMedia requires user gesture
try {
localStream = await navigator.mediaDevices.getDisplayMedia({ video: true, audio: false });
} catch (e) {
alert('Screen capture denied: ' + e.message);
return;
}
pc = new RTCPeerConnection(rtcConfig);
pc.onicecandidate = (e) => {
if (e.candidate) ws.send(JSON.stringify({ type: 'signal', room: roomId, payload: { candidate: e.candidate } }));
};


// Add all tracks (screen video) to peer
localStream.getTracks().forEach(t => pc.addTrack(t, localStream));


// Create offer
const offer = await pc.createOffer();
await pc.setLocalDescription(offer);


// Send the offer through signaling
ws.send(JSON.stringify({ type: 'signal', room: roomId, payload: offer }));


// Keep a local preview
remoteVideo.srcObject = localStream;
logStatus('Sharing started');
}
async function stopSharing() {
if (localStream) {
localStream.getTracks().forEach(t => t.stop());
localStream = null;
}
if (pc) {
pc.close(); pc = null;
}
remoteVideo.srcObject = null;
logStatus('Stopped');
}


// UI wiring
const connectBtn = document.getElementById('connectBtn');
const startBtn = document.getElementById('startBtn');
const stopBtn = document.getElementById('stopBtn');


connectBtn.onclick = () => {
roomId = document.getElementById('roomId').value || 'room1';
role = document.getElementById('roleSelect').value;
connectWS();
// enable start button only for broadcaster
startBtn.disabled = role !== 'broadcaster';
stopBtn.disabled = false;
};
startBtn.onclick = startSharing;
stopBtn.onclick = stopSharing;


window.addEventListener('beforeunload', () => {
try { ws.send(JSON.stringify({ type: 'leave', room: roomId })); } catch (e) {}
});