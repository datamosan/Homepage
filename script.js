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
    const cart = {
        items: [],
        
        addItem: function(id, name, price, size = null, quantity = 1) {
            // Create a unique identifier for the item with its size
            const itemId = size ? `${id}-${size}` : id;
            
            // Check if item already exists in cart
            const existingItem = this.items.find(item => item.id === itemId);
            
            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                this.items.push({
                    id: itemId,
                    originalId: id,
                    name: name,
                    price: parseFloat(price),
                    size: size,
                    quantity: quantity
                });
            }
            
            this.updateCart();
            this.saveCart();
        },
        
        removeItem: function(id) {
            this.items = this.items.filter(item => item.id !== id);
            this.updateCart();
            this.saveCart();
        },
        
        updateQuantity: function(id, quantity) {
            const item = this.items.find(item => item.id === id);
            if (item) {
                item.quantity = parseInt(quantity);
                if (item.quantity <= 0) {
                    this.removeItem(id);
                } else {
                    this.updateCart();
                    this.saveCart();
                }
            }
        },
        
        getTotal: function() {
            return this.items.reduce((total, item) => total + (item.price * item.quantity), 0).toFixed(2);
        },
        
        updateCart: function() {
            const cartItems = document.getElementById('cart-items');
            const cartTotal = document.getElementById('cart-total-amount');
            
            if (cartItems && cartTotal) {
                if (this.items.length === 0) {
                    cartItems.innerHTML = '<p class="empty-cart">Your cart is empty</p>';
                } else {
                    cartItems.innerHTML = '';
                    
                    this.items.forEach(item => {
                        const cartItem = document.createElement('div');
                        cartItem.className = 'cart-item';
                        
                        const itemName = item.size ? `${item.name} - ${item.size}` : item.name;
                        
                        cartItem.innerHTML = `
                            <div class="cart-item-name">${itemName}</div>
                            <div class="cart-item-price">₱${item.price.toFixed(2)}</div>
                            <div class="cart-item-quantity">
                                <input type="number" min="1" value="${item.quantity}" data-id="${item.id}" class="quantity-input">
                            </div>
                            <div class="cart-item-total">₱${(item.price * item.quantity).toFixed(2)}</div>
                            <div class="cart-item-remove" data-id="${item.id}">✕</div>
                        `;
                        
                        cartItems.appendChild(cartItem);
                    });
                    
                    // Add event listeners for quantity inputs
                    const quantityInputs = document.querySelectorAll('.quantity-input');
                    quantityInputs.forEach(input => {
                        input.addEventListener('change', function() {
                            const id = this.getAttribute('data-id');
                            const quantity = this.value;
                            cart.updateQuantity(id, quantity);
                        });
                    });
                    
                    // Add event listeners for remove buttons
                    const removeButtons = document.querySelectorAll('.cart-item-remove');
                    removeButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            cart.removeItem(id);
                        });
                    });
                }
                
                cartTotal.textContent = `₱${this.getTotal()}`;
            }
        },
        
        saveCart: function() {
            localStorage.setItem('dhensKitchenCart', JSON.stringify(this.items));
        },
        
        loadCart: function() {
            const savedCart = localStorage.getItem('dhensKitchenCart');
            if (savedCart) {
                this.items = JSON.parse(savedCart);
                this.updateCart();
            }
        }
    };
    
    // Load cart from localStorage
    cart.loadCart();
    
    // Add to Cart functionality
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const price = this.getAttribute('data-price');
            
            // Check if there's a size selector for this product
            const sizeSelector = document.querySelector(`select[data-id="${id}"]`);
            const size = sizeSelector ? sizeSelector.value : null;
            
            cart.addItem(id, name, price, size);
            
            // Show confirmation message
            alert(`${name}${size ? ` (${size})` : ''} added to cart!`);
        });
    });
    
    // Checkout button
document.addEventListener('DOMContentLoaded', function () {
    const checkoutBtn = document.getElementById('checkout-button');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function () {
            // Check if user is logged in (from PHP session)
            const isLoggedIn = !!document.body.getAttribute('data-logged-in');
            // Get cart items from localStorage (or however your cart is stored)
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            if (cart.length === 0) {
                alert('Your cart is empty!');
                return;
            }
            if (!isLoggedIn) {
                // Redirect to login page
                window.location.href = 'auth.html?redirect=place-order.php';
            } else {
                // Send cart to server via AJAX then redirect
                fetch('save-cart.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ cart })
                })
                .then(res => res.json())
                .then(data => {
                    window.location.href = 'place-order.php';
                });
            }
        });
    }
});

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

    // Admin Logout
    const logoutButton = document.querySelector('.admin-logout-btn');
    if (logoutButton) {
        logoutButton.addEventListener('click', function() {
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
        // In a real application, you would fetch item details from a server
        // For demonstration, we'll use dummy data
        const items = {
            'chocolate-cake': {
                name: 'Chocolate Cake',
                price: '₱550.00',
                description: 'Rich chocolate layers with smooth ganache frosting. Perfect for birthdays and special occasions.',
                image: 'images/chocolate-cake.jpg',
                specs: {
                    'Serving Size': '6-8 pax',
                    'Size': '8 inches',
                    'Allergens': 'Contains dairy, eggs, wheat',
                    'Storage': 'Refrigerate up to 3 days',
                    'Lead Time': '24-48 hours'
                }
            },
            'red-velvet': {
                name: 'Red Velvet Cake',
                price: '₱650.00',
                description: 'Classic red velvet cake with cream cheese frosting. A perfect blend of cocoa and vanilla flavors.',
                image: 'images/red-velvet.jpg',
                specs: {
                    'Serving Size': '10-12 pax',
                    'Size': '10 inches',
                    'Allergens': 'Contains dairy, eggs, wheat',
                    'Storage': 'Refrigerate up to 3 days',
                    'Lead Time': '24-48 hours'
                }
            },
            'bicol-express': {
                name: 'Bicol Express',
                price: '₱750.00',
                description: 'Spicy Filipino dish with pork, coconut milk, and chili peppers. A Bicolano specialty.',
                image: 'images/bicol-express.jpg',
                specs: {
                    'Serving Size': 'Party Tray (10-12 pax)',
                    'Spice Level': 'Medium to Hot',
                    'Allergens': 'Contains coconut, may contain shrimp paste',
                    'Storage': 'Refrigerate up to 2 days',
                    'Reheating': 'Microwave or stovetop'
                }
            }
        };

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
});
