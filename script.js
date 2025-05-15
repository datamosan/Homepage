// Wait for the DOM to be fully loaded
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
            window.location.href = 'index.html';
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
            
            // Here you would typically send the form data to a server
            // For demonstration, we'll just show an alert
            alert('Thank you for your message! We will get back to you soon.');
            contactForm.reset();
        });
    }

    // Login Form Submission
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Validate form (simple validation)
            if (!email || !password) {
                alert('Please fill in all fields');
                return;
            }
            
            // Here you would typically authenticate the user
            // For demonstration, we'll just redirect to the homepage
            alert('Login successful! Welcome back to Dhen\'s Kitchen.');
            window.location.href = 'index.html';
        });
    }

    // Sign Up Form Submission
    const signupForm = document.getElementById('signupForm');
    if (signupForm) {
        signupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const fullName = document.getElementById('fullName').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone') ? document.getElementById('phone').value : '';
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const terms = document.getElementById('terms').checked;
            
            // Validate form (simple validation)
            if (!fullName || !email || !password || !confirmPassword) {
                alert('Please fill in all required fields');
                return;
            }
            
            if (password !== confirmPassword) {
                alert('Passwords do not match');
                return;
            }
            
            if (!terms) {
                alert('Please agree to the terms and conditions');
                return;
            }
            
            // Here you would typically register the user
            // For demonstration, we'll just redirect to the login page
            alert('Account created successfully! Please log in with your new credentials.');
            window.location.href = 'login.html';
        });
    }

    // Shopping Cart Functionality
    const cart = {
        items: [],
        
        addItem: function(id, name, price) {
            // Check if item already exists in cart
            const existingItem = this.items.find(item => item.id === id);
            
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                this.items.push({
                    id: id,
                    name: name,
                    price: parseFloat(price),
                    quantity: 1
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
                        
                        cartItem.innerHTML = `
                            <div class="cart-item-name">${item.name}</div>
                            <div class="cart-item-price">$${item.price.toFixed(2)}</div>
                            <div class="cart-item-quantity">
                                <input type="number" min="1" value="${item.quantity}" data-id="${item.id}" class="quantity-input">
                            </div>
                            <div class="cart-item-total">$${(item.price * item.quantity).toFixed(2)}</div>
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
                
                cartTotal.textContent = `$${this.getTotal()}`;
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
            
            cart.addItem(id, name, price);
            
            // Show confirmation message
            alert(`${name} added to cart!`);
        });
    });
    
    // Checkout button
    const checkoutButton = document.getElementById('checkout-button');
    if (checkoutButton) {
        checkoutButton.addEventListener('click', function() {
            if (cart.items.length === 0) {
                alert('Your cart is empty. Please add items before checking out.');
            } else {
                alert('Proceeding to checkout...');
                // Here you would typically redirect to a checkout page
                // For demonstration, we'll just show an alert
                alert(`Total: $${cart.getTotal()}\nThank you for your order!`);
                cart.items = [];
                cart.updateCart();
                cart.saveCart();
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

    // Image gallery hover effects
    const galleryItems = document.querySelectorAll('.gallery-item');
    galleryItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            const caption = this.querySelector('.gallery-caption');
            if (caption) {
                caption.style.transform = 'translateY(0)';
            }
        });
        
        item.addEventListener('mouseleave', function() {
            const caption = this.querySelector('.gallery-caption');
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
            
            // Here you would typically load the content for the current page
            // For demonstration, we'll just show an alert
            alert(`Loading page ${currentPage}...`);
        }
    }
});