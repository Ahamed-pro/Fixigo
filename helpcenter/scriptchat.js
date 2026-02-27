
const RULES = [

 
  {
    patterns: ['hello','hi','hey','good morning','good afternoon','good evening','howdy','wassup','sup','hiya'],
    response: "Hey there! 👋 I'm the Fixigo AI Assistant. I can help you with registering, booking services, emergency requests, payments, and more. What do you need help with?",
    followups: ['Register account', 'Book a service', 'Emergency SOS', 'Payment help']
  },
  
    {
    patterns: ['what', 'can', 'you', 'do'],
    response: "Hey there!  I can help you with registering, booking services, emergency requests, payments, and more. What do you need help with?",
    followups: ['Register account', 'Book a service', 'Emergency SOS', 'Payment help']
  },


  {
    patterns: ['register','sign up','signup','create account','new account','join','how to register'],
    response: "Creating a Fixigo account is easy! 🎉\n\n<b>Step 1</b> — Go to <b>auth.php</b> or click <b>Sign Up</b> on the homepage.\n<b>Step 2</b> — Choose your account type:\n  • 🚗 <b>Vehicle Owner</b> — find workshops & request services\n  • 🏪 <b>Workshop Owner</b> — list your workshop & get clients\n<b>Step 3</b> — Fill in your name, email, phone & password.\n<b>Step 4</b> — Click <b>Create Account</b> and you're in! ✅",
    followups: ['How to login?', 'Workshop registration', 'Forgot password?']
  },


  {
    patterns: ['login','log in','sign in','signin','cant login','cannot login','access account','enter account'],
    response: "To log in to Fixigo: 🔐\n\n<b>Step 1</b> — Go to <b>auth.php</b> and click <b>Sign In</b>.\n<b>Step 2</b> — Enter your registered <b>email</b> and <b>password</b>.\n<b>Step 3</b> — Click <b>Login</b>.\n\nYou'll be redirected to your dashboard automatically based on your account type.",
    followups: ['Forgot password?', 'Register account', 'Dashboard help']
  },


  {
    patterns: ['forgot password','reset password','forgot my password','lost password','change password','cant remember'],
    response: "Forgot your password? No worries! 🔑\n\nCurrently you can reset your password by contacting our support team. We're working on an automated reset feature coming soon.\n\n📧 Contact: <b>support@fixigo.lk</b>",
    followups: ['Back to login', 'Contact support']
  },


  {
    patterns: ['book','booking','service request','request service','request a service','how to book','find workshop','need repair','car repair','vehicle repair','mechanic'],
    response: "Booking a service on Fixigo is simple! 🚗🔧\n\n<b>Step 1</b> — Log in to your <b>Vehicle Owner</b> dashboard.\n<b>Step 2</b> — Browse workshops or use the <b>Find a Workshop</b> feature.\n<b>Step 3</b> — Select a workshop and click <b>Request Service</b>.\n<b>Step 4</b> — Fill in your vehicle details and issue description.\n<b>Step 5</b> — Submit — the workshop will respond shortly! ✅",
    followups: ['Track my request', 'Emergency SOS', 'How to register?']
  },


  {
    patterns: ['emergency','sos','urgent','breakdown','stuck','accident','stranded','help me','roadside','broken down'],
    response: "🚨 <b>Emergency SOS</b> — Fast help when you need it!\n\n<b>Step 1</b> — Log in and go to your <b>Dashboard</b>.\n<b>Step 2</b> — Click the red <b>🚨 Emergency SOS</b> button.\n<b>Step 3</b> — Fill in your location, landmark, and emergency type.\n<b>Step 4</b> — Submit — nearby workshops and our team are alerted via SMS instantly! 📲\n\n⚠️ For life-threatening emergencies call <b>119</b> immediately.",
    followups: ['Book a service', 'Track my request', 'Contact support']
  },


  {
    patterns: ['payment','pay','paid','billing','invoice','fee','cost','price','how much','card','credit card','debit card','payment pending','payment status'],
    response: "💳 <b>Payment Information</b>\n\n<b>For Workshop Owners:</b>\nAfter registering your workshop, a listing fee is charged to activate your profile. Pay via the <b>Payment</b> section in your dashboard using your card details.\n\n<b>Payment Status:</b>\n• ⏳ <b>Pending</b> — Your listing is not yet visible to customers\n• ✅ <b>Paid & Active</b> — Your workshop is live!\n\nOnce an admin approves your payment, your workshop goes live automatically.",
    followups: ['Payment status', 'Workshop registration', 'Contact support']
  },


  {
    patterns: ['workshop','register workshop','list workshop','add workshop','workshop owner','add my workshop','my workshop'],
    response: "🏪 <b>Registering Your Workshop on Fixigo</b>\n\n<b>Step 1</b> — Sign up and choose <b>Workshop Owner</b> as your account type.\n<b>Step 2</b> — Fill in your workshop name, district, specialisation, address and business registration.\n<b>Step 3</b> — Complete the <b>listing payment</b> to activate your profile.\n<b>Step 4</b> — An admin reviews and approves your workshop.\n<b>Step 5</b> — Your workshop goes live and customers can find you! 🎉",
    followups: ['Payment help', 'Edit my workshop', 'How to login?']
  },


  {
    patterns: ['dashboard','my dashboard','where is','how to find','navigate','menu','profile','account settings'],
    response: "📊 <b>Your Dashboard</b>\n\nAfter logging in, you'll be directed to your dashboard automatically:\n\n• 🚗 <b>Vehicle Owners</b> → View service history, send requests, and trigger SOS.\n• 🏪 <b>Workshop Owners</b> → Manage service requests, edit your profile, and check payment status.\n• 🔐 <b>Admins</b> → Manage all users, workshops, payments and SOS alerts.",
    followups: ['Edit my workshop', 'Service requests', 'Payment help']
  },


  {
    patterns: ['track','status','my request','request status','accepted','ignored','pending request','response','did they accept'],
    response: "📋 <b>Tracking Your Service Request</b>\n\nYou can track your request from your <b>Vehicle Owner Dashboard</b>:\n\n• ⏳ <b>Pending</b> — The workshop hasn't responded yet.\n• ✅ <b>Accepted</b> — The workshop accepted your request!\n• ✗ <b>Ignored</b> — The workshop didn't respond. Try another workshop.\n\nTip: You'll receive a notification once the workshop responds.",
    followups: ['Book a service', 'Emergency SOS', 'Contact support']
  },


  {
    patterns: ['contact','support','help','customer service','talk to someone','human','agent','reach you','phone number','email'],
    response: "📞 <b>Contact Fixigo Support</b>\n\nWe're here to help!\n\n📧 <b>Email:</b> support@fixigo.lk\n📱 <b>Phone:</b> +94 77 123 4567\n🕐 <b>Hours:</b> Mon–Sat, 8am–8pm\n\nOr use the <b>Emergency SOS</b> feature on your dashboard for urgent roadside help.",
    followups: ['Emergency SOS', 'Book a service', 'Back to start']
  },

  {
    patterns: ['what is fixigo','about','who are you','what do you do','about fixigo','tell me about','what is this'],
    response: "🔧 <b>About Fixigo</b>\n\nFixigo is Sri Lanka's smart vehicle repair platform connecting <b>vehicle owners</b> with trusted <b>workshops</b>.\n\n✅ Find verified local workshops\n🚨 Emergency roadside SOS\n📋 Real-time service request tracking\n💳 Secure online payments\n\nOur mission: <i>Fast, transparent, trustworthy vehicle repair — anytime, anywhere.</i>",
    followups: ['Register account', 'Book a service', 'Emergency SOS']
  },


  {
    patterns: ['edit profile','update profile','change details','update workshop','change workshop','edit my info','update my info','change name','change address'],
    response: "✏️ <b>Editing Your Profile</b>\n\n<b>For Workshop Owners:</b>\n<b>Step 1</b> — Log in and go to your <b>Workshop Dashboard</b>.\n<b>Step 2</b> — Click <b>Quick Actions → Edit Profile</b> or the ⚙️ sidebar link.\n<b>Step 3</b> — Update your workshop name, address, district, specialisation or business reg.\n<b>Step 4</b> — Click <b>Save Changes</b>. Updates are live instantly! ✅",
    followups: ['Payment status', 'Dashboard help', 'Contact support']
  },


  {
    patterns: ['thank','thanks','thank you','cheers','great','awesome','perfect','helpful','appreciate','ok got it'],
    response: "You're welcome! 😊 Happy to help. Is there anything else you need assistance with?",
    followups: ['Book a service', 'Emergency SOS', 'Contact support']
  },

 
  {
    patterns: ['bye','goodbye','see you','later','cya','exit','close','done','no thanks','that is all','thats all'],
    response: "Take care! 👋 Drive safe and remember — Fixigo is always here when you need us. 🔧",
    followups: []
  },
];


