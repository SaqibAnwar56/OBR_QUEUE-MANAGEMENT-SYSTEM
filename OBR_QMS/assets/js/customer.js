/* ================================================================
   OBR RESTAURANT — Customer Panel JavaScript v4
   Handles: Nav scroll/mobile, Hero slideshow, Scroll reveal,
            Cart system, Order placement, Toast notifications,
            Password toggle, Marquee pause, Active nav links
   ================================================================ */

/* ──────────────────────────────────────────────────────────────
   1. NAVBAR — scroll effect & mobile hamburger
   ────────────────────────────────────────────────────────────── */
(function () {
  var nav  = document.getElementById('mainNav');
  var ham  = document.getElementById('hamburger');
  var menu = document.getElementById('navMenu');
  if (!nav) return;

  // Scroll → add .scrolled class (only when nav starts transparent)
  var isTransparent = !nav.classList.contains('nav-solid');
  if (isTransparent) {
    window.addEventListener('scroll', function () {
      nav.classList.toggle('scrolled', window.scrollY > 60);
    });
  }

  // Hamburger toggle
  if (ham && menu) {
    ham.addEventListener('click', function () {
      var open = menu.classList.toggle('open');
      ham.classList.toggle('open', open);
      document.body.style.overflow = open ? 'hidden' : '';
    });
    // Close on link click
    menu.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', function () {
        menu.classList.remove('open');
        ham.classList.remove('open');
        document.body.style.overflow = '';
      });
    });
    // Close on ESC
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && menu.classList.contains('open')) {
        menu.classList.remove('open');
        ham.classList.remove('open');
        document.body.style.overflow = '';
      }
    });
  }

  // Mark active link based on current filename
  var cur = window.location.pathname.split('/').pop() || 'index.php';
  nav.querySelectorAll('.nav-menu a').forEach(function (a) {
    var href = (a.getAttribute('href') || '').split('?')[0];
    if (href === cur) a.classList.add('active');
  });
})();


/* ──────────────────────────────────────────────────────────────
   2. HERO SLIDESHOW
   ────────────────────────────────────────────────────────────── */
(function () {
  var slides = document.querySelectorAll('.hero-slide');
  if (slides.length < 2) return;
  var cur = 0;
  setInterval(function () {
    slides[cur].classList.remove('active');
    cur = (cur + 1) % slides.length;
    slides[cur].classList.add('active');
  }, 5000);
})();


/* ──────────────────────────────────────────────────────────────
   3. SCROLL REVEAL
   ────────────────────────────────────────────────────────────── */
