require('./bootstrap');


const form = document.querySelector('.formRegister');

form.addEventListener('submit', (e) =>{
   

    // get response from captcha
    const captchaResponse = grecaptcha.getResponse();


    // if there are not response from captcha then show error message that is necessary

    if( !captchaResponse.length > 0 ) {

        // dont continue if recaptcha isnt complete
        e.preventDefault();
        alert('Please Complete the Recaptcha to proceed')
    } 
    
    // else , continue the normal ejecution


});

// method="post" action="register"