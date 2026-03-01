/**
 * Client-side Form Validation
 * Real-time validation with user-friendly messages
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeFormValidation();
    initializeNumericInputs();
    initializePhoneValidation();
    initializeMenuToggle();
});

function initializeFormValidation() {
    const forms = document.querySelectorAll('form[novalidate]');
    
    forms.forEach(form => {
        form.addEventListener('submit', validateForm);
        
        // Real-time validation on blur
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', () => validateField(input));
            input.addEventListener('input', () => clearFieldError(input));
        });
    });
}

function validateForm(event) {
    const form = event.target;
    let isValid = true;
    
    // Prevent HTML5 validation
    event.preventDefault();
    
    // Validate all required fields
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });
    
    // Validate specific form types
    if (form.id === 'orderForm') {
        isValid = validateOrderForm(form) && isValid;
    }
    
    if (isValid) {
        form.submit();
    }
}

function validateField(field) {
    clearFieldError(field);
    
    let isValid = true;
    let errorMessage = '';
    
    // Required field validation
    if (field.hasAttribute('required') && !field.value.trim()) {
        isValid = false;
        errorMessage = 'This field is required';
    }
    
    // Type-specific validation
    if (isValid && field.value.trim()) {
        switch (field.type) {
            case 'email':
                isValid = validateEmail(field.value);
                errorMessage = 'Please enter a valid email address';
                break;
                
            case 'tel':
                isValid = validatePhone(field.value);
                errorMessage = 'Phone number must have at least 10 digits';
                break;
                
            case 'number':
                isValid = validateNumber(field);
                errorMessage = 'Please enter a valid number';
                break;
        }
    }
    
    // Pattern validation
    if (isValid && field.pattern) {
        const pattern = new RegExp(field.pattern);
        if (!pattern.test(field.value)) {
            isValid = false;
            errorMessage = field.title || 'Invalid format';
        }
    }
    
    // Length validation
    if (isValid && field.minLength && field.value.length < field.minLength) {
        isValid = false;
        errorMessage = `Minimum length is ${field.minLength} characters`;
    }
    
    if (!isValid) {
        showFieldError(field, errorMessage);
    }
    
    return isValid;
}

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePhone(phone) {
    // Remove non-digits and check length
    const digits = phone.replace(/\D/g, '');
    return digits.length >= 10;
}

function validateNumber(field) {
    const value = parseFloat(field.value);
    
    if (isNaN(value)) return false;
    
    // Check min attribute
    if (field.min && value < parseFloat(field.min)) {
        return false;
    }
    
    // Check max attribute
    if (field.max && value > parseFloat(field.max)) {
        return false;
    }
    
    // Check step attribute
    if (field.step && field.step !== 'any') {
        const step = parseFloat(field.step);
        const remainder = (value * 100) % (step * 100);
        if (remainder > 0.01) {
            return false;
        }
    }
    
    return true;
}

function validateOrderForm(form) {
    let isValid = true;
    
    const quantity = parseFloat(form.quantity?.value);
    const price = parseFloat(form.unit_price?.value);
    
    if (quantity <= 0) {
        showFieldError(form.quantity, 'Quantity must be greater than 0');
        isValid = false;
    }
    
    if (price <= 0) {
        showFieldError(form.unit_price, 'Price must be greater than 0');
        isValid = false;
    }
    
    return isValid;
}

function showFieldError(field, message) {
    field.classList.add('is-invalid');
    
    // Check if error message already exists
    let feedback = field.nextElementSibling;
    if (!feedback || !feedback.classList.contains('invalid-feedback')) {
        feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        field.parentNode.insertBefore(feedback, field.nextSibling);
    }
    
    feedback.textContent = message;
}

function clearFieldError(field) {
    field.classList.remove('is-invalid');
    
    // Remove error message if exists
    const feedback = field.nextElementSibling;
    if (feedback && feedback.classList.contains('invalid-feedback')) {
        feedback.remove();
    }
}

function initializeNumericInputs() {
    const numericInputs = document.querySelectorAll('input[type="number"]');
    
    numericInputs.forEach(input => {
        // Prevent negative sign and 'e' character
        input.addEventListener('keypress', (e) => {
            if (e.key === '-' || e.key === 'e') {
                e.preventDefault();
            }
        });
        
        // Validate on blur
        input.addEventListener('blur', () => {
            const value = parseFloat(input.value);
            const min = parseFloat(input.min);
            
            if (input.value && (isNaN(value) || (min && value < min))) {
                input.value = min || '';
            }
        });
    });
}

function initializePhoneValidation() {
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    
    phoneInputs.forEach(input => {
        input.addEventListener('input', (e) => {
            // Only allow digits
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    });
}

function initializeMenuToggle() {
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');
    
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            
            // Animate hamburger icon
            const spans = navToggle.querySelectorAll('span');
            spans.forEach(span => span.classList.toggle('active'));
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
                navMenu.classList.remove('active');
            }
        });
    }
}

/**
 * Confirmation dialog for delete actions
 */
function confirmDelete(message) {
    return confirm(message || 'Are you sure you want to delete this item? This action cannot be undone.');
}

/**
 * Show notification message
 */
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.textContent = message;
    
    const container = document.querySelector('.container');
    if (container) {
        container.insertBefore(notification, container.firstChild);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }
}