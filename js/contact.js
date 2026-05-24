function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast ${type}`;
    toast.style.display = 'block';
    
    setTimeout(() => {
        toast.style.display = 'none';
    }, 3000);
}

function submitForm(event) {
    event.preventDefault();
    
    // Get form data
    const form = document.getElementById('contactForm');
    
    // Validate required fields
    if (!form.name.value || !form.phone.value || !form.message.value) {
        showToast('Please fill in all required fields', 'error');
        return false;
    }

    const formData = new FormData(form);
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Sending...';

    // Submit form
    fetch('../php/submit_contact.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Server response:', data);
        if (data.success) {
            showToast(data.message, 'success');
            form.reset(); // Clear the form
        } else {
            showToast(data.message || 'Error sending message', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error sending message. Please try again.', 'error');
    })
    .finally(() => {
        // Reset button state
        submitBtn.disabled = false;
        submitBtn.textContent = 'Send Message';
    });

    return false;
} 