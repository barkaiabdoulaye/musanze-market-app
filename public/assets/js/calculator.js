/**
 * Live Calculator for Order Totals
 * Real-time calculation of order amounts
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeOrderCalculator();
    initializeQuantityValidation();
});

function initializeOrderCalculator() {
    const quantityInput = document.getElementById('quantity');
    const priceInput = document.getElementById('unit_price');
    const totalDisplay = document.getElementById('totalDisplay');
    
    if (!quantityInput || !priceInput || !totalDisplay) {
        return;
    }
    
    function calculateTotal() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const total = quantity * price;
        
        // Format as currency
        const formattedTotal = new Intl.NumberFormat('en-RW', {
            style: 'currency',
            currency: 'RWF',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(total);
        
        totalDisplay.textContent = formattedTotal;
        
        // Add visual feedback
        if (total <= 0) {
            totalDisplay.style.color = 'var(--danger)';
        } else {
            totalDisplay.style.color = 'var(--success)';
        }
    }
    
    // Add event listeners
    quantityInput.addEventListener('input', calculateTotal);
    priceInput.addEventListener('input', calculateTotal);
    
    // Initial calculation
    calculateTotal();
}

function initializeQuantityValidation() {
    const quantityInput = document.getElementById('quantity');
    
    if (!quantityInput) return;
    
    quantityInput.addEventListener('blur', function() {
        let value = parseFloat(this.value);
        
        if (isNaN(value) || value < 0) {
            this.value = '';
        }
    });
}

/**
 * Advanced Calculator for multiple items
 * (Future enhancement)
 */
class OrderCalculator {
    constructor() {
        this.items = [];
        this.totals = {
            subtotal: 0,
            tax: 0,
            total: 0
        };
    }
    
    addItem(quantity, price, description = '') {
        this.items.push({
            quantity: parseFloat(quantity) || 0,
            price: parseFloat(price) || 0,
            description: description,
            total: (parseFloat(quantity) || 0) * (parseFloat(price) || 0)
        });
        
        this.recalculate();
    }
    
    removeItem(index) {
        if (index >= 0 && index < this.items.length) {
            this.items.splice(index, 1);
            this.recalculate();
        }
    }
    
    recalculate() {
        this.totals.subtotal = this.items.reduce((sum, item) => sum + item.total, 0);
        this.totals.total = this.totals.subtotal;
        
        this.updateDisplay();
    }
    
    updateDisplay() {
        // Update UI if needed
        const event = new CustomEvent('orderUpdated', { detail: this.totals });
        document.dispatchEvent(event);
    }
}