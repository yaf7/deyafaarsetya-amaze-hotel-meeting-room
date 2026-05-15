<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp - {{ $reservation->customer_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #0b141a;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .wa-container {
            width: 100%;
            max-width: 480px;
            height: 100vh;
            max-height: 850px;
            background: #0b141a;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            position: relative;
        }

        /* Header */
        .wa-header {
            background: #1f2c34;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            flex-shrink: 0;
        }

        .wa-header .back-btn {
            color: #00a884;
            text-decoration: none;
            font-size: 20px;
            display: flex;
            align-items: center;
            padding: 4px;
        }

        .wa-header .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00a884, #075e54);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
        }

        .wa-header .contact-info {
            flex: 1;
            min-width: 0;
        }

        .wa-header .contact-name {
            color: #e9edef;
            font-size: 16px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .wa-header .contact-status {
            color: #8696a0;
            font-size: 12px;
        }

        .wa-header .header-actions {
            display: flex;
            gap: 16px;
            color: #aebac1;
            font-size: 18px;
        }

        /* Chat Background */
        .wa-chat {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            background-color: #0b141a;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23111b21' fill-opacity='0.6'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        /* Date Badge */
        .date-badge {
            text-align: center;
            margin-bottom: 16px;
        }

        .date-badge span {
            background: #1d2831;
            color: #8696a0;
            font-size: 11px;
            padding: 5px 14px;
            border-radius: 8px;
            display: inline-block;
            font-weight: 500;
            box-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }

        /* Message Bubbles */
        .message {
            max-width: 85%;
            margin-bottom: 6px;
            animation: fadeInUp 0.4s ease-out;
        }

        .message.sent {
            margin-left: auto;
        }

        .message.received {
            margin-right: auto;
        }

        .bubble {
            padding: 8px 12px;
            border-radius: 8px;
            position: relative;
            line-height: 1.45;
            font-size: 14px;
            word-wrap: break-word;
        }

        .sent .bubble {
            background: #005c4b;
            color: #e9edef;
            border-top-right-radius: 2px;
        }

        .received .bubble {
            background: #1f2c34;
            color: #e9edef;
            border-top-left-radius: 2px;
        }

        .bubble .time {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 4px;
            margin-top: 4px;
        }

        .bubble .time span {
            font-size: 11px;
            color: rgba(255,255,255,0.5);
        }

        .bubble .time .read-check {
            color: #53bdeb;
            font-size: 13px;
        }

        /* Reservation Card in bubble */
        .reservation-card {
            background: rgba(255,255,255,0.06);
            border-radius: 8px;
            padding: 12px;
            margin: 6px 0;
            border-left: 3px solid #00a884;
        }

        .reservation-card .card-title {
            font-size: 13px;
            font-weight: 700;
            color: #00a884;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .reservation-card .card-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 4px 0;
            font-size: 13px;
        }

        .reservation-card .card-row .label {
            color: rgba(255,255,255,0.55);
            flex-shrink: 0;
        }

        .reservation-card .card-row .value {
            color: #e9edef;
            font-weight: 500;
            text-align: right;
            margin-left: 12px;
        }

        .reservation-card .divider {
            border-top: 1px solid rgba(255,255,255,0.08);
            margin: 8px 0;
        }

        .reservation-card .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0 2px;
            font-size: 15px;
        }

        .reservation-card .total-row .label {
            color: #e9edef;
            font-weight: 600;
        }

        .reservation-card .total-row .value {
            color: #00a884;
            font-weight: 700;
        }

        /* Menu list in card */
        .menu-list {
            margin: 4px 0;
        }

        .menu-list .menu-item {
            font-size: 12px;
            color: #e9edef;
            padding: 2px 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .menu-list .menu-item .dot {
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: #00a884;
            flex-shrink: 0;
        }

        /* Input Bar */
        .wa-input {
            background: #1f2c34;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .wa-input .emoji-btn {
            color: #8696a0;
            font-size: 22px;
            flex-shrink: 0;
        }

        .wa-input .input-field {
            flex: 1;
            background: #2a3942;
            border: none;
            border-radius: 8px;
            padding: 10px 14px;
            color: #e9edef;
            font-size: 14px;
            outline: none;
            font-family: inherit;
            resize: none;
            overflow-y: auto;
            min-height: 48px;
            max-height: 120px;
        }

        .wa-input .input-field::placeholder {
            color: #8696a0;
        }

        .wa-input .send-btn {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #00a884;
            border: none;
            color: white;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            transition: background 0.2s;
        }

        .wa-input .send-btn:hover {
            background: #06cf9c;
        }

        /* Encryption notice */
        .encryption-notice {
            text-align: center;
            padding: 8px;
            flex-shrink: 0;
        }

        .encryption-notice span {
            font-size: 11px;
            color: rgba(255,255,255,0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message:nth-child(2) { animation-delay: 0.15s; }
        .message:nth-child(3) { animation-delay: 0.3s; }
        .message:nth-child(4) { animation-delay: 0.5s; }
        .message:nth-child(5) { animation-delay: 0.7s; }
        .message:nth-child(6) { animation-delay: 0.9s; }

        /* Scrollbar */
        .wa-chat::-webkit-scrollbar { width: 5px; }
        .wa-chat::-webkit-scrollbar-track { background: transparent; }
        .wa-chat::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }

        /* Responsive */
        @media (min-width: 481px) {
            body { padding: 20px; }
            .wa-container {
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 20px 60px rgba(0,0,0,0.5);
                border: 1px solid rgba(255,255,255,0.05);
            }
        }
    </style>
</head>
<body>
    <div class="wa-container">
        <!-- Header -->
        <div class="wa-header">
            <a href="{{ route('admin.reservation.show', $reservation->id) }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="avatar">
                {{ strtoupper(substr($reservation->customer_name, 0, 1)) }}
            </div>
            <div class="contact-info">
                <div class="contact-name">{{ $reservation->customer_name }}</div>
                <div class="contact-status">{{ $reservation->phone }}</div>
            </div>
            <div class="header-actions">
                <i class="fas fa-video"></i>
                <i class="fas fa-phone"></i>
                <i class="fas fa-ellipsis-vertical"></i>
            </div>
        </div>

        <!-- Encryption Notice -->
        <div class="encryption-notice">
            <span><i class="fas fa-lock" style="font-size:9px"></i> Pesan dan panggilan terenkripsi end-to-end</span>
        </div>

        <!-- Chat Area -->
        <div class="wa-chat" id="chat-area">
            <!-- Date Badge -->
            <div class="date-badge">
                <span>{{ now()->locale('id')->isoFormat('D MMMM Y') }}</span>
            </div>
            
            @foreach($reservation->chats as $chat)
                @php
                    $formattedMsg = nl2br(htmlspecialchars($chat->message));
                    $formattedMsg = preg_replace('/\*(.*?)\*/', '<b>$1</b>', $formattedMsg);
                @endphp
                <div class="message {{ $chat->sender == 'admin' ? 'sent' : 'received' }}" style="animation-delay: 0s">
                    <div class="bubble">
                        {!! $formattedMsg !!}
                        <div class="time">
                            <span>{{ $chat->created_at->format('H:i') }}</span>
                            @if($chat->sender == 'admin')
                                <i class="fas fa-check-double read-check" style="color: #53bdeb;"></i>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @php
            $template = "";
            if ($reservation->chats->isEmpty()) {
                $tanggal = \Carbon\Carbon::parse($reservation->date)->locale('id')->isoFormat('dddd, D MMM Y');
                $buffetMenuText = "";
                if ($reservation->buffetSelections->count() > 0) {
                    $buffetMenuText = "\n🍱 *Menu Buffet:*\n";
                    foreach ($reservation->buffetSelections as $sel) {
                        $buffetMenuText .= "• " . $sel->buffetMenu->name . " (" . ucfirst($sel->buffetMenu->category) . ")\n";
                    }
                }

                $discountPercent = $reservation->promotion?->discount ?? 0;
                $discountAmount = ($reservation->total_price * $discountPercent) / 100;
                $finalPrice = number_format($reservation->total_price - $discountAmount, 0, ',', '.');
                
                $statusPaymentMsg = $reservation->status == 'sukses' 
                    ? "✅ Pembayaran telah diterima. Reservasi Anda sudah dikonfirmasi. Silakan datang 15 menit sebelum waktu yang ditentukan.\n\n"
                    : "⌛ Menunggu Pembayaran. Silakan selesaikan pembayaran untuk mengkonfirmasi reservasi Anda.\n\n";

                $template = "Assalamu'alaikum {$reservation->customer_name} 👋\n\n"
                    . "Kami dari *Amaze Hotel Kediri* ingin mengkonfirmasi reservasi meeting room Anda.\n\n"
                    . "📋 *KONFIRMASI RESERVASI #" . str_pad($reservation->id, 6, '0', STR_PAD_LEFT) . "*\n"
                    . "━━━━━━━━━━━━━━━━━━\n"
                    . "📅 Tanggal: {$tanggal}\n"
                    . "🕐 Waktu: {$reservation->time} WIB\n"
                    . "🏨 Ruangan: {$reservation->meetingRoom->name}\n"
                    . "👥 Peserta: {$reservation->participants} orang\n"
                    . "🍽️ Paket: {$reservation->foodPackage->name}\n\n"
                    . "🏢 *Fasilitas Ruangan:*\n"
                    . "• LCD Projector + Screen\n"
                    . "• Sound System\n"
                    . "• Flipchart & Writing Materials\n"
                    . "• Air Mineral\n\n"
                    . "🍴 *Menu Include:*\n"
                    . "• Nasi Putih\n"
                    . "• 2 Kind of Slice Fruit\n"
                    . "• Assorted Dessert\n"
                    . "• Any Kind Juice\n"
                    . "• Mineral Dispenser\n"
                    . "• Coffee Break\n"
                    . $buffetMenuText . "\n"
                    . "💰 *Total Bayar: Rp{$finalPrice}*\n\n"
                    . $statusPaymentMsg
                    . "Terima kasih! 🙏";
            }
        @endphp

        <!-- Input Bar with Form -->
        <form id="wa-form" class="wa-input m-0" style="align-items: flex-end;">
            <span class="emoji-btn" style="margin-bottom: 12px;"><i class="far fa-smile"></i></span>
            <textarea id="message-input" class="input-field" placeholder="Ketik pesan..." required>{{ $template }}</textarea>
            <button type="submit" class="send-btn" style="margin-bottom: 2px;">
                <i class="fas fa-paper-plane" style="margin-left: 2px"></i>
            </button>
        </form>
    </div>

    <script>
        const textarea = document.getElementById('message-input');
        let customerHasReplied = {{ $reservation->chats->where('sender', 'customer')->count() > 0 ? 'true' : 'false' }};
        
        // Auto-expand textarea
        textarea.addEventListener('input', function() {
            this.style.height = '48px';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Trigger expand on load
        setTimeout(() => {
            textarea.style.height = '48px';
            textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
        }, 100);

        document.getElementById('wa-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const message = textarea.value;
            if(!message.trim()) return;
            
            const chatArea = document.getElementById('chat-area');
            const now = new Date();
            const timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
            
            // Format message (simple replace newlines to br, wrap bold)
            let formattedMessage = message.replace(/\n/g, '<br>');
            formattedMessage = formattedMessage.replace(/\*(.*?)\*/g, '<b>$1</b>');
            
            // Append the sent message
            const sentHtml = `
                <div class="message sent" style="animation-delay: 0s">
                    <div class="bubble">
                        ${formattedMessage}
                        <div class="time">
                            <span>${timeStr}</span>
                            <i class="fas fa-check-double read-check" style="color: #8696a0;" id="pending-ticks"></i>
                        </div>
                    </div>
                </div>
            `;
            chatArea.insertAdjacentHTML('beforeend', sentHtml);
            
            // Clear and reset textarea
            textarea.value = '';
            textarea.style.height = '48px';
            chatArea.scrollTop = chatArea.scrollHeight;

            // Submit to backend asynchronously
            fetch("{{ route('admin.reservation.whatsapp.send', $reservation->id) }}", {
                method: 'POST',
                body: JSON.stringify({ message: message }),
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Turn ticks to blue
                    const ticks = document.querySelectorAll('#pending-ticks');
                    ticks.forEach(t => {
                        t.style.color = '#53bdeb';
                        t.id = ''; // remove id
                    });
                    
                    // Simulate customer replying shortly after, only ONCE
                    if (!customerHasReplied && {{ $reservation->chats->isEmpty() ? 'true' : 'false' }}) {
                        customerHasReplied = true;
                        setTimeout(() => {
                            fetch("{{ route('admin.reservation.whatsapp.reply', $reservation->id) }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            }).then(() => {
                                const replyHtml = `
                                    <div class="message received" style="animation-delay: 0s">
                                        <div class="bubble">
                                            Waalaikumsalam, baik terima kasih konfirmasinya 🙏
                                            <div class="time">
                                                <span>${timeStr}</span>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                chatArea.insertAdjacentHTML('beforeend', replyHtml);
                                chatArea.scrollTop = chatArea.scrollHeight;
                            });
                        }, 1500);
                    }
                }
            });
        });
    </script>
</body>
</html>
