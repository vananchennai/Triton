function formhash(form, password) {
   // Create a new element input, this will be out hashed password field.

   var p = document.createElement("input");
   // Add the new element to our form.
   
   form.appendChild(p);
    
   p.name = "p";
   
   p.type = "hidden"
 
   p.value = hex_sha512(password);

   // Make sure the plaintext password doesn't get sent.
  
   // Finally submit the form.
   form.submit();
}

// JavaScript Document