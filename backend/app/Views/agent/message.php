<div class="container-fluid mt-4">
<div class="container-fluid">
    <div class="card messenger-card">
        <!-- En-tête du chat -->
        <div class="chat-header">
            <div class="d-flex align-items-center">
                <button class="btn btn-link text-white me-2 back-btn d-md-none">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div class="avatar">
                    <i class="fas fa-user-circle fa-2x"></i>
                </div>
                <div class="ms-3 flex-grow-1">
                    <h5 class="mb-0">Ticket #<?= $ticket['id'] ?? '' ?></h5>
                    <small class="text-muted"><?= esc($ticket['titre'] ?? '') ?></small>
                </div>
            </div>
            <div class="header-actions">
                <button class="btn btn-link text-white d-none d-md-inline-flex">
                    <i class="fas fa-phone"></i>
                </button>
                <button class="btn btn-link text-white d-none d-md-inline-flex">
                    <i class="fas fa-video"></i>
                </button>
                <button class="btn btn-link text-white">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>
        </div>
        
        <!-- Zone des messages -->
        <div class="chat-body">
            <!-- Message principal -->
            <div class="message-group received">
                <div class="message-avatar">
                    <div class="avatar-circle">
                        <?= strtoupper(substr(esc($message['client_nom'] ?? ''), 0, 1)) ?>
                    </div>
                </div>
                <div class="message-content">
                    <div class="message-bubble">
                        <p class="mb-1"><?= nl2br(esc($message['message'])) ?></p>
                    </div>
                    <?php if (!empty($message['fichier_url'])): ?>
                        <div class="attachment-bubble">
                            <i class="fas fa-paperclip me-2"></i>
                            <a href="<?= base_url($message['fichier_url']) ?>" target="_blank">
                                Pièce jointe
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="message-info">
                        <span class="sender-name"><?= esc($message['client_nom'] ?? '') ?></span>
                        <span class="message-time">
                            <?= date('H:i', strtotime($message['date_message'])) ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Commentaires -->
            <?php if (isset($commentaires) && !empty($commentaires)): ?>
                <?php 
                $lastAuthor = null;
                foreach ($commentaires as $index => $commentaire): 
                    $isAgent = $commentaire['auteur'] === 'agent';
                    $currentAuthor = $isAgent ? $commentaire['nom_agent'] : $commentaire['nom_client'];
                    $showAvatar = $lastAuthor !== $currentAuthor;
                    $lastAuthor = $currentAuthor;
                ?>
                    <div class="message-group <?= $isAgent ? 'sent' : 'received' ?>">
                        <?php if (!$isAgent && $showAvatar): ?>
                            <div class="message-avatar">
                                <div class="avatar-circle">
                                    <?= strtoupper(substr($currentAuthor, 0, 1)) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="message-content <?= $isAgent ? 'agent-message' : '' ?>">
                            <div class="message-bubble <?= $isAgent ? 'agent-bubble' : '' ?>">
                                <p class="mb-0"><?= nl2br(esc($commentaire['commentaire'])) ?></p>
                            </div>
                            <div class="message-info <?= $isAgent ? 'agent-info' : '' ?>">
                                <?php if (!$isAgent && $showAvatar): ?>
                                    <span class="sender-name"><?= esc($currentAuthor) ?></span>
                                <?php endif; ?>
                                <span class="message-time">
                                    <?= date('H:i', strtotime($commentaire['date_commentaire'])) ?>
                                </span>
                                <?php if ($isAgent): ?>
                                    <i class="fas fa-check-circle text-primary ms-1"></i>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Zone de saisie -->
        <div class="chat-footer">
            <div class="typing-indicator" style="display: none;">
                <div class="typing-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <small class="text-muted ms-2">En train d'écrire...</small>
            </div>
            <form action="<?= base_url('agent/message/comment/' . ($message['id'] ?? '')) ?>" method="POST" class="message-form">
                <div class="input-container">
                    <button type="button" class="attachment-btn">
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="input-wrapper">
                        <textarea class="form-control message-input" name="commentaire" rows="1" placeholder="Message..." required></textarea>
                        <div class="input-actions">
                            <button type="button" class="emoji-btn">
                                <i class="far fa-smile"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="send-btn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.messenger-card {
    height: 85vh;
    display: flex;
    flex-direction: column;
    background: #ffffff;
    border: 1px solid #e4e6ea;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
}

.chat-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: none;
}

