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

document.getElementById('fos_user_registration_form_plainPassword_first').addEventListener("blur", function (e) {
    var firstmdp = e.target.value;

    document.getElementById('fos_user_registration_form_plainPassword_second').addEventListener("blur", function (ev) {
        var secondmdp = ev.target.value;

        if (firstmdp === secondmdp)
        {
            document.getElementById('fos_user_registration_form_plainPassword_first').style.color = "green";
            document.getElementById('fos_user_registration_form_plainPassword_second').style.color = "green";
        }
        else
        {
            document.getElementById('fos_user_registration_form_plainPassword_first').style.color = "red";
            document.getElementById('fos_user_registration_form_plainPassword_second').style.color = "red";
        }
    })
})
