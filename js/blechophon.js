  // decrypt helper function
function decryptCharcode(n,start,end,offset) {
	n = n + offset;
	if (offset > 0 && n > end)	{
		n = start + (n - end - 1);
	} else if (offset < 0 && n < start)	{
		n = end - (start - n - 1);
	}
	return String.fromCharCode(n);
}
  // decrypt string
function decryptString(enc,offset) {
	var dec = "";
	var len = enc.length;
	for(var i=0; i < len; i++)	{
		var n = enc.charCodeAt(i);
		if (n >= 48 && n <= 57)	{  // 0-9
			dec += decryptCharcode(n,48,57,offset);
		} else if (n >= 65 && n <= 90)	{ //A..Z
			dec += decryptCharcode(n,65,90,offset);
		} else if (n >= 97 && n <= 122)	{
			dec += decryptCharcode(n,97,122,offset);	// a-z
		} else {
			dec += enc.charAt(i);
		}
	}
	return dec;
}
  // decrypt spam-protected emails
function linkTo_UnCryptMailto(s)	{
	location.href = decryptString(s,-1);
}

function askDelete() {
  if (confirm("Wirklich löschen?")){
    return true;
  } else {
    return false;
  }
}

function askDeaktivate() {
  if (confirm("Wirklich deaktivieren?\nKind wird nicht gelöscht sondern deaktiviert!\nFür erneute Aktivierung Admin Fragen NICHT EINFACH NEU EINTRAGEN!!")){
    return true;
  } else {
    return false;
  }
}

function checkEmail(fieldid) {
  if ((jQuery("#" + fieldid).val().length != 0) &&
      ((jQuery("#" + fieldid).val().indexOf('@') == -1) ||
       (jQuery("#" + fieldid).val().indexOf('@') == 0) ||
       (jQuery("#" + fieldid).val().indexOf('@') == (jQuery("#" + fieldid).val().length - 1)))){
      
        alert("E-Mail adresse ungültig: entweder leer oder korrekte E-Mailadresse oder \nmehrere korrekte E-Milaadressen mit Semikolon (;) getrennt");
        jQuery("#" + fieldid).focus();
        return false;
      }
  return true;
}

function checkBirthdate(fieldid) {
  if (jQuery("#" + fieldid).val().length != 10 ||
      jQuery("#" + fieldid).val().charAt(4) != '-' ||
      jQuery("#" + fieldid).val().charAt(7) != '-' ||
      jQuery("#" + fieldid).val().charAt(0) < '0' || jQuery("#" + fieldid).val().charAt(0) > '2' ||
      jQuery("#" + fieldid).val().charAt(1) < '0' || jQuery("#" + fieldid).val().charAt(1) > '9' ||
      jQuery("#" + fieldid).val().charAt(2) < '0' || jQuery("#" + fieldid).val().charAt(2) > '9' ||
      jQuery("#" + fieldid).val().charAt(3) < '0' || jQuery("#" + fieldid).val().charAt(3) > '9' ||
      jQuery("#" + fieldid).val().charAt(5) < '0' || jQuery("#" + fieldid).val().charAt(5) > '1' ||
      jQuery("#" + fieldid).val().charAt(6) < '0' || jQuery("#" + fieldid).val().charAt(6) > '9' ||
      jQuery("#" + fieldid).val().charAt(8) < '0' || jQuery("#" + fieldid).val().charAt(8) > '3' ||
      jQuery("#" + fieldid).val().charAt(9) < '0' || jQuery("#" + fieldid).val().charAt(9) > '9'
      ) {
      
      alert("Geburtsdatum ungültig!\nFormat: yyyy-mm-dd\nWenn das Geburtsdatum nicht bekannt ist, dann 0001-01-01 eingeben");
      jQuery("#" + fieldid).focus();
      return false;
  }
  return true;
}