.chat-header .avatar {
    width: 40px;
    height: 40px;
    color: white;
}

.header-actions {
    display: flex;
    gap: 8px;
}

.header-actions .btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.2s;
}

.header-actions .btn:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.chat-body {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    background: #f8f9fa;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.message-group {
    display: flex;
    margin-bottom: 8px;
}

.message-group.received {
    justify-content: flex-start;
}

.message-group.sent {
    justify-content: flex-end;
}

.message-avatar {
    margin-right: 10px;
    display: flex;
    align-items: flex-end;
}

.avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 14px;
}

.message-content {
    max-width: 70%;
    display: flex;
    flex-direction: column;
}

.message-content.agent-message {
    align-items: flex-end;
}

.message-bubble {
    background: #e4e6ea;
    padding: 12px 16px;
    border-radius: 18px;
    margin-bottom: 4px;
    position: relative;
    word-wrap: break-word;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.message-bubble.agent-bubble {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.message-group.sent .message-bubble {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.attachment-bubble {
    background: #f0f2f5;
    padding: 10px 14px;
    border-radius: 14px;
    margin-bottom: 4px;
    border: 1px solid #e4e6ea;
}

.attachment-bubble a {
    color: #1877f2;
    text-decoration: none;
    font-weight: 500;
}

.attachment-bubble a:hover {
    text-decoration: underline;
}

.message-info {
    display: flex;
    align-items: center;
    font-size: 12px;
    color: #65676b;
    margin-top: 2px;
}

.message-info.agent-info {
    justify-content: flex-end;
}

.sender-name {
    font-weight: 600;
    margin-right: 8px;
    color: #1877f2;
}

.message-time {
    color: #65676b;
}

.chat-footer {
    background: white;
    padding: 16px 20px;
    border-top: 1px solid #e4e6ea;
}

.typing-indicator {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    padding: 0 4px;
}

.typing-dots {
    display: flex;
    gap: 3px;
}

.typing-dots span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #1877f2;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-dots span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-dots span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: scale(0.8);
        opacity: 0.5;
    }
    30% {
        transform: scale(1);
        opacity: 1;
    }
}

.input-container {
    display: flex;
    align-items: flex-end;
    gap: 8px;
}

.attachment-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: #e4e6ea;
    color: #65676b;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    flex-shrink: 0;
}

.attachment-btn:hover {
    background: #d0d2d7;
    transform: scale(1.05);
}

.input-wrapper {
    flex: 1;
    position: relative;
    background: #f0f2f5;
    border-radius: 20px;
    display: flex;
    align-items: flex-end;
}

.message-input {
    flex: 1;
    border: none;
    background: transparent;
    padding: 10px 16px;
    border-radius: 20px;
    resize: none;
    outline: none;
    max-height: 120px;
    font-size: 15px;
    line-height: 1.4;
}

.message-input:focus {
    box-shadow: none;
}

.input-actions {
    display: flex;
    align-items: center;
    padding: 0 8px 0 0;
    margin-bottom: 8px;
}

.emoji-btn {
    width: 28px;
    height: 28px;
    border: none;
    background: transparent;
    color: #65676b;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s;
}

.emoji-btn:hover {
    background: #e4e6ea;
    transform: scale(1.1);
}

.send-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    flex-shrink: 0;
}

