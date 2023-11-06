console.log("hello world");


// Close update form
const closeEditButton = document.querySelector('#close-edit');

if (closeEditButton) {
    closeEditButton.onclick = () => {
        document.querySelector('.edit-form-container').style.display = 'none';
        window.location.href = 'admin.php';
    };
}

// add new fruits textbox
const productSelect = document.getElementById("productSelect");
const newFruitContainer = document.getElementById("newFruitContainer");
const newFruitInput = document.querySelector("#newFruitContainer input[name='new_fruit_name']");

if (productSelect) {
    productSelect.addEventListener("change", function () {
        const selectedOption = productSelect.value;
        if (selectedOption === "addNew") {
            newFruitContainer.style.display = "block";
            newFruitInput.name = "p_name";
        } else {
            newFruitContainer.style.display = "none";
            newFruitInput.name = "new_fruit_name";
        }
    });
}

// admin code textbox
var userTypeElement = document.getElementById("user_type");

if (userTypeElement) {
    userTypeElement.addEventListener("change", function() {
        var adminCodeInput = document.getElementById("admin-code");
        if (this.value === "admin") {
            adminCodeInput.style.display = "block"; // Show the admin code input
        } else {
            adminCodeInput.style.display = "none"; // Hide the admin code input
        }
    });
}

// check for email format
function validateEmail() {
    var emailInput = document.getElementById('email');
    var emailError = document.getElementById('emailError');

    // Define individual regex components
    var usernameRegex = /^[a-zA-Z0-9._-]+/;
    var atSymbolRegex = /@/;
    var email = emailInput.value;

    if (email.trim() === "") {
        emailError.style.display = 'none'; // Hide the error message when email is empty
    } else if (!usernameRegex.test(email)) {
        emailError.textContent = 'Invalid email format. The username is invalid.';
        emailError.style.display = 'block';
        return false; // Cancel form submission
    } else if (!atSymbolRegex.test(email)) {
        emailError.textContent = 'Invalid email format. Missing the "@" symbol.';
        emailError.style.display = 'block';
        return false; // Cancel form submission
    } else {
        var parts = email.split('@');
        
        if (parts.length !== 2) {
            emailError.textContent = 'Invalid email format. There should be exactly one "@" symbol.';
            emailError.style.display = 'block';
            return false; // Cancel form submission
        }
        
        if (/[0-9]/.test(parts[1][0])) {
            emailError.textContent = 'Invalid email format. No numbers are allowed immediately after "@" symbol.';
            emailError.style.display = 'block';
            return false; // Cancel form submission
        }
        
        if (parts[1] !== "localhost") {
            emailError.textContent = 'Invalid email format. The domain should be "localhost".';
            emailError.style.display = 'block';
            return false; // Cancel form submission
        }
        
        emailError.style.display = 'none'; // Clear error message
        return true; // Allow form submission
    }
}












