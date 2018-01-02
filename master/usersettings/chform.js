function formhashchange(form, newpassword, oldpassword) {
   // Create a new element input, this will be out hashed password field.

   var p = document.createElement("input");
   // Add the new element to our form.
   form.appendChild(p);
   p.name = "p";
   p.type = "hidden"
   p.value = hex_sha512(newpassword.value);
   // Make sure the plaintext password doesn't get sent.
	var op = document.createElement("input");
   // Add the new element to our form.
   form.appendChild(op);
   op.name = "op";
   op.type = "hidden"
   op.value = hex_sha512(oldpassword.value);
   // Finally submit the form.

   form.submit();
}// JavaScript Document