const FALLBACKS = [
  "Hmm, I'm not sure about that. Try asking about <b>registration</b>, <b>booking a service</b>, <b>emergency SOS</b>, or <b>payments</b>. 🤔",
  "I didn't quite catch that! I can help with <b>account setup</b>, <b>finding workshops</b>, <b>SOS alerts</b>, and <b>payment questions</b>.",
  "That's outside what I know! Try one of the quick questions below, or <b>contact our support team</b> at support@fixigo.lk. 😊",
];


let isOpen = false;
let messageCount = 0;


function toggleChat() {
  isOpen = !isOpen;
  const container = document.getElementById('chat-container');
  const btnIcon   = document.getElementById('help-btn-icon');
  const btnText   = document.getElementById('help-btn-text');

  if (isOpen) {
    container.classList.add('open');
    btnIcon.textContent = '✕';
    btnText.textContent = 'Close';
    if (messageCount === 0) {
      setTimeout(() => addBotMessage(
        "Hi! 👋 I'm <b>Fixigo AI</b> — your smart help assistant. I can answer questions about registration, bookings, emergency SOS, payments, and more!",
        ['Register account', 'Book a service', 'Emergency SOS', 'Payment help']
      ), 400);
    }
    setTimeout(() => document.getElementById('chat-input').focus(), 300);
  } else {
    container.classList.remove('open');
    btnIcon.textContent = '🔧';
    btnText.textContent = 'Need Help?';
  }
}


