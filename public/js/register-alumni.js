function CheckForm() {

    if (!document.frmConfirm.frmNama.value) {
        alert('Masukkan nama anda.');
    	document.frmConfirm.frmNama.focus();
    	return false;
	} else if (!document.frmConfirm.frmNpm.value) {
        alert('Masukkan NPM anda.');
    	document.frmConfirm.frmNpm.focus();
    	return false;
	} else if (!CheckJK()) {
        alert('Pilih jenis kelamin anda.');
    	return false;
	} else if (!CheckJob()) {
  	   	alert('Pilih agama anda.');
  	   	return false;
    } else if (!document.frmConfirm.frmOtherSpec.value && document.getElementById('rdOth').checked == true) {
	    alert('Masukkan agama anda.');
	   	document.frmConfirm.frmOtherSpec.focus();
	   	return false;
	} else if (!document.frmConfirm.frmTempatLahir.value) {
        alert('Masukkan tempat lahir anda.');
    	document.frmConfirm.frmTempatLahir.focus();
    	return false;
	} else if (document.frmConfirm.tgl.value == 0) {
        alert('Pilih tanggal kelahiran anda.');
    	document.frmConfirm.tgl.focus();
    	return false;
	} else if (document.frmConfirm.bln.value == 0) {
        alert('Pilih bulan kelahiran anda.');
    	document.frmConfirm.bln.focus();
    	return false;
	} else if (!document.frmConfirm.th.value) {
        alert('Masukkan tahun kelahiran anda.');
    	document.frmConfirm.th.focus();
    	return false;
	} else if (!CheckStatus()) {
		alert('Pilih status anda.');
		return false;
	} else if (!document.frmConfirm.frmAlamat.value) {
		alert('Masukkan alamat lengkap anda.');
		document.frmConfirm.frmAlamat.focus();
		return false;
	} else if (!document.frmConfirm.frmCity.value) {
		alert('Masukkan kota anda.');
		document.frmConfirm.frmCity.focus();
		return false;
	} else if (!document.frmConfirm.frmKodePos.value) {
		alert('Masukkan kodepos anda.');
		document.frmConfirm.frmKodePos.focus();
		return false;
	} else if (!document.frmConfirm.frmCountry.value) {
		alert('Pilih negara.');
		document.frmConfirm.frmCountry.focus();
		return false;
	} else if (!document.frmConfirm.frmPhone.value) {
		alert('Masukkan nomer telefon anda lengkap dengan kode area.');
		document.frmConfirm.frmPhone.focus();
		return false;
	} else if (!document.frmConfirm.frmHP.value) {
		alert('Masukkan nomer HP anda.');
		document.frmConfirm.frmHP.focus();
		return false;
	} else if (!document.frmConfirm.frmEmail.value) {
		alert('Masukkan alamat email yang valid.');
		document.frmConfirm.frmEmail.focus();
		return false;
	} else if (!isValidEmail(document.frmConfirm.frmEmail.value)) {
		alert('Email yang anda masukkan tidak valid.\n'+'Masukkan alamat email yang valid.');
		document.frmConfirm.frmEmail.focus();
		return false;
	}

    /* in case blm punya kerja, jadi dicomment aja deh */
    /* check pekerjaan begins
    else if (!document.frmConfirm.frmPeru.value) {
		alert('Masukkan nama kantor anda.');
		document.frmConfirm.frmPeru.focus();
		return false;
	} else if (!document.frmConfirm.frmJabatan.value) {
		alert('Masukkan jabatan anda.');
		document.frmConfirm.frmJabatan.focus();
		return false;
	} else if (!document.frmConfirm.frmAlamat2.value) {
		alert('Masukkan alamat kantor anda.');
		document.frmConfirm.frmAlamat2.focus();
		return false;
	} else if (!document.frmConfirm.frmCity2.value) {
		alert('Masukkan kota kantor anda.');
		document.frmConfirm.frmCity2.focus();
		return false;
	} else if (!document.frmConfirm.frmPostCode2.value) {
		alert('Masukkan kodepos kantor anda.');
		document.frmConfirm.frmPostCode2.focus();
		return false;
	} else if (!document.frmConfirm.frmPhone2.value) {
		alert('Masukkan nomer telefon kantor anda lengkap dengan kode area.');
		document.frmConfirm.frmPhone2.focus();
		return false;
	} else if (!document.frmConfirm.frmFax2.value) {
		alert('Masukkan nomer fax kantor anda lengkap dengan kode area.');
		document.frmConfirm.frmFax2.focus();
		return false;
	}
    /* check pekerjaan ends */

    else if (!document.frmConfirm.frmJur.value) {
		alert('Masukkan program studi anda.');
		document.frmConfirm.frmJur.focus();
		return false;
	} else if (!document.frmConfirm.frmNIM.value) {
		alert('Masukkan NIM anda.');
		document.frmConfirm.frmNIM.focus();
		return false;
	} else if (!document.frmConfirm.frmThnMsk.value) {
		alert('Masukkan tahun masuk anda.');
		document.frmConfirm.frmThnMsk.focus();
		return false;
	} else if (!document.frmConfirm.frmThnKeluar.value) {
		alert('Masukkan tahun keluar anda.');
		document.frmConfirm.frmThnKeluar.focus();
		return false;
	} else if (!document.frmConfirm.frmCode.value) {
		alert('Masukkan kode verifikasi ke box yang tersedia.');
		document.frmConfirm.frmCode.focus();
		return false;
	}


	//return false;
	document.frmConfirm.submit();

}
function CheckJob() {

	ckInt = document.frmConfirm.elements['jobfunc[]'];

	for (i = 0; i < ckInt.length; i++) {
		if (ckInt[i].checked) {
			return true;
		}
	}

	return false;

}
function CheckJK() {
	ckInt = document.frmConfirm.elements['jk[]'];

	for (i = 0; i < ckInt.length; i++) {
		if (ckInt[i].checked) {
			return true;
		}
	}
	return false;
}
function CheckStatus() {
	ckInt = document.frmConfirm.elements['status[]'];

	for (i = 0; i < ckInt.length; i++) {
		if (ckInt[i].checked) {
			return true;
		}
	}
	return false;
}
function ckOther(elm, vl) {

	var el = document.getElementById(elm);
	if (vl)
		el.disabled = false;
	else
		el.disabled = true;

}