.send-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.send-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Animations */
.message-group {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Scrollbar styling */
.chat-body::-webkit-scrollbar {
    width: 6px;
}

.chat-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.chat-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.chat-body::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Mobile First Design */
.messenger-card {
    height: 100vh;
    border-radius: 0;
    margin: 0;
    max-width: 100%;
}

.chat-header {
    padding: 12px 16px;
    position: sticky;
    top: 0;
    z-index: 100;
}

.chat-header h5 {
    font-size: 16px;
}

.chat-header small {
    font-size: 12px;
}

.header-actions .btn {
    width: 32px;
    height: 32px;
    font-size: 14px;
}

.chat-body {
    padding: 12px;
    padding-bottom: 20px;
    -webkit-overflow-scrolling: touch;
}

.message-content {
    max-width: 80%;
}

.message-bubble {
    padding: 10px 14px;
    font-size: 15px;
    line-height: 1.4;
    border-radius: 16px;
}

.avatar-circle {
    width: 28px;
    height: 28px;
    font-size: 12px;
}

.message-avatar {
    margin-right: 8px;
}

.message-info {
    font-size: 11px;
    margin-top: 3px;
}

.chat-footer {
    padding: 12px 16px;
    position: sticky;
    bottom: 0;
    background: white;
    border-top: 1px solid #e4e6ea;
    box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
}

.input-container {
    gap: 6px;
}

.attachment-btn {
    width: 32px;
    height: 32px;
    font-size: 14px;
}

.message-input {
    padding: 8px 12px;
    font-size: 16px; /* Prevents zoom on iOS */
    line-height: 1.4;
    max-height: 100px;
}

.input-actions {
    padding: 0 6px 0 0;
    margin-bottom: 6px;
}

.emoji-btn {
    width: 24px;
    height: 24px;
    font-size: 14px;
}

.send-btn {
    width: 32px;
    height: 32px;
    font-size: 14px;
}

/* Touch-friendly hover states */
.attachment-btn:active,
.emoji-btn:active,
.send-btn:active {
    transform: scale(0.95);
}

.header-actions .btn:active {
    background-color: rgba(255, 255, 255, 0.2);
}

/* Prevent text selection on touch */
.chat-header,
.message-info,
.header-actions {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

/* Keyboard adjustments for mobile */
.chat-footer.keyboard-open {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 1000;
}

/* Tablet adjustments */
@media (min-width: 481px) and (max-width: 768px) {
    .messenger-card {
        height: 90vh;
        border-radius: 8px;
        margin: 20px;
        max-width: calc(100% - 40px);
    }
    
    .message-content {
        max-width: 75%;
    }
    
    .chat-header {
        padding: 16px 20px;
    }
    
    .chat-body {
        padding: 16px;
    }
    
    .chat-footer {
        padding: 16px 20px;
    }
}

/* Desktop adjustments */
@media (min-width: 769px) {
    .messenger-card {
        height: 85vh;
        border-radius: 12px;
        margin: 20px auto;
        max-width: 800px;
    }
    
    .message-content {
        max-width: 70%;
    }
    
    .chat-header {
        padding: 16px 20px;
    }
    
    .chat-body {
        padding: 20px;
    }
    
    .chat-footer {
        padding: 16px 20px;
    }
    
    .message-input {
        font-size: 15px;
    }
    
    .header-actions .btn {
        width: 36px;
        height: 36px;
        font-size: 16px;
    }
    
    .attachment-btn,
    .send-btn {
        width: 36px;
        height: 36px;
        font-size: 16px;
    }
    
    .avatar-circle {
        width: 32px;
        height: 32px;
        font-size: 14px;
    }
    
    .message-avatar {
        margin-right: 10px;
    }
    
    .message-bubble {
        padding: 12px 16px;
        font-size: 15px;
        border-radius: 18px;
    }
    
    .message-info {
        font-size: 12px;
        margin-top: 2px;
    }
    
    /* Hover effects for desktop */
    .attachment-btn:hover,
    .emoji-btn:hover {
        background: #e4e6ea;
        transform: scale(1.05);
    }
    
    .send-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }
    
    .header-actions .btn:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
}
</style>

<script>
// Mobile-optimized JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const chatBody = document.querySelector('.chat-body');
    const textarea = document.querySelector('.message-input');
    const sendBtn = document.querySelector('.send-btn');
    const chatFooter = document.querySelector('.chat-footer');
    const typingIndicator = document.querySelector('.typing-indicator');
    let typingTimeout;
    
    // Auto-scroll to bottom on page load
    chatBody.scrollTop = chatBody.scrollHeight;
    
    // Detect mobile device
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    // Handle virtual keyboard on mobile
    if (isMobile) {
        // Adjust chat when virtual keyboard appears
        const initialViewportHeight = window.innerHeight;
        
        window.addEventListener('resize', function() {
            const currentViewportHeight = window.innerHeight;
            
            if (currentViewportHeight < initialViewportHeight * 0.75) {
                // Keyboard is likely open
                chatFooter.classList.add('keyboard-open');
                document.body.style.height = currentViewportHeight + 'px';
            } else {
                // Keyboard is likely closed
                chatFooter.classList.remove('keyboard-open');
                document.body.style.height = '100vh';
            }
            
            // Auto-scroll when keyboard opens/closes
            setTimeout(() => {
                chatBody.scrollTop = chatBody.scrollHeight;
            }, 100);
        });
        
        // Prevent zoom on input focus (iOS)
        textarea.addEventListener('touchstart', function() {
            this.style.fontSize = '16px';
        });
    }
    
    // Auto-expand textarea with mobile optimization
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        const maxHeight = isMobile ? 100 : 120;
        this.style.height = Math.min(this.scrollHeight, maxHeight) + 'px';
        
        // Auto-scroll to show new content
        setTimeout(() => {
            chatBody.scrollTop = chatBody.scrollHeight;
        }, 10);
    });
    
    // Send button state with mobile feedback
    textarea.addEventListener('input', function() {
        if (this.value.trim()) {
            sendBtn.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
            sendBtn.disabled = false;
            
            // Add haptic feedback on mobile
            if (isMobile && navigator.vibrate) {
                navigator.vibrate(10);
            }
        } else {
            sendBtn.style.background = '#bcc0c4';
            sendBtn.disabled = true;
        }
    });
    
    // Enhanced typing indicator
    textarea.addEventListener('input', function() {
        // Show typing indicator
        typingIndicator.style.display = 'flex';
        
        // Clear previous timeout
        clearTimeout(typingTimeout);
        
        // Hide typing indicator after 1 second of no typing
        typingTimeout = setTimeout(() => {
            typingIndicator.style.display = 'none';
        }, 1000);
    });
    
    // Mobile-optimized form submission
    document.querySelector('.message-form').addEventListener('submit', function(e) {
        if (!textarea.value.trim()) {
            e.preventDefault();
            return;
        }
        
        // Haptic feedback on send
        if (isMobile && navigator.vibrate) {
            navigator.vibrate(20);
        }
        
        // Add sending animation
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        sendBtn.disabled = true;
        
        // Reset after response (simulated)
        setTimeout(() => {
            sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
            sendBtn.disabled = false;
        }, 500);
    });
    
    // Touch-optimized attachment functionality
    document.querySelector('.attachment-btn').addEventListener('click', function() {
        // Add haptic feedback
        if (isMobile && navigator.vibrate) {
            navigator.vibrate(15);
        }
        
        // Create file input for mobile
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.accept = 'image/*,video/*,audio/*,.pdf,.doc,.docx';
        fileInput.multiple = false;
        
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                console.log('File selected:', file.name);
                // Add file upload logic here
            }
        });
        
        fileInput.click();
    });
    
    // Emoji button with mobile optimization
    document.querySelector('.emoji-btn').addEventListener('click', function() {
        // Add haptic feedback
        if (isMobile && navigator.vibrate) {
            navigator.vibrate(10);
        }
        
        // Simple emoji insertion for mobile
        const emojis = ['😊', '👍', '❤️', '😂', '🎉', '👏', '🔥', '💯'];
        const randomEmoji = emojis[Math.floor(Math.random() * emojis.length)];
        
        const cursorPos = textarea.selectionStart;
        const textBefore = textarea.value.substring(0, cursorPos);
        const textAfter = textarea.value.substring(cursorPos);
        
        textarea.value = textBefore + randomEmoji + textAfter;
        textarea.focus();
        textarea.setSelectionRange(cursorPos + randomEmoji.length, cursorPos + randomEmoji.length);
        
        // Trigger input event to update send button state
        textarea.dispatchEvent(new Event('input'));
    });
    
    // Swipe gestures for mobile
    if (isMobile) {
        let startY;
        let isScrolling = false;
        
        chatBody.addEventListener('touchstart', function(e) {
            startY = e.touches[0].pageY;
            isScrolling = false;
        });
        
        chatBody.addEventListener('touchmove', function(e) {
            if (!isScrolling) {
                const currentY = e.touches[0].pageY;
                const diffY = Math.abs(currentY - startY);
                
                if (diffY > 10) {
                    isScrolling = true;
                }
            }
        });
        
        // Pull-to-refresh gesture (optional)
        chatBody.addEventListener('touchend', function(e) {
            if (chatBody.scrollTop === 0 && !isScrolling) {
                // Could implement pull-to-refresh here
                console.log('Pull to refresh triggered');
            }
        });
    }
    
    // Auto-hide typing indicator when focus is lost
    textarea.addEventListener('blur', function() {
        setTimeout(() => {
            typingIndicator.style.display = 'none';
        }, 500);
    });
    
    // Keep footer visible on mobile keyboard
    textarea.addEventListener('focus', function() {
        if (isMobile) {
            setTimeout(() => {
                this.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
        }
    });
});
</script>