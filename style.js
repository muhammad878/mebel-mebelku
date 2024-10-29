(function(){
    emailjs.init("YOUR_USER_ID"); 
})();


document.getElementById('myForm').addEventListener('submit', function(event) {
    event.preventDefault();

 
    emailjs.sendForm('YOUR_SERVICE_ID', 'YOUR_TEMPLATE_ID', this)
        .then(function() {
            document.getElementById('notification').style.display = 'block';
            document.getElementById('myForm').reset();
        }, function(error) {
            console.error('Error sending email:', error);
        });
});


// untuk ke gmail nya belum bisa karena emailjs nya susah login