var errorfield = document.getElementById('information');


document.getElementById('fos_user_registration_form_zipcode').addEventListener("blur", function (e) {
    var zipcode = e.target.value;
    if (zipcode.length !== 5)
    {
        document.getElementById('fos_user_registration_form_zipcode').style.color = "red";
        document.getElementById('fos_user_registration_form_zipcode').style.border = "2px solid red";
        errorfield.style.display = "block";
        errorfield.innerHTML += "<br/> - Le code postal doit être composé de 5 chiffres";
    }
});

document.getElementById('fos_user_registration_form_phone').addEventListener("blur", function (e) {
    var phone = e.target.value;
    if (phone.length !== 10)
    {
        document.getElementById('fos_user_registration_form_phone').style.color = "red";
        document.getElementById('fos_user_registration_form_phone').style.border = "2px solid red";
        errorfield.style.display = "block";
        errorfield.innerHTML += "<br/> - Le code numéro de téléphone doit comporter 10 chiffres";
    }
});

document.getElementById('fos_user_registration_form_plainPassword_first').addEventListener("blur", function (e) {
    var firstmdp = e.target.value;

    document.getElementById('fos_user_registration_form_plainPassword_second').addEventListener("blur", function (ev) {
        var secondmdp = ev.target.value;

        if (firstmdp === secondmdp)
        {
            document.getElementById('fos_user_registration_form_plainPassword_first').style.color = "green";
            document.getElementById('fos_user_registration_form_plainPassword_first').style.border = "1px solid green";
            document.getElementById('fos_user_registration_form_plainPassword_second').style.color = "green";
            document.getElementById('fos_user_registration_form_plainPassword_second').style.border = "1px solid green";
        }
        else
        {
            document.getElementById('fos_user_registration_form_plainPassword_first').style.color = "red";
            document.getElementById('fos_user_registration_form_plainPassword_first').style.border = "2px solid red";
            document.getElementById('fos_user_registration_form_plainPassword_second').style.color = "red";
            document.getElementById('fos_user_registration_form_plainPassword_second').style.border = "2px solid red";
            errorfield.style.display = "block";
            errorfield.innerHTML += "<br/> - Les mots de passe ne correspondent pas"
        }
    })
});