function CheckFormPre() {

    if (!document.frmFront.frmNama.value) {
        alert('Masukkan nama anda.');
    	document.frmFront.frmNama.focus();
    	return false;
	} else if (!document.frmFront.frmEmail.value) {
		alert('Masukkan alamat email anda.');
		document.frmFront.frmEmail.focus();
		return false;
	} else if (!isValidEmail(document.frmFront.frmEmail.value)) {
		alert('Alamat email yang anda masukkan tidak benar.\n'+'Masukkan alamat email yang benar.');
		document.frmFront.frmEmail.focus();
		return false;
	}

	//return false;
	document.frmConfirm.submit();

}

function isValidEmail(email, required) {
    if (required==undefined) {   // if not specified, assume it's required
        required=true;
    }
    if (email==null) {
        if (required) {
            return false;
        }
        return true;
    }
    if (email.length==0) {
        if (required) {
            return false;
        }
        return true;
    }
    if (! allValidChars(email)) {  // check to make sure all characters are valid
        return false;
    }
    if (email.indexOf("@") < 1) { //  must contain @, and it must not be the first character
        return false;
    } else if (email.lastIndexOf(".") <= email.indexOf("@")) {  // last dot must be after the @
        return false;
    } else if (email.indexOf("@") == email.length) {  // @ must not be the last character
        return false;
    } else if (email.indexOf("..") >=0) { // two periods in a row is not valid
	return false;
    } else if (email.indexOf(".") == email.length) {  // . must not be the last character
	return false;
    }
    return true;
}
function allValidChars(email) {
  var parsed = true;
  var validchars = "abcdefghijklmnopqrstuvwxyz0123456789@.-_";
  for (var i=0; i < email.length; i++) {
    var letter = email.charAt(i).toLowerCase();
    if (validchars.indexOf(letter) != -1)
      continue;
    parsed = false;
    break;
  }
  return parsed;
}