(function () {
  var els = document.querySelectorAll('.reveal');
  if (!els.length) return;

  if ('IntersectionObserver' in window) {
    var obs = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) {
          e.target.classList.add('visible');
          obs.unobserve(e.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
    els.forEach(function (el) { obs.observe(el); });
  } else {
    // Fallback for older browsers
    els.forEach(function (el) { el.classList.add('visible'); });
  }
})();


/* ──────────────────────────────────────────────────────────────
   4. PASSWORD TOGGLE
   ────────────────────────────────────────────────────────────── */
function togglePw(inputId, btn) {
  var inp = document.getElementById(inputId);
  if (!inp) return;
  inp.type = inp.type === 'password' ? 'text' : 'password';
  if (btn) btn.textContent = inp.type === 'password' ? '👁' : '🙈';
}


/* ──────────────────────────────────────────────────────────────
   5. MARQUEE — pause on hover
   ────────────────────────────────────────────────────────────── */
(function () {
  var track = document.querySelector('.marquee-track');
  if (!track) return;
  track.addEventListener('mouseenter', function () { track.style.animationPlayState = 'paused'; });
  track.addEventListener('mouseleave', function () { track.style.animationPlayState = 'running'; });
})();


/* ──────────────────────────────────────────────────────────────
   6. TOAST NOTIFICATIONS
   ────────────────────────────────────────────────────────────── */
function showToast(msg, type) {
  // Remove any existing toast first
  var existing = document.querySelector('.obr-toast');
  if (existing && existing.parentNode) existing.parentNode.removeChild(existing);

  var t = document.createElement('div');
  t.className = 'obr-toast obr-toast-' + (type || 'ok');
  t.textContent = msg;
  t.style.cssText = [
    'position:fixed',
    'bottom:5rem',
    'left:50%',
    'transform:translateX(-50%)',
    'background:' + (type === 'err' ? 'var(--red)' : type === 'info' ? 'var(--blue)' : 'var(--green)'),
    'color:#fff',
    'padding:.85rem 2rem',
    'border-radius:50px',
    'font-size:.85rem',
    'font-weight:600',
    'z-index:9999',
    'box-shadow:0 8px 30px rgba(0,0,0,.5)',
    'white-space:nowrap',
    'pointer-events:none',
    'animation:fadeUp .3s ease'
  ].join(';');
  document.body.appendChild(t);
  setTimeout(function () { if (t.parentNode) t.parentNode.removeChild(t); }, 3500);
}


/* ──────────────────────────────────────────────────────────────
   7. CART SYSTEM
   ────────────────────────────────────────────────────────────── */
var cart = {}; // { id: { id, name, price, img, qty } }

function addToCart(id, name, price, img) {
  var sid = String(id);
  if (cart[sid]) {
    cart[sid].qty++;
  } else {
    cart[sid] = {
      id:    sid,
      name:  name,
      price: parseFloat(price),
      img:   img,
      qty:   1
    };
  }
  renderCart();
  showToast('Added: ' + name, 'ok');
}

function removeFromCart(id) {
  delete cart[String(id)];
  renderCart();
}

function changeQty(id, delta) {
  var sid = String(id);
  if (!cart[sid]) return;
  cart[sid].qty = Math.max(1, cart[sid].qty + delta);
  renderCart();
}

function renderCart() {
  var fab      = document.getElementById('cartFab');
  var badge    = document.getElementById('cartBadge');
  var items    = document.getElementById('cartItems');
  var footer   = document.getElementById('cartFooter');
  var totalAmt = document.getElementById('cartTotalAmt');
  if (!fab) return;

  var keys     = Object.keys(cart);
  var totalQty = keys.reduce(function (s, k) { return s + cart[k].qty; }, 0);
  var total    = keys.reduce(function (s, k) { return s + cart[k].price * cart[k].qty; }, 0);

  if (totalQty === 0) {
    fab.classList.add('hidden');
    if (footer) footer.style.display = 'none';
    if (items)  items.innerHTML = [
      '<div class="cart-empty">',
      '  <div class="cart-empty-ico">🛒</div>',
      '  <p>Your cart is empty</p>',
      '  <button onclick="closeCart()" class="btn-outline sm">Continue Browsing</button>',
      '</div>'
    ].join('');
    return;
  }

  // Show FAB + update badge
  fab.classList.remove('hidden');
  if (badge)    badge.textContent = totalQty;
  if (totalAmt) totalAmt.textContent = 'Rs. ' + Math.round(total).toLocaleString();
  if (footer)   footer.style.display = '';

  // Render cart items
  if (items) {
    var html = '';
    keys.forEach(function (sid) {
      var c = cart[sid];
      html += '<div class="cart-item">';
      html += '  <div class="cart-item-img"><img src="' + escapeHtml(c.img) + '" alt="' + escapeHtml(c.name) + '"></div>';
      html += '  <div class="cart-item-info">';
      html += '    <h4>' + escapeHtml(c.name) + '</h4>';
      html += '    <p class="ci-price">Rs. ' + Math.round(c.price * c.qty).toLocaleString() + '</p>';
      html += '    <div class="qty-ctrl">';
      html += '      <button onclick="changeQty(\'' + sid + '\',-1)">−</button>';
      html += '      <span>' + c.qty + '</span>';
      html += '      <button onclick="changeQty(\'' + sid + '\',1)">+</button>';
      html += '    </div>';
      html += '  </div>';
      html += '  <button class="cart-item-del" onclick="removeFromCart(\'' + sid + '\')" title="Remove">✕</button>';
      html += '</div>';
    });
    items.innerHTML = html;
  }
}

function openCart() {
  var m = document.getElementById('cartModal');
  if (m) {
    m.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
}

function closeCart() {
  var m = document.getElementById('cartModal');
  if (m) {
    m.classList.remove('open');
    document.body.style.overflow = '';
  }
}

// Close cart on Escape
document.addEventListener('keydown', function (e) {
  if (e.key === 'Escape') closeCart();
});

function placeOrder() {
  var keys = Object.keys(cart);
  if (!keys.length) { showToast('Cart is empty!', 'err'); return; }

  // Filter to only numeric IDs (real DB items, not demo)
  var realItems = keys
    .filter(function (sid) { return /^\d+$/.test(sid); })
    .map(function (sid) {
      return { id: parseInt(sid), name: cart[sid].name, price: cart[sid].price, qty: cart[sid].qty };
    });

  if (!realItems.length) {
    showToast('Please add real menu items to order.', 'info');
    return;
  }

  var notes = '';
  var notesEl = document.getElementById('cartNotes');
  if (notesEl) notes = notesEl.value.trim();

  // Get selected payment method
  var payEl = document.querySelector('input[name="payment_method"]:checked');
  var payment_method = payEl ? payEl.value : 'cash';
  var txnEl = document.getElementById('txnId');
  var transaction_id = txnEl ? txnEl.value.trim() : '';

  // Validate: online payments need transaction ID
  if (payment_method !== 'cash' && !transaction_id) {
    showToast('⚠ Please enter your Transaction ID for ' + payment_method, 'err');
    return;
  }

  var btn = document.getElementById('placeOrderBtn');
  if (btn) { btn.textContent = 'Placing Order…'; btn.disabled = true; }

  fetch('place_order.php', {
    method:  'POST',
    headers: { 'Content-Type': 'application/json' },
    body:    JSON.stringify({ items: realItems, notes: notes, payment_method: payment_method, transaction_id: transaction_id })
  })
  .then(function (r) { return r.json(); })
  .then(function (data) {
    if (data.success) {
      cart = {};
      renderCart();
      closeCart();
      showToast('✅ Order #' + data.order_id + ' placed! Admin notified.', 'ok');
      setTimeout(function () {
        window.location.href = 'orders.php?placed=' + data.order_id;
      }, 2000);
    } else {
      showToast('⚠ ' + (data.msg || 'Order failed.'), 'err');
      if (btn) { btn.textContent = '✅ Place Order'; btn.disabled = false; }
    }
  })
  .catch(function () {
    showToast('⚠ Network error. Please try again.', 'err');
    if (btn) { btn.textContent = '✅ Place Order'; btn.disabled = false; }
  });
}


/* ──────────────────────────────────────────────────────────────
   8. SMOOTH SCROLL for anchor links
   ────────────────────────────────────────────────────────────── */
document.querySelectorAll('a[href^="#"]').forEach(function (a) {
  a.addEventListener('click', function (e) {
    var id = this.getAttribute('href').slice(1);
    var el = document.getElementById(id);
    if (el) {
      e.preventDefault();
      el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});


/* ──────────────────────────────────────────────────────────────
   9. UTILITY: HTML escape
   ────────────────────────────────────────────────────────────── */
function escapeHtml(str) {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}


/* ──────────────────────────────────────────────────────────────
   10. AUTO-DISMISS ALERTS after 6 seconds
   ────────────────────────────────────────────────────────────── */
(function () {
  var alerts = document.querySelectorAll('.alert');
  alerts.forEach(function (a) {
    setTimeout(function () {
      a.style.transition = 'opacity .5s ease, max-height .5s ease';
      a.style.opacity = '0';
      a.style.maxHeight = '0';
      a.style.overflow = 'hidden';
      setTimeout(function () { if (a.parentNode) a.parentNode.removeChild(a); }, 600);
    }, 6000);
  });
})();