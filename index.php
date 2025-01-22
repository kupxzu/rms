<?php session_start(); ?>
<html>
<body>
<head> 

<link rel="stylesheet" href="style.css">
</head>



<div class="container" id="container">
	<div class="form-container sign-up-container" >
		<form action="register.php" method="POST" >
		<img src="includes/www.png" alt="">

			<h1>LGU ENRILE</h1>
			<span>Tuguegarao, Cagayan</span>
			<input type="text" name="name" placeholder="Name" required hidden />
			<input type="email" name="email" placeholder="Email" required hidden/>
			<input type="password" name="password" placeholder="Password" required hidden/>
			<button type="submit" hidden>Sign Up</button>
		</form>
	</div>
	<div class="form-container sign-in-container">
    <form action="function.php" method="POST">
        <h1>Sign in</h1>

        <span hidden>or use your account</span>
        <input type="text" name="username" placeholder="Username" required />

        <!-- Password input with show/hide functionality (CSS only) -->
        <div class="password-wrapper">
            <input type="password" name="password" id="password" class="password-input" placeholder="Password" required />
            <input type="checkbox" id="togglePassword" class="toggle-checkbox">
            <label for="togglePassword" class="toggle-label">
                <i class="fas fa-eye"></i>
                <i class="fas fa-eye-slash"></i>
            </label>
        </div>

        <?php
        if (isset($_SESSION['error'])) {
            echo "<div style='color: red; margin-bottom: 10px; font-size: 15px;'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']); // Clear error message after displaying it
        }
        ?>

        <a href="#" hidden>Forgot your password?</a>
        <button type="submit">Sign In</button>
    </form>
</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Welcome Back!</h1>
				<p>To keep connected with us please login with your personal info</p>
				<button class="ghost" id="signIn">Sign In</button>
			</div>
			<div class="overlay-panel overlay-right">
				<img src="includes/www.png" alt="">
				<h1>LGU ENRILE</h1>
				<p>Reecord Management System</p>
				<button class="ghost" id="signUp">Read More</button>
			</div>
		</div>
	</div>
</div>


<script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/js/all.min.js"></script>

<script>
    document.getElementById("togglePassword").addEventListener("click", function () {
        var passwordField = document.getElementById("password");
        var icon = this.querySelector("i");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    });
</script>
<script src="function.js"></script>

<footer>

</footer>
</html>
</body>