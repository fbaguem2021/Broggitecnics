const infoApp = document.querySelector("#info-app");
const operatorData = document.querySelector("#operator-data");
const expanded = "expanded";
const mediaQuery = window.matchMedia("(max-width: 991px)");

//BLOCK LB Phone input
const slider = document.querySelector(".switch input");
const side = document.getElementById("side");
const phoneMode = document.getElementById("phone-mode");

if (slider) {
    slider.addEventListener("change", function () {
        if (this.checked) {
            side.classList.add("expand");
            setTimeout(() => {
                phoneMode.style.display = "none";
            }, 90);
            side.focus();
        } else {
            side.classList.remove("expand");
            setTimeout(() => {
                phoneMode.style.display = "block";
            }, 180);
        }
    });
}

if (infoApp && operatorData) {
    infoApp.addEventListener("click", () => {
        if (mediaQuery.matches) {
            if (infoApp.classList.contains(expanded)) {
                infoApp.classList.remove(expanded);
                operatorData.classList.add(expanded);
            } else {
                infoApp.classList.add(expanded);
                operatorData.classList.remove(expanded);
            }
        }
    });
    operatorData.addEventListener("click", () => {
        if (mediaQuery.matches) {
            if (operatorData.classList.contains(expanded)) {
                operatorData.classList.remove(expanded);
                infoApp.classList.add(expanded);
            } else {
                operatorData.classList.add(expanded);
                infoApp.classList.remove(expanded);
            }
        }
    });
}
