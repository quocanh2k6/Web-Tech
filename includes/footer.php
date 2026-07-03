<footer class="bg-brand-card border-t border-brand-border mt-16">
  <div class="max-w-screen-xl mx-auto px-6 py-16">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-16">
      <!-- Brand column -->
      <div class="md:col-span-1">
        <div class="brand-logo text-2xl mb-4">TECHNOVA</div>
        <p class="font-body text-brand-sub text-sm leading-relaxed mb-6">
          Cửa hàng điện tử cao cấp — Chính hãng 100% — Bảo hành uy tín toàn quốc.
        </p>
        <div class="flex gap-4">
          <!-- Social icons -->
          <a href="#" class="w-9 h-9 border border-brand-border flex items-center justify-center text-brand-sub hover:border-brand-gold hover:text-brand-gold transition-all">
            <i class="fab fa-facebook-f text-xs"></i>
          </a>
          <a href="#" class="w-9 h-9 border border-brand-border flex items-center justify-center text-brand-sub hover:border-brand-gold hover:text-brand-gold transition-all">
            <i class="fab fa-instagram text-xs"></i>
          </a>
          <a href="#" class="w-9 h-9 border border-brand-border flex items-center justify-center text-brand-sub hover:border-brand-gold hover:text-brand-gold transition-all">
            <i class="fab fa-tiktok text-xs"></i>
          </a>
        </div>
      </div>

      <!-- Links -->
      <div>
        <h4 class="font-display font-700 text-brand-white text-sm mb-6 uppercase tracking-widest">Sản Phẩm</h4>
        <ul class="space-y-3">
          <li><a href="shop.php?category=1" class="font-body text-brand-sub text-sm hover:text-brand-gold transition-colors">Điện Thoại & Tablet</a></li>
          <li><a href="shop.php?category=2" class="font-body text-brand-sub text-sm hover:text-brand-gold transition-colors">Laptop & Máy Tính</a></li>
          <li><a href="shop.php?category=3" class="font-body text-brand-sub text-sm hover:text-brand-gold transition-colors">Âm Thanh</a></li>
          <li><a href="shop.php?category=4" class="font-body text-brand-sub text-sm hover:text-brand-gold transition-colors">Đồng Hồ Thông Minh</a></li>
          <li><a href="shop.php?category=5" class="font-body text-brand-sub text-sm hover:text-brand-gold transition-colors">Phụ Kiện</a></li>
        </ul>
      </div>

      <div>
        <h4 class="font-display font-700 text-brand-white text-sm mb-6 uppercase tracking-widest">Công Ty</h4>
        <ul class="space-y-3">
          <li><a href="about.php" class="font-body text-brand-sub text-sm hover:text-brand-gold transition-colors">Về Chúng Tôi</a></li>
          <li><a href="careers.php" class="font-body text-brand-sub text-sm hover:text-brand-gold transition-colors">Tuyển Dụng</a></li>
          <li><a href="support.php" class="font-body text-brand-sub text-sm hover:text-brand-gold transition-colors">Liên Hệ</a></li>
        </ul>
      </div>

      <div>
        <h4 class="font-display font-700 text-brand-white text-sm mb-6 uppercase tracking-widest">Liên Hệ</h4>
        <ul class="space-y-3 font-body text-brand-sub text-sm">
          <li class="flex gap-2"><i class="fas fa-map-marker-alt text-brand-gold mt-0.5 text-xs"></i>123 Nguyễn Huệ, Q.1, TP.HCM</li>
          <li class="flex gap-2"><i class="fas fa-phone text-brand-gold mt-0.5 text-xs"></i>1900 9999</li>
          <li class="flex gap-2"><i class="fas fa-envelope text-brand-gold mt-0.5 text-xs"></i>support@technova.vn</li>
        </ul>
      </div>
    </div>

    <!-- Bottom bar -->
    <div class="border-t border-brand-border pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
      <p class="font-body text-brand-sub text-xs">© 2026 TechNova Store. All rights reserved.</p>
      <p class="font-body text-brand-sub text-xs">Thiết kế bởi Team TechNova</p>
    </div>
  </div>
</footer>

