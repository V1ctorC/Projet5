var errorfield = document.getElementById('information');
var zipCodefield = document.getElementById('zipCodeProblem');
var phonefield = document.getElementById('phoneProblem');

function errorBlock()
{
    if ((zipCodefield.innerHTML === "success") || (zipCodefield.innerHTML === ""))
    {
        if ((phonefield.innerHTML === "success") || (phonefield.innerHTML === ""))
        {
            errorfield.style.display = "none";
        }
    }
}

document.getElementById('fos_user_profile_form_zipcode').addEventListener("blur", function (e) {
    var zipcode = e.target.value;
    if (zipcode.length !== 5)
    {
        document.getElementById('fos_user_profile_form_zipcode').style.color = "red";
        document.getElementById('fos_user_profile_form_zipcode').style.border = "2px solid red";
        errorfield.style.display = "block";
        zipCodefield.style.display ="block";
        zipCodefield.innerHTML = "<br/> - Le code postal doit être composé de 5 chiffres";
    }
    else
    {
        document.getElementById('fos_user_profile_form_zipcode').style.color = "inherit";
        document.getElementById('fos_user_profile_form_zipcode').style.border = "1px solid #ccc";
        zipCodefield.style.display = "none";
        zipCodefield.innerHTML = "success";
        errorBlock();
    }
});

document.getElementById('fos_user_profile_form_phone').addEventListener("blur", function (e) {
    var phone = e.target.value;
    if (phone.length !== 10)
    {
        document.getElementById('fos_user_profile_form_phone').style.color = "red";
        document.getElementById('fos_user_profile_form_phone').style.border = "2px solid red";
        errorfield.style.display = "block";
        phonefield.style.display = "block";
        phonefield.innerHTML = "<br/> - Le code numéro de téléphone doit comporter 10 chiffres";
    }
    else
    {
        document.getElementById('fos_user_profile_form_phone').style.color = "inherit";
        document.getElementById('fos_user_profile_form_phone').style.border = "1px solid #ccc";
        phonefield.style.display = "none";
        phonefield.innerHTML = "success";
        errorBlock();
    }
});
