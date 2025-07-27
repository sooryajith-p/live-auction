const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
// Countdown Script
const countdown = document.getElementById("countdown");

const timer = setInterval(() => {
    const now = new Date().getTime();
    const diff = endTime - now;

    if (diff <= 0) {
        countdown.innerText = "Auction Ended";
        clearInterval(timer);

        const bidAmountInput = bidForm.querySelector('input[name="amount"]');
        const bidButton = bidForm.querySelector('button[type="submit"]');
        bidAmountInput.disabled = true;
        bidButton.disabled = true;
        bidButton.classList.add('opacity-50', 'cursor-not-allowed');
        bidButton.innerText = 'Auction Ended';

        const notification = document.getElementById(`bid-notification-${productId}`);
        notification.innerText = "⏳ Bidding has closed. Auction ended.";

        setTimeout(() => {
            alert("⏰ The auction has ended.");
            window.location.href = redirectUrl;
        }, 200);

        return;
    }

    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

    let output = '';
    if (days > 0) output += `${days}d `;
    if (days > 0 || hours > 0) output += `${hours}h `;
    output += `${minutes}m ${seconds}s`;

    countdown.innerText = output;
}, 1000);

Pusher.logToConsole = false;

const pusher = new Pusher(pusherKey, {
    cluster: pusherCluster,
    forceTLS: true,
    encrypted: true,
});

const channel = pusher.subscribe(`product.${productId}`);

// Message Event
channel.bind('MessageSent', function (data) {
    if (data.user.id === userId) return;
    const chatBox = document.getElementById(`chat-${productId}`);
    const html =
        `<div class="flex justify-start"><div class="inline-block px-3 py-2 rounded-lg bg-gray-200 text-gray-800"><strong class="text-indigo-700">${data.user.name}:</strong> ${data.message.message}</div></div>`;
    chatBox.insertAdjacentHTML('beforeend', html);
    chatBox.scrollTop = chatBox.scrollHeight;
});

// Bid Event
channel.bind('BidPlaced', function (data) {
    if (data.user.id === userId) return;
    const bidList = document.getElementById(`bids-${productId}`);
    bidList.insertAdjacentHTML('afterbegin',
        `<li class="text-gray-700">💸 <strong>${data.user.name}</strong> bid ₹${data.bid.amount} just now</li>`
    );
    const notification = document.getElementById(`bid-notification-${productId}`);
    notification.innerText = `${data.user.name} placed a new bid!`;

    setTimeout(() => {
        notification.innerText = '';
    }, 5000);
});

// AJAX Submission
const bidForm = document.getElementById('bid-form');
const chatForm = document.getElementById('chat-form');

bidForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(bidForm);
    const amount = formData.get('amount');
    const formattedAmount = parseFloat(amount).toFixed(2);

    fetch(bidForm.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
        body: formData
    })
        .then(res => {
            if (!res.ok) return res.json().then(err => Promise.reject(err));
            return res.json();
        })
        .then(() => {
            bidForm.reset();

            // Add the bid instantly
            const bidList = document.getElementById(`bids-${productId}`);
            const notification = document.getElementById(`bid-notification-${productId}`);

            bidList.insertAdjacentHTML('afterbegin',
                `<li class="text-gray-700">💸 <strong>Me</strong> bid ₹${formattedAmount} just now</li>`);
            notification.innerText = `You placed a new bid!`;
            setTimeout(() => {
                notification.innerText = '';
            }, 5000);
        })
        .catch(err => {
            alert(err.error || 'Failed to place bid.');
        });
});

chatForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const input = chatForm.querySelector('input[name="message"]');
    const message = input.value.trim();
    if (!message) return;

    fetch(chatForm.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            product_id: productId,
            message
        })
    })
        .then(res => {
            if (!res.ok) return res.json().then(err => Promise.reject(err));
            return res.json();
        })
        .then(() => {
            const chatBox = document.getElementById(`chat-${productId}`);
            const html =
                `<div class="flex justify-end"><div class="inline-block px-3 py-2 rounded-lg bg-indigo-100 text-right text-indigo-700"><strong class="text-indigo-600"> Me:</strong> ${message}</div></div>`;

            chatBox.insertAdjacentHTML('beforeend', html);
            chatBox.scrollTop = chatBox.scrollHeight;

            input.value = '';
        })
        .catch(err => {
            alert(err.error || 'Failed to send message.');
        });
});