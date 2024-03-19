let user_mail = document.querySelector("#user_email");

function isValidEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

function isValidPassword(password) {
  const re =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
  return re.test(password);
}

user_mail.addEventListener("blur", function () {
  let emailValue = this.value;
  if (!isValidEmail(emailValue)) {
    document.querySelector("#alerte").innerHTML =
      "Veuillez entrer une adresse email valide.";
    user_mail.focus();
    return;
  }

  fetch("ajouteruser.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "user_email=" + encodeURIComponent(emailValue),
  })
    .then((response) => response.json())
    .then((result) => {
      if (result.error) {
        document.querySelector("#alerte").innerHTML = result.message;
        user_mail.focus();
      } else {
        document.querySelector("#alerte").innerHTML = "";
      }
    })
    .catch((error) => console.error("Error:", error));
});

let pwd1 = document.querySelector("#password");
let alerte2 = document.querySelector("#alerte2");

pwd1.addEventListener("input", function () {
  if (!isValidPassword(this.value)) {
    alerte2.innerHTML =
      "Le mot de passe doit contenir au moins 8 caractères, dont au moins une majuscule, une minuscule, un chiffre et un caractère spécial.";
  } else {
    alerte2.innerHTML = "";
  }
});

let btn_submit = document.querySelector("#valider");

btn_submit.addEventListener("click", function (e) {
  e.preventDefault();

  let emailValue = user_mail.value;
  if (!isValidEmail(emailValue)) {
    document.querySelector("#alerte").innerHTML =
      "Veuillez entrer une adresse email valide avant de soumettre.";
    user_mail.focus();
    return;
  }

  let pwd2 = document.querySelector("#confirm_password");

  if (!isValidPassword(pwd1.value)) {
    alerte2.innerHTML =
      "Le mot de passe doit contenir au moins 8 caractères, dont au moins une majuscule, une minuscule, un chiffre et un caractère spécial.";
    pwd1.focus();
    return;
  }

  if (pwd1.value === pwd2.value) {
    let formData = new FormData(document.querySelector("#formulaire"));
    formData.append("user_email", emailValue);

    fetch("ajouteruser.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        console.log("Success:", data);
        if (data.success) {
          alert("User ajouté avec succès.");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  } else {
    document.querySelector("#alerte2").innerHTML =
      "Vos mots de passe sont différents";
  }
});