function sendMessage() {
  const input = document.getElementById('chat-input');
  const text  = input.value.trim();
  if (!text) return;

  addUserMessage(text);
  input.value = '';


  showTyping();
  setTimeout(() => {
    removeTyping();
    const reply = getResponse(text);
    addBotMessage(reply.response, reply.followups);
  }, 600 + Math.random() * 400);
}


function sendSuggestion(btn) {
  const text = btn.textContent;
  addUserMessage(text);
  btn.closest('#suggestions-row') && btn.closest('#quick-suggestions').remove();

  showTyping();
  setTimeout(() => {
    removeTyping();
    const reply = getResponse(text);
    addBotMessage(reply.response, reply.followups);
  }, 500);
}


function getResponse(userText) {
  const lower = userText.toLowerCase();

  for (const rule of RULES) {
    for (const pattern of rule.patterns) {
      if (lower.includes(pattern)) {
        return { response: rule.response, followups: rule.followups || [] };
      }
    }
  }


  return {
    response: FALLBACKS[Math.floor(Math.random() * FALLBACKS.length)],
    followups: ['Register account', 'Book a service', 'Emergency SOS', 'Contact support']
  };
}


function addUserMessage(text) {
  messageCount++;
  const msgs = document.getElementById('chat-messages');
  const div  = document.createElement('div');
  div.className = 'msg-row user-row';
  div.innerHTML = `<div class="msg user-msg">${escapeHtml(text)}</div>`;
  msgs.appendChild(div);
  scrollBottom();
}


function addBotMessage(text, followups) {
  messageCount++;
  const msgs = document.getElementById('chat-messages');
  const div  = document.createElement('div');
  div.className = 'msg-row bot-row';

  let followupHTML = '';
  if (followups && followups.length > 0) {
    followupHTML = `<div class="followup-row">` +
      followups.map(f => `<button class="followup-btn" onclick="sendSuggestion(this)">${f}</button>`).join('') +
      `</div>`;
  }

  div.innerHTML = `
    <div class="bot-avatar-sm">🤖</div>
    <div>
      <div class="msg bot-msg">${text}</div>
      ${followupHTML}
    </div>`;
  msgs.appendChild(div);
  scrollBottom();
}


function showTyping() {
  const msgs = document.getElementById('chat-messages');
  const div  = document.createElement('div');
  div.className = 'msg-row bot-row';
  div.id = 'typing-indicator';
  div.innerHTML = `
    <div class="bot-avatar-sm">🤖</div>
    <div class="msg bot-msg typing-dots">
      <span></span><span></span><span></span>
    </div>`;
  msgs.appendChild(div);
  scrollBottom();
}

function removeTyping() {
  const t = document.getElementById('typing-indicator');
  if (t) t.remove();
}


function scrollBottom() {
  const msgs = document.getElementById('chat-messages');
  msgs.scrollTop = msgs.scrollHeight;
}

function escapeHtml(text) {
  return text.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}