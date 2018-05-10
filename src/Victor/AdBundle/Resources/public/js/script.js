document.getElementById('fos_user_registration_form_zipcode').addEventListener("blur", function (e) {
    var zipcode = e.target.value;
    if (zipcode.length !== 5)
    {
        alert('Un code postal est composé de 5 chiffres')
    }
});

document.getElementById('fos_user_registration_form_phone').addEventListener("blur", function (e) {
    var phone = e.target.value;
    if (phone.length !== 10)
    {
        alert('Un numéro de téléphone est composé de 10 chiffres')
    }
});

/*document.getElementById('fos_user_registration_form_email').addEventListener("blur", function (e) {

    console.log(e.target.value);
});*/