<!-- Chatbot Bubble -->
<div id="chatbot-container" class="fixed bottom-6 right-6 z-[9999] flex flex-col items-end">
    <!-- Chat Window -->
    <div id="chat-window" class="hidden w-80 sm:w-96 bg-brand-surface border border-brand-border rounded-2xl shadow-2xl overflow-hidden flex flex-col transition-all duration-300 transform scale-95 opacity-0 origin-bottom-right mb-4">
        <!-- Header -->
        <div class="bg-brand-card p-4 border-b border-brand-border flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-brand-gold flex items-center justify-center text-[#000] font-bold">
                    N
                </div>
                <div>
                    <h3 class="font-display font-bold text-brand-white text-sm">TechNova Assistant</h3>
                    <p class="text-xs text-brand-sub flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span> Đang hoạt động</p>
                </div>
            </div>
            <button id="close-chat" class="text-brand-sub hover:text-brand-white transition-colors focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Messages -->
        <div id="chat-messages" class="p-4 h-80 overflow-y-auto font-body text-sm flex flex-col gap-3 bg-brand-surface">
            <!-- Bot message -->
            <div class="flex justify-start">
                <div class="bg-brand-card text-brand-white p-3 rounded-2xl rounded-tl-none max-w-[85%] shadow-sm border border-brand-border leading-relaxed">
                    Xin chào! Tôi là Nova, trợ lý ảo của TechNova Store. Tôi có thể giúp gì cho bạn hôm nay?
                </div>
            </div>
        </div>
        
        <!-- Input -->
        <div class="p-3 border-t border-brand-border bg-brand-card flex items-center gap-2">
            <input type="text" id="chat-input" class="flex-1 bg-brand-surface border border-brand-border rounded-full px-4 py-2.5 text-sm text-brand-white focus:outline-none focus:border-brand-gold transition-colors placeholder-brand-sub" placeholder="Nhập tin nhắn..." autocomplete="off">
            <button id="send-chat" class="w-10 h-10 shrink-0 rounded-full bg-brand-gold text-[#000] flex items-center justify-center hover:bg-white transition-colors disabled:opacity-50 focus:outline-none">
                <i class="fas fa-paper-plane text-xs"></i>
            </button>
        </div>
    </div>

    <!-- Bubble Button -->
    <button id="chatbot-trigger" class="w-16 h-16 bg-brand-gold rounded-full text-[#000] flex items-center justify-center shadow-[0_0_20px_rgba(212,175,55,0.3)] hover:scale-110 transition-transform duration-300 ml-auto cursor-pointer focus:outline-none relative">
        <i class="fas fa-comment-dots text-2xl"></i>
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const trigger = document.getElementById('chatbot-trigger');
    const chatWindow = document.getElementById('chat-window');
    const closeBtn = document.getElementById('close-chat');
    const inputField = document.getElementById('chat-input');
    const sendBtn = document.getElementById('send-chat');
    const messagesContainer = document.getElementById('chat-messages');

    let isOpen = false;

    function toggleChat() {
        isOpen = !isOpen;
        if (isOpen) {
            chatWindow.classList.remove('hidden');
            // Trigger reflow
            void chatWindow.offsetWidth;
            chatWindow.classList.remove('scale-95', 'opacity-0');
            chatWindow.classList.add('scale-100', 'opacity-100');
            inputField.focus();
        } else {
            chatWindow.classList.remove('scale-100', 'opacity-100');
            chatWindow.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                chatWindow.classList.add('hidden');
            }, 300);
        }
    }

    trigger.addEventListener('click', toggleChat);
    closeBtn.addEventListener('click', toggleChat);

    function addMessage(text, isUser = false) {
        const msgDiv = document.createElement('div');
        msgDiv.className = `flex ${isUser ? 'justify-end' : 'justify-start'}`;
        
        let contentClass = isUser 
            ? 'bg-brand-gold text-[#000] p-3 rounded-2xl rounded-tr-none max-w-[85%] shadow-sm leading-relaxed font-medium'
            : 'bg-brand-card text-brand-white p-3 rounded-2xl rounded-tl-none max-w-[85%] shadow-sm border border-brand-border leading-relaxed';
            
        msgDiv.innerHTML = `<div class="${contentClass}">${text}</div>`;
        messagesContainer.appendChild(msgDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    async function sendMessage() {
        const text = inputField.value.trim();
        if (!text) return;

        addMessage(text, true);
        inputField.value = '';
        inputField.disabled = true;
        sendBtn.disabled = true;
        
        // Show loading indicator
        const loadingDiv = document.createElement('div');
        loadingDiv.id = 'chat-loading';
        loadingDiv.className = 'flex justify-start';
        loadingDiv.innerHTML = `<div class="bg-brand-card text-brand-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-brand-border flex gap-1.5 items-center h-10 px-4">
            <span class="w-1.5 h-1.5 bg-brand-sub rounded-full animate-bounce"></span>
            <span class="w-1.5 h-1.5 bg-brand-sub rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
            <span class="w-1.5 h-1.5 bg-brand-sub rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
        </div>`;
        messagesContainer.appendChild(loadingDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;

        try {
            // Determine absolute path to chatbot_api.php based on current location
            const currentPath = window.location.pathname;
            const apiPath = currentPath.includes('/admin/') ? '../chatbot_api.php' : 'chatbot_api.php';
            
            const response = await fetch(apiPath, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ message: text })
            });
            
            const data = await response.json();
            
            document.getElementById('chat-loading')?.remove();
            
            if (data.reply) {
                // Convert basic markdown
                let formattedReply = data.reply
                    .replace(/\n/g, '<br>')
                    .replace(/\*\*(.*?)\*\*/g, '<b>$1</b>')
                    .replace(/\*(.*?)\*/g, '<i>$1</i>');
                addMessage(formattedReply);
            } else if (data.error) {
                addMessage('Lỗi: ' + data.error);
            } else {
                addMessage('Xin lỗi, tôi không thể xử lý yêu cầu lúc này.');
            }
            
        } catch (error) {
            document.getElementById('chat-loading')?.remove();
            addMessage('Lỗi kết nối. Vui lòng thử lại sau.');
        } finally {
            inputField.disabled = false;
            sendBtn.disabled = false;
            inputField.focus();
        }
    }

    sendBtn.addEventListener('click', sendMessage);
    inputField.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
});
</script>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="script.js"></script>

</body>
</html>
