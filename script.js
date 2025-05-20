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
            ge
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

            alert('Account created successfully! Please log in with your new credentials.');
            window.location.href = 'login.html';
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
                        
                        const itemName = item.size ? `${item.name} (${item.size})` : item.name;
                        
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
    const checkoutButton = document.getElementById('checkout-button');
    if (checkoutButton) {
        checkoutButton.addEventListener('click', function() {
            if (cart.items.length === 0) {
                alert('Your cart is empty. Please add items before checking out.');
            } else {
                alert('Proceeding to checkout...');
                alert(`Total: ₱${cart.getTotal()}\nThank you for your order!`);
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

    // Dish Detail Modal
    const modal = document.getElementById('dish-modal');
    const closeBtn = document.querySelector('.dish-modal-close');
    const closeButton = document.getElementById('dish-modal-close');
    const orderButton = document.getElementById('dish-modal-order');

    // Dish data
    const dishData = {
        'bicol-express': {
            title: 'Bicol Express',
            price: '₱750.00',
            image: 'images/bicol-express.jpg',
            description: 'A spicy Filipino dish made from pork cooked in coconut milk with shrimp paste, chili peppers, and other spices. This authentic Bicolano recipe brings the perfect balance of creaminess and heat that the region is known for.',
            serving: '4-5 people',
            spice: 'Medium-Hot',
            prep: '45 minutes',
            ingredients: [
                'Pork belly', 'Coconut milk', 'Shrimp paste', 'Bird's eye chili', 
                'Bell peppers', 'Onion', 'Garlic', 'Ginger', 'Salt', 'Pepper'
            ]
        },
        'laing': {
            title: 'Laing',
            price: '₱650.00',
            image: 'images/laing.jpg',
            description: 'A creamy dish made from dried taro leaves cooked in coconut milk, with meat and chili. This traditional Bicolano delicacy has a rich, spicy flavor that comes from the perfect blend of coconut cream and chili peppers.',
            serving: '4 people',
            spice: 'Medium',
            prep: '60 minutes',
            ingredients: [
                'Dried taro leaves', 'Coconut milk', 'Pork', 'Shrimp paste', 
                'Chili peppers', 'Garlic', 'Onion', 'Ginger', 'Salt'
            ]
        },
        'kinunot': {
            title: 'Kinunot',
            price: '₱850.00',
            image: 'images/kinunot.jpg',
            description: 'A traditional Bicolano dish made with shark meat or stingray cooked in coconut milk with malunggay leaves and chili peppers. The name comes from the Bicolano word "kinunot" which means "to tear apart" as the fish meat is shredded.',
            serving: '3-4 people',
            spice: 'Medium',
            prep: '50 minutes',
            ingredients: [
                'Shark meat/Stingray', 'Coconut milk', 'Malunggay leaves', 
                'Chili peppers', 'Ginger', 'Garlic', 'Onion', 'Lemongrass', 'Salt', 'Pepper'
            ]
        },
        'tilmok': {
            title: 'Tilmok',
            price: '₱750.00',
            image: 'images/tilmok.jpg',
            description: 'A delicacy from Bicol made of crab meat cooked in coconut milk, wrapped in taro leaves and then steamed. This dish showcases the region\'s love for coconut milk and seafood in a unique presentation.',
            serving: '4 people',
            spice: 'Mild',
            prep: '55 minutes',
            ingredients: [
                'Crab meat', 'Coconut milk', 'Taro leaves', 'Garlic', 
                'Onion', 'Ginger', 'Chili', 'Salt', 'Pepper'
            ]
        },
        'adobo': {
            title: 'Pork Adobo',
            price: '₱650.00',
            image: 'images/adobo.jpg',
            description: 'The unofficial national dish of the Philippines. Pork is marinated and simmered in a mixture of soy sauce, vinegar, garlic, bay leaves, and black peppercorns. The result is a savory, slightly tangy meat dish that's both comforting and flavorful.',
            serving: '4-5 people',
            spice: 'Mild',
            prep: '60 minutes',
            ingredients: [
                'Pork belly', 'Soy sauce', 'Vinegar', 'Garlic', 
                'Bay leaves', 'Black peppercorns', 'Sugar', 'Water', 'Cooking oil'
            ]
        },
        'kare-kare': {
            title: 'Kare-Kare',
            price: '₱850.00',
            image: 'images/kare-kare.jpg',
            description: 'A Filipino stew with a thick, savory peanut sauce. It's traditionally made with oxtail, tripe, and vegetables such as eggplant, banana heart, and string beans. Served with a side of bagoong (shrimp paste) for an extra layer of flavor.',
            serving: '5-6 people',
            spice: 'Mild',
            prep: '120 minutes',
            ingredients: [
                'Oxtail', 'Tripe', 'Eggplant', 'String beans', 'Banana heart', 
                'Peanut butter', 'Ground rice', 'Annatto seeds', 'Garlic', 'Onion', 'Bagoong'
            ]
        },
        'crispy-pata': {
            title: 'Crispy Pata',
            price: '₱950.00',
            image: 'images/crispy-pata.jpg',
            description: 'A famous Filipino dish of deep-fried pork leg, first simmered in spices until tender, then deep-fried until the skin becomes crispy. Served with a dipping sauce of soy sauce, vinegar, and chili.',
            serving: '6-8 people',
            spice: 'Mild',
            prep: '150 minutes',
            ingredients: [
                'Whole pork leg', 'Bay leaves', 'Peppercorns', 'Star anise', 
                'Garlic', 'Onion', 'Salt', 'Soy sauce', 'Vinegar', 'Cooking oil'
            ]
        },
        'sinigang': {
            title: 'Sinigang na Baboy',
            price: '₱750.00',
            image: 'images/sinigang.jpg',
            description: 'A sour Filipino soup made with pork, vegetables, and tamarind as the souring agent. The dish perfectly balances sour and savory flavors, creating a comforting soup that's perfect for rainy days.',
            serving: '5 people',
            spice: 'Mild',
            prep: '60 minutes',
            ingredients: [
                'Pork ribs', 'Tamarind', 'Radish', 'String beans', 'Eggplant', 
                'Tomatoes', 'Onion', 'Fish sauce', 'Chili peppers', 'Kangkong leaves'
            ]
        },
        'leche-flan': {
            title: 'Leche Flan',
            price: '₱350.00',
            image: 'images/leche-flan.jpg',
            description: 'A rich and creamy Filipino custard dessert made with egg yolks, condensed milk, and caramelized sugar. This smooth, silky dessert has a perfect balance of sweetness and a delightful caramel topping.',
            serving: '6-8 people',
            spice: 'None',
            prep: '60 minutes',
            ingredients: [
                'Egg yolks', 'Condensed milk', 'Evaporated milk', 'Sugar', 'Vanilla extract', 'Lemon zest'
            ]
        },
        'halo-halo': {
            title: 'Halo-Halo',
            price: '₱180.00',
            image: 'images/halo-halo.jpg',
            description: 'A popular Filipino dessert with layers of shaved ice, sweet beans, jellies, fruits, and ice cream. The name literally means "mix-mix" in Filipino, as you mix all the ingredients together before eating.',
            serving: '1 person',
            spice: 'None',
            prep: '15 minutes',
            ingredients: [
                'Shaved ice', 'Ube ice cream', 'Sweetened beans', 'Nata de coco', 
                'Kaong', 'Macapuno', 'Jackfruit', 'Leche flan', 'Evaporated milk'
            ]
        },
        'chocolate-cake': {
            title: 'Chocolate Cake',
            price: '₱550.00',
            image: 'images/chocolate-cake.jpg',
            description: 'A rich, moist chocolate cake with smooth ganache frosting. Made with premium cocoa and high-quality chocolate for an intense flavor that chocolate lovers will adore.',
            serving: '8-10 people',
            spice: 'None',
            prep: '90 minutes',
            ingredients: [
                'Flour', 'Sugar', 'Cocoa powder', 'Eggs', 'Butter', 
                'Baking powder', 'Milk', 'Vanilla extract', 'Dark chocolate', 'Heavy cream'
            ]
        },
        'red-velvet': {
            title: 'Red Velvet Cake',
            price: '₱650.00',
            image: 'images/red-velvet.jpg',
            description: 'A classic red velvet cake with cream cheese frosting. This elegant dessert features a subtle chocolate flavor with a distinctive red color, topped with rich, tangy cream cheese frosting.',
            serving: '8-10 people',
            spice: 'None',
            prep: '90 minutes',
            ingredients: [
                'Flour', 'Sugar', 'Cocoa powder', 'Eggs', 'Butter', 
                'Buttermilk', 'Vinegar', 'Red food coloring', 'Cream cheese', 'Vanilla extract'
            ]
        },
        'calamansi-juice': {
            title: 'Calamansi Juice',
            price: '₱80.00',
            image: 'images/calamansi-juice.jpg',
            description: 'A refreshing Filipino citrus drink made from freshly squeezed calamansi limes and sweetened with honey or sugar. This tangy beverage is perfect for hot days.',
            serving: '1 person',
            spice: 'None',
            prep: '10 minutes',
            ingredients: [
                'Calamansi limes', 'Honey/Sugar', 'Water', 'Ice'
            ]
        },
        'sago-gulaman': {
            title: 'Sago\'t Gulaman',
            price: '₱90.00',
            image: 'images/sago-gulaman.jpg',
            description: 'A sweet Filipino drink with tapioca pearls (sago) and gelatin (gulaman) in brown sugar syrup. This popular refreshment has a delightful mix of textures and a sweet, caramel-like flavor.',
            serving: '1 person',
            spice: 'None',
            prep: '20 minutes',
            ingredients: [
                'Tapioca pearls', 'Gelatin', 'Brown sugar', 'Vanilla extract', 'Water', 'Ice'
            ]
        },
        'buko-juice': {
            title: 'Buko Juice',
            price: '₱100.00',
            image: 'images/buko-juice.jpg',
            description: 'Fresh coconut water served with strips of young coconut meat. This natural, hydrating beverage is lightly sweetened to enhance the coconut\'s natural flavor.',
            serving: '1 person',
            spice: 'None',
            prep: '15 minutes',
            ingredients: [
                'Young coconut water', 'Young coconut meat', 'Sugar (optional)', 'Ice'
            ]
        },
        'iced-tea': {
            title: 'Iced Tea',
            price: '₱70.00',
            image: 'images/iced-tea.jpg',
            description: 'Homemade sweet iced tea with a hint of lemon. This classic refreshment is brewed from premium tea leaves and perfectly balanced with just the right amount of sweetness.',
            serving: '1 person',
            spice: 'None',
            prep: '15 minutes',
            ingredients: [
                'Black tea', 'Sugar', 'Lemon', 'Water', 'Ice'
            ]
        }
    };

    // Open modal when View Details button is clicked
    const viewDetailsButtons = document.querySelectorAll('.item-button');
    viewDetailsButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent event bubbling to parent menu-item
            const menuItem = this.closest('.menu-item');
            const dishId = menuItem.getAttribute('data-id');
            openDishModal(dishId);
        });
    });

    // Function to open dish modal
    function openDishModal(dishId) {
        const dish = dishData[dishId];
        if (dish) {
            // Populate modal with dish data
            document.getElementById('dish-modal-title').textContent = dish.title;
            document.getElementById('dish-modal-price').textContent = dish.price;
            document.getElementById('dish-modal-img').src = dish.image;
            document.getElementById('dish-modal-img').alt = dish.title;
            document.getElementById('dish-modal-description').textContent = dish.description;
            document.getElementById('dish-modal-serving').textContent = dish.serving;
            document.getElementById('dish-modal-spice').textContent = dish.spice;
            document.getElementById('dish-modal-prep').textContent = dish.prep;

            // Clear and populate ingredients list
            const ingredientsList = document.getElementById('dish-modal-ingredients');
            ingredientsList.innerHTML = '';
            dish.ingredients.forEach(ingredient => {
                const li = document.createElement('li');
                li.textContent = ingredient;
                ingredientsList.appendChild(li);
            });

            // Set up order button
            orderButton.setAttribute('data-id', dishId);
            orderButton.setAttribute('data-name', dish.title);
            orderButton.setAttribute('data-price', dish.price.replace('₱', ''));

            // Show modal
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        }
    }

    // Close modal when X button is clicked
    if (closeBtn) {
        closeBtn.addEventListener('click', closeDishModal);
    }

    // Close modal when Close button is clicked
    if (closeButton) {
        closeButton.addEventListener('click', closeDishModal);
    }

    // Close modal when clicking outside the modal content
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeDishModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && modal.style.display === 'block') {
            closeDishModal();
        }
    });

    // Function to close dish modal
    function closeDishModal() {
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto'; // Re-enable scrolling
        }
    }

    // Add to order from modal
    if (orderButton) {
        orderButton.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const price = this.getAttribute('data-price');
            
            cart.addItem(id, name, price);
            alert(`${name} added to your order!`);
            closeDishModal();
        });
    }

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
});