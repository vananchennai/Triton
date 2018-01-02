function formhash(form, password) {
   // Create a new element input, this will be out hashed password field.

   var p = document.createElement("input");
   // Add the new element to our form.
   form.appendChild(p);
   p.name = "p";
   p.type = "hidden"
   p.value = hex_sha512(password.value);
   // Make sure the plaintext password doesn't get sent.
  // password.value = "";
	
   // Finally submit the form.
   form.submit();
}

function formhashed(form, password) {
   // Create a new element input, this will be out hashed password field.

   var fp = document.createElement("input");
   // Add the new element to our form.
   form.appendChild(fp);
   fp.name = "fp";
   fp.type = "hidden"
   fp.value = hex_sha512(password.value);
   // Make sure the plaintext password doesn't get sent.
  // password.value = "";
	
   // Finally submit the form.
   form.submit();
}