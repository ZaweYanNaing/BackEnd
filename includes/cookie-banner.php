<?php
// Cookie consent banner component
?>

<!-- Cookie Consent Banner -->
<div id="cookieBanner" class="fixed bottom-0 left-0 right-0 bg-sky-700 shadow-lg z-100 hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex-1">
                <h3 class="text-2xl font-semibold text-gray-300 mb-3">We use cookies</h3>
                <p class="text-sm text-gray-300">
                    We use cookies to enhance your experience, analyze site traffic, and personalize content. 
                    By clicking "Accept All", you consent to our use of cookies. 
                    <a href="index.php?page=cookies" class="text-green-400 hover:text-green-500 underline">Learn more</a>
                </p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <button onclick="showCookieSettings()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Cookie Settings
                </button>
                <button onclick="acceptAllCookies()" 
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Accept All
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Cookie Settings Modal -->
<div id="cookieSettingsModal" class="fixed inset-0 bg-gray-200 hidden z-100">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-2xl font-bold text-gray-900">Cookie Settings</h3>
                <button onclick="closeCookieSettings()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <p class="text-gray-600 mb-6">
                    We use different types of cookies to optimize your experience on our platform. 
                    You can choose which categories you want to allow.
                </p>
                
                <div class="space-y-6">
                    <!-- Essential Cookies -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-900">Essential Cookies</h4>
                                <p class="text-sm text-gray-600">Required for basic site functionality</p>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="essentialCookies" checked disabled
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="essentialCookies" class="ml-2 text-sm text-gray-600">Always Active</label>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500">
                            These cookies are necessary for the website to function and cannot be switched off. 
                            They are usually only set in response to actions made by you which amount to a request for services.
                        </p>
                    </div>
                    
                    <!-- Analytics Cookies -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-900">Analytics Cookies</h4>
                                <p class="text-sm text-gray-600">Help us understand how visitors interact with our website</p>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="analyticsCookies" 
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="analyticsCookies" class="ml-2 text-sm text-gray-600">Optional</label>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500">
                            These cookies allow us to count visits and traffic sources so we can measure and improve the performance of our site.
                        </p>
                    </div>
                    
                    <!-- Marketing Cookies -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-900">Marketing Cookies</h4>
                                <p class="text-sm text-gray-600">Used to track visitors across websites for advertising</p>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="marketingCookies" 
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="marketingCookies" class="ml-2 text-sm text-gray-600">Optional</label>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500">
                            These cookies may be set through our site by our advertising partners to build a profile of your interests.
                        </p>
                    </div>
                    
                    <!-- Functional Cookies -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-900">Functional Cookies</h4>
                                <p class="text-sm text-gray-600">Enable enhanced functionality and personalization</p>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="functionalCookies" 
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="functionalCookies" class="ml-2 text-sm text-gray-600">Optional</label>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500">
                            These cookies enable the website to provide enhanced functionality and personalization.
                        </p>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <button onclick="rejectAllCookies()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Reject All
                    </button>
                    <button onclick="saveCookiePreferences()" 
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                        Save Preferences
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Cookie management functions
function showCookieBanner() {
    if (!getCookie('cookieConsent')) {
        document.getElementById('cookieBanner').classList.remove('hidden');
    }
}

function hideCookieBanner() {
    document.getElementById('cookieBanner').classList.add('hidden');
}

function showCookieSettings() {
    document.getElementById('cookieSettingsModal').classList.remove('hidden');
}

function closeCookieSettings() {
    document.getElementById('cookieSettingsModal').classList.add('hidden');
}

function acceptAllCookies() {
    setCookie('cookieConsent', 'all', 1/24); // 1 hour
    setCookie('analyticsCookies', 'true', 1/24);
    setCookie('marketingCookies', 'true', 1/24);
    setCookie('functionalCookies', 'true', 1/24);
    hideCookieBanner();
    initializeCookies();
}

function rejectAllCookies() {
    setCookie('cookieConsent', 'essential', 1/24); // 1 hour
    setCookie('analyticsCookies', 'false', 1/24);
    setCookie('marketingCookies', 'false', 1/24);
    setCookie('functionalCookies', 'false', 1/24);
    hideCookieBanner();
    closeCookieSettings();
    initializeCookies();
}

function saveCookiePreferences() {
    const analytics = document.getElementById('analyticsCookies').checked;
    const marketing = document.getElementById('marketingCookies').checked;
    const functional = document.getElementById('functionalCookies').checked;
    
    setCookie('cookieConsent', 'custom', 1/24); // 1 hour
    setCookie('analyticsCookies', analytics.toString(), 1/24);
    setCookie('marketingCookies', marketing.toString(), 1/24);
    setCookie('functionalCookies', functional.toString(), 1/24);
    
    hideCookieBanner();
    closeCookieSettings();
    initializeCookies();
}

// Cookie utility functions
function setCookie(name, value, days) {
    const expires = new Date();
    expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
    document.cookie = name + '=' + value + ';expires=' + expires.toUTCString() + ';path=/';
}

function getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

// Initialize cookies based on user preferences
function initializeCookies() {
    const analytics = getCookie('analyticsCookies') === 'true';
    const marketing = getCookie('marketingCookies') === 'true';
    const functional = getCookie('functionalCookies') === 'true';
    
    // Initialize Google Analytics if analytics cookies are accepted
    if (analytics) {
        // Add Google Analytics code here
        console.log('Analytics cookies enabled');
    }
    
    // Initialize marketing tools if marketing cookies are accepted
    if (marketing) {
        // Add marketing tools code here
        console.log('Marketing cookies enabled');
    }
    
    // Initialize functional features if functional cookies are accepted
    if (functional) {
        // Add functional features code here
        console.log('Functional cookies enabled');
    }
}

// Show cookie banner on page load
document.addEventListener('DOMContentLoaded', function() {
    showCookieBanner();
    initializeCookies();
});

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    const cookieSettingsModal = document.getElementById('cookieSettingsModal');
    if (event.target === cookieSettingsModal) {
        closeCookieSettings();
    }
});
</script>
