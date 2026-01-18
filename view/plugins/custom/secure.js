
        // Function to redirect after 60 seconds
        setTimeout(function() {
            window.location.href = "https://assoec.org/view/verifyClearance.php"; // Change the URL to wherever you want to redirect
        }, 60000); // 60 seconds

        // Detect print screen attempt (this is an approximation and might not work for all scenarios)
        document.addEventListener('keydown', function(event) {
            // 44 is the keycode for "Print Screen"
            if (event.keyCode === 44) {
                document.getElementById('warningMessage').style.display = 'block';
                setTimeout(function() {
                    document.getElementById('warningMessage').style.display = 'none';
                }, 3000); // Show warning for 3 seconds
            }
        });

        // Disable right-click (context menu)
        document.addEventListener('contextmenu', function(event) {
            event.preventDefault();
            document.getElementById('warningMessage').style.display = 'block';
            setTimeout(function() {
                document.getElementById('warningMessage').style.display = 'none';
            }, 3000); // Show warning for 3 seconds
        });

        // Block some common keyboard shortcuts (for screen capture or inspect element)
        document.addEventListener('keydown', function(event) {
            if (event.keyCode == 123 || (event.ctrlKey && event.shiftKey && event.keyCode == 73) || (event.ctrlKey && event.keyCode == 85)) {
                // Prevent the default behavior (F12, Ctrl+Shift+I, Ctrl+U)
                event.preventDefault();
                document.getElementById('warningMessage').style.display = 'block';
                setTimeout(function() {
                    document.getElementById('warningMessage').style.display = 'none';
                }, 3000); // Show warning for 3 seconds
            }
        });
