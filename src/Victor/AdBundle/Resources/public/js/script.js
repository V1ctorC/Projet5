var errorfield = document.getElementById('information');
var mailfield = document.getElementById('mailProblem');
var usernamefield = document.getElementById('usernameProblem');
var passwordfield = document.getElementById('passwordProblem');
var zipCodefield = document.getElementById('zipCodeProblem');
var phonefield = document.getElementById('phoneProblem');


document.getElementById('fos_user_registration_form_zipcode').addEventListener("blur", function (e) {
    var zipcode = e.target.value;
    if (zipcode.length !== 5)
    {
        document.getElementById('fos_user_registration_form_zipcode').style.color = "red";
        document.getElementById('fos_user_registration_form_zipcode').style.border = "2px solid red";
        errorfield.style.display = "block";
        zipCodefield.style.display ="block";
        zipCodefield.innerHTML = "<br/> - Le code postal doit être composé de 5 chiffres";
    }
    else
    {
        document.getElementById('fos_user_registration_form_zipcode').style.color = "inherit";
        document.getElementById('fos_user_registration_form_zipcode').style.border = "1px solid #ccc";
        zipCodefield.style.display = "none";
        zipCodefield.innerHTML = "success";
        errorBlock();
    }
});

document.getElementById('fos_user_registration_form_phone').addEventListener("blur", function (e) {
    var phone = e.target.value;
    if (phone.length !== 10)
    {
        document.getElementById('fos_user_registration_form_phone').style.color = "red";
        document.getElementById('fos_user_registration_form_phone').style.border = "2px solid red";
        errorfield.style.display = "block";
        phonefield.style.display = "block";
        phonefield.innerHTML = "<br/> - Le code numéro de téléphone doit comporter 10 chiffres";
    }
    else
    {
        document.getElementById('fos_user_registration_form_phone').style.color = "inherit";
        document.getElementById('fos_user_registration_form_phone').style.border = "1px solid #ccc";
        phonefield.style.display = "none";
        phonefield.innerHTML = "success";
        errorBlock();
    }
});

document.getElementById('fos_user_registration_form_plainPassword_first').addEventListener("blur", function (e) {
    var firstmdp = e.target.value;

    document.getElementById('fos_user_registration_form_plainPassword_second').addEventListener("blur", function (ev) {
        var secondmdp = ev.target.value;

        if (firstmdp === secondmdp)
        {
            document.getElementById('fos_user_registration_form_plainPassword_first').style.color = "inherit";
            document.getElementById('fos_user_registration_form_plainPassword_first').style.border = "1px solid #ccc";
            document.getElementById('fos_user_registration_form_plainPassword_second').style.color = "inherit";
            document.getElementById('fos_user_registration_form_plainPassword_second').style.border = "1px solid #ccc";
            passwordfield.style.display = "none";
            passwordfield.innerHTML = "success";
            errorBlock();
        }
        else
        {
            document.getElementById('fos_user_registration_form_plainPassword_first').style.color = "red";
            document.getElementById('fos_user_registration_form_plainPassword_first').style.border = "2px solid red";
            document.getElementById('fos_user_registration_form_plainPassword_second').style.color = "red";
            document.getElementById('fos_user_registration_form_plainPassword_second').style.border = "2px solid red";
            errorfield.style.display = "block";
            passwordfield.style.display = "block";
            passwordfield.innerHTML = "<br/> - Les mots de passe ne correspondent pas"
        }
    })
});

function errorBlock()
{
    if ((mailfield.innerHTML === "success") || (mailfield.innerHTML === ""))
    {
        if ((usernamefield.innerHTML === "success") || (usernamefield.innerHTML === ""))
        {
            if ((passwordfield.innerHTML === "success") || (passwordfield.innerHTML === ""))
            {
                if ((zipCodefield.innerHTML === "success") || (zipCodefield.innerHTML === ""))
                {
                    if ((phonefield.innerHTML === "success") || (phonefield.innerHTML === ""))
                    {
                        errorfield.style.display = "none";
                    }
                }
            }
        }
    }
}



/*function errorBlock()
{
    if ((mailfield.innerHTML && usernamefield.innerHTML && passwordfield.innerHTML && zipCodefield.innerHTML && phonefield.innerHTML )=== "success")
    {
        errorfield.style.display = "none";
    }
}

*/

