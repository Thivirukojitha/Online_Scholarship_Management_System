function validateRegistration() {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirm_password").value;

    if (password !== confirmPassword) {
        alert("Error: Passwords do not match!");
        return false;
    }
    return true;
}