document.addEventListener('DOMContentLoaded', function() {
    // Navigation Menu Toggle
    const menuIcons = document.querySelectorAll('.footer-menu');
    menuIcons.forEach(icon => {
        icon.addEventListener('click', function() {
            window.location.href = 'menu.html';
        });
    });

    // Home Button
    const homeIcons = document.querySelectorAll('.footer-home');
    homeIcons.forEach(icon => {
        icon.addEventListener('click', function() {
            window.location.href = 'index.php';
        });
    });

    // Account Button
    const accountIcons = document.querySelectorAll('.footer-account');
    accountIcons.forEach(icon => {
        icon.addEventListener('click', function() {
            window.location.href = 'auth.html';
        });
    });

    // FAQ Accordion
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        question.addEventListener('click', function() {
            // Toggle current FAQ item
            item.classList.toggle('active');
            const answer = item.querySelector('.faq-answer');
            if (item.classList.contains('active')) {
                answer.style.display = 'block';
            } else {
                answer.style.display = 'none';
            }
        });
    });

    // Contact Form Submission
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const subject = document.getElementById('subject') ? document.getElementById('subject').value : '';
            const message = document.getElementById('message').value;
            
            // Validate form (simple validation)
            if (!name || !email || !message) {
                alert('Please fill in all required fields');
                return;
            }
            

            alert('Thank you for your message! We will get back to you soon.');
            contactForm.reset();
        });
    }

    // Login Form Submission
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            // e.preventDefault();
            
            // // Get form values
            // const email = document.getElementById('email').value;
            // const password = document.getElementById('password').value;
            
            // // Validate form (simple validation)
            // if (!email || !password) {
            //     alert('Please fill in all fields');
            //     return;
            // }
            // ge
            // alert('Login successful! Welcome back to Dhen\'s Kitchen.');
            // window.location.href = 'index.php';
        });
    }

    // Sign Up Form Submission
    const signupForm = document.getElementById('signupForm');
    if (signupForm) {
        signupForm.addEventListener('submit', function(e) {
            // e.preventDefault();
            
            // Get form values
            const fullName = document.getElementById('fullName').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone') ? document.getElementById('phone').value : '';
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const terms = document.getElementById('terms').checked;
            
        //     // Validate form (simple validation)
        //     if (!fullName || !email || !password || !confirmPassword) {
        //         alert('Please fill in all required fields');
        //         return;
        //     }
            
        //     if (password !== confirmPassword) {
        //         alert('Passwords do not match');
        //         return;
        //     }
            
        //     if (!terms) {
        //         alert('Please agree to the terms and conditions');
        //         return;
        //     }

        // // alert('Account created successfully! Please log in with your new credentials.');
        //     window.location.href = 'login.php';
        });
    }

    // Shopping Cart Functionality
    // const cart = {
    //     items: [],
        
    //     addItem: function(id, name, price, size = null, quantity = 1) {
    //         const itemId = size ? `${id}-${size}` : id;
    //         const existingItem = this.items.find(item => item.id === itemId);
    //         if (existingItem) {
    //             existingItem.quantity += quantity;
    //         } else {
    //             this.items.push({
    //                 id: itemId,
    //                 originalId: id,
    //                 name: name,
    //                 price: parseFloat(price),
    //                 size: size,
    //                 quantity: quantity
    //             });
    //         }
    //         this.updateCart();
    //         this.saveCart();
    //     },
        
    //     removeItem: function(id) {
    //         this.items = this.items.filter(item => item.id !== id);
    //         this.updateCart();
    //         this.saveCart();
    //     },
        
    //     updateQuantity: function(id, quantity) {
    //         const item = this.items.find(item => item.id === id);
    //         if (item) {
    //             item.quantity = parseInt(quantity);
    //             if (item.quantity <= 0) {
    //                 this.removeItem(id);
    //             } else {
    //                 this.updateCart();
    //                 this.saveCart();
    //             }
    //         }
    //     },
        
    //     getTotal: function() {
    //         return this.items.reduce((total, item) => total + (item.price * item.quantity), 0).toFixed(2);
    //     },
        
    //     updateCart: function() {
    //         const cartItems = document.getElementById('cart-items');
    //         const cartTotal = document.getElementById('cart-total-amount');
    //         if (cartItems && cartTotal) {
    //             if (this.items.length === 0) {
    //                 cartItems.innerHTML = '<p class="empty-cart">Your cart is empty</p>';
    //             } else {
    //                 cartItems.innerHTML = '';
    //                 this.items.forEach(item => {
    //                     const cartItem = document.createElement('div');
    //                     cartItem.className = 'cart-item';
    //                     const itemName = item.size ? `${item.name} - ${item.size}` : item.name;
    //                     cartItem.innerHTML = `
    //                         <div class="cart-item-name">${itemName}</div>
    //                         <div class="cart-item-price">₱${item.price.toFixed(2)}</div>
    //                         <div class="cart-item-quantity">
    //                             <input type="number" min="1" value="${item.quantity}" data-id="${item.id}" class="quantity-input">
    //                         </div>
    //                         <div class="cart-item-total">₱${(item.price * item.quantity).toFixed(2)}</div>
    //                         <div class="cart-item-remove" data-id="${item.id}">✕</div>
    //                     `;
    //                     cartItems.appendChild(cartItem);
    //                 });
    //                 // Quantity and remove event listeners
    //                 const quantityInputs = document.querySelectorAll('.quantity-input');
    //                 quantityInputs.forEach(input => {
    //                     input.addEventListener('change', function() {
    //                         const id = this.getAttribute('data-id');
    //                         const quantity = this.value;
    //                         cart.updateQuantity(id, quantity);
    //                     });
    //                 });
    //                 const removeButtons = document.querySelectorAll('.cart-item-remove');
    //                 removeButtons.forEach(button => {
    //                     button.addEventListener('click', function() {
    //                         const id = this.getAttribute('data-id');
    //                         cart.removeItem(id);
    //                     });
    //                 });
    //             }
    //             cartTotal.textContent = `₱${this.getTotal()}`;
    //         }
    //     },
        
        // Save cart to both active and user-specific key
    //     saveCart: function() {
    //         localStorage.setItem('dhensKitchenCart', JSON.stringify(this.items));
    //         const userKey = getCurrentUserKey();
    //         if (userKey) {
    //             localStorage.setItem('dhensKitchenCart_' + userKey, JSON.stringify(this.items));
    //         }
    //     },
        
    //     // Load cart from user-specific key if logged in, else from active cart
    //     loadCart: function() {
    //         const userKey = getCurrentUserKey();
    //         let savedCart = null;
    //         if (userKey) {
    //             savedCart = localStorage.getItem('dhensKitchenCart_' + userKey);
    //         }
    //         if (!savedCart) {
    //             savedCart = localStorage.getItem('dhensKitchenCart');
    //         }
    //         if (savedCart) {
    //             this.items = JSON.parse(savedCart);
    //             this.updateCart();
    //         }
    //     }
    // };
    
    // // Load cart from localStorage
    // cart.loadCart();
    
    // // Add to Cart functionality
    // const addToCartButtons = document.querySelectorAll('.add-to-cart');
    // addToCartButtons.forEach(button => {
    //     button.addEventListener('click', function() {
    //         // Check login status
    //         const isLoggedIn = document.body.getAttribute('data-logged-in') === '1';
    //         if (!isLoggedIn) {
    //             // Redirect to login with redirect back to order.php
    //             window.location.href = 'auth.html?redirect=order.php';
    //             return;
    //         }
    //         const id = this.getAttribute('data-id');
    //         const name = this.getAttribute('data-name');
    //         const price = this.getAttribute('data-price');
    //         const sizeSelector = document.querySelector(`select[data-id="${id}"]`);
    //         const size = sizeSelector ? sizeSelector.value : null;
    //         cart.addItem(id, name, price, size);
    //     });
    // });
    
    // Checkout button
    const checkoutBtn = document.getElementById('checkout-button');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function () {
            const isLoggedIn = document.body.getAttribute('data-logged-in') === '1';
            if (cart.items.length === 0) {
                alert('Your cart is empty!');
                return;
            }
            if (!isLoggedIn) {
                // Redirect to login with redirect to place-order.php
                window.location.href = 'auth.php?redirect=place-order.php';
            } else {
                window.location.href = 'place-order.php';
            }
        });
    }

    // Menu category navigation
    const categoryLinks = document.querySelectorAll('.category-nav a');
    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all links
            categoryLinks.forEach(l => l.classList.remove('active'));
            
            // Add active class to clicked link
            this.classList.add('active');
            
            // Scroll to the corresponding section
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            
            if (targetSection) {
                window.scrollTo({
                    top: targetSection.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Menu item click to go to item detail page
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            const itemId = this.getAttribute('data-id');
            if (itemId) {
                window.location.href = `item.php?id=${itemId}`;
            }
        });
    });

    // Instagram grid hover effects
    const instagramItems = document.querySelectorAll('.instagram-item');
    instagramItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            const caption = this.querySelector('.instagram-caption');
            if (caption) {
                caption.style.transform = 'translateY(0)';
            }
        });
        
        item.addEventListener('mouseleave', function() {
            const caption = this.querySelector('.instagram-caption');
            if (caption) {
                caption.style.transform = 'translateY(100%)';
            }
        });
    });

    // Pagination functionality
    const paginationPrev = document.querySelector('.page-prev');
    const paginationNext = document.querySelector('.page-next');
    const paginationNumber = document.querySelector('.page-number');
    
    if (paginationPrev && paginationNext && paginationNumber) {
        let currentPage = 1;
        const totalPages = 3; // Assuming 3 pages for demonstration
        
        paginationPrev.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                updatePagination();
            }
        });
        
        paginationNext.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPage < totalPages) {
                currentPage++;
                updatePagination();
            }
        });
        
        function updatePagination() {
            paginationNumber.textContent = `${currentPage}/${totalPages}`;
            
            alert(`Loading page ${currentPage}...`);
        }
    }

    // Custom Order Button
    const customOrderButton = document.querySelector('.custom-order-button');
    if (customOrderButton) {
        customOrderButton.addEventListener('click', function() {
            window.location.href = 'contact.html?subject=custom-order';
        });
    }

    // Admin Dashboard Functionality
    const adminSectionButtons = document.querySelectorAll('.admin-nav button');
    if (adminSectionButtons.length > 0) {
        adminSectionButtons.forEach(button => {
            button.addEventListener('click', function() {
                const section = this.getAttribute('data-section');
                showAdminSection(section);
            });
        });

        function showAdminSection(section) {
            const sections = document.querySelectorAll('section[id$="Section"]');
            sections.forEach(s => {
                s.style.display = 'none';
            });
            
            document.getElementById(section + 'Section').style.display = 'block';
            
            // Hide any open details panels
            document.getElementById('details').style.display = 'none';
            document.getElementById('paymentDetails').style.display = 'none';
            document.getElementById('replyForm').style.display = 'none';
        }
    }

    // Admin Order View
    const viewOrderButtons = document.querySelectorAll('.admin-view-btn[data-type="order"]');
    if (viewOrderButtons.length > 0) {
        viewOrderButtons.forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-id');
                viewOrder(orderId);
            });
        });

        function viewOrder(orderId) {
            const orderDetails = {
                O001: { customer: 'Rhy Estrella', items: [{ name: 'Chocolate Cake', qty: 1 }] },
                O002: { customer: 'Lyra Cunanan', items: [{ name: 'Strawberry Cake', qty: 2 }] }
            };

            const order = orderDetails[orderId];
            let detailText = `<strong>Customer:</strong> ${order.customer}<br><strong>Items:</strong><br>`;
            order.items.forEach(item => detailText += `• ${item.name} - ${item.qty}<br>`);
            
            document.getElementById('orderDetails').innerHTML = detailText;
            document.getElementById('statusSelect').value = document.getElementById('status' + orderId).innerText;
            document.getElementById('details').style.display = 'block';
            
            // Store current order ID for status update
            document.getElementById('details').setAttribute('data-current-order', orderId);
        }
    }

    // Admin Status Update
    const saveStatusButton = document.querySelector('.admin-save-btn[data-action="update-status"]');
    if (saveStatusButton) {
        saveStatusButton.addEventListener('click', function() {
            const orderId = document.getElementById('details').getAttribute('data-current-order');
            const newStatus = document.getElementById('statusSelect').value;
            
            document.getElementById('status' + orderId).innerText = newStatus;
            alert(`Order ${orderId} updated to ${newStatus}`);
            document.getElementById('details').style.display = 'none';
        });
    }

    // Admin Payment View
    const viewPaymentButtons = document.querySelectorAll('.admin-view-btn[data-type="payment"]');
    if (viewPaymentButtons.length > 0) {
        viewPaymentButtons.forEach(button => {
            button.addEventListener('click', function() {
                const paymentId = this.getAttribute('data-id');
                viewPayment(paymentId);
            });
        });

        function viewPayment(paymentId) {
            const payments = {
                P001: { customer: 'Rhy Estrella', amount: '₱500', mode: 'Gcash', proof: 'gcash_proof.jpg', sender: 'Rhy Estrella', bank: 'None' },
                P002: { customer: 'Lyra Cunanan', amount: '₱1200', mode: 'Bank Transfer', proof: 'bank_receipt.jpg', sender: 'Lyra Cunanan', bank: 'BDO' }
            };

            const payment = payments[paymentId];
            let info = `<strong>Customer:</strong> ${payment.customer}<br>
                        <strong>Amount:</strong> ${payment.amount}<br>
                        <strong>Mode:</strong> ${payment.mode}<br>
                        <strong>Sender:</strong> ${payment.sender}<br>
                        <strong>Bank:</strong> ${payment.bank}<br>
                        <strong>Proof of Payment:</strong> <a href="${payment.proof}" target="_blank">View Proof</a>`;
            
            document.getElementById('paymentInfo').innerHTML = info;
            document.getElementById('paymentStatus').value = 'Pending';
            document.getElementById('paymentDetails').style.display = 'block';
            
            // Store current payment ID for status update
            document.getElementById('paymentDetails').setAttribute('data-current-payment', paymentId);
        }
    }

    // Admin Payment Status Update
    const savePaymentStatusButton = document.querySelector('.admin-save-btn[data-action="update-payment"]');
    if (savePaymentStatusButton) {
        savePaymentStatusButton.addEventListener('click', function() {
            const paymentId = document.getElementById('paymentDetails').getAttribute('data-current-payment');
            const status = document.getElementById('paymentStatus').value;
            
            alert(`Payment ${paymentId} marked as ${status}`);
            document.getElementById('paymentDetails').style.display = 'none';
        });
    }

    // Admin Message Reply
    const replyMessageButtons = document.querySelectorAll('.admin-reply-btn');
    if (replyMessageButtons.length > 0) {
        replyMessageButtons.forEach(button => {
            button.addEventListener('click', function() {
                const customer = this.getAttribute('data-customer');
                replyMessage(customer);
            });
        });

        function replyMessage(customer) {
            document.getElementById('replyForm').style.display = 'block';
            document.getElementById('replyTo').innerText = customer;
            document.getElementById('replyMessageBox').value = '';
        }
    }

    // Admin Send Reply
    const sendReplyButton = document.querySelector('.admin-save-btn[data-action="send-reply"]');
    if (sendReplyButton) {
        sendReplyButton.addEventListener('click', function() {
            const customer = document.getElementById('replyTo').innerText;
            const message = document.getElementById('replyMessageBox').value;
            
            if (message.trim() === '') {
                alert('Please type a message before sending.');
                return;
            }
            
            alert(`Reply sent to ${customer}: ${message}`);
            document.getElementById('replyForm').style.display = 'none';
        });
    }

    // Helper to get current user key (adjust as needed)
    function getCurrentUserKey() {
        // You can set this value on login, e.g., localStorage.setItem('dhensKitchenUser', email);
        return localStorage.getItem('dhensKitchenUser'); // e.g., user's email
    }

    // Admin Logout
    const logoutButton = document.querySelector('.admin-logout-btn');
    if (logoutButton) {
        logoutButton.addEventListener('click', function() {
            localStorage.removeItem('dhensKitchenCart');
            // Optionally, remove user key as well
            localStorage.removeItem('dhensKitchenUser');
            alert('Logging out...');
            window.location.href = 'login.php';
        });
    }

    // Load item details on item page
    const itemDetailContainer = document.querySelector('.item-detail-container');
    if (itemDetailContainer) {
        // Get item ID from URL
        const urlParams = new URLSearchParams(window.location.search);
        const itemId = urlParams.get('id');
        
        if (itemId) {
            loadItemDetails(itemId);
        }
    }

    function loadItemDetails(itemId) {
        const item = items[itemId];
        if (item) {
            document.querySelector('.item-detail-title').textContent = item.name;
            document.querySelector('.item-detail-price').textContent = item.price;
            document.querySelector('.item-detail-description').textContent = item.description;
            document.querySelector('.item-detail-image img').src = item.image;
            
            const specsList = document.querySelector('.item-detail-specs ul');
            specsList.innerHTML = '';
            
            for (const [key, value] of Object.entries(item.specs)) {
                const li = document.createElement('li');
                li.innerHTML = `<span>${key}</span><span>${value}</span>`;
                specsList.appendChild(li);
            }
        }
    }

    // Initialize any active FAQ items
    if (faqItems.length > 0) {
        // Activate the first FAQ item by default
        faqItems[0].classList.add('active');
        faqItems[0].querySelector('.faq-answer').style.display = 'block';
    }

    // Check if URL has a subject parameter for contact form
    if (contactForm) {
        const urlParams = new URLSearchParams(window.location.search);
        const subject = urlParams.get('subject');
        
        if (subject && subject === 'custom-order') {
            const subjectSelect = document.getElementById('subject');
            if (subjectSelect) {
                // Find the option for custom order or catering
                for (let i = 0; i < subjectSelect.options.length; i++) {
                    if (subjectSelect.options[i].value === 'catering') {
                        subjectSelect.selectedIndex = i;
                        break;
                    }
                }
            }
            
            // Scroll to the form
            contactForm.scrollIntoView({ behavior: 'smooth' });
        }
    }

    document.querySelectorAll('.size-select').forEach(function(select) {
        const priceDisplay = document.getElementById('product-price-' + select.dataset.id);
        // Set initial price
        if (select.options.length > 0) {
            priceDisplay.textContent = '₱' + Number(select.options[select.selectedIndex].dataset.price).toLocaleString(undefined, {minimumFractionDigits:2});
        }
        // Update price on change
        select.addEventListener('change', function() {
            const selected = select.options[select.selectedIndex];
            priceDisplay.textContent = '₱' + Number(selected.dataset.price).toLocaleString(undefined, {minimumFractionDigits:2});
        });
    });

    // --- CART HANDLERS ---

    function attachCartHandlers() {
        const cartItemsDiv = document.getElementById('cart-items');
        if (cartItemsDiv) {
            cartItemsDiv.addEventListener('submit', function(e) {
                if (e.target.classList.contains('update-cart-form')) {
                    e.preventDefault();
                    const form = e.target;
                    const cartItemId = form.getAttribute('data-id');
                    const quantity = form.querySelector('input[name="quantity"]').value;
                    const formData = new FormData();
                    formData.append('cart_item_id', cartItemId);
                    formData.append('quantity', quantity);

                    fetch('update-cart-item.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const row = form.closest('tr');
                            row.querySelector('input[type="number"]').value = data.updated_quantity;
                            row.querySelector('.cart-subtotal').textContent = '₱' + Number(data.subtotal).toLocaleString(undefined, {minimumFractionDigits:2});
                            document.getElementById('cart-total-amount').textContent = '₱' + Number(data.cart_total).toLocaleString(undefined, {minimumFractionDigits:2});
                        }
                    });
                }
                if (e.target.classList.contains('remove-cart-form')) {
                    e.preventDefault();
                    const form = e.target;
                    const cartItemId = form.getAttribute('data-id');
                    const formData = new FormData();
                    formData.append('cart_item_id', cartItemId);

                    fetch('remove-from-cart.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const row = form.closest('tr');
                            row.parentNode.removeChild(row);
                            document.getElementById('cart-total-amount').textContent = '₱' + Number(data.cart_total).toLocaleString(undefined, {minimumFractionDigits:2});
                            if (data.cart_total == 0) {
                                cartItemsDiv.innerHTML = '<p class="empty-cart">Your cart is empty</p>';
                            }
                        }
                    });
                }
            });
        }
    }

    // Add to Cart AJAX
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);

            fetch('add-to-cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartDOM(data.cart_items, data.cart_total);
                } else if (data.message === 'Not logged in') {
                    window.location.href = 'auth.php'; // Redirect to login
                } else {
                    alert('Failed to add to cart.');
                }
            });
        });
    });

    // Helper to update cart DOM and re-attach handlers
    function updateCartDOM(cart_items, cart_total) {
        const cartItemsDiv = document.getElementById('cart-items');
        const checkoutBtn = document.getElementById('checkout-button');
        if (!cart_items.length) {
            cartItemsDiv.innerHTML = '<p class="empty-cart">Your cart is empty</p>';
            document.getElementById('cart-total-amount').textContent = '₱0.00';
            if (checkoutBtn) {
                checkoutBtn.disabled = true;
                checkoutBtn.style.opacity = '0.5';
                checkoutBtn.style.pointerEvents = 'none';
            }
            return;
        }
        let html = `<table style="width:90%; max-width:600px; margin:auto; border-collapse:separate; border-spacing:0 8px; background:#fff;">
            <thead>
                <tr style="background:none;">
                    <th style="text-align:left; padding:8px 6px;">Product</th>
                    <th style="text-align:center; padding:8px 6px; width:80px;">Qty</th>
                    <th style="text-align:right; padding:8px 6px;">Unit Price</th>
                    <th style="text-align:right; padding:8px 6px;">Subtotal</th>
                    <th style="text-align:center; padding:8px 6px; width:40px;"></th>
                </tr>
            </thead>
            <tbody>`;
        cart_items.forEach(item => {
            html += `<tr style="background:none;">
                <td style="padding:8px 6px; vertical-align:middle; text-align:left; font-weight:500; color:#234;">
                    ${item.ProductName}
                </td>
                <td style="padding:8px 6px; text-align:center; vertical-align:middle;">
                    <form class="update-cart-form" data-id="${item.CartItemID}" style="display:inline;">
                        <input type="number" name="quantity" value="${item.CartQuantity}" min="1"
                            style="width:48px; text-align:center; border-radius:6px; border:1px solid #ccc; padding:2px 4px;">
                    </form>
                </td>
                <td style="padding:8px 6px; text-align:right; vertical-align:middle;">₱${Number(item.UnitPrice).toLocaleString(undefined, {minimumFractionDigits:2})}</td>
                <td class="cart-subtotal" style="padding:8px 6px; text-align:right; vertical-align:middle; font-weight:500; color:var(--coral);">
                    ₱${Number(item.Subtotal).toLocaleString(undefined, {minimumFractionDigits:2})}
                </td>
                <td style="padding:8px 6px; text-align:center; vertical-align:middle;">
                    <form class="remove-cart-form" data-id="${item.CartItemID}" style="display:inline;">
                        <button type="submit" title="Remove" style="background:none;border:none;color:red;cursor:pointer;font-size:1.2em;line-height:1;">&#10005;</button>
                    </form>
                </td>
            </tr>`;
        });
        html += `</tbody></table>`;
        cartItemsDiv.innerHTML = html;
        document.getElementById('cart-total-amount').textContent = '₱' + Number(cart_total).toLocaleString(undefined, {minimumFractionDigits:2});
        // Re-attach AJAX handlers for new forms
        attachCartHandlers();

        // Enable checkout button
        if (checkoutBtn) {
            checkoutBtn.disabled = false;
            checkoutBtn.style.opacity = '1';
            checkoutBtn.style.pointerEvents = '';
        }
    }

    // Initial attach on page load
    attachCartHandlers();

    // Attach event for quantity change
    document.querySelectorAll('.update-cart-form input[name="quantity"]').forEach(function(input) {
        input.addEventListener('change', function() {
            const form = input.closest('form');
            // Manually trigger the submit event so your existing handler runs
            form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
        });
    });

    document.addEventListener('change', function(e) {
        if (e.target.matches('.update-cart-form input[name="quantity"]')) {
            const form = e.target.closest('form');
            form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
        }
    });
});

