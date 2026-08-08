function validateRegistration() {
    var fullName = document.getElementById("full_name").value.trim();
    var age = document.getElementById("age").value.trim();
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirm_password").value;

    if (fullName === "") {
        alert("Error: Full name is required.");
        return false;
    }

    if (age === "" || isNaN(age) || Number(age) <= 0) {
        alert("Error: Please enter a valid age.");
        return false;
    }

    if (password !== confirmPassword) {
        alert("Error: Passwords do not match!");
        return false;
    }
    return true;
}