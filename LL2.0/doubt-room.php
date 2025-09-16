<?php 
session_start();
if(!isset($_SESSION['email'])){
    die("⚠️ No email in session");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Doubt Room - LoopLearn</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    html { scroll-behavior: smooth; }
  </style>
</head>
<body class="bg-gray-100 h-screen flex flex-col font-sans">

  <!-- 🔷 Header -->
  <header class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-4 flex justify-between items-center shadow">
    <h1 class="text-xl font-bold">🎓 LoopLearn | Doubt Room</h1>
    <div class="flex items-center gap-4">
        <!-- ✅ Dashboard Button -->
        <a href="dashboard.php" class="bg-white text-blue-600 px-4 py-1 rounded shadow hover:bg-gray-100 transition text-sm">Dashboard</a>

        <!-- ✅ 3 Dot Menu -->
        <div class="relative">
          <button onclick="toggleMenu()" class="text-white text-2xl menu-icon">⋮</button>
          <div id="menuDropdown" class="absolute right-0 mt-2 w-40 bg-white rounded shadow-lg text-sm text-gray-700 hidden z-50">
            <a href="logout.php" class="block px-4 py-2 hover:bg-gray-100">Logout</a>
            <a href="settings.php" class="block px-4 py-2 hover:bg-gray-100">Settings</a>
            <button onclick="toggleDarkMode()" class="w-full text-left px-4 py-2 hover:bg-gray-100">Dark Mode</button>
          </div>
        </div>
    </div>
  </header>

  <!-- 💬 Chat Section -->
  <main id="chat-box" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
    
    <!-- Student Message -->
    <div class="flex items-start gap-3">
      <img src="https://api.dicebear.com/7.x/thumbs/svg?seed=Student" class="w-10 h-10 rounded-full" alt="Student Avatar" />
      <div>
        <div class="bg-white p-3 rounded-xl shadow max-w-xl">
          <p class="text-sm text-blue-700 font-semibold">Student</p>
          <p>How do I loop through an array in JavaScript?</p>
        </div>
        <span class="text-xs text-gray-500 ml-1">10:45 AM</span>
      </div>
    </div>

    <!-- Helper Message -->
    <div class="flex items-start justify-end gap-3">
      <div>
        <div class="bg-green-100 p-3 rounded-xl shadow max-w-xl text-right">
          <p class="text-sm text-green-700 font-semibold">Helper</p>
          <p>You can use a <code>for</code> loop or <code>.map()</code> function. Want an example?</p>
        </div>
        <span class="text-xs text-gray-500 text-right block">10:46 AM</span>
      </div>
      <img src="https://api.dicebear.com/7.x/thumbs/svg?seed=Helper" class="w-10 h-10 rounded-full" alt="Helper Avatar" />
    </div>

    <!-- Typing Indicator -->
    <div id="typing" class="flex gap-2 items-center text-sm text-gray-400" style="display: none;">
      <span class="animate-pulse">Helper is typing...</span>
    </div>

  </main>

  <!-- 🛠️ Actions & Tools -->
  <div class="p-2 flex justify-between items-center text-sm text-gray-600 border-t bg-white shadow-sm">
    <div class="flex gap-4">
      <button class="hover:text-blue-600" onclick="handleAttach()">📎 Attach</button>
      <button class="hover:text-blue-600" onclick="handleScreenShare()">🖥️ Share Screen</button>
      <button class="hover:text-blue-600" onclick="handleVoice()">🎙️ Voice</button>
    </div>
    <button class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-xs" onclick="resolveDoubt()">Resolve Doubt</button>
  </div>

  <!-- 🔻 Input Footer -->
  <footer class="p-4 border-t bg-white flex items-center gap-2">
    <input type="text" id="messageInput" placeholder="Type your reply..." class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" />
    <button id="sendButton" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Send</button>
  </footer>

  <script>
    const chatBox = document.getElementById("chat-box");
    const messageInput = document.getElementById("messageInput");
    const sendButton = document.getElementById("sendButton");
    
    // Auto scroll to bottom
    chatBox.scrollTop = chatBox.scrollHeight;
    
    // Toggle dropdown menu
    function toggleMenu() {
      const dropdown = document.getElementById("menuDropdown");
      dropdown.classList.toggle("hidden");
    }

    // Close dropdown when clicking outside
    document.addEventListener("click", function(event) {
      const dropdown = document.getElementById("menuDropdown");
      const menuButton = document.querySelector(".menu-icon");
      if (!dropdown.contains(event.target) && !menuButton.contains(event.target)) {
        dropdown.classList.add("hidden");
      }
    });

    // Dark mode toggle
    function toggleDarkMode() {
      document.body.classList.toggle("dark");
      // Save preference to localStorage if needed
      const isDark = document.body.classList.contains("dark");
      localStorage.setItem("darkMode", isDark);
    }

    // Load dark mode preference
    if (localStorage.getItem("darkMode") === "true") {
      document.body.classList.add("dark");
    }

    // Voting System
    let votes = 0;
    function vote(val) {
      votes += val;
      const voteElement = document.getElementById("voteCount");
      if (voteElement) {
        voteElement.innerText = votes;
      }
    }

    // Action button handlers
    function handleAttach() {
      console.log("Attach file functionality");
      // Implement file attachment logic
    }

    function handleScreenShare() {
      console.log("Screen share functionality");
      // Implement screen sharing logic
    }

    function handleVoice() {
      console.log("Voice functionality");
      // Implement voice chat logic
    }

    function resolveDoubt() {
      if (confirm("Are you sure you want to resolve this doubt?")) {
        console.log("Doubt resolved");
        // Implement doubt resolution logic
      }
    }

    // Send message functionality
    function sendMessage() {
      const msg = messageInput.value.trim();
      if (msg === "") return;

      const div = document.createElement("div");
      div.className = "flex items-start justify-end gap-3";
      div.innerHTML = `
        <div>
          <div class="bg-green-100 p-3 rounded-xl shadow max-w-xl text-right">
            <p class="text-sm text-green-700 font-semibold">You</p>
            <p>${escapeHtml(msg)}</p>
            <div class="flex items-center justify-end gap-2 mt-2 text-sm">
              <button onclick="vote(1)" class="px-2 py-1 bg-blue-200 rounded hover:bg-blue-300">⬆️</button>
              <span id="voteCount">0</span>
              <button onclick="vote(-1)" class="px-2 py-1 bg-red-200 rounded hover:bg-red-300">⬇️</button>
            </div>
          </div>
          <span class="text-xs text-gray-500 text-right block">Now</span>
        </div>
        <img src="https://api.dicebear.com/7.x/thumbs/svg?seed=You" class="w-10 h-10 rounded-full" alt="Your Avatar" />
      `;
      chatBox.appendChild(div);
      chatBox.scrollTop = chatBox.scrollHeight;
      messageInput.value = "";
    }

    // HTML escape function for security
    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    // Event listeners
    sendButton.addEventListener("click", sendMessage);
    messageInput.addEventListener("keypress", function(e) {
      if (e.key === "Enter") {
        sendMessage();
      }
    });

    // Typing status functionality
    const targetEmail = "<?php echo $_SESSION['email']; ?>";
    let typingTimer;

    function checkTypingStatus() {
      fetch("typing_status.php?email=" + encodeURIComponent(targetEmail))
        .then(response => response.json())
        .then(data => {
          const typingBox = document.getElementById("typing");
          typingBox.style.display = data.typing ? "flex" : "none";
        })
        .catch(err => console.error("Typing status error:", err));
    }

    function updateTyping(isTyping) {
      fetch('typing_status.php', {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "typing=" + encodeURIComponent(isTyping)
      }).catch(err => console.error("Update typing error:", err));
    }

    // Track typing activity
    messageInput.addEventListener("input", function() {
      updateTyping(true);
      clearTimeout(typingTimer);
      typingTimer = setTimeout(() => {
        updateTyping(false);
      }, 2000);
    });

    // Check typing status every 3 seconds
    setInterval(checkTypingStatus, 3000);

    // Real-time chat functionality
    function loadMessages() {
      fetch("fetch_message.php")
        .then(res => res.json())
        .then(data => {
          // Clear existing messages except static ones
          const staticMessages = chatBox.querySelectorAll('.flex:not([data-dynamic])');
          chatBox.innerHTML = "";
          staticMessages.forEach(msg => chatBox.appendChild(msg));
          
          // Add new messages
          data.forEach(msg => {
            const div = document.createElement("div");
            div.className = "p-2 bg-white rounded shadow mb-2";
            div.setAttribute('data-dynamic', 'true');
            div.innerHTML = `<b>${escapeHtml(msg.sender)}</b>: ${escapeHtml(msg.message)}<br>
                             <span class="text-xs text-gray-400">${escapeHtml(msg.time_at)}</span>`;
            chatBox.appendChild(div);
          });
          chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(err => console.error("Fetch error:", err));
    }

    // Auto refresh messages every 3 seconds
    setInterval(loadMessages, 3000);
    loadMessages();
  </script>
</body>
</html>