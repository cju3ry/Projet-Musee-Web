let btn = document.getElementById("btn");
let btnIcon = document.getElementById("btn-icon");
let details = document.getElementById("details");

const toggleDetails = () => {
  if (btnIcon.name === "chevron-down") {
    btnIcon.name = "chevron-up";
    details.style.height = "max-content";
  } else {
    btnIcon.name = "chevron-down";
    details.style.height = 0;
  }
};

btn.addEventListener("click", toggleDetails);