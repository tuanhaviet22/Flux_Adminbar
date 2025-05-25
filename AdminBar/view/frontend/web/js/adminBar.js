define([
    'jquery'
], function ($) {
    'use strict';
    
    return function(config) {
        return {
            init() {
                // Alpine.js component data
                window.adminBarData = {
                    isLoggedIn: false,
                    userId: null,
                    isLoading: true,
                    visible: false,
                    
                    // Initialize the component
                    init() {
                        this.checkAdminStatus();
                    },
                    
                    // Check admin authentication status
                    checkAdminStatus() {
                        this.isLoading = true;
                        
                        fetch(config.statusUrl, {
                            method: 'GET',
                            credentials: 'same-origin'
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.isLoggedIn = data.isLoggedIn;
                            this.userId = data.userId;
                            this.visible = data.isLoggedIn;
                            this.isLoading = false;
                        })
                        .catch(error => {
                            console.error('Admin status check failed:', error);
                            this.isLoading = false;
                        });
                    }
                };
                
                // Initialize Alpine.js if not already done
                if (typeof Alpine !== 'undefined') {
                    // Alpine is available globally
                    Alpine.data('adminBar', () => window.adminBarData);
                } else {
                    console.warn('Alpine.js is not loaded. Admin bar may not function correctly.');
                }
            }
        };
    };
}); 