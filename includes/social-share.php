<?php
// Social media sharing component
function getSocialShareLinks($url, $title, $description = '') {
    $encodedUrl = urlencode($url);
    $encodedTitle = urlencode($title);
    $encodedDescription = urlencode($description);
    
    return [
        'facebook' => "https://www.facebook.com/sharer/sharer.php?u={$encodedUrl}",
        'twitter' => "https://twitter.com/intent/tweet?url={$encodedUrl}&text={$encodedTitle}",
        'linkedin' => "https://www.linkedin.com/sharing/share-offsite/?url={$encodedUrl}",
        'pinterest' => "https://pinterest.com/pin/create/button/?url={$encodedUrl}&description={$encodedDescription}",
        'whatsapp' => "https://wa.me/?text={$encodedTitle}%20{$encodedUrl}",
        'email' => "mailto:?subject={$encodedTitle}&body={$encodedDescription}%20{$encodedUrl}"
    ];
}
?>

<!-- Social Share Buttons -->
<div class="social-share-buttons flex flex-wrap gap-2">
    <a href="<?php echo getSocialShareLinks($url ?? '', $title ?? '', $description ?? '')['facebook']; ?>" 
       target="_blank" rel="noopener noreferrer"
       class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
        <i class="fab fa-facebook-f mr-2"></i>
        Facebook
    </a>
    
    <a href="<?php echo getSocialShareLinks($url ?? '', $title ?? '', $description ?? '')['twitter']; ?>" 
       target="_blank" rel="noopener noreferrer"
       class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-400 rounded-md hover:bg-blue-500 transition-colors">
        <i class="fab fa-twitter mr-2"></i>
        Twitter
    </a>
    
    <a href="<?php echo getSocialShareLinks($url ?? '', $title ?? '', $description ?? '')['linkedin']; ?>" 
       target="_blank" rel="noopener noreferrer"
       class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-700 rounded-md hover:bg-blue-800 transition-colors">
        <i class="fab fa-linkedin-in mr-2"></i>
        LinkedIn
    </a>
    
    <a href="<?php echo getSocialShareLinks($url ?? '', $title ?? '', $description ?? '')['pinterest']; ?>" 
       target="_blank" rel="noopener noreferrer"
       class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors">
        <i class="fab fa-pinterest-p mr-2"></i>
        Pinterest
    </a>
    
    <a href="<?php echo getSocialShareLinks($url ?? '', $title ?? '', $description ?? '')['whatsapp']; ?>" 
       target="_blank" rel="noopener noreferrer"
       class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors">
        <i class="fab fa-whatsapp mr-2"></i>
        WhatsApp
    </a>
    
    <a href="<?php echo getSocialShareLinks($url ?? '', $title ?? '', $description ?? '')['email']; ?>" 
       class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-gray-600 rounded-md hover:bg-gray-700 transition-colors">
        <i class="fas fa-envelope mr-2"></i>
        Email
    </a>
</div>

<!-- Social Media Follow Buttons -->
<div class="social-follow-buttons flex flex-wrap gap-3 mt-4">
    <a href="https://facebook.com/foodfusion" target="_blank" rel="noopener noreferrer"
       class="inline-flex items-center justify-center w-10 h-10 text-white bg-blue-600 rounded-full hover:bg-blue-700 transition-colors">
        <i class="fab fa-facebook-f"></i>
    </a>
    
    <a href="https://twitter.com/foodfusion" target="_blank" rel="noopener noreferrer"
       class="inline-flex items-center justify-center w-10 h-10 text-white bg-blue-400 rounded-full hover:bg-blue-500 transition-colors">
        <i class="fab fa-twitter"></i>
    </a>
    
    <a href="https://instagram.com/foodfusion" target="_blank" rel="noopener noreferrer"
       class="inline-flex items-center justify-center w-10 h-10 text-white bg-pink-600 rounded-full hover:bg-pink-700 transition-colors">
        <i class="fab fa-instagram"></i>
    </a>
    
    <a href="https://youtube.com/foodfusion" target="_blank" rel="noopener noreferrer"
       class="inline-flex items-center justify-center w-10 h-10 text-white bg-red-600 rounded-full hover:bg-red-700 transition-colors">
        <i class="fab fa-youtube"></i>
    </a>
    
    <a href="https://pinterest.com/foodfusion" target="_blank" rel="noopener noreferrer"
       class="inline-flex items-center justify-center w-10 h-10 text-white bg-red-500 rounded-full hover:bg-red-600 transition-colors">
        <i class="fab fa-pinterest-p"></i>
    </a>
</div>

<script>
// Social media sharing functionality
function shareOnSocial(platform, url, title, description) {
    const shareLinks = {
        facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`,
        twitter: `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`,
        linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`,
        pinterest: `https://pinterest.com/pin/create/button/?url=${encodeURIComponent(url)}&description=${encodeURIComponent(description)}`,
        whatsapp: `https://wa.me/?text=${encodeURIComponent(title)}%20${encodeURIComponent(url)}`,
        email: `mailto:?subject=${encodeURIComponent(title)}&body=${encodeURIComponent(description)}%20${encodeURIComponent(url)}`
    };
    
    if (shareLinks[platform]) {
        window.open(shareLinks[platform], '_blank', 'width=600,height=400');
    }
}

// Copy link to clipboard
function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('Link copied to clipboard!', 'success');
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showToast('Link copied to clipboard!', 'success');
    }
}

// Toast notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-4 py-2 rounded-md text-white z-50 ${
        type === 'success' ? 'bg-green-600' : 
        type === 'error' ? 'bg-red-600' : 
        'bg-blue-600'
    }`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        document.body.removeChild(toast);
    }, 3000);
}
</script>
